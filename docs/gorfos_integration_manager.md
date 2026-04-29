# Gorfos.com Integration Specification

## Overview
We're implementing a **shared GitHub repository** to sync order and tracking data between Streicher GmbH and Gorfos.com. This replaces traditional API calls with simple git-based file sharing.

## Data Flow Architecture

| Direction | File | Streicher | Gorfos |
|-----------|------|-----------|--------|
| Streicher → Gorfos | `orders.csv` | **Writes** | **Reads** |
| Gorfos → Streicher | `tracking_events.csv` | **Reads** | **Writes** |
| Gorfos → Streicher | `shipments.csv` | **Reads** | **Writes** |

## Repository Details

- **Repository**: `298althr/Machine-store`
- **Data Path**: `data/db/`
- **Authentication**: GitHub Personal Access Token (PAT)
- **Sync Frequency**: Every 2 hours (cron job)

## What You Need To Implement

### 1. GitHub Access
- Create a GitHub Personal Access Token (PAT) with `repo` scope
- Send the PAT to Streicher team for repository access
- Or Streicher will send you a PAT to use

### 2. Server Setup
Ensure your production server has:
```bash
git --version  # Must be installed
```

### 3. Repository Clone
```bash
git clone https://github.com/298althr/Machine-store.git /var/www/gorfos-sync
cd /var/www/gorfos-sync
git config user.email "sync@gorfos.com"
git config user.name "Gorfos Sync"
```

### 4. Sync Script (Every 2 Hours)
Create `/var/www/gorfos-sync/sync.sh`:
```bash
#!/bin/bash
REPO_DIR="/var/www/gorfos-sync"
GITHUB_PAT="your_pat_here"
REPO="298althr/Machine-store"

cd $REPO_DIR

# Configure remote with authentication
git remote set-url origin "https://${GITHUB_PAT}@github.com/${REPO}.git"

# Pull latest orders from Streicher
git pull origin main

# Check if we have new tracking events to push
if git diff --quiet data/db/tracking_events.csv 2>/dev/null; then
    echo "No tracking changes"
else
    git add data/db/tracking_events.csv
    git add data/db/shipments.csv 2>/dev/null
    git commit -m "Gorfos: Tracking update $(date '+%Y-%m-%d %H:%M')"
    git push origin main
    echo "Pushed tracking updates"
fi

# Reset remote to not expose PAT
git remote set-url origin "https://github.com/${REPO}.git"
```

Make executable:
```bash
chmod +x /var/www/gorfos-sync/sync.sh
```

### 5. Cron Job (Every 2 Hours)
```bash
# Edit crontab
crontab -e

# Add line:
0 */2 * * * /var/www/gorfos-sync/sync.sh >> /var/log/gorfos-sync.log 2>&1
```

## CSV Schema Reference

### orders.csv (Read-Only for Gorfos)
Streicher writes orders here when payment is confirmed.

| Column | Example | Description |
|--------|---------|-------------|
| `id` | `1` | Internal order ID |
| `order_number` | `ST-20260428-441BAF` | Unique order reference |
| `status` | `payment_confirmed` | Current order status |
| `shipping_name` | `Hans Müller` | Recipient name |
| `shipping_company` | `OilCo GmbH` | Company name |
| `shipping_address` | `Industriestrasse 12` | Street address |
| `shipping_city` | `Berlin` | City |
| `shipping_postal` | `10115` | Postal code |
| `shipping_country` | `Germany` | Country |
| `total_amount` | `2038903.16` | Order total |
| `currency` | `EUR` | Currency |
| `payment_confirmed_at` | `2026-04-28 07:21:10` | When ready for pickup |

**Important**: Only process orders with `status = 'payment_confirmed'` or `'ready_to_ship'`

### tracking_events.csv (Write for Gorfos)
Log every tracking status change here.

| Column | Example | Description |
|--------|---------|-------------|
| `id` | `1` | Auto-increment |
| `tracking_number` | `DHL123456789` | Carrier tracking number |
| `order_id` | `1` | Link to order |
| `status` | `in_transit` | Status code |
| `location` | `Hamburg Hub` | Current location |
| `event_time` | `2026-04-29 14:30:00` | Event timestamp |
| `notes` | `Departed facility` | Human-readable details |

