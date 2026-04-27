<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

use Streicher\App\Services\CsvPdo;

class ProductRepository
{
    private $db;
    private $categories = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    private function getCategories(): array
    {
        if ($this->categories === null) {
            $stmt = $this->db->prepare("SELECT * FROM categories");
            $stmt->execute();
            $cats = $stmt->fetchAll();
            $this->categories = [];
            foreach ($cats as $cat) {
                $this->categories[$cat['id']] = $cat;
            }
        }
        return $this->categories;
    }

    private function hydrate(array $product): array
    {
        $cats = $this->getCategories();
        $catId = $product['category_id'] ?? null;
        if ($catId && isset($cats[$catId])) {
            $product['category_name'] = $cats[$catId]['name'];
            $product['category_slug'] = $cats[$catId]['slug'];
        }
        return $product;
    }

    public function all(bool $activeOnly = true): array
    {
        $sql = "SELECT * FROM products";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();
        return array_map([$this, 'hydrate'], $products);
    }

    public function featured(int $limit = 6): array
    {
        // Simple manual sorting and limiting for CSV
        $products = $this->all();
        usort($products, function($a, $b) {
            return ($b['is_featured'] <=> $a['is_featured']) ?: ($b['created_at'] <=> $a['created_at']);
        });
        return array_slice($products, 0, $limit);
    }

    public function findBySku(string $sku): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE sku = ? LIMIT 1");
        $stmt->execute([$sku]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function getByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE category_id = ? AND is_active = 1");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function search(?string $query = null, ?string $categorySlug = null): array
    {
        $products = $this->all();
        
        if ($categorySlug) {
            // Need Category ID first
            $categories = $this->db->prepare("SELECT * FROM categories WHERE slug = ? LIMIT 1");
            $categories->execute([$categorySlug]);
            $cat = $categories->fetch();
            if ($cat) {
                $products = array_filter($products, function($p) use ($cat) {
                    return (string)$p['category_id'] === (string)$cat['id'];
                });
            }
        }

        if ($query) {
            $query = strtolower($query);
            $products = array_filter($products, function($p) use ($query) {
                return strpos(strtolower($p['name']), $query) !== false || 
                       strpos(strtolower($p['sku']), $query) !== false || 
                       strpos(strtolower($p['description']), $query) !== false;
            });
        }

        usort($products, function($a, $b) {
            return ($b['is_featured'] <=> $a['is_featured']) ?: ($b['created_at'] <=> $a['created_at']);
        });

        return array_values($products);
    }

    public function getRelated(string $sku, int $categoryId, int $limit = 4): array
    {
        $products = $this->getByCategory($categoryId);
        $products = array_filter($products, function($p) use ($sku) {
            return $p['sku'] !== $sku;
        });
        shuffle($products);
        return array_slice($products, 0, $limit);
    }

    public function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $inserted = $this->db->insert('products', $data);
        return (int)$inserted['id'];
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('products', $data, ['id' => $id]);
    }
}
