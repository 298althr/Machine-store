<?php

declare(strict_types=1);

namespace Streicher\App\Services;

class AutoCorrelator
{
    private $db;
    private $imagesDir;

    public function __construct($db, string $imagesDir)
    {
        $this->db = $db;
        $this->imagesDir = $imagesDir;
    }

    /**
     * Scan the images directory and link products with matching slugs
     */
    public function run(): array
    {
        $results = [
            'total_products' => 0,
            'missing_images' => 0,
            'correlated' => 0,
            'errors' => []
        ];

        try {
            $stmt = $this->db->prepare("SELECT id, name, sku, slug, image_url FROM products");
            $stmt->execute();
            $products = $stmt->fetchAll();
            $results['total_products'] = count($products);

            foreach ($products as $p) {
                // If product already has an image_url, skip or verify
                if (!empty($p['image_url']) && strpos($p['image_url'], '/images/') === 0) {
                    continue;
                }

                $slug = $p['slug'];
                if (!$slug) {
                    $results['errors'][] = "Missing slug for product: {$p['name']} (SKU: {$p['sku']})";
                    continue;
                }

                // Check if directory exists for this slug
                $productDir = $this->imagesDir . '/' . $slug;
                if (is_dir($productDir)) {
                    // Check for 1.jpg or 1.png or any image
                    $files = glob($productDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
                    if (!empty($files)) {
                        // Found at least one image!
                        $firstImage = basename($files[0]);
                        $imageUrl = "/images/{$slug}/{$firstImage}";
                        
                        // Update product in DB
                        $this->db->update('products', ['image_url' => $imageUrl], ['id' => $p['id']]);
                        $results['correlated']++;
                    } else {
                        $results['missing_images']++;
                    }
                } else {
                    $results['missing_images']++;
                }
            }
        } catch (\Throwable $e) {
            $results['errors'][] = "System error: " . $e->getMessage();
        }

        return $results;
    }
}
