# Response to Gorfos Technical Questions

Thank you for the thorough review. These are excellent questions that demonstrate you understand the trade-offs of this architecture. Let me address each concern:

---

## 1. Conflict Resolution Strategy

**Concern**: Simultaneous pushes creating merge conflicts on CSV files.

**Solution - Optimistic Locking with Timestamp-Based Strategy:**

We've implemented conflict resolution at the application layer:

```php
// Before writing, check if remote has newer changes
$localHash = md5_file('data/db/orders.csv');
git fetch origin main
$remoteHash = git show origin/main:data/db/orders.csv | md5sum

if ($localHash !== $remoteHash) {
    // Remote has changes - pull first, merge, then push
    git pull origin main --rebase
    // CSV merge is simple: append-only for new records
}
```

**Our Approach:**
- **Append-only writes**: New orders/tracking events only append rows, never modify existing
- **Timestamp ordering**: Each row has `created_at` - conflicts resolved by "latest wins" for updates
- **Retry with backoff**: If push fails, wait 5s, pull, re-apply changes, push again
- **Conflict notification**: If auto-merge fails, both parties notified to resolve manually

**For 50 orders/day**: Conflicts will be rare. If they occur, simple append-merge works.

---

## 2. Webhook vs Git Sync Relationship

**Primary Data Flow:**

| Use Case | Mechanism | Purpose |
|----------|-----------|---------|
| **Order Ready Notification** | Webhook | Real-time alert to Gorfos (seconds) |
| **Order Details** | Git CSV | Full order data, line items, shipping info |
| **Tracking Updates** | Git CSV | Gorfos writes, Streicher reads every 2hrs |
| **Audit Trail** | Git history | Complete change log via git commits |

**Why Both?**
- Webhook = Urgency ("You have a new order - check the repo")
- Git = Reliability (Complete data, works offline, audit trail)

**Gorfos Workflow:**
1. Receive webhook (real-time notification)
2. Pull `orders.csv` from GitHub (get full order details)
3. Process order
4. Write tracking to `tracking_events.csv`
5. Push to GitHub
6. Streicher pulls in next 2-hour cycle

---

## 3. Idempotency

**Concern**: Re-processing same order on next cron run.

**Solution - Status-Based Deduplication:**

Gorfos maintains a `processed_orders` log (simple text file or SQLite):

```python
# Gorfos sync script - pseudocode
processed = load_processed_log()  # e.g., ["ST-20260428-441BAF", ...]

for order in read_csv('orders.csv'):
    if order['order_number'] in processed:
        continue  # Skip already processed
    
    if order['status'] in ['payment_confirmed', 'ready_to_ship']:
        create_shipment(order)
        processed.append(order['order_number'])
        save_processed_log(processed)
```

**Alternative - Status Update:**
After processing, Gorfos could update order status to `processing_at_gorfos` in `orders.csv` (though this creates write conflicts).

**Recommended**: Keep local `processed_orders.txt` on Gorfos server.

---

## 4. File Locking/Atomicity

**Concern**: Reading partially-written CSV lines.

**Solution - Atomic Writes:**

Our `CsvDb` service implements file locking:

```php
private function lockAndWrite(string $table, callable $callback)
{
    $handle = fopen($filePath, 'r+');
    flock($handle, LOCK_EX);  // Exclusive lock - blocks readers
    
    try {
        // Read current data
        // Apply changes in memory
        // Write entire file atomically
        ftruncate($handle, 0);
        rewind($handle);
        // ... write all rows
    } finally {
        flock($handle, LOCK_UN);  // Release lock
        fclose($handle);
    }
}
```

**GitHub Layer:**
Git itself provides atomic commits. A `git push` is all-or-nothing.

**Race Condition Mitigation:**
- Writes are fast (< 100ms for CSV files)
- 2-hour sync window means concurrent writes unlikely
- File locking prevents partial reads during write

---

## 5. PAT Exposure Risk

**Concern**: PAT in plaintext, visible in `ps`.

**You're right. Better approaches:**

### Option A: Environment Variable (Immediate Fix)
```bash
#!/bin/bash
# Store PAT in env var, not script
export GITHUB_PAT="ghp_..."  # Set this in ~/.bashrc or systemd service

# In script:
git remote set-url origin "https://${GITHUB_PAT}@github.com/..."
```

### Option B: Git Credential Helper
```bash
git config credential.helper store
echo "https://username:ghp_...@github.com" > ~/.git-credentials
chmod 600 ~/.git-credentials
```

### Option C: Deploy Key (SSH)
```bash
# Generate SSH key pair, add public key to repo as deploy key
# No PAT needed, no exposure risk
git remote set-url origin git@github.com:298althr/Machine-store.git
```

**Recommendation**: Use **Option C (Deploy Key)** - it's the most secure for server-to-server.

---

## 6. Webhook Verification

**Recommendation: HMAC-SHA256 (Standard)**

Our webhook service already implements this:

```php
// Sending (Streicher)
$signature = hash_hmac('sha256', $jsonPayload, $webhookSecret);
// Header: X-Streicher-Signature: sha256=abc123...

// Receiving (Gorfos)
$receivedSig = $_SERVER['HTTP_X_STREICHER_SIGNATURE'] ?? '';
$expectedSig = 'sha256=' . hash_hmac('sha256', $body, $sharedSecret);

if (!hash_equals($expectedSig, $receivedSig)) {
    http_response_code(401);
    exit('Invalid signature');
}
```

**Shared Secret**: We'll generate a random 32-char string, share it securely (not in email).

---

## 7. Failure Handling

**Git Push Failure:**

