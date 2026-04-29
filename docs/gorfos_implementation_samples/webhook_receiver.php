<?php
/**
 * Gorfos Webhook Receiver
 * Endpoint: /api/webhooks/streicher/order-ready
 * 
 * This receives real-time notifications from Streicher when orders are ready for pickup.
 */

// Configuration - UPDATE THESE
define('WEBHOOK_SECRET', 'your_shared_secret_here');  // Streicher will provide this
define('PROCESSED_LOG', '/var/www/gorfos-sync/processed_orders.txt');
define('ORDERS_CSV', '/var/www/gorfos-sync/data/orders.csv');

// Response helper
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Main webhook handler
function handleWebhook() {
    // Only accept POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['error' => 'Method not allowed'], 405);
    }

    // Get raw body
    $body = file_get_contents('php://input');
    if (empty($body)) {
        jsonResponse(['error' => 'Empty body'], 400);
    }

    // Verify HMAC signature
    $receivedSig = $_SERVER['HTTP_X_STREICHER_SIGNATURE'] ?? '';
    $expectedSig = 'sha256=' . hash_hmac('sha256', $body, WEBHOOK_SECRET);
    
    if (!hash_equals($expectedSig, $receivedSig)) {
        error_log("Webhook signature mismatch. Received: {$receivedSig}, Expected: {$expectedSig}");
        jsonResponse(['error' => 'Invalid signature'], 401);
    }

    // Parse payload
    $payload = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        jsonResponse(['error' => 'Invalid JSON'], 400);
    }

    // Validate event type
    if (($payload['event'] ?? '') !== 'order.ready_for_pickup') {
        jsonResponse(['error' => 'Unknown event type'], 400);
    }

    $order = $payload['order'] ?? [];
    $orderNumber = $order['order_number'] ?? '';

    if (empty($orderNumber)) {
        jsonResponse(['error' => 'Missing order_number'], 400);
    }

    // Check if already processed (idempotency)
    if (isOrderProcessed($orderNumber)) {
        jsonResponse([
            'received' => true,
            'gorfos_order_id' => 'GF-' . date('Y') . '-' . substr(md5($orderNumber), 0, 8),
            'note' => 'Order already processed'
        ]);
    }

    // Log the webhook receipt
    logWebhookReceipt($order);

    // Mark as processed
    markOrderProcessed($orderNumber);

    // TODO: Trigger your internal order processing
    // - Create shipment in your system
    // - Notify warehouse
    // - etc.
    processOrderInternal($order);

    // Respond with success
    jsonResponse([
        'received' => true,
        'gorfos_order_id' => 'GF-' . date('Y') . '-' . substr(md5($orderNumber), 0, 8),
        'order_number' => $orderNumber,
        'timestamp' => date('c')
    ]);
}

// Check if order was already processed
function isOrderProcessed($orderNumber) {
    if (!file_exists(PROCESSED_LOG)) {
        return false;
    }
    
    $processed = file(PROCESSED_LOG, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($orderNumber, $processed);
}

// Mark order as processed
function markOrderProcessed($orderNumber) {
    $line = $orderNumber . ',' . date('Y-m-d H:i:s') . "\n";
    file_put_contents(PROCESSED_LOG, $line, FILE_APPEND | LOCK_EX);
}

// Log webhook for debugging
function logWebhookReceipt($order) {
    $logDir = '/var/www/gorfos-sync/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/webhooks.log';
    $entry = date('Y-m-d H:i:s') . ' | ' . 
             $order['order_number'] . ' | ' . 
             $order['shipping']['name'] . ' | ' .
             $order['total_amount'] . ' ' . $order['currency'] . "\n";
    
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

// Internal order processing (implement your logic here)
function processOrderInternal($order) {
    // Example: Send email to warehouse, create internal shipment, etc.
    // This is where you integrate with your existing order management system
    
    error_log("Processing order: " . $order['order_number']);
    
    // TODO: Your integration code here
    // Examples:
    // - Insert into your database
    // - Send notification to warehouse team
    // - Create shipping label
    // - etc.
}

// Handle the webhook
handleWebhook();
