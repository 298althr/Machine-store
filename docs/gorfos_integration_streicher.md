# Streicher → Gorfos Integration Guide (Internal)

## What We Built

### 1. GitHub Auto-Sync for CSV Database
- **Service**: `app/Services/GitHubSyncService.php`
- **Auto-sync**: After every database write, triggers GitHub commit
- **Manual buttons**: Admin dashboard has "Refresh Data" and "Sync to GitHub"

### 2. Admin Dashboard Buttons
Located in `web/templates/admin/dashboard.php`:
- **Refresh Data** (`POST /admin/sync/github/pull`) - Pulls latest from GitHub
- **Sync to GitHub** (`POST /admin/sync/github`) - Pushes local changes

### 3. Routes Added
In `web/includes/routes_admin.php`:
- `POST /admin/sync/github` - Manual sync (push)
- `POST /admin/sync/github/pull` - Pull latest
- `GET /admin/sync/status` - AJAX status check

## Your Action Items

### 1. Get New PAT for Gorfos
Generate a new GitHub PAT for shared use:
1. Go to GitHub → Settings → Developer settings → Personal access tokens
2. Generate new token (classic)
3. Scope: `repo` (full access to repositories)
4. Copy the token and share with Gorfos manager securely

### 2. Grant Repository Access
- Add Gorfos manager as collaborator to `298althr/Machine-store`
- Or give them the PAT to use

### 3. Webhook Endpoint (To Build)
We need a button that sends orders to Gorfos **immediately** when payment is confirmed.

**Button location**: Admin order detail page (`/admin/orders/{id}`)
**Button text**: "Send to Gorfos for Shipping"
**Action**: Sends webhook + updates order status

**Webhook payload** (sent to Gorfos):
```json
{
  "event": "order.ready_for_pickup",
  "order": {
    "id": 1,
    "order_number": "ST-20260428-441BAF",
    "shipping": { ... },
    "items": [ ... ],
    "payment_confirmed_at": "..."
  },
  "webhook_secret": "..."
}
```

## Implementation: "Send to Gorfos" Button

### Step 1: Add Webhook Config to .env
```bash
# Add to .env
GORFOS_WEBHOOK_URL="https://gorfos.com/api/webhooks/streicher/order-ready"
GORFOS_WEBHOOK_SECRET="your_random_secret_here"
GORFOS_ENABLED="true"
```

### Step 2: Create Webhook Service
File: `app/Services/GorfosWebhookService.php`

```php
<?php
declare(strict_types=1);

namespace Streicher\App\Services;

class GorfosWebhookService
{
    private string $webhookUrl;
    private string $webhookSecret;
    private bool $enabled;

    public function __construct()
    {
        $this->webhookUrl = $_ENV['GORFOS_WEBHOOK_URL'] ?? '';
        $this->webhookSecret = $_ENV['GORFOS_WEBHOOK_SECRET'] ?? '';
        $this->enabled = ($_ENV['GORFOS_ENABLED'] ?? 'false') === 'true';
    }

    public function sendOrderReady(array $order, array $items): array
    {
        if (!$this->enabled || empty($this->webhookUrl)) {
            return ['success' => false, 'error' => 'Gorfos not configured'];
        }

        $payload = [
            'event' => 'order.ready_for_pickup',
            'order' => [
                'id' => $order['id'],
                'order_number' => $order['order_number'],
                'total_amount' => $order['total_amount'],
                'currency' => $order['currency'],
                'shipping' => [
                    'name' => $order['shipping_name'],
                    'company' => $order['shipping_company'],
                    'address' => $order['shipping_address'],
                    'city' => $order['shipping_city'],
                    'postal' => $order['shipping_postal'],
                    'country' => $order['shipping_country'],
                ],
                'items' => array_map(fn($item) => [
                    'sku' => $item['sku'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ], $items),
                'payment_confirmed_at' => $order['payment_confirmed_at'],
            ],
            'webhook_secret' => $this->webhookSecret,
        ];

        $ch = curl_init($this->webhookUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Streicher-Signature: ' . $this->generateSignature($payload)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'http_code' => $httpCode,
            'response' => $response
        ];
    }

    private function generateSignature(array $payload): string
    {
        return hash_hmac('sha256', json_encode($payload), $this->webhookSecret);
    }
}
```

