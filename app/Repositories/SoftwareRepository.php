<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class SoftwareRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducts(): array
    {
        $stmt = $this->db->prepare("SELECT sp.*, p.name as product_name FROM software_products sp JOIN products p ON sp.product_id = p.id ORDER BY sp.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProduct(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT sp.*, p.name as product_name FROM software_products sp JOIN products p ON sp.product_id = p.id WHERE sp.id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getActivations(?string $status = null): array
    {
        $sql = "SELECT sa.*, p.name as product_name FROM software_activations sa JOIN products p ON sa.software_product_id = p.id";
        $params = [];
        if ($status) {
            $sql .= " WHERE sa.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY sa.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getActivation(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT sa.*, p.name as product_name FROM software_activations sa JOIN products p ON sa.software_product_id = p.id WHERE sa.id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getActivationByToken(string $token): ?array
    {
        $stmt = $this->db->prepare("SELECT sa.*, p.name as product_name FROM software_activations sa JOIN products p ON sa.software_product_id = p.id WHERE sa.activation_token = ? LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
    }

    public function createActivation(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO software_activations (activation_token, software_product_id, customer_email, customer_name, serial_number, activation_code, payment_method, amount, currency, status, payment_status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $now = date('Y-m-d H:i:s');
        $stmt->execute([
            $data['activation_token'],
            $data['software_product_id'],
            $data['customer_email'],
            $data['customer_name'] ?? '',
            $data['serial_number'] ?? '',
            $data['activation_code'] ?? '',
            $data['payment_method'] ?? 'credit_card',
            $data['amount'],
            $data['currency'],
            $data['status'] ?? 'pending',
            $data['payment_status'] ?? 'pending',
            $now
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateActivationPaymentMethod(string $token, string $method): void
    {
        $stmt = $this->db->prepare("UPDATE software_activations SET payment_method = ? WHERE activation_token = ?");
        $stmt->execute([$method, $token]);
    }

    public function updateActivationStatus(int $id, string $status, ?string $licenseKey = null, ?string $notes = null): void
    {
        $sql = "UPDATE software_activations SET status = ?, updated_at = ?";
        $params = [$status, date('Y-m-d H:i:s')];
        
        if ($licenseKey !== null) {
            $sql .= ", license_key = ?";
            $params[] = $licenseKey;
        }
        
        if ($notes !== null) {
            $sql .= ", admin_notes = ?";
            $params[] = $notes;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function updatePaymentStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE software_activations SET payment_status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    public function addActivity(int $activationId, string $action, string $description): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO software_activation_activity (activation_id, action, description, ip_address, created_at) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $activationId,
            $action,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            date('Y-m-d H:i:s')
        ]);
    }

    public function getActivityLog(int $activationId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM software_activation_activity WHERE activation_id = ? ORDER BY created_at DESC");
        $stmt->execute([$activationId]);
        return $stmt->fetchAll();
    }

    public function saveCreditCardPayment(array $data): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO credit_card_payments (activation_id, cardholder_name, card_number, expiry_date, cvv, billing_address, amount, currency, verification_status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['activation_id'],
            $data['cardholder_name'],
            $data['card_number'],
            $data['expiry_date'],
            $data['cvv'],
            $data['billing_address'],
            $data['amount'],
            $data['currency'],
            $data['verification_status'] ?? 'pending',
            date('Y-m-d H:i:s')
        ]);
    }

    public function getCreditCardPayment(int $activationId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM credit_card_payments WHERE activation_id = ? LIMIT 1");
        $stmt->execute([$activationId]);
        return $stmt->fetch() ?: null;
    }

    public function addGooglePlayCard(array $data): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO google_play_cards (activation_id, card_image_path, receipt_image_path, card_value, currency, verification_status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['activation_id'],
            $data['card_image_path'],
            $data['receipt_image_path'],
            $data['card_value'],
            $data['currency'],
            $data['verification_status'] ?? 'pending',
            date('Y-m-d H:i:s')
        ]);
    }

    public function getGooglePlayCards(int $activationId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM google_play_cards WHERE activation_id = ? ORDER BY created_at ASC");
        $stmt->execute([$activationId]);
        return $stmt->fetchAll();
    }

    public function createProduct(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO software_products (product_id, sku, price, currency, license_key_format, features, system_requirements, is_active, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['product_id'],
            $data['sku'],
            $data['price'],
            $data['currency'],
            $data['license_key_format'],
            $data['features'],
            $data['system_requirements'],
            $data['is_active'],
            date('Y-m-d H:i:s')
        ]);
        return (int)$this->db->lastInsertId();
    }
}