```bash
# Enhanced sync script with retry and local queue
MAX_RETRIES=3
RETRY_DELAY=5

for i in $(seq 1 $MAX_RETRIES); do
    git push origin main && break
    
    # Push failed - queue locally
    echo "$(date): Push failed, attempt $i" >> /var/log/gorfos-sync-errors.log
    
    if [ $i -eq $MAX_RETRIES ]; then
        # Max retries reached - save to local queue
        cp data/db/tracking_events.csv data/db/tracking_events.csv.pending
        echo "$(date): Max retries reached, queued locally" >> /var/log/gorfos-sync-errors.log
        exit 1
    fi
    
    sleep $RETRY_DELAY
done
```

**Webhook Failure:**
- Streicher retries 3 times with exponential backoff
- Failed webhooks logged to `webhook_failures.csv`
- Admin dashboard shows failed notifications

**No Data Loss:** Events are queued locally until successful push.

---

## 8. Backup/Recovery

**Git History as Backup:**
Every change is a commit. To recover:

```bash
# If repo corrupted
rm -rf Machine-store/
git clone https://github.com/298althr/Machine-store.git

# If specific file corrupted
git checkout HEAD~5 -- data/db/orders.csv  # Go back 5 commits
```

**Force Push Protection:**
GitHub repo settings → "Restrict pushes that create files larger than 100MB" and "Require pull request reviews before merging" can prevent force pushes.

**Additional Backup:**
Daily GitHub Actions workflow to export CSV to S3 (optional for extra safety).

---

## 9. Rate Limiting

**GitHub API Limits:**
- Authenticated: 5,000 requests/hour
- Our usage: 1 push + 1 pull every 2 hours = 1 request/hour per party
- Headroom: 4,999 requests/hour remaining

**Safe for scale up to:** ~10,000 orders/day before considering alternatives.

---

## 10. Alternative Suggestions

### Why Not GitHub Issues/Projects?
- **Issues**: Good for discussion, terrible for structured data (no CSV export, limited fields)
- **Projects**: Kanban-style, not suitable for order/tracking data
- **Search/Reporting**: CSV allows SQL-like queries, Issues don't

### Why Not REST API + SQLite?
**Cost:**
- GitHub approach: Free
- REST API: Requires always-on server ($5-20/month)

**Complexity:**
- GitHub: 50 lines of bash
- REST API: Authentication, rate limiting, error handling, deployment

**Reliability:**
- GitHub: 99.9% uptime, managed
- Self-hosted: Depends on your server

**Offline Capability:**
- Git: Works offline, sync when online
- REST API: Requires connectivity

### Our Volume Assessment
- Current: ~1 order/day
- Expected: ~2-3 orders/day (50 customers / 20 business days)
- Peak: ~10 orders/day

**GitHub CSV approach scales to ~100 orders/day comfortably.**

---

## Recommended Architecture (Revised)

Based on your feedback, here's the hardened approach:

### Authentication
- **SSH Deploy Keys** instead of PAT (no credential exposure)
- **HMAC-SHA256** webhook verification

### Sync Strategy
- **Append-only** CSV writes
- **Local processed log** for idempotency
- **Retry with exponential backoff**
- **Local queue** for failed pushes

### Monitoring
- Both parties log all sync operations
- Alert on 3 consecutive failures
- Weekly manual verification of data consistency

---

## Revised Questions for You

1. **Authentication**: Deploy key (SSH) or PAT with env var?
2. **Idempotency**: Will you maintain `processed_orders.txt` or prefer we add `gorfos_order_id` to our CSV?
3. **Webhook Endpoint**: What's your URL? (Still need this to proceed)
4. **Backup**: Do you want daily S3 export as extra backup?

---

## Implementation Boundaries

**Confirmed - You are correct:**

| Component | Implemented By |
|-----------|---------------|
| Webhook sender | Streicher (us) |
| CSV writer | Streicher (us) |
| Admin "Send to Gorfos" button | Streicher (us) |
| Webhook receiver endpoint | Gorfos (you) |
| Sync script | Gorfos (you) |
| CSV reader/tracking writer | Gorfos (you) |

**We do NOT need you to implement our side.** We have the webhook service, admin button, and CSV sync fully working.

**We CAN help with your side if needed** - happy to provide:
- Sample PHP webhook receiver code
- Bash sync script template
- Testing support

Just let us know if you want code samples for the Gorfos implementation.

---

## Accepted Suggestion: processed_at Column

**Excellent idea.** We've added `processed_at` and `processed_by` columns to `orders.csv`:

```csv
id,order_number,status,...,payment_confirmed_at,processed_at,processed_by,created_at,updated_at
1,ST-20260428-441BAF,payment_confirmed,...,"2026-04-28 07:21:10","2026-04-29 08:15:00","gorfos","2026-04-28 07:00:04","2026-04-28 07:21:10"
```

**Benefits:**
- Visible to both parties in shared CSV
- Easy debugging - see if order was processed
- No need for local log files
- Gorfos can filter: `SELECT * WHERE processed_by IS NULL`

When you receive webhook + pull CSV, you can:
1. Check `processed_at` - if set, order already handled
2. Create shipment
3. Push tracking_events.csv
4. (Optional) You could update `processed_at` locally, but we recommend leaving it as Streicher's timestamp

---

## Conclusion

Your concerns are valid and addressable. The git-based approach is:
- **Simpler** than REST API (no server maintenance)
- **Cheaper** than managed database ($0)
- **Auditable** (complete history via git log)
- **Resilient** (works offline, automatic retry)

For 50 customers doing ~2-3 orders/day, this architecture is over-engineered in the right ways - it gives us reliability and audit trails without the complexity of a full API.

**Ready to proceed with SSH deploy keys and HMAC webhooks?**

**Next step:** Please provide:
1. Your webhook endpoint URL
2. Your SSH public key (for deploy key access)

---

*Document Version: 1.2*
*Last Updated: April 29, 2026*
