<?php

declare(strict_types=1);

require_once __DIR__ . '/../web/bootstrap.php';

use Streicher\App\Services\AutoCorrelator;

echo "--- Streicher Auto-Correlator Starting ---\n";

$imagesDir = dirname(__DIR__) . '/web/images';
$correlator = new AutoCorrelator($GLOBALS['pdo'], $imagesDir);

$results = $correlator->run();

echo "Total Products: " . $results['total_products'] . "\n";
echo "Missing Images: " . $results['missing_images'] . "\n";
echo "Newly Correlated: " . $results['correlated'] . "\n";

if (!empty($results['errors'])) {
    echo "\nErrors found:\n";
    foreach ($results['errors'] as $error) {
        echo "- " . $error . "\n";
    }
}

echo "--- Correlation Complete ---\n";
