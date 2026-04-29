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
if (($path === '/catalog' || $path === '/catalog/') && $method === 'GET') {
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
    
    // Collect spec filters from GET
    $specFilters = [];
    $reservedKeys = ['category', 'search', 'page', 'lang', 'currency', 'sort'];
    foreach ($_GET as $key => $value) {
        if (!in_array($key, $reservedKeys) && !empty($value)) {
            $specFilters[$key] = $value;
        }
    }
    
    $currentCategory = null;
    if ($categorySlug) {
        $currentCategory = $categoryRepo->findBySlug($categorySlug);
    }
    
    $products = $productRepo->search($search, $categorySlug, $specFilters);
    $filterOptions = $specRepo->getFilterOptions();
    
    // Add stock status
    foreach ($products as &$p) {
        $inv = $inventoryRepo->getByProductId((int)$p['id']);
        $p['stock_level'] = $inv ? (int)$inv['stock_level'] : 0;
        $p['in_stock'] = $p['stock_level'] > 0;
    }
    unset($p);
    
    render_template('catalog.php', [
        'title' => ($currentCategory ? $currentCategory['name'] . ' - ' : '') . 'Product Catalog - Streicher',
        'products' => $products,
        'categories' => $categories,
        'currentCategory' => $currentCategory,
        'search' => $search,
        'filterOptions' => $filterOptions,
        'specFilters' => $specFilters,
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
    $cart = get_cart();
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

// GET /cart/add - Add to cart (non-JS fallback for browser automation)
if ($path === '/cart/add' && $method === 'GET') {
    $sku = $_GET['sku'] ?? '';
    $qty = max(1, (int)($_GET['qty'] ?? 1));
    
    if ($sku) {
        $product = $productRepo->findBySku($sku);
        if ($product) {
            $cart = get_cart();
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
                $cart[] = [
                    'sku' => $sku,
                    'name' => $product['name'],
                    'slug' => $product['slug'] ?? '',
                    'price' => (float)$product['unit_price'],
                    'qty' => $qty
                ];
            }
            save_cart($cart);
        }
    }
    header('Location: /cart');
    exit;
}

// GET /checkout - Checkout page
if ($path === '/checkout' && $method === 'GET') {
    $cart = get_cart();
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
        $cart = get_cart();
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }
        
        $total = 0;
        $orderItems = [];
        $hasSoftware = false;
        $hasHardware = false;
        
        $displayCurrency = get_display_currency();
        $exchangeRate = get_exchange_rate();
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $product = $productRepo->findBySku($item['sku']);
            $productType = $product['product_type'] ?? 'hardware';
            
            if ($productType === 'software') {
                $hasSoftware = true;
            } else {
                $hasHardware = true;
                // Decrement stock for hardware
                if ($product) {
                    $newStock = $inventoryRepo->decrementStock((int)$product['id'], (int)$item['qty']);
                    
                    // Check for low stock alert
                    if ($newStock !== null) {
                        $threshold = 5; // Default
                        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'low_stock_threshold' LIMIT 1");
                        $stmt->execute();
                        $dbThreshold = $stmt->fetchColumn();
                        if ($dbThreshold !== false) $threshold = (int)$dbThreshold;
                        
                        $notifyEnabled = true;
                        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'notify_low_stock' LIMIT 1");
                        $stmt->execute();
                        $dbNotify = $stmt->fetchColumn();
                        if ($dbNotify !== false) $notifyEnabled = (bool)$dbNotify;

                        if ($notifyEnabled && $newStock <= $threshold) {
                            $telegramService->notifyLowStock($product, $newStock);
                        }
                    }
                }
            }

            $orderItems[] = [
                'product_id' => $product['id'] ?? 0,
                'sku' => $item['sku'],
                'name' => $product['name'] ?? $item['name'],
                'quantity' => $item['qty'],
                'unit_price' => convert_price((float)$item['price'], $displayCurrency),
                'total_price' => convert_price((float)$item['price'] * $item['qty'], $displayCurrency),
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
        $deliveryCostBase = ($deliveryMode === 'emergency') ? 15000 : 5000;
        $deliveryCost = convert_price((float)$deliveryCostBase, $displayCurrency);
        
        $orderNumber = generate_order_number();
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(substr($orderNumber, -6), 6, '0', STR_PAD_LEFT);
        
        $totalConverted = convert_price((float)$total, $displayCurrency);
        
        $orderData = [
            'user_id' => $_SESSION['user_id'] ?? null,
            'order_number' => $orderNumber,
            'invoice_number' => $invoiceNumber,
            'status' => 'awaiting_payment',
            'order_type' => $orderType,
            'subtotal' => $totalConverted,
            'delivery_mode' => $deliveryMode,
            'delivery_cost' => $deliveryCost,
            'total_amount' => ($totalConverted + $deliveryCost) * 1.19,
            'currency' => $displayCurrency,
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
        ];

        $orderId = $orderRepo->create($orderData, $orderItems);
        
        // Notify Admin via Telegram
        $orderData['id'] = $orderId;
        $telegramService->notifyNewOrder($orderData);
        
        save_cart([]);
        header('Location: /order/' . $orderId . '/invoice');
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error placing order: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}

// GET /order/lookup - Guest order lookup form
if ($path === '/order/lookup' && $method === 'GET') {
    render_template('pages/order_lookup.php', [
        'title' => 'Find Your Order - Streicher',
        'error' => $_GET['error'] ?? null,
    ]);
}

// POST /order/lookup - Guest order lookup handler
if ($path === '/order/lookup' && $method === 'POST') {
    $orderNumber = trim($_POST['order_number'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($orderNumber) || empty($email)) {
        header('Location: /order/lookup?error=missing_fields');
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM orders WHERE order_number = ? AND billing_email = ? LIMIT 1');
    $stmt->execute([$orderNumber, $email]);
    $order = $stmt->fetch();

    if (!$order) {
        header('Location: /order/lookup?error=not_found');
        exit;
    }

    header('Location: /order/' . $order['id'] . '/invoice');
    exit;
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

// GET /order/{id}/payment-confirm - "I have made payment" confirmation step
if (preg_match('#^/order/(\d+)/payment-confirm$#', $path, $m) && $method === 'GET') {
    $orderId = (int)$m[1];

    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        render_template('404.php', ['title' => 'Order Not Found']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT oi.*, p.name as product_name, p.sku FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();

    $settings = $settingRepo->all();

    render_template('order_payment_confirm.php', [
        'title' => 'Confirm Payment - Order ' . $order['order_number'],
        'order' => $order,
        'items' => $items,
        'settings' => $settings,
    ]);
}

// POST /order/{id}/payment-confirm - Record "I have made payment" intent
if (preg_match('#^/order/(\d+)/payment-confirm$#', $path, $m) && $method === 'POST') {
    $orderId = (int)$m[1];

    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        echo 'Order not found';
        exit;
    }

    // Only allow if currently awaiting payment
    if ($order['status'] !== 'awaiting_payment') {
        header('Location: /order/' . $orderId . '/payment');
        exit;
    }

    $pdo->prepare('UPDATE orders SET status = ?, updated_at = ? WHERE id = ?')
        ->execute(['payment_pending_upload', date('Y-m-d H:i:s'), $orderId]);

    // Notify admin via Telegram
    $orderData = $order;
    $orderData['id'] = $orderId;
    $telegramService->notifyPaymentClaimed($orderData);

    header('Location: /order/' . $orderId . '/payment');
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
        exit;
    }

    // Enforce confirmation step: redirect if user hasn't clicked "I have made payment"
    if ($order['status'] === 'awaiting_payment') {
        header('Location: /order/' . $orderId . '/payment-confirm');
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT oi.*, p.name as product_name, p.sku FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    $stmt = $pdo->prepare('SELECT * FROM payment_uploads WHERE order_id = ? ORDER BY created_at DESC');
    $stmt->execute([$orderId]);
    $uploads = $stmt->fetchAll();
    
    $settings = $settingRepo->all();
    
    render_template('order_payment.php', [
        'title' => 'Upload Payment - Order ' . $order['order_number'],
        'order' => $order,
        'items' => $items,
        'uploads' => $uploads,
        'settings' => $settings,
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

    // Notify admin via Telegram that receipt was uploaded
    $orderData = $order;
    $orderData['id'] = $orderId;
    $telegramService->notifyPaymentUploaded($orderData);
    
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

// GET /forgot-password - Password recovery placeholder
if ($path === '/forgot-password' && $method === 'GET') {
    render_template('pages/forgot_password.php', ['title' => 'Password Recovery - Streicher']);
}

// GET /register - Customer Registration
if ($path === '/register' && $method === 'GET') {
    render_template('pages/register.php', ['title' => 'Register - Streicher']);
}

// POST /register - Process registration
if ($path === '/register' && $method === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $terms = $_POST['terms'] ?? false;

    $errors = [];
    if (empty($firstName) || empty($lastName)) {
        $errors[] = 'Full name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Passwords do not match.';
    }
    if (!$terms) {
        $errors[] = 'You must accept the institutional terms.';
    }

    if (!empty($errors)) {
        render_template('pages/register.php', [
            'title' => 'Register - Streicher',
            'errors' => $errors,
        ]);
        exit;
    }

    $existing = $userRepo->findByEmail($email);
    if ($existing) {
        render_template('pages/register.php', [
            'title' => 'Register - Streicher',
            'errors' => ['An account with this email already exists.'],
        ]);
        exit;
    }

    $fullName = $firstName . ' ' . $lastName;
    $userId = $userRepo->create([
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'full_name' => $fullName,
        'role' => 'customer',
        'is_active' => 1,
    ]);

    $_SESSION['user_id'] = $userId;
    $_SESSION['user_role'] = 'customer';
    $_SESSION['user_name'] = $fullName;
    header('Location: /account');
    exit;
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

// GET /account/profile - Profile settings stub
if ($path === '/account/profile' && $method === 'GET') {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    render_template('pages/account_profile.php', ['title' => 'Profile - Streicher']);
}

// GET /account/quotes - Quotes stub
if ($path === '/account/quotes' && $method === 'GET') {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    render_template('pages/account_quotes.php', ['title' => 'Quotes - Streicher']);
}
