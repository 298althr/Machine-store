# Gorfos Implementation Samples

This directory contains sample implementations for Gorfos to integrate with Streicher's GitHub-based order sync.

## Files

| File | Purpose | Location on Gorfos Server |
|------|---------|---------------------------|
| `webhook_receiver.php` | Receive real-time order notifications from Streicher | `/var/www/gorfos.com/api/webhooks/streicher/order-ready` |
| `sync.sh` | Sync script that runs every 2 hours | `/var/www/gorfos-sync/sync.sh` |
| `process_orders.php` | CSV processor - reads orders, writes tracking | Called by sync.sh |

## Setup Instructions

### 1. Directory Structure

Create this structure on your server:
```
/var/www/gorfos-sync/
├── data/
│   └── db/
│       ├── orders.csv          (pulled from GitHub)
│       ├── tracking_events.csv (your writes)
│       └── shipments.csv       (your writes)
├── logs/
│   ├── webhooks.log
│   ├── processing.log
│   └── sync.log
├── processed_orders.txt       (idempotency log)
├── sync.sh                      (main sync script)
├── process_orders.php         (CSV processor)
└── .git/                       (cloned repo)
```

### 2. Clone Repository

```bash
cd /var/www
git clone https://github.com/298althr/Machine-store.git gorfos-sync
cd gorfos-sync
git config user.email "sync@gorfos.com"
git config user.name "Gorfos Sync"
```

### 3. Install Files

Copy the sample files:
```bash
cp /path/to/sync.sh /var/www/gorfos-sync/
cp /path/to/process_orders.php /var/www/gorfos-sync/
cp /path/to/webhook_receiver.php /var/www/gorfos.com/api/webhooks/streicher/order-ready
```

### 4. Configure

Edit each file and update:
- `WEBHOOK_SECRET` in webhook_receiver.php (Streicher will provide)
- `GITHUB_PAT` in sync.sh (or use SSH deploy key)
- File paths if different from defaults

### 5. Set Permissions

```bash
chmod +x /var/www/gorfos-sync/sync.sh
chmod 755 /var/www/gorfos-sync/logs
touch /var/www/gorfos-sync/processed_orders.txt
chmod 644 /var/www/gorfos-sync/processed_orders.txt
```

### 6. Cron Job

```bash
crontab -e

# Add this line for 2-hour sync:
0 */2 * * * /var/www/gorfos-sync/sync.sh >> /var/log/gorfos-sync.log 2>&1
```

### 7. Webhook Endpoint

Ensure your web server routes this URL to the PHP file:
```
POST https://gorfos.com/api/webhooks/streicher/order-ready
→ /var/www/gorfos.com/api/webhooks/streicher/order-ready
```

## Workflow

### When New Order Arrives:

1. **Streicher sends webhook** to your endpoint (real-time)
2. **Your webhook handler** validates signature, logs receipt
3. **Webhook marks order** as processed (prevents duplicates)
4. **Your internal system** creates shipment, generates tracking
5. **You write tracking** to `tracking_events.csv` locally
6. **2-hour sync** pushes tracking back to GitHub
7. **Streicher pulls** tracking updates in their next sync

### CSV Data Flow:

```
Streicher                    GitHub                     Gorfos
   │                           │                          │
   │── Write order ─────────>│                          │
   │                          │                          │
   │── Push ────────────────>│                          │
   │                          │<── Pull every 2hrs ──────│
   │                          │                          │
   │                          │<── Read orders.csv ──────│
   │                          │                          │
   │                          │                          ├── Process order
   │                          │                          ├── Create shipment
   │                          │                          ├── Write tracking
   │                          │                          │
   │                          │<── Push tracking.csv ────│
   │<── Pull every 2hrs ─────│                          │
   │                          │                          │
   │── Read tracking.csv ──>│                          │
   │                          │                          │
```

## Testing

### Test Webhook:

Use curl to test your endpoint:
```bash
curl -X POST https://gorfos.com/api/webhooks/streicher/order-ready \
  -H "Content-Type: application/json" \
  -H "X-Streicher-Signature: sha256=..." \
  -d '{
    "event": "order.ready_for_pickup",
    "order": {
      "order_number": "ST-TEST-001",
      "total_amount": 1000.00,
      "currency": "EUR",
      "shipping": {
        "name": "Test Customer",
        "company": "Test Co",
        "address": "Test Street 1",
        "city": "Berlin",
        "postal": "10115",
        "country": "Germany"
      }
    }
  }'
```

### Test Sync:

Run sync manually:
```bash
/var/www/gorfos-sync/sync.sh
```

Check logs:
```bash
tail -f /var/log/gorfos-sync.log
tail -f /var/www/gorfos-sync/logs/webhooks.log
```

## Security Notes

1. **Webhook Secret**: Store in environment variable, not in code
2. **File Permissions**: Ensure logs and CSV files are not web-accessible
3. **SSH Deploy Key**: More secure than PAT - generate with `ssh-keygen -t ed25519`
4. **HTTPS Only**: Always use HTTPS for webhook endpoints

## Troubleshooting

### Webhook signature mismatch
- Check that `WEBHOOK_SECRET` matches what Streicher sends
- Verify HMAC calculation (sha256)

### Git push failures
- Check PAT/SSH key has write access to repository
- Check network connectivity to GitHub
- Check for merge conflicts (rare with append-only)

### CSV parsing errors
- Ensure PHP has read/write permissions on CSV files
- Check CSV format matches expected schema
- Validate column count matches headers

## Contact

For technical questions:
- Streicher Technical Team: [your-email@streichergmbh.com]

## Version

- Document Version: 1.0
- Last Updated: April 29, 2026
