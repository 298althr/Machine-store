<?php
declare(strict_types=1);

// Fast Health Check (Before anything else)
if (($_SERVER['REQUEST_URI'] ?? '/') === '/health' || ($_SERVER['REQUEST_URI'] ?? '/') === '/healthz') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'timestamp' => date('c')]);
    exit;
}

/**
 * entry point for the sovereign machine store
 * all requests are routed through here.
 */

// Serve static files natively when using PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

try {
    require_once __DIR__ . '/bootstrap.php';
} catch (\Throwable $e) {
    $errorMsg = "FATAL ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine();
    file_put_contents('php://stderr', $errorMsg . "\n");
    http_response_code(500);
    if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
        echo "<h1>Internal Server Error</h1>";
        echo "<pre>" . htmlspecialchars($errorMsg) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "Internal Server Error";
    }
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $requestPath; // alias for compatibility

// Debug route (Temporary)
if ($path === '/debug-users' && isset($_GET['auth']) && $_GET['auth'] === 'Americana12') {
    header('Content-Type: text/plain');
    echo file_get_contents(dirname(__DIR__) . '/data/db/users.csv');
    exit;
}

// Telegram webhook endpoint
if ($path === '/telegram-webhook') {
    $content = file_get_contents('php://input');
    $update = json_decode($content, true);
    if ($update) {
        $telegramService->handleWebhook($update);
    }
    http_response_code(200);
    exit('OK');
}

// Serve uploaded product images
if (preg_match('#^/uploads/products/(.+)$#', $path, $m)) {
    $filename = $m[1];
    $filePath = __DIR__ . '/uploads/products/' . $filename;
    if (file_exists($filePath)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . (string)filesize($filePath));
        header('Content-Disposition: inline; filename="' . $filename . '"');
        readfile($filePath);
        exit;
    }
    http_response_code(404);
    echo 'File not found';
    exit;
}

// Route inclusions
require __DIR__ . '/includes/routes_api.php';
require __DIR__ . '/includes/routes_public.php';
require __DIR__ . '/includes/routes_admin.php';
require __DIR__ . '/includes/routes_software.php';

// Fallback 404
http_response_code(404);
render_template('404.php', [
    'title' => 'Page Not Found',
    'path' => $path
]);
