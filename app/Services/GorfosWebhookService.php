<?php

declare(strict_types=1);

namespace Streicher\App\Services;

/**
 * GorfosWebhookService - Sends real-time notifications to Gorfos.com
 * when orders are ready for pickup
 */
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

    /**
     * Send order ready notification to Gorfos
     */
    public function sendOrderReady(array $order, array $items): array
    {
        if (!$this->enabled || empty($this->webhookUrl)) {
            return ['success' => false, 'error' => 'Gorfos webhook not configured - check GORFOS_WEBHOOK_URL in .env'];
        }

        $payload = [
            'event' => 'order.ready_for_pickup',
            'timestamp' => date('c'),
            'order' => [
                'id' => (int)$order['id'],
                'order_number' => $order['order_number'],
                'total_amount' => (float)$order['total_amount'],
                'currency' => $order['currency'] ?? 'EUR',
                'shipping' => [
                    'name' => $order['shipping_name'] ?? '',
                    'company' => $order['shipping_company'] ?? '',
                    'address' => $order['shipping_address'] ?? '',
                    'city' => $order['shipping_city'] ?? '',
                    'postal' => $order['shipping_postal'] ?? '',
                    'country' => $order['shipping_country'] ?? '',
                ],
                'items' => array_map(fn($item) => [
                    'sku' => $item['sku'] ?? '',
                    'name' => $item['name'] ?? '',
                    'quantity' => (int)($item['quantity'] ?? 1),
                    'unit_price' => (float)($item['unit_price'] ?? 0),
                ], $items),
                'payment_confirmed_at' => $order['payment_confirmed_at'] ?? null,
            ],
            'webhook_secret' => $this->webhookSecret,
        ];

        return $this->sendWebhook($payload);
    }

    /**
     * Send generic webhook payload
     */
    private function sendWebhook(array $payload): array
    {
        $ch = curl_init($this->webhookUrl);
        if (!$ch) {
            return ['success' => false, 'error' => 'Failed to initialize cURL'];
        }

        $jsonPayload = json_encode($payload);
        $signature = $this->generateSignature($jsonPayload);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Streicher-Event: ' . $payload['event'],
            'X-Streicher-Signature: sha256=' . $signature,
            'X-Streicher-Timestamp: ' . time()
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error' => 'cURL error: ' . $error,
                'http_code' => 0
            ];
        }

        $success = $httpCode >= 200 && $httpCode < 300;

        return [
            'success' => $success,
            'http_code' => $httpCode,
            'response' => $response,
            'error' => $success ? null : 'HTTP ' . $httpCode . ': ' . $response
        ];
    }

    /**
     * Generate HMAC signature for webhook verification
     */
    private function generateSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->webhookSecret);
    }

    /**
     * Test webhook configuration
     */
    public function testConnection(): array
    {
        $payload = [
            'event' => 'test.connection',
            'timestamp' => date('c'),
            'message' => 'This is a test from Streicher GmbH',
            'webhook_secret' => $this->webhookSecret
        ];

        return $this->sendWebhook($payload);
    }

    /**
     * Get configuration status
     */
    public function getStatus(): array
    {
        return [
            'enabled' => $this->enabled,
            'webhook_url' => $this->webhookUrl ? 'configured' : 'missing',
            'webhook_secret' => $this->webhookSecret ? 'configured' : 'missing',
            'ready' => $this->enabled && !empty($this->webhookUrl) && !empty($this->webhookSecret)
        ];
    }
}
