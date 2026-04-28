<?php declare(strict_types=1);

// Diagnostic route (Temporary)
if (($_SERVER['REQUEST_URI'] ?? '') === '/diag-login' || isset($_GET['diag_login'])) {
    require_once __DIR__ . '/bootstrap.php';
    header('Content-Type: text/plain');
    echo "DIAGNOSTIC START\n";
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $all = $stmt->fetchAll();
    echo "Total users: " . count($all) . "\n";
    $found = false;
    foreach ($all as $u) {
        echo " - Email: [" . $u['email'] . "] Role: " . $u['role'] . "\n";
        if ($u['email'] === 'mgr@streichergmbh.com') {
             $found = true;
             echo "   PASSWORD VERIFY TEST: " . (password_verify('Americana12', $u['password_hash']) ? "PASS" : "FAIL") . "\n";
        }
    }
    
    if (!$found) {
        echo "MGR NOT FOUND. Creating...\n";
        $res = $userRepo->create([
            'email' => 'mgr@streichergmbh.com',
            'password_hash' => password_hash('Americana12', PASSWORD_DEFAULT),
            'full_name' => 'General Manager',
            'role' => 'admin',
            'is_active' => 1
        ]);
        echo "Creation Result: " . ($res ? "SUCCESS" : "FAILURE") . "\n";
    }
    exit;
}

if (($_SERVER['REQUEST_URI'] ?? '/') === '/health' || ($_SERVER['REQUEST_URI'] ?? '/') === '/healthz') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'timestamp' => date('c')]);
    exit;
}

/**
 * entry point for the sovereign machine store
 * all requests are routed through here.
 */
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
