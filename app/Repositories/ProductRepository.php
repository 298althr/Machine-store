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
        $stmt = $this->db->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll();
        
        /*
        if ($activeOnly) {
            $products = array_filter($products, function($p) {
                return (string)($p['is_active'] ?? '0') === '1';
            });
        }
        */
        
        $hydrated = array_map([$this, 'hydrate'], array_values($products));
        file_put_contents('php://stderr', "DEBUG: ProductRepository::all - Fetched " . count($products) . " active products\n");
        return $hydrated;
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
                $name = strtolower($p['name'] ?? '');
                $sku = strtolower($p['sku'] ?? '');
                $desc = strtolower($p['description'] ?? '');
                return strpos($name, $query) !== false || 
                       strpos($sku, $query) !== false || 
                       strpos($desc, $query) !== false;
            });
        }

        usort($products, function($a, $b) {
            $featB = (int)($b['is_featured'] ?? 0);
            $featA = (int)($a['is_featured'] ?? 0);
            $dateB = $b['created_at'] ?? '';
            $dateA = $a['created_at'] ?? '';
            return ($featB <=> $featA) ?: ($dateB <=> $dateA);
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
