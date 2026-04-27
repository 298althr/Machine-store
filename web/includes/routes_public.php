<?php
// ============ HTML ROUTES ============

// GET / - Homepage with featured products
if ($path === '/' && $method === 'GET') {
    $products = $productRepo->featured(12);
    $categories = $categoryRepo->all();
    
    render_template('home.php', [
        'title' => 'Streicher GmbH - Industrial Parts & Equipment',
        'products' => $products,
        'categories' => $categories,
        'isHomePage' => true,
    ]);
}

// GET /catalog - Product catalog
if ($path === '/catalog' && $method === 'GET') {
    $categorySlug = $_GET['category'] ?? null;
    $search = $_GET['search'] ?? null;
    
    // Enhanced currency switching
    if (isset($_GET['currency']) && in_array($_GET['currency'], ['EUR', 'USD'])) {
        $_SESSION['display_currency'] = $_GET['currency'];
        setcookie('preferred_currency', $_GET['currency'], time() + (30 * 24 * 60 * 60), '/', '', false, true);
    }
    
    $categories = $categoryRepo->all();
    $search = $_GET['search'] ?? '';
    $categorySlug = $_GET['category'] ?? '';
    
    $currentCategory = null;
    if ($categorySlug) {
        $currentCategory = $categoryRepo->findBySlug($categorySlug);
    }
    
    $products = $productRepo->search($search, $categorySlug);
    
    render_template('catalog.php', [
        'title' => ($currentCategory ? $currentCategory['name'] . ' - ' : '') . 'Product Catalog - Streicher',
        'products' => $products,
        'categories' => $categories,
        'currentCategory' => $currentCategory,
        'search' => $search,
    ]);
}

// GET /product - Product detail
if ($path === '/product' && $method === 'GET') {
    $sku = $_GET['sku'] ?? '';
    if (!$sku) {
        header('Location: /catalog');
        exit;
    }
    
    $product = $productRepo->findBySku($sku);
    if (!$product) {
        header('Location: /catalog');
        exit;
    }
    
    $related = $productRepo->getRelated($sku, (int)$product['category_id']);
    
    render_template('product_detail.php', [
        'title' => $product['name'] . ' - Streicher',
        'product' => $product,
        'related' => $related
    ]);
}

// GET /cart - Shopping cart
if ($path === '/cart' && $method === 'GET') {
    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }
    
    render_template('cart.php', [
        'title' => __('shopping_cart') . ' - Streicher',
        'cart' => $cart,
        'total' => $total,
    ]);
}

// GET /checkout - Checkout page
if ($path === '/checkout' && $method === 'GET') {
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        header('Location: /cart');
        exit;
    }
    
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }
    
    $settings = $settingRepo->all();
    
    render_template('checkout.php', [
        'title' => 'Checkout - Streicher',
        'cart' => $cart,
        'total' => $total,
        'settings' => $settings,
    ]);
}

