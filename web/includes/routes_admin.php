// ============ ADMIN ROUTES ============

// GET /admin/login
if ($path === '/admin/login' && $method === 'GET') {
    render_template('admin_login.php', ['title' => 'Admin Login']);
}

// POST /admin/login
if ($path === '/admin/login' && $method === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Rate limiting: Check failed attempts in last 15 minutes
    $failedAttempts = $userRepo->countFailedAttempts($clientIp, 15);
    
    if ($failedAttempts >= 5) {
        render_template('admin_login.php', [
            'title' => 'Admin Login',
            'error' => 'Too many failed attempts. Please try again in 15 minutes.',
        ]);
        exit;
    }
    
    $user = $userRepo->findByEmail($email);
    
    if ($user && $user['role'] === 'admin' && password_verify($password, $user['password_hash'])) {
        // Log successful attempt
        $userRepo->logLoginAttempt($clientIp, $email, true);
        
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = 'admin';
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header('Location: /admin');
        exit;
    }
    
    // Log failed attempt
    $userRepo->logLoginAttempt($clientIp, $email, false);
    
    render_template('admin_login.php', [
        'title' => 'Admin Login',
        'error' => 'Invalid credentials',
    ]);
}

// GET /admin/logout
if ($path === '/admin/logout' || $path === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
}

// GET /admin - Dashboard
if ($path === '/admin' && $method === 'GET') {
    require_admin();
    
    $stats = $orderRepo->stats();
    $stats['total_products'] = count($productRepo->all(true));
    
    $recentOrders = $orderRepo->all(null, 10);
    $pendingPayments = $orderRepo->all('payment_uploaded', 5);
    
    render_admin_template('dashboard.php', [
        'title' => 'Admin Dashboard - Streicher',
        'stats' => $stats,
        'recentOrders' => $recentOrders,
        'pendingPayments' => $pendingPayments,
    ]);
}

// GET /admin/orders
if ($path === '/admin/orders' && $method === 'GET') {
    require_admin();
    
    $status = $_GET['status'] ?? null;
    $orders = $orderRepo->all($status);
    
    render_admin_template('orders.php', [
        'title' => 'Orders - Admin',
        'orders' => $orders,
        'currentStatus' => $status,
    ]);
}

// GET /admin/orders/{id}
if (preg_match('#^/admin/orders/(\d+)$#', $path, $m) && $method === 'GET') {
    require_admin();
    $orderId = (int)$m[1];
    
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        header('Location: /admin/orders');
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT oi.*, p.name as product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
    $stmt = $pdo->prepare('SELECT * FROM payment_uploads WHERE order_id = ? ORDER BY created_at DESC');
    $stmt->execute([$orderId]);
    $paymentUploads = $stmt->fetchAll();
    
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE order_id = ?');
    $stmt->execute([$orderId]);
    $shipments = $stmt->fetchAll();
    
    render_admin_template('order_detail.php', [
        'title' => 'Order ' . $order['order_number'] . ' - Admin',
        'order' => $order,
        'items' => $items,
        'paymentUploads' => $paymentUploads,
        'shipments' => $shipments,
    ]);
}

// GET /admin/products
if ($path === '/admin/products' && $method === 'GET') {
    require_admin();
    $products = $productRepo->all(false); // Get all, including inactive
    
    render_admin_template('products.php', [
        'title' => 'Products - Admin',
        'products' => $products,
    ]);
}

// POST /admin/products/new
if ($path === '/admin/products/new' && $method === 'POST') {
    require_admin();
    
    $productRepo->create([
        'sku' => $_POST['sku'],
        'category_id' => $_POST['category_id'] ?: null,
        'name' => $_POST['name'],
        'short_desc' => $_POST['short_desc'] ?? '',
        'description' => $_POST['description'] ?? '',
        'unit_price' => $_POST['unit_price'],
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ]);
    
    header('Location: /admin/products');
    exit;
}

