<?php

declare(strict_types=1);

function get_public_base_url(): string {
    if (!empty($_ENV['APP_URL'])) {
        return rtrim($_ENV['APP_URL'], '/');
    }
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'streichergmbh.com';
    return $scheme . '://' . $host;
}

function get_shipment_customer_details(array $shipment, ?PDO $connection = null): array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    $details = [
        'name' => null,
        'email' => null,
        'phone' => null,
    ];

    if (!empty($shipment['customer_info'])) {
        $decoded = json_decode($shipment['customer_info'], true);
        if (is_array($decoded)) {
            $details['name'] = $decoded['name'] ?? $decoded['customer_name'] ?? $details['name'];
            $details['email'] = $decoded['email'] ?? $decoded['customer_email'] ?? $details['email'];
            $details['phone'] = $decoded['phone'] ?? $decoded['customer_phone'] ?? $details['phone'];
        }
    }

    if ($pdo && !empty($shipment['order_id']) && (!$details['name'] || !$details['email'])) {
        $stmt = $pdo->prepare('SELECT billing_name, billing_email, billing_phone FROM orders WHERE id = ? LIMIT 1');
        $stmt->execute([$shipment['order_id']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
            $details['name'] = $details['name'] ?: $order['billing_name'];
            $details['email'] = $details['email'] ?: $order['billing_email'];
            $details['phone'] = $details['phone'] ?: $order['billing_phone'];
        }
    }

    return $details;
}

function get_agent_by_id(int $agentId, ?PDO $connection = null): ?array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        return null;
    }
    $stmt = $pdo->prepare('SELECT * FROM agents WHERE id = ? LIMIT 1');
    $stmt->execute([$agentId]);
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);
    return $agent ?: null;
}

function get_agent_by_telegram_chat_id($chatId, ?PDO $connection = null): ?array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        return null;
    }
    $stmt = $pdo->prepare('SELECT * FROM agents WHERE telegram_chat_id = ? LIMIT 1');
    $stmt->execute([$chatId]);
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);
    return $agent ?: null;
}

function get_default_agent(?PDO $connection = null): ?array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        return null;
    }
    $stmt = $pdo->query("SELECT * FROM agents WHERE status = 'active' ORDER BY id ASC LIMIT 1");
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);
    return $agent ?: null;
}

function get_authenticated_agent(?PDO $connection = null): ?array {
    // For now, reuse the default agent so the logged-in admin represents that agent.
    return get_default_agent($connection);
}

function get_shipment_agent(array $shipment, ?PDO $connection = null): ?array {
    if (!empty($shipment['assigned_agent_id'])) {
        $agent = get_agent_by_id((int)$shipment['assigned_agent_id'], $connection);
        if ($agent && $agent['status'] === 'active') {
            return $agent;
        }
    }
    return get_default_agent($connection);
}

