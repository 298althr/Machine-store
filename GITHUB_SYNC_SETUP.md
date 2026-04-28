# GitHub Sync Setup Guide

This guide walks you through setting up automatic GitHub synchronization for your CSV database, ensuring data persists across Railway redeploys.

---

## Overview

With GitHub sync enabled, every database write (insert/update/delete) automatically commits changes to your GitHub repository. This solves the data persistence problem on Railway where CSV files reset on each deployment.

**Flow:**
1. User submits form → Data written to local CSV
2. GitHubSync commits CSV to repo via GitHub API
3. On next Railway deploy → `git pull` gets latest data
4. Application starts with current data

---

## Step 1: Generate GitHub Personal Access Token (PAT)

### 1.1 Go to GitHub Settings
- Visit: https://github.com/settings/tokens
- Click **"Generate new token (classic)"**

### 1.2 Token Configuration
**Note:** Give it a descriptive name like `GORFOS-Railway-Sync`

**Expiration:** Choose based on your preference:
- 90 days (recommended for security)
- 1 year
- No expiration (convenient but less secure)

### 1.3 Required Scopes (Permissions)
Check these boxes:

| Scope | Reason |
|-------|--------|
| ☑️ `repo` | Full control of private repositories |
|   → `repo:status` | Access commit status |
|   → `repo_deployment` | Access deployment status |
|   → `public_repo` | Access public repositories |
|   → `repo:invite` | Access repository invitations |
|   → `security_events` | Read and write security events |

**Important:** The `repo` scope gives full repository access including pushing files.

### 1.4 Generate Token
- Click **"Generate token"** at bottom
- **COPY THE TOKEN IMMEDIATELY** — GitHub only shows it once!
- Store it securely (you'll add it to Railway next)

---

## Step 2: Configure Railway Environment Variables

### 2.1 Access Railway Dashboard
- Go to https://railway.app/dashboard
- Select your `GFS` project
- Click on your service (likely `web` or `app`)

### 2.2 Add Environment Variables
Navigate to: **Variables** tab → **New Variable**

Add these 4 variables:

| Variable | Value | Description |
|----------|-------|-------------|
| `GITHUB_PAT` | `ghp_xxxxxxxxxxxx` | Your GitHub token from Step 1 |
| `GITHUB_OWNER` | `298althr` | Your GitHub username/org |
| `GITHUB_REPO` | `GFS` | Repository name |
| `GITHUB_DB_PATH` | `db/` | Path to CSV files in repo |

**Example:**
```
GITHUB_PAT=ghp_aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890
GITHUB_OWNER=298althr
GITHUB_REPO=GFS
GITHUB_DB_PATH=db/
```

### 2.3 Redeploy
After adding variables:
1. Click **"Deploy"** button
2. Or push any commit to trigger redeploy
3. Watch logs for: "GitHub sync configured" or similar

---

## Step 3: Verify It's Working

### 3.1 Test Write Operation
1. Go to your site: `https://your-app.railway.app`
2. Submit a test form (e.g., contact form, supply request)
3. Check GitHub repo → `db/` folder should show new commit

### 3.2 Check Admin Sync Feature
1. Login to `/admin`
2. Go to **Settings**
3. Look for **"GitHub Sync"** section
4. Click **"Sync All Tables to GitHub"**
5. Should show success message

### 3.3 Manual Sync (Admin)
Admins can manually trigger sync:
- Single table: POST to `/admin/sync-github` with `table=contractors`
- All tables: POST to `/admin/sync-github` without table parameter

---

## Troubleshooting

### Issue: "GitHub sync not configured" error
**Cause:** Missing environment variables
**Fix:** Check Railway variables are set exactly as shown above

### Issue: Changes not appearing in GitHub
**Cause:** Token lacks permissions
**Fix:** Regenerate PAT with `repo` scope checked

### Issue: Rate limiting (403 errors)
**Cause:** GitHub API has limits (5000 requests/hour)
**Fix:** With 50 customers, you won't hit this. If you do, add delays between syncs.

### Issue: Merge conflicts on CSV files
**Cause:** Multiple servers or manual edits to CSV
**Fix:** 
- Always edit through app (not GitHub UI)
- Pull latest before deployments
- Consider switching to PostgreSQL if conflicts persist

---

## Database Schema Overview

Your current 29 tables cover all business needs:

### Core Business Tables
- `users` — Admin and customer accounts
- `contractors` — Offshore contractor companies
- `contractor_applications` — New client applications
- `companies` — Business partner companies

### Supply Chain
- `user_supply_requests` — Client supply orders (with payment workflow)
- `user_supply_request_items` — Line items for requests
- `products` — Supply catalog
- `inventory` — Stock tracking

### Food Service
- `food_categories` — Menu categories
- `food_items` — Menu items with pricing
- `food_orders` — Meal orders
- `food_order_items` — Order line items
- `meal_plans` — Subscription plans

### Shipping Service
- `shipping_requests` — Freight/cargo requests
- `shipping_routes` — Route definitions
- `shipments` — Active shipments

### Transport Service
- `fleet` — Vehicles and vessels
- `transport_bookings` — Crew transport bookings
- `transport_manifests` — Passenger/cargo manifests
- `fleet_maintenance` — Maintenance records

### Operations
- `orders` — General orders
- `rentals` — Equipment rentals
- `support` — Support tickets
- `contact_submissions` — Contact form entries

### System Tables
- `settings` — App configuration
- `audit_log` — Admin action logs
- `discount_tiers` — Pricing tiers
- `notifications` — System notifications

### Legacy (being phased out)
- `supply_requests` — Old supply system

---

## Security Notes

1. **PAT Storage:** Railway encrypts environment variables. Never commit PAT to code.

2. **Token Scope:** `repo` scope is powerful — it can read/write all your repos. If concerned:
   - Create a separate GitHub account just for Railway sync
   - Give that account collaborator access to only the GFS repo
   - Use that account's PAT

3. **Rotation:** GitHub shows token usage. If you see unexpected activity, regenerate immediately.

---

## Next Steps

1. ✅ Generate PAT with `repo` scope
2. ✅ Add 4 environment variables to Railway
3. ✅ Redeploy application
4. ✅ Submit test form and verify GitHub commit
5. ✅ Use admin settings sync button as backup

---

## Alternative: Manual Git Workflow

If auto-sync has issues, you can manually commit:

```bash
# SSH into Railway
railway connect

# Navigate to app
cd /app

# Add and commit
git add db/*.csv
git commit -m "Manual data sync"
git push origin main
```

Or use Railway's CLI to run commands.

---

**Questions?** Check Railway logs for error messages or verify PAT has correct scopes.
