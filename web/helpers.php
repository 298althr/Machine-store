<?php

declare(strict_types=1);

/**
 * Common helper functions for the Streicher platform.
 */

function json_response($data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function render_template(string $template, array $params = []): void {
    global $pdo, $lang;
    $params['lang'] = $lang;
    extract($params);
    ob_start();
    require __DIR__ . '/templates/' . $template;
    $content = ob_get_clean();
    require __DIR__ . '/templates/layout.php';
    exit;
}

function render_admin_template(string $template, array $params = []): void {
    global $pdo;
    extract($params);
    ob_start();
    require __DIR__ . '/templates/admin/' . $template;
    $content = ob_get_clean();
    require __DIR__ . '/templates/admin/layout.php';
    exit;
}

function require_admin(): void {
    if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /admin/login');
        exit;
    }
}

function format_price(float $amount, string $currency = 'EUR'): string {
    if ($currency === 'USD') {
        return '$' . number_format($amount, 2);
    }
    return '€' . number_format($amount, 2);
}

function get_exchange_rate(): float {
    global $pdo;
    
    // Try to get cached rate from database
    $stmt = $pdo->prepare("SELECT setting_value, updated_at FROM settings WHERE setting_key = ?");
    $stmt->execute(['eur_usd_rate']);
    $row = $stmt->fetch();
    
    if ($row) {
        $lastUpdate = strtotime($row['updated_at']);
        // If rate is less than 24 hours old, use cached
        if (time() - $lastUpdate < 86400) {
            return (float)$row['setting_value'];
        }
    }
    
    // Fetch new rate from API (fallback to default if fails)
    $rate = 1.08; // Default EUR/USD rate
    try {
        $url = 'https://api.exchangerate-api.com/v4/latest/EUR';
        $context = stream_context_create(['http' => ['timeout' => 5]]);
        $response = @file_get_contents($url, false, $context);
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['rates']['USD'])) {
                $rate = (float)$data['rates']['USD'];
            }
        }
    } catch (Exception $e) {
        // Use default rate
    }
    
    // Cache the rate
    $stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?");
    $stmt->execute([$rate, 'eur_usd_rate']);
    if ($stmt->rowCount() === 0) {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
        $stmt->execute(['eur_usd_rate', $rate]);
    }
    
    return $rate;
}

function convert_price(float $eurAmount, string $toCurrency): float {
    if ($toCurrency === 'EUR') {
        return $eurAmount;
    }
    $rate = get_exchange_rate();
    return $eurAmount * $rate;
}

function get_cart_count(): int {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    return array_sum(array_column($_SESSION['cart'], 'qty'));
}

function set_flash_message(string $message, string $key = 'success_message'): void {
    $_SESSION[$key] = $message;
}

function get_flash_message(string $key = 'success_message'): ?string {
    if (!isset($_SESSION[$key])) {
        return null;
    }
    $message = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $message;
}

function get_cart_total(): float {
    global $pdo;
    $cart = $_SESSION['cart'] ?? [];
    $total = 0.0;
    foreach ($cart as $item) {
        $stmt = $pdo->prepare('SELECT unit_price FROM products WHERE sku = ? LIMIT 1');
        $stmt->execute([$item['sku']]);
        $price = (float)($stmt->fetchColumn() ?: 0);
        $total += $price * $item['qty'];
    }
    return $total;
}

function generate_order_number(): string {
    return 'ST-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

function generate_tracking_number(): string {
    return 'STR' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 10));
}

function get_status_label(string $status): string {
    $labels = [
        'pending' => 'Pending',
        'awaiting_payment' => 'Awaiting Payment',
        'payment_uploaded' => 'Payment Uploaded',
        'payment_confirmed' => 'Payment Confirmed',
        'payment_declined' => 'Payment Declined',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'in_transit' => 'In Transit',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];
    return $labels[$status] ?? ucfirst($status);
}

function verify_csrf(): bool {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'] ?? '') . '">';
}

/**
 * Get product image URL with fallback
 */
function get_product_image(array $product): string {
    if (!empty($product['image_url'])) {
        return $product['image_url'];
    }
    
    $slug = $product['slug'] ?? '';
    if ($slug) {
        $path = 'web/images/' . $slug . '/1.jpg';
        if (file_exists(__DIR__ . '/' . $path)) {
            return '/images/' . $slug . '/1.jpg';
        }
        $path = 'web/images/' . $slug . '/1.webp';
        if (file_exists(__DIR__ . '/' . $path)) {
            return '/images/' . $slug . '/1.webp';
        }
    }
    
    // Industrial placeholders based on type
    $type = $product['product_type'] ?? 'hardware';
    if ($type === 'software') {
        return '/images/photos/electrical.jpg';
    }
    
    return '/images/photos/mechanical1.jpg';
}
