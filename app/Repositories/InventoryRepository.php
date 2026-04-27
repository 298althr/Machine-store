<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class InventoryRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByProductId(int $productId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE product_id = ? LIMIT 1");
        $stmt->execute([$productId]);
        return $stmt->fetch() ?: null;
    }

    public function decrementStock(int $productId, int $quantity): ?int
    {
        $inventory = $this->getByProductId($productId);
        if (!$inventory) {
            return null;
        }

        $currentStock = (int)$inventory['stock_level'];
        $newStock = max(0, $currentStock - $quantity);

        $success = (bool)$this->db->update('inventory', [
            'stock_level' => $newStock,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['product_id' => $productId]);

        return $success ? $newStock : null;
    }

    public function isAvailable(int $productId, int $requestedQty): bool
    {
        $inventory = $this->getByProductId($productId);
        if (!$inventory) {
            return false;
        }

        return (int)$inventory['stock_level'] >= $requestedQty;
    }
}