// POST /admin/products/{id}/edit
if (preg_match('#^/admin/products/(\d+)/edit$#', $path, $m) && $method === 'POST') {
    require_admin();
    $productId = (int)$m[1];
    
    $productRepo->update($productId, [
        'sku' => $_POST['sku'],
        'category_id' => $_POST['category_id'] ?: null,
        'name' => $_POST['name'],
        'short_desc' => $_POST['short_desc'] ?? '',
        'description' => $_POST['description'] ?? '',
        'unit_price' => $_POST['unit_price'],
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ]);
    
    header('Location: /admin/products');
    exit;
}
// POST /admin/products/{id}/delete - Delete product
if (preg_match('#^/admin/products/(\d+)/delete$#', $path, $m) && $method === 'POST') {
    require_admin();
    $productId = (int)$m[1];
    $productRepo->delete($productId);
    header('Location: /admin/products');
    exit;
}

// GET /admin/support
if ($path === '/admin/support' && $method === 'GET') {
    require_admin();
    $status = $_GET['status'] ?? null;
    $tickets = $supportRepo->all($status);
    
    render_admin_template('support_tickets.php', [
        'title' => 'Support Tickets - Admin',
        'tickets' => $tickets,
        'currentStatus' => $status,
    ]);
}

// GET /admin/support/{id}
if (preg_match('#^/admin/support/(\d+)$#', $path, $m) && $method === 'GET') {
    require_admin();
    $ticketId = (int)$m[1];
    $ticket = $supportRepo->find($ticketId);
    
    if (!$ticket) {
        header('Location: /admin/support');
        exit;
    }
    
    render_admin_template('support_ticket_detail.php', [
        'title' => 'Ticket #' . $ticket['ticket_number'] . ' - Admin',
        'ticket' => $ticket,
    ]);
}

// POST /admin/support/{id}/update
if (preg_match('#^/admin/support/(\d+)/update$#', $path, $m) && $method === 'POST') {
    require_admin();
    $ticketId = (int)$m[1];
    $status = $_POST['status'] ?? 'open';
    $adminNotes = $_POST['admin_notes'] ?? '';
    
    $supportRepo->updateStatus($ticketId, $status, $adminNotes);
    
    set_flash_message('Ticket status updated successfully');
    header('Location: /admin/support/' . $ticketId);
    exit;
}

