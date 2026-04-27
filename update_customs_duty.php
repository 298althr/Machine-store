<?php
// One-time script to update customs duty amount and currency
require_once __DIR__ . '/web/index.php';

// Update shipment ID 3 (STR20251224C376BA50BA) customs duty
$stmt = $pdo->prepare('UPDATE shipments SET customs_duty_amount = ?, customs_duty_currency = ? WHERE tracking_number = ?');
$stmt->execute([84789.60, 'USD', 'STR20251224C376BA50BA']);

echo "Updated customs duty to $84,789.60 USD for tracking number STR20251224C376BA50BA\n";
