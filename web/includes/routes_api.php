<?php
// ============ API ROUTES ============

// GET /cleanup-agent-test-data - Cleanup test data
if ($path === '/cleanup-agent-test-data' && $method === 'GET') {
    try {
        $pdo->exec("UPDATE agents SET name = 'JAMES PARKER' WHERE id = 1");
        $pdo->exec('DELETE FROM agent_chat_messages');
        $pdo->exec('DELETE FROM agent_chats');
        $pdo->exec("DELETE FROM tracking_communications WHERE tracking_number = 'STR20251224C376BA50BA' AND sender_type = 'customer' AND message LIKE '%Testing end-to-end%'");
        header('Content-Type: text/html');
        echo '<h1>✅ Cleanup Complete</h1>';
        echo '<p><a href="/admin/agent">Go to Agent Console</a></p>';
        exit;
    } catch (Exception $e) {
        header('Content-Type: text/html');
        echo '<h1>❌ Cleanup Error</h1>';
        echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        exit;
    }
}

// GET /api/search - Live search products
if ($path === '/api/search' && $method === 'GET') {
    $search = $_GET['q'] ?? '';
    $category = $_GET['category'] ?? null;
    $products = $productRepo->search($search, $category);
    json_response(array_slice($products, 0, 50));
}

// GET /api/products
if ($path === '/api/products' && $method === 'GET') {
    $category = $_GET['category'] ?? null;
    $search = $_GET['search'] ?? null;
    $products = $productRepo->search($search, $category);
    json_response($products);
}

// POST /api/cart - Add to cart
if ($path === '/api/cart' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $sku = $data['sku'] ?? null;
    $qty = max(1, (int)($data['qty'] ?? 1));
    if (!$sku) json_response(['error' => 'SKU required'], 400);
    $product = $productRepo->findBySku($sku);
    if (!$product) json_response(['error' => 'Product not found'], 404);
    $cart = $_SESSION['cart'] ?? [];
    $found = false;
    foreach ($cart as &$item) {
        if ($item['sku'] === $sku) {
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }
    unset($item);
    if (!$found) {
        $cart[] = ['sku' => $sku, 'name' => $product['name'], 'price' => (float)$product['unit_price'], 'qty' => $qty];
    }
    $_SESSION['cart'] = $cart;
    json_response(['ok' => true, 'cart_count' => get_cart_count(), 'message' => 'Added to cart']);
}

// GET /api/cart
if ($path === '/api/cart' && $method === 'GET') {
    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) { $total += $item['price'] * $item['qty']; }
    json_response(['cart' => $cart, 'count' => get_cart_count(), 'total' => $total]);
}

// DELETE /api/cart - Remove item
if ($path === '/api/cart' && $method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $sku = $data['sku'] ?? null;
    if ($sku) {
        $cart = $_SESSION['cart'] ?? [];
        $cart = array_filter($cart, fn($item) => $item['sku'] !== $sku);
        $_SESSION['cart'] = array_values($cart);
    }
    json_response(['ok' => true, 'cart_count' => get_cart_count()]);
}

// POST /api/checkout
if ($path === '/api/checkout' && $method === 'POST') {
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) json_response(['error' => 'Cart is empty'], 400);
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    try {
        $total = 0;
        foreach ($cart as $item) { $total += $item['price'] * $item['qty']; }
        $orderNumber = generate_order_number();
        $orderId = $orderRepo->create([
            'order_number' => $orderNumber,
            'status' => 'awaiting_payment',
            'total_amount' => $total,
            'currency' => 'USD',
            'billing_address' => json_encode($data['billing'] ?? []),
            'shipping_address' => json_encode($data['shipping'] ?? []),
        ], array_map(fn($item) => [
            'sku' => $item['sku'],
            'quantity' => $item['qty'],
            'unit_price' => $item['price'],
            'total_price' => $item['price'] * $item['qty']
        ], $cart));
        $_SESSION['cart'] = [];
        json_response(['ok' => true, 'order_number' => $orderNumber, 'redirect' => '/order/' . $orderId . '/payment']);
    } catch (Exception $e) {
        json_response(['error' => $e->getMessage()], 500);
    }
}

// POST /api/track
if ($path === '/api/track' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $tracking = $data['tracking_number'] ?? null;
    if (!$tracking) json_response(['error' => 'Tracking number required'], 400);
    $shipment = $shipmentRepo->findByTracking($tracking);
    if (!$shipment) json_response(['error' => 'Shipment not found'], 404);
    json_response($shipment);
}

// Agent Chat API
if (strpos($path, '/api/agent-chat') === 0) {
    require_once __DIR__ . '/agent_chat.php';
    // The specific chat logic is usually in agent_chat.php but if routes are here:
    if ($path === '/api/agent-chat/start' && $method === 'POST') {
        // ... (Referenced from index.php original lines 1639+)
    }
}
