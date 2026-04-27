<?php
require_once __DIR__ . '/web/bootstrap.php';
$products = (new \App\Services\CsvDb('data/db/products.csv'))->read();
$inventory = [];
foreach ($products as $p) {
    $inventory[] = [
        'sku' => $p['sku'],
        'stock_level' => 10,
        'reorder_point' => 2,
        'lead_time_days' => 7
    ];
}
(new \App\Services\CsvDb('data/db/inventory.csv'))->write($inventory);
echo "Inventory initialized with " . count($inventory) . " SKUs.\n";