**Status Codes** (use these exact values):
- `label_created` - Shipping label created
- `picked_up` - Package picked up
- `in_transit` - Moving through network
- `customs_hold` - Held at customs
- `out_for_delivery` - Out for delivery
- `delivered` - Successfully delivered
- `exception` - Problem/delay

### shipments.csv (Write for Gorfos)
Create shipment records when you fulfill orders.

| Column | Example | Description |
|--------|---------|-------------|
| `id` | `1` | Auto-increment |
| `order_id` | `1` | Link to order |
| `tracking_number` | `DHL123456789` | Your tracking number |
| `carrier` | `dhl` | Carrier code (dhl, fedex, ups) |
| `service_level` | `express` | Service type |
| `weight_kg` | `150.5` | Package weight |
| `dimensions` | `120x80x60` | Dimensions in cm |
| `status` | `shipped` | Shipment status |
| `shipped_at` | `2026-04-29 10:00:00` | Ship timestamp |
| `estimated_delivery` | `2026-05-02` | ETA |
| `shipping_cost` | `450.00` | Cost (optional) |
| `created_at` | `2026-04-29 10:00:00` | Record created |

## REQUIRED: Your Webhook Endpoint URL

**We need you to provide a webhook endpoint URL** that we can call when orders are ready for pickup.

### Please provide this information:
```
Your Webhook URL: ___________________________________
                    (e.g., https://gorfos.com/api/webhooks/streicher/order-ready)
```

When Streicher admin confirms payment and clicks "Send to Gorfos", we will send a **real-time webhook** to this endpoint:

### Webhook Endpoint You Need to Create
```
POST https://gorfos.com/api/webhooks/streicher/order-ready
```

### Webhook Payload
```json
{
  "event": "order.ready_for_pickup",
  "order": {
    "id": 1,
    "order_number": "ST-20260428-441BAF",
    "total_amount": 2038903.16,
    "currency": "EUR",
    "shipping": {
      "name": "Hans Müller",
      "company": "OilCo GmbH",
      "address": "Industriestrasse 12",
      "city": "Berlin",
      "postal": "10115",
      "country": "Germany"
    },
    "items": [
      {
        "sku": "HYD-0001",
        "name": "Hydraulic Pump HP-5000",
        "quantity": 2,
        "unit_price": 854182.00
      }
    ],
    "payment_confirmed_at": "2026-04-29T10:30:00Z"
  },
  "webhook_secret": "your_verification_secret"
}
```

### Your Response
Return HTTP 200 to acknowledge:
```json
{
  "received": true,
  "gorfos_order_id": "GF-2026-XXXX"
}
```

## Questions for You (Please Reply With Answers)

### Critical - We need these to proceed:

1. **Webhook URL** ⚠️ **REQUIRED**
   - Your endpoint to receive order notifications
   - Example format: `https://gorfos.com/api/webhooks/streicher/order-ready`
   - Must accept POST requests with JSON payload

2. **Authentication Preference**
   - How should we verify webhook authenticity?
   - Options: Shared secret in payload, HMAC signature, API key header

### Important for setup:

3. **Git**: Is git installed on your production server?
4. **PAT**: Will you create your own PAT or use one from Streicher?
5. **Carriers**: Which carriers do you use? (DHL, FedEx, UPS, etc.)
6. **Volume**: How many orders per day do you expect? (for capacity planning)

## Next Steps

1. **Reply with your webhook endpoint URL** ← **START HERE**
2. Confirm git is available on your server
3. Decide on PAT approach
4. We'll grant you repository access
5. You implement the sync script
6. Test with one order
7. Go live

**Please reply with your webhook URL as soon as possible so we can complete the integration.**

## Contact

For technical questions or to share your PAT/endpoint:
- Email: [your-email@streichergmbh.com]
- Or reply to this document

---

*Document Version: 1.0*
*Last Updated: April 29, 2026*
