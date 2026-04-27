<?php

declare(strict_types=1);

// Resilient autoloader detection
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} else {
    // Final fallback for root-level execution
    require_once __DIR__ . '/vendor/autoload.php';
}

use Dotenv\Dotenv;
use Streicher\App\Services\CsvDb;
use Streicher\App\Services\CsvPdo;

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Configure error reporting based on environment
if (($_ENV['APP_ENV'] ?? 'production') === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'America/Chicago');

// Database Initialization
// Default to 'csv' for the zero-infrastructure setup on Railway
$dbType = $_ENV['DB_TYPE'] ?? 'csv';

if ($dbType === 'csv') {
    $csvDb = new CsvDb();
    $pdo = new CsvPdo($csvDb);
} else {
    $dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $_ENV['DB_PORT'] ?? '3306';
    $dbName = $_ENV['DB_NAME'] ?? 'streicher';
    $dbUser = $_ENV['DB_USER'] ?? 'root';
    $dbPass = $_ENV['DB_PASS'] ?? '';

    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
            die("Database connection failed: " . $e->getMessage());
        }
        http_response_code(503);
        exit("Service Temporarily Unavailable");
    }
}

// Export $pdo to globals for legacy code compatibility
$GLOBALS['pdo'] = $pdo;

// Initialize Repositories
$productRepo = new \Streicher\App\Repositories\ProductRepository($pdo);
$categoryRepo = new \Streicher\App\Repositories\CategoryRepository($pdo);
$userRepo = new \Streicher\App\Repositories\UserRepository($pdo);
$settingRepo = new \Streicher\App\Repositories\SettingRepository($pdo);
$orderRepo = new \Streicher\App\Repositories\OrderRepository($pdo);
$shipmentRepo = new \Streicher\App\Repositories\ShipmentRepository($pdo);
$supportRepo = new \Streicher\App\Repositories\SupportRepository($pdo);
$softwareRepo = new \Streicher\App\Repositories\SoftwareRepository($pdo);
$emailService = new \Streicher\App\Services\EmailService($pdo);
$pdfService = new \Streicher\App\Services\PdfService();
$telegramService = new \Streicher\App\Services\TelegramService($_ENV['TELEGRAM_BOT_TOKEN'] ?? '', $pdo);

// Initialize session
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}
session_start();

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Translations & Helpers
require_once __DIR__ . '/translations.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/includes/agent_chat.php';

$lang = $_SESSION['lang'] ?? 'de';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['de', 'en'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
}

/**
 * Telegram notification helper function
 */
function sendTelegramNotification(string $message, ?string $documentUrl = null): bool {
    $botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? getenv('TELEGRAM_BOT_TOKEN') ?? null;
    $chatId = $_ENV['TELEGRAM_USER_ID'] ?? getenv('TELEGRAM_USER_ID') ?? null;
    
    if (!$botToken || !$chatId) {
        return false;
    }
    
    // Send text message
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
    
    // If there's a document, send it too
    if ($documentUrl && $result !== false) {
        $docUrl = "https://api.telegram.org/bot$botToken/sendDocument";
        $fullDocUrl = (strpos($documentUrl, 'http') === 0) ? $documentUrl : 'https://streichergmbh.com' . $documentUrl;
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