// POST /checkout - Process checkout (form submit)
if ($path === '/checkout' && $method === 'POST') {
    try {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }
        
        $total = 0;
        $orderItems = [];
        $hasSoftware = false;
        $hasHardware = false;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $product = $productRepo->findBySku($item['sku']);
            $productType = $product['product_type'] ?? 'hardware';
            
            if ($productType === 'software') {
                $hasSoftware = true;
            } else {
                $hasHardware = true;
            }

            $orderItems[] = [
                'product_id' => $product['id'] ?? 0,
                'sku' => $item['sku'],
                'name' => $product['name'] ?? $item['name'],
                'quantity' => $item['qty'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['qty'],
                'serial_number' => 'SN-' . strtoupper(substr($item['sku'], 0, 6)) . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4))
            ];
        }
        
        $orderType = 'hardware';
        if ($hasSoftware && !$hasHardware) {
            $orderType = 'software';
        } elseif ($hasSoftware && $hasHardware) {
            $orderType = 'mixed';
        }
        
        $deliveryMode = $_POST['delivery_mode'] ?? 'regular';
        $deliveryCost = ($deliveryMode === 'emergency') ? 15000 : 5000;
        
        $orderNumber = generate_order_number();
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(substr($orderNumber, -6), 6, '0', STR_PAD_LEFT);
        
        $orderId = $orderRepo->create([
            'user_id' => $_SESSION['user_id'] ?? null,
            'order_number' => $orderNumber,
            'invoice_number' => $invoiceNumber,
            'status' => 'awaiting_payment',
            'order_type' => $orderType,
            'subtotal' => $total,
            'delivery_mode' => $deliveryMode,
            'delivery_cost' => $deliveryCost,
            'total_amount' => ($total + $deliveryCost) * 1.19,
            'currency' => $_SESSION['display_currency'] ?? 'EUR',
            'billing_name' => $_POST['name'] ?? '',
            'billing_company' => $_POST['company'] ?? '',
            'billing_email' => $_POST['email'] ?? '',
            'billing_phone' => $_POST['phone'] ?? '',
            'billing_address' => $_POST['address'] ?? '',
            'billing_city' => $_POST['city'] ?? '',
            'billing_postal' => $_POST['zip'] ?? '',
            'billing_country' => $_POST['country'] ?? 'Germany',
            'shipping_name' => $_POST['name'] ?? '',
            'shipping_company' => $_POST['company'] ?? '',
            'shipping_address' => $_POST['address'] ?? '',
            'shipping_city' => $_POST['city'] ?? '',
            'shipping_postal' => $_POST['zip'] ?? '',
            'shipping_country' => $_POST['country'] ?? 'Germany',
            'delivery_facility' => $_POST['delivery_facility'] ?? null,
            'notes' => $_POST['notes'] ?? null,
        ], $orderItems);
        
        $_SESSION['cart'] = [];
        header('Location: /order/' . $orderId . '/invoice');
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error placing order: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}

// GET /order/{id}/invoice - Order Invoice
if (preg_match('#^/order/(\d+)/invoice$#', $path, $m) && $method === 'GET') {
    $orderId = (int)$m[1];
    $order = $orderRepo->find($orderId);
    
    if (!$order) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Order Not Found']);
    }
    
    $orderItems = $orderRepo->getItems($orderId);
    
    render_template('proforma_invoice.php', [
        'title' => 'Order Invoice - ' . ($order['invoice_number'] ?? $order['order_number']),
        'order' => $order,
        'orderItems' => $orderItems,
    ]);
}

// GET /order/{id}/invoice/pdf - Download PDF Invoice
if (preg_match('#^/order/(\d+)/invoice/pdf$#', $path, $m) && $method === 'GET') {
    $orderId = (int)$m[1];
    $order = $orderRepo->find($orderId);
    if (!$order) {
        http_response_code(404);
        exit('Order not found');
    }
    
    $items = $orderRepo->getItems($orderId);
    $pdfContent = $pdfService->generateInvoice($order, $items);
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Invoice-' . $order['order_number'] . '.pdf"');
    echo $pdfContent;
    exit;
}

// GET /order/{id}/payment - Payment upload page
if (preg_match('#^/order/(\d+)/payment$#', $path, $m) && $method === 'GET') {
    $orderId = (int)$m[1];
    
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Order Not Found']);
    }
    
    $stmt = $pdo->prepare('SELECT oi.*, p.name as product_name, p.sku FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    $stmt = $pdo->prepare('SELECT * FROM payment_uploads WHERE order_id = ? ORDER BY created_at DESC');
    $stmt->execute([$orderId]);
    $uploads = $stmt->fetchAll();
    
    render_template('order_payment.php', [
        'title' => 'Upload Payment - Order ' . $order['order_number'],
        'order' => $order,
        'items' => $items,
        'uploads' => $uploads,
    ]);
}

// POST /order/{id}/payment - Upload payment receipt
if (preg_match('#^/order/(\d+)/payment$#', $path, $m) && $method === 'POST') {
    $orderId = (int)$m[1];
    
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        http_response_code(404);
        echo 'Order not found';
        exit;
    }
    
    if (empty($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
        header('Location: /order/' . $orderId . '/payment?error=upload_failed');
        exit;
    }
    
    $file = $_FILES['receipt'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    
    if (!in_array($file['type'], $allowedTypes)) {
        header('Location: /order/' . $orderId . '/payment?error=invalid_type');
        exit;
    }
    
    $uploadDir = __DIR__ . '/../uploads/payments/' . $orderId;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'receipt_' . time() . '_' . uniqid() . '.' . $ext;
    $filepath = $uploadDir . '/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        header('Location: /order/' . $orderId . '/payment?error=save_failed');
        exit;
    }
    
    $stmt = $pdo->prepare(
        'INSERT INTO payment_uploads (order_id, filename, original_filename, file_path, file_size, mime_type, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $orderId,
        $filename,
        $file['name'],
        'uploads/payments/' . $orderId . '/' . $filename,
        $file['size'],
        $file['type'],
        $_POST['notes'] ?? null,
    ]);
    
    $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')
        ->execute(['payment_uploaded', $orderId]);
    
    header('Location: /order/' . $orderId . '/confirmation');
    exit;
}

