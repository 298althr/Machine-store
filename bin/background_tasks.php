<?php

declare(strict_types=1);

/**
 * Streicher Operational Background Tasks
 * This script should be run via Cron every 6-12 hours.
 */

require_once __DIR__ . '/../web/bootstrap.php';

use Streicher\App\Services\AutoCorrelator;

echo "[" . date('Y-m-d H:i:s') . "] Starting background maintenance tasks...\n";

// 1. Sync Image Assets
echo "Syncing product images... ";
$imagesDir = dirname(__DIR__) . '/web/images';
$correlator = new AutoCorrelator($GLOBALS['pdo'], $imagesDir);
$corrResults = $correlator->run();
echo "Done. Linked {$corrResults['correlated']} products.\n";

// 2. Pre-cache Exchange Rates
echo "Updating exchange rates... ";
$rate = get_exchange_rate(); // This helper automatically updates/caches if needed
echo "Done. Current EUR/USD: {$rate}\n";

// 3. Scan for Critical Stock Issues
echo "Scanning inventory health... ";
$stmt = $GLOBALS['pdo']->query("SELECT * FROM inventory WHERE stock_level <= 2");
$criticalItems = $stmt->fetchAll();
if (!empty($criticalItems)) {
    foreach ($criticalItems as $item) {
        $product = $productRepo->find((int)$item['product_id']);
        if ($product) {
            echo "CRITICAL: {$product['sku']} at {$item['stock_level']} units.\n";
            // Optional: Send a specific 'CRITICAL' alert if needed
        }
    }
} else {
    echo "Healthy.\n";
}

echo "[" . date('Y-m-d H:i:s') . "] Background tasks completed successfully.\n";
