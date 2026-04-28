<?php declare(strict_types=1);

// PHP built-in server: serve static files directly
if (PHP_SAPI === 'cli-server') {
    $staticFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (is_file($staticFile)) {
        return false;
    }
}

if (($_SERVER['REQUEST_URI'] ?? '/') === '/health' || ($_SERVER['REQUEST_URI'] ?? '/') === '/healthz') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'timestamp' => date('c')]);
    exit;
}

require_once __DIR__ . '/bootstrap.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $requestPath;

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