// GET /order/{id}/confirmation - Order confirmation
if (preg_match('#^/order/(\d+)/confirmation$#', $path, $m) && $method === 'GET') {
    $orderId = (int)$m[1];
    
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Order Not Found']);
    }
    
    $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    render_template('order_confirmation.php', [
        'title' => 'Order Confirmation - ' . $order['order_number'],
        'order' => $order,
        'items' => $items,
    ]);
}

// GET /order/{id} - Order status page
if (preg_match('#^/order/(\d+)$#', $path, $m) && $method === 'GET') {
    $orderId = (int)$m[1];
    
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Order Not Found']);
    }
    
    $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE order_id = ?');
    $stmt->execute([$orderId]);
    $shipments = $stmt->fetchAll();
    
    render_template('order_status.php', [
        'title' => 'Order ' . $order['order_number'] . ' - Streicher',
        'order' => $order,
        'items' => $items,
        'shipments' => $shipments,
    ]);
}

// GET /agent - Agent live chat page
if ($path === '/agent' && $method === 'GET') {
    $initialTracking = $_GET['tracking'] ?? '';
    render_template('agent.php', [
        'title' => 'Customs Agent Live Chat',
        'initialTracking' => $initialTracking,
        'csrfToken' => $_SESSION['csrf_token'] ?? '',
    ]);
}

// GET /track - Tracking page
if ($path === '/track' && $method === 'GET') {
    $trackingNumber = $_GET['tracking'] ?? null;
    $shipment = null;
    $events = [];
    $communications = [];
    $unreadCount = 0;
    
    if ($trackingNumber) {
        $stmt = $pdo->prepare('SELECT * FROM shipments WHERE tracking_number = ?');
        $stmt->execute([$trackingNumber]);
        $shipment = $stmt->fetch();
        
        if ($shipment) {
            $events = json_decode($shipment['events'] ?? '[]', true) ?: [];
            
            // Fetch communications for this order
            $stmt = $pdo->prepare('SELECT * FROM tracking_communications WHERE order_id = ? ORDER BY created_at ASC');
            $stmt->execute([$shipment['order_id']]);
            $communications = $stmt->fetchAll();
            
            // Auto-send welcome message if this is the first time viewing (no communications yet)
            if (empty($communications)) {
                $welcomeMessage = "ðŸŽ‰ Great news! Your order has been shipped and is on its way!\n\n" .
                    "Your tracking number: " . $trackingNumber . "\n\n" .
                    "ðŸ“¦ You can track your shipment status on this page at any time.\n\n" .
                    "ðŸ’¬ Have questions? Need assistance? Feel free to send us a message right here! " .
                    "Our logistics team monitors this chat and will respond promptly to any inquiries, " .
                    "feedback, or concerns you may have.\n\n" .
                    "We're here to help make your delivery experience smooth and hassle-free!\n\n" .
                    "â€” Streicher Logistics Team";
                
                $stmt = $pdo->prepare(
                    'INSERT INTO tracking_communications (order_id, tracking_number, sender_type, sender_name, message_type, message) 
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $shipment['order_id'],
                    $trackingNumber,
                    'admin',
                    'Streicher Logistics',
                    'message',
                    $welcomeMessage,
                ]);
                
                // Re-fetch communications to include the welcome message
                $stmt = $pdo->prepare('SELECT * FROM tracking_communications WHERE order_id = ? ORDER BY created_at ASC');
                $stmt->execute([$shipment['order_id']]);
                $communications = $stmt->fetchAll();
            }
            
            // Count unread messages from admin
            $unreadCount = 0;
            foreach ($communications as $comm) {
                if ($comm['sender_type'] === 'admin' && empty($comm['is_read'])) {
                    $unreadCount++;
                }
            }
        }
    }
    
    render_template('tracking.php', [
        'title' => 'Track Shipment - Streicher',
        'trackingNumber' => $trackingNumber,
        'shipment' => $shipment,
        'events' => $events,
        'communications' => $communications,
        'unreadCount' => $unreadCount,
    ]);
}

