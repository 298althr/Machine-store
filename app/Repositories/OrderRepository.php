<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class OrderRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(array $orderData, array $items): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO orders (user_id, order_number, invoice_number, status, order_type, subtotal, delivery_mode, delivery_cost, total_amount, currency, 
             billing_name, billing_company, billing_email, billing_phone, billing_address, billing_city, 
             billing_postal, billing_country, vat_number, 
             shipping_name, shipping_company, shipping_address, shipping_city, shipping_postal, shipping_country,
             delivery_facility, notes, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $orderData['created_at'] = date('Y-m-d H:i:s');
        $orderData['updated_at'] = date('Y-m-d H:i:s');

        $stmt->execute([
            $orderData['user_id'] ?? null,
            $orderData['order_number'],
            $orderData['invoice_number'],
            $orderData['status'] ?? 'awaiting_payment',
            $orderData['order_type'],
            $orderData['subtotal'],
            $orderData['delivery_mode'],
            $orderData['delivery_cost'],
            $orderData['total_amount'],
            $orderData['currency'],
            $orderData['billing_name'],
            $orderData['billing_company'],
            $orderData['billing_email'],
            $orderData['billing_phone'],
            $orderData['billing_address'],
            $orderData['billing_city'],
            $orderData['billing_postal'],
            $orderData['billing_country'],
            $orderData['vat_number'] ?? null,
            $orderData['shipping_name'],
            $orderData['shipping_company'],
            $orderData['shipping_address'],
            $orderData['shipping_city'],
            $orderData['shipping_postal'],
            $orderData['shipping_country'],
            $orderData['delivery_facility'] ?? null,
            $orderData['notes'] ?? null,
            $orderData['created_at'],
            $orderData['updated_at']
        ]);

        $orderId = (int)$this->db->lastInsertId();

        $stmtItem = $this->db->prepare(
            'INSERT INTO order_items (order_id, product_id, sku, name, serial_number, quantity, unit_price, total_price)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        foreach ($items as $item) {
            $stmtItem->execute([
                $orderId,
                $item['product_id'],
                $item['sku'],
                $item['name'],
                $item['serial_number'],
                $item['quantity'],
                $item['unit_price'],
                $item['total_price']
            ]);
        }

        return $orderId;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function all(string $status = null, int $limit = null): array
    {
        $sql = "SELECT * FROM orders";
        if ($status) {
            $sql .= " WHERE status = '{$status}'"; // Simplified for CSV
        }
        $sql .= " ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function stats(): array
    {
        $orders = $this->all();
        $stats = [
            'total_orders' => count($orders),
            'pending_payments' => 0,
            'total_revenue' => 0,
            'total_products' => 0, // Handled separately or by productRepo
        ];

        foreach ($orders as $order) {
            if ($order['status'] === 'payment_uploaded') {
                $stats['pending_payments']++;
            }
            if (!in_array($order['status'], ['cancelled', 'awaiting_payment'])) {
                $stats['total_revenue'] += (float)($order['total_amount'] ?? $order['total'] ?? 0);
            }
        }

        return $stats;
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = ?, updated_at = ? WHERE id = ?");
        $stmt->execute([$status, date('Y-m-d H:i:s'), $id]);
    }

    public function confirmPayment(int $id, int $adminId): void
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = ?, payment_confirmed_at = ?, payment_confirmed_by = ?, updated_at = ? WHERE id = ?");
        $stmt->execute(['payment_confirmed', date('Y-m-d H:i:s'), $adminId, date('Y-m-d H:i:s'), $id]);
    }
}