function ensure_agent_chat(PDO $pdo, array $shipment, array $agent, array $customerData = []): array {
    $stmt = $pdo->prepare('SELECT * FROM agent_chats WHERE shipment_id = ? LIMIT 1');
    $stmt->execute([$shipment['id']]);
    $chat = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($chat) {
        $updates = [];
        $params = [];
        // Always update customer name if provided (not just when empty)
        if (!empty($customerData['name']) && $chat['customer_name'] !== $customerData['name']) {
            $updates[] = 'customer_name = ?';
            $params[] = $customerData['name'];
            $chat['customer_name'] = $customerData['name'];
        }
        if (empty($chat['customer_email']) && !empty($customerData['email'])) {
            $updates[] = 'customer_email = ?';
            $params[] = $customerData['email'];
            $chat['customer_email'] = $customerData['email'];
        }
        if (empty($chat['customer_phone']) && !empty($customerData['phone'])) {
            $updates[] = 'customer_phone = ?';
            $params[] = $customerData['phone'];
            $chat['customer_phone'] = $customerData['phone'];
        }
        if ((int)$chat['agent_id'] !== (int)$agent['id']) {
            $updates[] = 'agent_id = ?';
            $params[] = $agent['id'];
            $chat['agent_id'] = $agent['id'];
        }
        if ($updates) {
            $updates[] = 'updated_at = NOW()';
            $params[] = $chat['id'];
            $pdo->prepare('UPDATE agent_chats SET ' . implode(', ', $updates) . ' WHERE id = ?')->execute($params);
        }
        return $chat;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO agent_chats (shipment_id, agent_id, tracking_number, customer_name, customer_email, customer_phone, is_open, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())'
    );
    $stmt->execute([
        $shipment['id'],
        $agent['id'],
        $shipment['tracking_number'],
        $customerData['name'] ?? null,
        $customerData['email'] ?? null,
        $customerData['phone'] ?? null,
    ]);

    $chatId = (int)$pdo->lastInsertId();
    $stmt = $pdo->prepare('SELECT * FROM agent_chats WHERE id = ?');
    $stmt->execute([$chatId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function fetch_agent_chats_for_agent(int $agentId, int $limit = 30, ?string $statusFilter = null, ?PDO $connection = null): array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        return [];
    }

    $where = ['agent_id = ?'];
    $params = [$agentId];
    if ($statusFilter === 'open') {
        $where[] = 'is_open = 1';
    } elseif ($statusFilter === 'closed') {
        $where[] = 'is_open = 0';
    }

    $sql = 'SELECT * FROM agent_chats WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC LIMIT ' . (int)$limit;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function ($chat) use ($pdo) {
        $stmt = $pdo->prepare('SELECT status FROM shipments WHERE id = ? LIMIT 1');
        $stmt->execute([$chat['shipment_id']]);
        $shipmentStatus = $stmt->fetchColumn() ?: 'unknown';

        return [
            'id' => (int)$chat['id'],
            'tracking_number' => $chat['tracking_number'],
            'customer_name' => $chat['customer_name'],
            'customer_email' => $chat['customer_email'],
            'customer_phone' => $chat['customer_phone'],
            'is_open' => (int)$chat['is_open'] === 1,
            'shipment_status' => $shipmentStatus,
            'order_id' => null, // Order ID would require another fetch if needed
            'last_message_at' => $chat['last_message_at'] ?? null,
            'updated_at' => $chat['updated_at'],
        ];
    }, $chats);
}

function ensure_chat_session_token(int $chatId): string {
    if (!isset($_SESSION['agent_chat_tokens'])) {
        $_SESSION['agent_chat_tokens'] = [];
    }
    if (empty($_SESSION['agent_chat_tokens'][$chatId])) {
        $_SESSION['agent_chat_tokens'][$chatId] = bin2hex(random_bytes(16));
    }
    return $_SESSION['agent_chat_tokens'][$chatId];
}

function get_agent_chat_with_access(int $chatId, ?string $token = null, ?PDO $connection = null): ?array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        return null;
    }
    
    $stmt = $pdo->prepare('SELECT * FROM agent_chats WHERE id = ? LIMIT 1');
    $stmt->execute([$chatId]);
    $chat = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$chat) {
        return null;
    }

    // Manual join for shipment status
    $stmt = $pdo->prepare('SELECT status FROM shipments WHERE id = ? LIMIT 1');
    $stmt->execute([$chat['shipment_id']]);
    $chat['shipment_status'] = $stmt->fetchColumn() ?: 'unknown';

    $isAdmin = !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    if ($isAdmin) {
        return $chat;
    }

    if (empty($_SESSION['agent_chat_tokens'][$chatId]) || $_SESSION['agent_chat_tokens'][$chatId] !== $token) {
        return null;
    }
    return $chat;
}

