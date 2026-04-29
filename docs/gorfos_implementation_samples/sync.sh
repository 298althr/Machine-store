#!/bin/bash
# Gorfos GitHub Sync Script
# Syncs with Streicher shared repository every 2 hours
# Features: Retry logic, conflict resolution, local queue, error logging

# Configuration - UPDATE THESE
REPO_DIR="/var/www/gorfos-sync"
GITHUB_PAT="ghp_your_pat_here"  # Or use SSH deploy key (recommended)
REPO="298althr/Machine-store"
DB_DIR="$REPO_DIR/data/db"
PROCESSED_LOG="$REPO_DIR/processed_orders.txt"
LOG_FILE="/var/log/gorfos-sync.log"
ERROR_LOG="/var/log/gorfos-sync-errors.log"
MAX_RETRIES=3
RETRY_DELAY=5

# Ensure directories exist
mkdir -p "$REPO_DIR/logs"
touch "$PROCESSED_LOG"

# Logging function
log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') | $1" | tee -a "$LOG_FILE"
}

error() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') | ERROR | $1" | tee -a "$ERROR_LOG"
}

# Change to repo directory
cd "$REPO_DIR" || exit 1

# Configure git (if not already done)
git config user.email "sync@gorfos.com"
git config user.name "Gorfos Sync"

# Set authenticated remote URL
if [[ -n "$GITHUB_PAT" ]]; then
    git remote set-url origin "https://${GITHUB_PAT}@github.com/${REPO}.git"
fi

log "Starting sync cycle"

# PULL LATEST FROM STREICHER
log "Pulling latest data from Streicher..."

PULL_SUCCESS=false
for i in $(seq 1 $MAX_RETRIES); do
    if git pull origin main --rebase; then
        PULL_SUCCESS=true
        log "Pull successful"
        break
    else
        error "Pull failed (attempt $i/$MAX_RETRIES)"
        if [ $i -lt $MAX_RETRIES ]; then
            log "Waiting ${RETRY_DELAY}s before retry..."
            sleep $RETRY_DELAY
        fi
    fi
done

if ! $PULL_SUCCESS; then
    error "Pull failed after $MAX_RETRIES attempts. Skipping processing."
    exit 1
fi

# PROCESS NEW ORDERS
log "Processing orders from CSV..."

# Read orders.csv and find unprocessed orders
# This is a simplified version - you may want to use the PHP processor instead
php "$REPO_DIR/process_orders.php"
PROCESS_RESULT=$?

if [ $PROCESS_RESULT -ne 0 ]; then
    error "Order processing failed with code $PROCESS_RESULT"
    # Don't exit - still try to push any tracking updates we have
fi

# Check if we have tracking updates to push
if git diff --quiet "$DB_DIR/tracking_events.csv" 2>/dev/null; then
    log "No tracking updates to push"
else
    log "Tracking updates detected, preparing to push..."
    
    # Stage changes
    git add "$DB_DIR/tracking_events.csv"
    
    # Commit
    git commit -m "Gorfos: Tracking update $(date '+%Y-%m-%d %H:%M')"
    
    if [ $? -ne 0 ]; then
        log "No changes to commit (already up to date)"
    else
        # PUSH WITH RETRY
        PUSH_SUCCESS=false
        for i in $(seq 1 $MAX_RETRIES); do
            if git push origin main; then
                PUSH_SUCCESS=true
                log "Push successful"
                break
            else
                error "Push failed (attempt $i/$MAX_RETRIES)"
                
                if [ $i -lt $MAX_RETRIES ]; then
                    log "Pulling latest and retrying..."
                    git pull origin main --rebase
                    sleep $RETRY_DELAY
                fi
            fi
        done
        
        if ! $PUSH_SUCCESS; then
            error "Push failed after $MAX_RETRIES attempts. Saving to local queue."
            # Save pending changes for next run
            cp "$DB_DIR/tracking_events.csv" "$DB_DIR/tracking_events.csv.pending"
        fi
    fi
fi

# Reset remote URL to not expose PAT in future commands
if [[ -n "$GITHUB_PAT" ]]; then
    git remote set-url origin "https://github.com/${REPO}.git"
fi

log "Sync cycle complete"
exit 0
