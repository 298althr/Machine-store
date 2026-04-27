<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class ShipmentRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByOrderId(int $orderId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM shipments WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO shipments (order_id, assigned_agent_id, tracking_number, carrier, status, shipped_at, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $data['created_at'] = date('Y-m-d H:i:s');
        $stmt->execute([
            $data['order_id'],
            $data['assigned_agent_id'] ?? null,
            $data['tracking_number'],
            $data['carrier'] ?? 'Streicher Logistics',
            $data['status'] ?? 'shipped',
            $data['shipped_at'] ?? date('Y-m-d H:i:s'),
            $data['created_at']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $shipmentId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE shipments SET status = ? WHERE id = ?");
        $stmt->execute([$status, $shipmentId]);
    }

    public function addHistory(int $shipmentId, string $status, string $location, string $description): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tracking_history (shipment_id, status, location, description, timestamp)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$shipmentId, $status, $location, $description, date('Y-m-d H:i:s')]);
    }

    public function getHistory(int $shipmentId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tracking_history WHERE shipment_id = ? ORDER BY timestamp DESC");
        $stmt->execute([$shipmentId]);
        return $stmt->fetchAll();
    }
}
