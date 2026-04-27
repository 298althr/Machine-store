<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class SupportRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function all(string $status = null): array
    {
        $sql = "SELECT * FROM support_tickets";
        if ($status) {
            $sql .= " WHERE status = '{$status}'";
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM support_tickets WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO support_tickets (ticket_number, name, company, email, phone, subject, message, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $data['status'] = $data['status'] ?? 'open';
        $data['created_at'] = date('Y-m-d H:i:s');
        $stmt->execute([
            $data['ticket_number'],
            $data['name'],
            $data['company'] ?? null,
            $data['email'],
            $data['phone'] ?? null,
            $data['subject'],
            $data['message'],
            $data['status'],
            $data['created_at']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status, string $adminNotes = null): void
    {
        $stmt = $this->db->prepare("UPDATE support_tickets SET status = ?, admin_notes = ? WHERE id = ?");
        $stmt->execute([$status, $adminNotes, $id]);
    }
}