// GET /admin/shipments - All shipments
if ($path === '/admin/shipments' && $method === 'GET') {
    require_admin();
    
    try {
        $status = $_GET['status'] ?? null;
        $sql = 'SELECT s.*, o.order_number, o.billing_address 
                FROM shipments s 
                LEFT JOIN orders o ON s.order_id = o.id 
                ORDER BY s.created_at DESC';
        
        $stmt = $pdo->query($sql);
        $shipments = $stmt->fetchAll();
        
        render_admin_template('shipments.php', [
            'title' => 'Shipments',
            'shipments' => $shipments,
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error loading shipments: " . $e->getMessage();
        exit;
    }
}

// GET /admin/agents - Redirect to agent console
if ($path === '/admin/agents' && $method === 'GET') {
    require_admin();
    header('Location: /admin/agent');
    exit;
}

// GET /admin/agent - Agent console
if ($path === '/admin/agent' && $method === 'GET') {
    require_admin();
    try {
        $agent = get_authenticated_agent($pdo);
        if (!$agent) {
            render_admin_template('agent_console.php', [
                'title' => 'Agent Console',
                'agentError' => 'No active agent found. Please run /setup-database or create an agent record.',
                'csrfToken' => $_SESSION['csrf_token'] ?? '',
            ]);
        } else {
            render_admin_template('agent_console.php', [
                'title' => 'Agent Console',
                'agent' => $agent,
                'csrfToken' => $_SESSION['csrf_token'] ?? '',
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error loading agent console: " . $e->getMessage();
        exit;
    }
}

// GET /admin/api/agent/chats - list chats for current agent
if ($path === '/admin/api/agent/chats' && $method === 'GET') {
    require_admin();
    try {
        $agent = get_authenticated_agent($pdo);
        if (!$agent) {
            json_response(['error' => 'No agent configured'], 404);
        }
        $status = $_GET['status'] ?? null;
        $limit = min((int)($_GET['limit'] ?? 40), 100);
        $chats = fetch_agent_chats_for_agent((int)$agent['id'], $limit, $status ?: null, $pdo);
        json_response([
            'ok' => true,
            'chats' => $chats,
            'agent' => [
                'id' => (int)$agent['id'],
                'name' => $agent['name'],
                'email' => $agent['email'],
            ],
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        json_response(['error' => 'Failed to fetch chats: ' . $e->getMessage()], 500);
    }
}

// GET /admin/api/agent/chats/{id}/messages
if (preg_match('#^/admin/api/agent/chats/(\d+)/messages$#', $path, $m) && $method === 'GET') {
    require_admin();
    $chatId = (int)$m[1];
    $agent = get_authenticated_agent($pdo);
    if (!$agent) {
        json_response(['error' => 'No agent configured'], 404);
    }
    $chat = get_agent_chat_with_access($chatId, null, $pdo);
    if (!$chat || (int)$chat['agent_id'] !== (int)$agent['id']) {
        json_response(['error' => 'Chat not found'], 404);
    }
    $sinceId = isset($_GET['since_id']) ? (int)$_GET['since_id'] : null;
    $messages = fetch_agent_chat_messages($chatId, $sinceId, $pdo);
    json_response([
        'ok' => true,
        'chat' => [
            'id' => (int)$chat['id'],
            'tracking_number' => $chat['tracking_number'],
            'customer_name' => $chat['customer_name'],
            'customer_email' => $chat['customer_email'],
            'customer_phone' => $chat['customer_phone'],
            'is_open' => (int)$chat['is_open'] === 1,
            'shipment_status' => $chat['shipment_status'],
        ],
        'messages' => $messages,
    ]);
}

// POST /admin/api/agent/chats/{id}/message
if (preg_match('#^/admin/api/agent/chats/(\d+)/message$#', $path, $m) && $method === 'POST') {
    require_admin();
    $chatId = (int)$m[1];
    $agent = get_authenticated_agent();
    if (!$agent) {
        json_response(['error' => 'No agent configured'], 404);
    }
    $chat = get_agent_chat_with_access($chatId, null);
    if (!$chat || (int)$chat['agent_id'] !== (int)$agent['id']) {
        json_response(['error' => 'Chat not found'], 404);
    }

    $messageText = trim($_POST['message'] ?? '');
    $attachment = [];
    try {
        if (!empty($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
            $attachment = process_agent_chat_attachment($_FILES['attachment'], $chat['tracking_number']);
        }
    } catch (RuntimeException $e) {
        $errorMap = [
            'upload_error' => 'Could not upload attachment. Please try again.',
            'too_large' => 'Attachment exceeds the 5 MB limit.',
            'invalid_type' => 'Unsupported file type. Use PDF, JPG, PNG, or DOCX.',
        ];
        $code = $e->getMessage();
        json_response(['error' => $errorMap[$code] ?? 'Attachment upload failed'], 400);
    }

    if ($messageText === '' && empty($attachment)) {
        json_response(['error' => 'Message text or attachment is required'], 400);
    }

    $savedMessage = save_agent_chat_message($chat, 'agent', $agent['name'] ?? 'Agent', $messageText, $attachment);
    json_response(['ok' => true, 'message' => $savedMessage]);
}

// PUT /admin/api/agent/messages/{id} - Edit message
if (preg_match('#^/admin/api/agent/messages/(\d+)$#', $path, $m) && $method === 'PUT') {
    require_admin();
    $messageId = (int)$m[1];
    $agent = get_authenticated_agent($pdo);
    if (!$agent) {
        json_response(['error' => 'No agent configured'], 404);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $newMessage = trim($input['message'] ?? '');
    
    if ($newMessage === '') {
        json_response(['error' => 'Message text is required'], 400);
    }
    
    // Verify message exists and belongs to this agent
    $stmt = $pdo->prepare('SELECT * FROM agent_chat_messages WHERE id = ?');
    $stmt->execute([$messageId]);
    $msg = $stmt->fetch();
    
    if (!$msg) {
        json_response(['error' => 'Message not found'], 404);
    }
    
    // Update message
    $stmt = $pdo->prepare('UPDATE agent_chat_messages SET message = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$newMessage, $messageId]);
    
    json_response(['ok' => true, 'message' => 'Message updated']);
}

// DELETE /admin/api/agent/messages/{id} - Delete message
if (preg_match('#^/admin/api/agent/messages/(\d+)$#', $path, $m) && $method === 'DELETE') {
    require_admin();
    $messageId = (int)$m[1];
    $agent = get_authenticated_agent($pdo);
    if (!$agent) {
        json_response(['error' => 'No agent configured'], 404);
    }
    
    // Verify message exists
    $stmt = $pdo->prepare('SELECT * FROM agent_chat_messages WHERE id = ?');
    $stmt->execute([$messageId]);
    $msg = $stmt->fetch();
    
    if (!$msg) {
        json_response(['error' => 'Message not found'], 404);
    }
    
    // Delete message
    $stmt = $pdo->prepare('DELETE FROM agent_chat_messages WHERE id = ?');
    $stmt->execute([$messageId]);
    
    json_response(['ok' => true, 'message' => 'Message deleted']);
}

// DELETE /admin/api/agent/chats/{id}/wipe - Wipe all messages in chat
if (preg_match('#^/admin/api/agent/chats/(\d+)/wipe$#', $path, $m) && $method === 'DELETE') {
    require_admin();
    $chatId = (int)$m[1];
    $agent = get_authenticated_agent($pdo);
    if (!$agent) {
        json_response(['error' => 'No agent configured'], 404);
    }
    
    // Verify chat exists and belongs to this agent
    $stmt = $pdo->prepare('SELECT * FROM agent_chats WHERE id = ? AND agent_id = ?');
    $stmt->execute([$chatId, $agent['id']]);
    $chat = $stmt->fetch();
    
    if (!$chat) {
        json_response(['error' => 'Chat not found'], 404);
    }
    
    // Delete all messages in this chat
    $stmt = $pdo->prepare('DELETE FROM agent_chat_messages WHERE chat_id = ?');
    $stmt->execute([$chatId]);
    
    json_response(['ok' => true, 'message' => 'Chat wiped successfully']);
}

// GET /admin/shipments/create - Create manual shipment form
if ($path === '/admin/shipments/create' && $method === 'GET') {
    require_admin();
    
    render_admin_template('shipment_create.php', [
        'title' => 'Create Manual Shipment - Admin',
    ]);
}

// POST /admin/shipments/create - Create manual shipment
if ($path === '/admin/shipments/create' && $method === 'POST') {
    require_admin();
    $data = $_POST;
    
    $trackingNumber = !empty($data['tracking_number']) ? $data['tracking_number'] : generate_tracking_number();
    $carrier = $data['carrier'] ?? 'Streicher Logistics';
    $shippingMethod = $data['shipping_method'] ?? 'air_freight';
    $packageType = $data['package_type'] ?? 'crate';
    $status = $data['status'] ?? 'pending';
    $shippedAt = !empty($data['shipped_at']) ? $data['shipped_at'] : null;
    
    // Create initial tracking event
    $initialEvents = [[
        'timestamp' => !empty($data['shipped_at']) ? $data['shipped_at'] : date('Y-m-d H:i:s'),
        'status' => strtoupper($status),
        'description' => $data['initial_description'] ?? 'Shipment created',
        'location' => $data['initial_location'] ?? 'Regensburg, Germany',
        'facility' => $data['initial_facility'] ?? 'Streicher Logistics Center',
    ]];
    
    // Create customer info JSON
    $customerInfo = json_encode([
        'name' => $data['customer_name'] ?? '',
        'email' => $data['customer_email'] ?? '',
        'phone' => $data['customer_phone'] ?? '',
        'company' => $data['customer_company'] ?? '',
    ]);
    
    // Insert shipment (order_id is NULL for manual shipments)
    $stmt = $pdo->prepare(
        'INSERT INTO shipments (order_id, carrier, tracking_number, status, shipped_at, origin_city, origin_country, destination_city, destination_country, shipping_method, package_type, events, customer_info, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    
    $stmt->execute([
        null, // No order_id for manual shipments
        $carrier,
        $trackingNumber,
        $status,
        $shippedAt,
        $data['origin_city'] ?? 'Regensburg',
        $data['origin_country'] ?? 'DE',
        $data['destination_city'] ?? '',
        $data['destination_country'] ?? '',
        $shippingMethod,
        $packageType,
        json_encode($initialEvents),
        $customerInfo,
        $data['notes'] ?? null,
    ]);
    
    $_SESSION['success_message'] = 'Manual shipment created successfully!';
    header('Location: /admin/shipments');
    exit;
}

// GET /admin/shipments/{id}/edit - Edit shipment
if (preg_match('#^/admin/shipments/(\d+)/edit$#', $path, $m) && $method === 'GET') {
    require_admin();
    $shipmentId = (int)$m[1];
    
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = ?');
    $stmt->execute([$shipmentId]);
    $shipment = $stmt->fetch();
    
    if (!$shipment) {
        header('Location: /admin/shipments');
        exit;
    }
    
    render_admin_template('shipment_edit.php', [
        'title' => 'Edit Shipment - Admin',
        'shipment' => $shipment,
    ]);
}

// POST /admin/shipments/{id}/update-info - Update shipment basic info
if (preg_match('#^/admin/shipments/(\d+)/update-info$#', $path, $m) && $method === 'POST') {
    require_admin();
    $shipmentId = (int)$m[1];
    $data = $_POST;
    
    $stmt = $pdo->prepare(
        'UPDATE shipments SET carrier = ?, status = ?, shipped_at = ?, estimated_delivery = ?, destination = ? WHERE id = ?'
    );
    $stmt->execute([
        $data['carrier'] ?? 'Streicher Logistics',
        $data['status'] ?? 'pending',
        !empty($data['shipped_at']) ? $data['shipped_at'] : null,
        !empty($data['estimated_delivery']) ? $data['estimated_delivery'] : null,
        $data['destination'] ?? null,
        $shipmentId,
    ]);
    
    $_SESSION['success_message'] = 'Shipment information updated!';
    header('Location: /admin/shipments/' . $shipmentId . '/edit');
    exit;
}

// POST /admin/shipments/{id}/add-event - Add tracking event
if (preg_match('#^/admin/shipments/(\d+)/add-event$#', $path, $m) && $method === 'POST') {
    require_admin();
    $shipmentId = (int)$m[1];
    $data = $_POST;
    
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = ?');
    $stmt->execute([$shipmentId]);
    $shipment = $stmt->fetch();
    
    if (!$shipment) {
        header('Location: /admin/shipments');
        exit;
    }
    
    $events = json_decode($shipment['events'] ?? '[]', true) ?: [];
    
    // Add new event at the beginning (most recent first)
    $newEvent = [
        'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s'),
        'status' => $data['status_code'] ?? 'UPDATE',
        'description' => $data['description'] ?? '',
        'location' => $data['location'] ?? '',
        'facility' => $data['facility'] ?? '',
    ];
    
    array_unshift($events, $newEvent);
    
    // Update shipment
    $stmt = $pdo->prepare('UPDATE shipments SET events = ? WHERE id = ?');
    $stmt->execute([json_encode($events), $shipmentId]);
    
    $_SESSION['success_message'] = 'Tracking event added!';
    header('Location: /admin/shipments/' . $shipmentId . '/edit');
    exit;
}

// POST /admin/shipments/{id}/edit-event - Edit tracking event
if (preg_match('#^/admin/shipments/(\d+)/edit-event$#', $path, $m) && $method === 'POST') {
    require_admin();
    $shipmentId = (int)$m[1];
    $data = $_POST;
    $eventIndex = (int)($data['event_index'] ?? -1);
    
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = ?');
    $stmt->execute([$shipmentId]);
    $shipment = $stmt->fetch();
    
    if (!$shipment) {
        header('Location: /admin/shipments');
        exit;
    }
    
    $events = json_decode($shipment['events'] ?? '[]', true) ?: [];
    
    if (isset($events[$eventIndex])) {
        $events[$eventIndex] = [
            'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s'),
            'status' => $data['status_code'] ?? 'UPDATE',
            'description' => $data['description'] ?? '',
            'location' => $data['location'] ?? '',
            'facility' => $data['facility'] ?? '',
        ];
        
        // Update shipment
        $stmt = $pdo->prepare('UPDATE shipments SET events = ? WHERE id = ?');
        $stmt->execute([json_encode($events), $shipmentId]);
        
        $_SESSION['success_message'] = 'Tracking event updated!';
    }
    
    header('Location: /admin/shipments/' . $shipmentId . '/edit');
    exit;
}

// POST /admin/shipments/{id}/delete-event - Delete tracking event
if (preg_match('#^/admin/shipments/(\d+)/delete-event$#', $path, $m) && $method === 'POST') {
    require_admin();
    $shipmentId = (int)$m[1];
    $data = $_POST;
    $eventIndex = (int)($data['event_index'] ?? -1);
    
    $stmt = $pdo->prepare('SELECT * FROM shipments WHERE id = ?');
    $stmt->execute([$shipmentId]);
    $shipment = $stmt->fetch();
    
    if (!$shipment) {
        header('Location: /admin/shipments');
        exit;
    }
    
    $events = json_decode($shipment['events'] ?? '[]', true) ?: [];
    
    if (isset($events[$eventIndex])) {
        array_splice($events, $eventIndex, 1);
        
        // Update shipment
        $stmt = $pdo->prepare('UPDATE shipments SET events = ? WHERE id = ?');
        $stmt->execute([json_encode($events), $shipmentId]);
        
        $_SESSION['success_message'] = 'Tracking event deleted!';
    }
    
    header('Location: /admin/shipments/' . $shipmentId . '/edit');
    exit;
}

// GET /admin/customers - All customers
if ($path === '/admin/customers' && $method === 'GET') {
    require_admin();
    
    // Get unique customers from orders
    $stmt = $pdo->query(
        "SELECT 
            JSON_UNQUOTE(JSON_EXTRACT(billing_address, '$.email')) as email,
            MAX(JSON_UNQUOTE(JSON_EXTRACT(billing_address, '$.company'))) as company,
            MAX(JSON_UNQUOTE(JSON_EXTRACT(billing_address, '$.name'))) as name,
            MAX(JSON_UNQUOTE(JSON_EXTRACT(billing_address, '$.phone'))) as phone,
            MAX(JSON_UNQUOTE(JSON_EXTRACT(billing_address, '$.country'))) as country,
            COUNT(*) as order_count,
            SUM(total_amount) as total_spent,
            MAX(created_at) as last_order
         FROM orders 
         GROUP BY JSON_UNQUOTE(JSON_EXTRACT(billing_address, '$.email'))
         ORDER BY last_order DESC"
    );
    $customers = $stmt->fetchAll();
    
    render_admin_template('customers.php', [
        'title' => 'Customers - Admin',
        'customers' => $customers,
    ]);
}

// GET /admin/reports - Reports dashboard
if ($path === '/admin/reports' && $method === 'GET') {
    require_admin();
    
    // Sales by month
    $stmt = $pdo->query(
        "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as order_count,
            SUM(total_amount) as revenue
         FROM orders 
         WHERE status NOT IN ('cancelled')
         GROUP BY DATE_FORMAT(created_at, '%Y-%m')
         ORDER BY month DESC
         LIMIT 12"
    );
    $salesByMonth = $stmt->fetchAll();
    
    // Top products
    $stmt = $pdo->query(
        "SELECT 
            oi.sku,
            p.name as product_name,
            SUM(oi.qty) as total_qty,
            SUM(oi.total) as total_revenue
         FROM order_items oi
         JOIN orders o ON oi.order_id = o.id
         LEFT JOIN products p ON oi.product_id = p.id
         WHERE o.status NOT IN ('cancelled')
         GROUP BY oi.sku, p.name
         ORDER BY total_revenue DESC
         LIMIT 10"
    );
    $topProducts = $stmt->fetchAll();
    
    // Orders by status
    $stmt = $pdo->query(
        "SELECT status, COUNT(*) as count FROM orders GROUP BY status"
    );
    $ordersByStatus = $stmt->fetchAll();
    
    render_admin_template('reports.php', [
        'title' => 'Reports - Admin',
        'salesByMonth' => $salesByMonth,
        'topProducts' => $topProducts,
        'ordersByStatus' => $ordersByStatus,
    ]);
}

// GET /admin/settings - Settings page
if ($path === '/admin/settings' && $method === 'GET') {
    require_admin();
    
    // Load settings from database
    $stmt = $pdo->query('SELECT setting_key, setting_value, updated_at FROM settings');
    $settingsRows = $stmt->fetchAll();
    $settings = [];
    foreach ($settingsRows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
        if ($row['setting_key'] === 'eur_usd_rate') {
            $settings['eur_usd_updated'] = $row['updated_at'];
        }
    }
    
    render_admin_template('settings.php', [
        'title' => 'Settings - Admin',
        'settings' => $settings,
    ]);
}

// POST /admin/settings - Update settings
if ($path === '/admin/settings' && $method === 'POST') {
    require_admin();
    
    // Settings to save
    $settingsToSave = [
        'company_name', 'vat_id', 'address',
        'bank_name', 'account_holder', 'iban', 'bic',
        'support_email', 'support_phone', 'sales_email', 'shipping_email',
        'vat_rate', 'currency', 'free_shipping_threshold', 'eur_usd_rate',
    ];
    
    // Update each setting in database
    $stmt = $pdo->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    foreach ($settingsToSave as $key) {
        if (isset($_POST[$key])) {
            $stmt->execute([$key, $_POST[$key]]);
        }
    }
    
    // Handle checkboxes (notifications)
    $checkboxes = ['notify_new_order', 'notify_payment', 'notify_low_stock'];
    foreach ($checkboxes as $key) {
        $value = isset($_POST[$key]) ? '1' : '0';
        $stmt->execute([$key, $value]);
    }
    
    header('Location: /admin/settings?saved=1');
    exit;
}

// ============ STATIC PAGES ============

// GET /contact - Contact page
if ($path === '/contact' && $method === 'GET') {
    render_template('pages/contact.php', ['title' => 'Contact Us - Streicher']);
}

// POST /contact - Contact form submission
if ($path === '/contact' && $method === 'POST') {
    $name = $_POST['name'] ?? '';
    $company = $_POST['company'] ?? '';
    $ticketNumber = 'TICK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    
    $supportRepo->create([
        'ticket_number' => $ticketNumber,
        'name' => $_POST['name'] ?? '',
        'company' => $_POST['company'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'subject' => $_POST['subject'] ?? 'General Inquiry',
        'message' => $_POST['message'] ?? '',
        'status' => 'open'
    ]);
    
    render_template('pages/contact.php', [
        'title' => 'Contact Us - Streicher',
        'success' => true,
        'ticketNumber' => $ticketNumber,
    ]);
}

// GET /quote - Request Quote page
if ($path === '/quote' && $method === 'GET') {
    $productSku = $_GET['product'] ?? null;
    $product = null;
    
    if ($productSku) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE sku = ?');
        $stmt->execute([$productSku]);
        $product = $stmt->fetch();
    }
    
    render_template('pages/quote.php', [
        'title' => 'Request a Quote - Streicher',
        'product' => $product,
    ]);
}

// POST /quote - Quote form submission
if ($path === '/quote' && $method === 'POST') {
    $name = $_POST['name'] ?? '';
    $company = $_POST['company'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $country = $_POST['country'] ?? '';
    $category = $_POST['category'] ?? '';
    $quantity = $_POST['quantity'] ?? '1';
    $requirements = $_POST['requirements'] ?? '';
    $productSku = $_POST['product_sku'] ?? '';
    
    // Generate ticket number
    $ticketNumber = 'QTE-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    
    // Build message from quote details
    $message = "Quote Request\n\n";
    $message .= "Product Category: $category\n";
    $message .= "Quantity: $quantity\n";
    if ($productSku) {
        $message .= "Product SKU: $productSku\n";
    }
    $message .= "Country: $country\n\n";
    $message .= "Requirements:\n$requirements";
    
    // Save to support_tickets table
    $stmt = $pdo->prepare('INSERT INTO support_tickets (ticket_number, name, company, email, phone, subject, message) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$ticketNumber, $name, $company, $email, $phone, 'Quote Request - ' . $category, $message]);
    
    render_template('pages/quote.php', [
        'title' => 'Request a Quote - Streicher',
        'success' => true,
        'ticketNumber' => $ticketNumber,
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
    render_template('pages/business-sectors.php', ['title' => $lang === 'de' ? 'GeschÃ¤ftsbereiche - Streicher' : 'Business Sectors - Streicher']);
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

