<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class SpecificationRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM product_specs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByProductId(int $productId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM product_specs WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    /**
     * Get unique spec names and their unique values for filtering
     */
    public function getFilterOptions(): array
    {
        $all = $this->all();
        $options = [];
        foreach ($all as $spec) {
            $name = $spec['spec_name'];
            $value = $spec['spec_value'];
            $unit = $spec['unit'];
            
            if (!isset($options[$name])) {
                $options[$name] = [
                    'unit' => $unit,
                    'values' => []
                ];
            }
            
            if (!in_array($value, $options[$name]['values'])) {
                $options[$name]['values'][] = $value;
            }
        }
        
        // Sort values
        foreach ($options as &$opt) {
            sort($opt['values']);
        }
        
        return $options;
    }

    public function getProductIdsBySpec(string $name, string $value): array
    {
        $stmt = $this->db->prepare("SELECT product_id FROM product_specs WHERE spec_name = ? AND spec_value = ?");
        $stmt->execute([$name, $value]);
        $rows = $stmt->fetchAll();
        return array_column($rows, 'product_id');
    }
}