function fetch_agent_chat_messages(int $chatId, ?int $sinceId = null, ?PDO $connection = null): array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        return [];
    }
    if ($sinceId) {
        $stmt = $pdo->prepare('SELECT * FROM agent_chat_messages WHERE chat_id = ? AND id > ? ORDER BY id ASC');
        $stmt->execute([$chatId, $sinceId]);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM agent_chat_messages WHERE chat_id = ? ORDER BY id ASC');
        $stmt->execute([$chatId]);
    }
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_map('format_chat_message_row', $messages);
}

function format_chat_message_row(array $row): array {
    return [
        'id' => (int)$row['id'],
        'sender_type' => $row['sender_type'],
        'sender_name' => $row['sender_name'],
        'message' => $row['message'],
        'attachment_name' => $row['attachment_name'],
        'attachment_path' => $row['attachment_path'],
        'attachment_type' => $row['attachment_type'],
        'attachment_url' => $row['attachment_path'] ? '/' . ltrim($row['attachment_path'], '/') : null,
        'created_at' => $row['created_at'],
    ];
}

function save_agent_chat_message(array $chat, string $senderType, ?string $senderName, ?string $message, array $attachment = [], ?PDO $connection = null): array {
    $pdo = $connection ?? ($GLOBALS['pdo'] ?? null);
    if (!$pdo) {
        throw new RuntimeException('Database connection is unavailable');
    }
    $stmt = $pdo->prepare(
        'INSERT INTO agent_chat_messages (chat_id, sender_type, sender_name, message, attachment_name, attachment_path, attachment_type, attachment_size, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    $stmt->execute([
        $chat['id'],
        $senderType,
        $senderName,
        $message,
        $attachment['name'] ?? null,
        $attachment['path'] ?? null,
        $attachment['type'] ?? null,
        $attachment['size'] ?? null,
    ]);
    $messageId = (int)$pdo->lastInsertId();

    $pdo->prepare('UPDATE agent_chats SET last_message_at = NOW(), updated_at = NOW() WHERE id = ?')
        ->execute([$chat['id']]);

    $stmt = $pdo->prepare('SELECT * FROM agent_chat_messages WHERE id = ?');
    $stmt->execute([$messageId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    return format_chat_message_row($row);
}

function process_agent_chat_attachment(array $file, string $trackingNumber): array {
    if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return [];
    }

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('upload_error');
    }

    if (!isset($file['size']) || $file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('too_large');
    }

    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
    $allowedMimeTypes = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('invalid_type');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        throw new RuntimeException('invalid_type');
    }

    $uploadDir = __DIR__ . '/../uploads/agent_chat/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $trackingSlug = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($trackingNumber)) ?: 'chat';
    $safeName = $trackingSlug . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $destination = $uploadDir . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('upload_error');
    }

    return [
        'name' => $file['name'],
        'path' => 'uploads/agent_chat/' . $safeName,
        'type' => $mimeType,
        'size' => $file['size'],
        'url' => '/uploads/agent-chat/' . $safeName,
    ];
}

function send_agent_telegram_notification(array $agent, string $message, ?string $documentPath = null): bool {
    $botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? getenv('TELEGRAM_BOT_TOKEN') ?? null;
    if (!$botToken) {
        return false;
    }

    $chatId = $agent['telegram_chat_id'] ?? null;
    if ($chatId) {
        $url = "https://api.telegram.org/bot$botToken/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data),
                'timeout' => 10,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($documentPath && $result !== false) {
            $docUrl = "https://api.telegram.org/bot$botToken/sendDocument";
            $fullDocUrl = (strpos($documentPath, 'http') === 0) ? $documentPath : get_public_base_url() . '/' . ltrim($documentPath, '/');
            $docData = [
                'chat_id' => $chatId,
                'document' => $fullDocUrl,
                'caption' => 'Attachment from customer',
            ];
            $docOptions = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($docData),
                    'timeout' => 15,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ];
            $docContext = stream_context_create($docOptions);
            @file_get_contents($docUrl, false, $docContext);
        }
        return $result !== false;
    }

    if (function_exists('sendTelegramNotification')) {
        return sendTelegramNotification($message, $documentPath);
    }

    return false;
}
