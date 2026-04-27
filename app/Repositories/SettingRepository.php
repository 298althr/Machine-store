<?php

declare(strict_types=1);

namespace Streicher\App\Repositories;

class SettingRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM settings");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function get(string $key, $default = null)
    {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    }
}