// GET /support - Support page
if ($path === '/support' && $method === 'GET') {
    render_template('pages/support.php', ['title' => 'Technical Support - Streicher']);
}

// GET /faq - FAQ page
if ($path === '/faq' && $method === 'GET') {
    render_template('pages/faq.php', ['title' => 'FAQ - Streicher']);
}

// GET /about - About page
if ($path === '/about' && $method === 'GET') {
    render_template('pages/about.php', ['title' => 'About Us - Streicher']);
}

// GET /profile - Company Profile page
if ($path === '/profile' && $method === 'GET') {
    render_template('pages/profile.php', ['title' => $lang === 'de' ? 'Unternehmensprofil - Streicher' : 'Company Profile - Streicher']);
}

// GET /news - News page
if ($path === '/news' && $method === 'GET') {
    render_template('pages/news.php', ['title' => $lang === 'de' ? 'Neuigkeiten - Streicher' : 'News - Streicher']);
}

// GET /mediathek - Media Library page
if ($path === '/mediathek' && $method === 'GET') {
    render_template('pages/mediathek.php', ['title' => $lang === 'de' ? 'Mediathek - Streicher' : 'Media Library - Streicher']);
}

// GET /business-sectors - Business Sectors page
if ($path === '/business-sectors' && $method === 'GET') {
    render_template('pages/business-sectors.php', ['title' => $lang === 'de' ? 'Geschäftsbereiche - Streicher' : 'Business Sectors - Streicher']);
}

// GET /reference-projects - Reference Projects page
if ($path === '/reference-projects' && $method === 'GET') {
    render_template('pages/reference-projects.php', ['title' => $lang === 'de' ? 'Referenzprojekte - Streicher' : 'Reference Projects - Streicher']);
}

// GET /hse-q - HSE-Q page
if ($path === '/hse-q' && $method === 'GET') {
    render_template('pages/hse-q.php', ['title' => 'HSE-Q - Streicher']);
}

// GET /events - Events page
if ($path === '/events' && $method === 'GET') {
    render_template('pages/events.php', ['title' => $lang === 'de' ? 'Veranstaltungen - Streicher' : 'Events - Streicher']);
}

// GET /careers - Careers page
if ($path === '/careers' && $method === 'GET') {
    render_template('pages/careers.php', ['title' => 'Careers - Streicher']);
}

// GET /privacy - Privacy Policy
if ($path === '/privacy' && $method === 'GET') {
    render_template('pages/privacy.php', ['title' => 'Privacy Policy - Streicher']);
}

// GET /terms - Terms & Conditions
if ($path === '/terms' && $method === 'GET') {
    render_template('pages/terms.php', ['title' => 'Terms & Conditions - Streicher']);
}

// GET /shipping - Shipping Information
if ($path === '/shipping' && $method === 'GET') {
    render_template('pages/shipping.php', ['title' => 'Shipping Information - Streicher']);
}

// GET /returns - Returns Policy
if ($path === '/returns' && $method === 'GET') {
    render_template('pages/returns.php', ['title' => 'Returns Policy - Streicher']);
}

// GET /login - Customer Login
if ($path === '/login' && $method === 'GET') {
    render_template('pages/login.php', ['title' => 'Login - Streicher']);
}

// POST /login - Customer Login
if ($path === '/login' && $method === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        header('Location: /account');
        exit;
    }
    
    render_template('pages/login.php', [
        'title' => 'Login - Streicher',
        'error' => 'Invalid email or password',
    ]);
}

// GET /register - Customer Registration
if ($path === '/register' && $method === 'GET') {
    render_template('pages/register.php', ['title' => 'Register - Streicher']);
}

// GET /account - Customer Account
if ($path === '/account' && $method === 'GET') {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10');
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
    
    render_template('pages/account.php', [
        'title' => 'My Account - Streicher',
        'orders' => $orders,
    ]);
}
