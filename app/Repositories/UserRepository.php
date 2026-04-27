<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class UserRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function logLoginAttempt(string $ip, string $email, bool $success): void
    {
        $stmt = $this->db->prepare("INSERT INTO login_attempts (ip_address, email, success, attempted_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ip, $email, $success ? 1 : 0, date('Y-m-d H:i:s')]);
    }

    public function countFailedAttempts(string $ip, int $minutes = 15): int
    {
        $stmt = $this->db->prepare("SELECT * FROM login_attempts WHERE ip_address = ? AND success = 0");
        $stmt->execute([$ip]);
        $attempts = $stmt->fetchAll();
        $cutoff = time() - ($minutes * 60);
        $count = 0;
        foreach ($attempts as $attempt) {
            if (strtotime($attempt['attempted_at']) > $cutoff) {
                $count++;
            }
        }
        return $count;
    }
}