### Step 3: Add Route for "Send to Gorfos" Button
In `web/includes/routes_admin.php`:

```php
// POST /admin/orders/{id}/send-to-gorfos
if (preg_match('/^\/admin\/orders\/(\d+)\/send-to-gorfos$/', $path, $matches) && $method === 'POST') {
    require_admin();
    $orderId = (int)$matches[1];
    
    // Get order
    $orderRepo = new OrderRepository($pdo);
    $order = $orderRepo->find($orderId);
    
    if (!$order) {
        $_SESSION['flash_error'] = 'Order not found';
        header("Location: /admin/orders");
        exit;
    }
    
    // Check order is ready
    if (!in_array($order['status'], ['payment_confirmed', 'payment_uploaded'])) {
        $_SESSION['flash_error'] = 'Order must have confirmed payment before sending to Gorfos';
        header("Location: /admin/orders/{$orderId}");
        exit;
    }
    
    // Get order items
    $itemRepo = new OrderItemRepository($pdo);
    $items = $itemRepo->findByOrderId($orderId);
    
    // Send webhook
    $webhookService = new \Streicher\App\Services\GorfosWebhookService();
    $result = $webhookService->sendOrderReady($order, $items);
    
    if ($result['success']) {
        // Update order status
        $orderRepo->update($orderId, [
            'status' => 'ready_to_ship',
            'sent_to_gorfos_at' => date('Y-m-d H:i:s')
        ]);
        
        // Sync to GitHub immediately
        $syncService = new \Streicher\App\Services\GitHubSyncService();
        $syncService->sync(['orders']);
        
        $_SESSION['flash_success'] = 'Order sent to Gorfos successfully';
    } else {
        $_SESSION['flash_error'] = 'Failed to notify Gorfos: ' . ($result['response'] ?? 'Unknown error');
    }
    
    header("Location: /admin/orders/{$orderId}");
    exit;
}
```

### Step 4: Add Button to Order Detail Page
In `web/templates/admin/order_detail.php`, after "Confirm Payment" button:

```php
<?php if ($order['status'] === 'payment_confirmed'): ?>
<form action="/admin/orders/<?= $order['id'] ?>/send-to-gorfos" method="POST" style="display: inline;">
    <button type="submit" class="btn btn-accent" onclick="return confirm('Send this order to Gorfos for shipping?')">
        <i data-lucide="truck"></i>
        Send to Gorfos for Shipping
    </button>
</form>
<?php endif; ?>
```

## Sync Schedule

| Action | Frequency | Mechanism |
|--------|-----------|-----------|
| Auto-sync on write | Immediate | GitHubSyncService trigger |
| Manual sync | On demand | Admin dashboard button |
| Pull updates | Every 2 hours | Cron job (your server) |
| Gorfos sync | Every 2 hours | Their cron job |
| Real-time notification | Immediate | Webhook (payment → Gorfos) |

## Monitoring

Check sync status: `GET /admin/sync/status`

Response:
```json
{
  "enabled": true,
  "last_sync": "21ac33c Auto-sync: DB update at 2026-04-29 10:30:00",
  "pending_changes": false,
  "repo": "298althr/Machine-store",
  "tables": ["orders", "tracking_events", "shipments", ...]
}
```

## Files We Modified

1. `app/Services/GitHubSyncService.php` - New
2. `app/Services/CsvDb.php` - Added auto-sync trigger
3. `web/includes/routes_admin.php` - Added sync routes + webhook route (pending)
4. `web/templates/admin/dashboard.php` - Added sync buttons
5. `web/templates/admin/order_detail.php` - Add "Send to Gorfos" button (pending)
6. `.env` - Add Gorfos config (pending)

## What We Await

### From You:
1. New GitHub PAT to share with Gorfos
2. Gorfos webhook endpoint URL (ask them)
3. Webhook secret (generate random string)

### From Gorfos:
1. Confirmation git is installed
2. Their webhook endpoint URL
3. Their preferred authentication method
4. Confirmation they received and understood the integration doc

Once we have these, we'll:
1. Complete the "Send to Gorfos" button implementation
2. Test end-to-end with one order
3. Go live

## Questions?

Check `docs/gorfos_integration_manager.md` for what we're sending to Gorfos.

---

*Document Version: 1.0*
*Last Updated: April 29, 2026*
