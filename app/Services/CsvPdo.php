<?php

declare(strict_types=1);

namespace Streicher\App\Services;

use PDO;
use RuntimeException;

/**
 * CsvPdo - A PDO-compatible wrapper for the CsvDb engine.
 * This allows swapping MySQL with CSV without refactoring every query.
 */
class CsvPdo
{
    private CsvDb $db;
    private ?int $lastInsertId = null;

    public function __construct(CsvDb $db)
    {
        $this->db = $db;
    }

    public function prepare(string $sql): CsvStatement
    {
        return new CsvStatement($this->db, $sql, $this);
    }

    public function query(string $sql): CsvStatement
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function exec(string $sql): int
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function lastInsertId(): ?string
    {
        return $this->lastInsertId ? (string)$this->lastInsertId : null;
    }

    public function setLastInsertId(int $id): void
    {
        $this->lastInsertId = $id;
    }
}

/**
 * CsvStatement - Mock PDOStatement for CSV operations.
 */
class CsvStatement
{
    private CsvDb $db;
    private string $sql;
    private CsvPdo $pdo;
    private ?array $results = null;
    private int $cursor = 0;
    private int $rowCount = 0;

    public function __construct(CsvDb $db, string $sql, CsvPdo $pdo)
    {
        $this->db = $db;
        $this->sql = $sql;
        $this->pdo = $pdo;
    }

    public function execute(array $params = []): bool
    {
        $sql = $this->sql;
        
        // Simple SQL Parsing logic
        if (preg_match('/SELECT\s+.*?\s+FROM\s+[`"\'\s]*([a-zA-Z0-9_]+)[`"\'\s]*(?:\s+WHERE\s+(.*))?/is', $sql, $matches)) {
            $table = $matches[1];
            $whereStr = $matches[2] ?? '';
            
            // Aggressive LIMIT cleaning
            $whereStr = preg_replace('/LIMIT\s+\d+\s*$/i', '', trim($whereStr));
            
            $where = $this->parseWhere($whereStr, $params);
            $this->results = $this->db->select($table, $where);
            
            // Handle LIMIT 1
            if (stripos($sql, 'LIMIT 1') !== false) {
                $this->results = !empty($this->results) ? [reset($this->results)] : [];
            }
            
            $this->rowCount = count($this->results);
            return true;
        }

        if (preg_match('/INSERT\s+INTO\s+([a-zA-Z0-9_]+)\s+\((.*?)\)\s+VALUES\s+\((.*?)\)/is', $sql, $matches)) {
            $table = $matches[1];
            $columns = array_map('trim', explode(',', $matches[2]));
            $values = array_map('trim', explode(',', $matches[3]));
            
            $data = [];
            foreach ($columns as $i => $col) {
                $val = $values[$i];
                if ($val === '?') {
                    $data[$col] = array_shift($params);
                } else {
                    $data[$col] = trim($val, "'\"");
                }
            }
            
            $inserted = $this->db->insert($table, $data);
            $this->pdo->setLastInsertId((int)$inserted['id']);
            $this->rowCount = 1;
            return true;
        }

        if (preg_match('/UPDATE\s+([a-zA-Z0-9_]+)\s+SET\s+(.*?)(?:\s+WHERE\s+(.*))?/is', $sql, $matches)) {
            $table = $matches[1];
            $setStr = $matches[2];
            $whereStr = $matches[3] ?? '';
            
            $updates = $this->parseSet($setStr, $params);
            $where = $this->parseWhere($whereStr, $params);
            
            $this->rowCount = $this->db->update($table, $updates, $where);
            return true;
        }

        if (preg_match('/DELETE\s+FROM\s+([a-zA-Z0-9_]+)(?:\s+WHERE\s+(.*))?/is', $sql, $matches)) {
            $table = $matches[1];
            $whereStr = $matches[2] ?? '';
            
            $where = $this->parseWhere($whereStr, $params);
            $this->rowCount = $this->db->delete($table, $where);
            return true;
        }

        // Fallback for complex queries or unsupported syntax
        $this->results = [];
        $this->rowCount = 0;
        return true;
    }

    public function fetch($mode = PDO::FETCH_ASSOC)
    {
        if ($this->results === null) return false;
        $row = $this->results[$this->cursor] ?? false;
        if ($row !== false) $this->cursor++;
        return $row;
    }

    public function fetchAll($mode = PDO::FETCH_ASSOC): array
    {
        if ($this->results === null) return [];
        return array_values($this->results);
    }

    public function fetchColumn(int $index = 0)
    {
        $row = $this->fetch();
        if ($row === false) return false;
        return array_values($row)[$index] ?? false;
    }

    public function rowCount(): int
    {
        return $this->rowCount;
    }

    private function parseWhere(string $whereStr, array &$params): array
    {
        if (empty($whereStr)) return [];
        $where = [];
        
        // Remove LIMIT clause from where string if present
        $whereStr = preg_replace('/\s+LIMIT\s+\d+\s*$/i', '', $whereStr);
        
        // Very basic AND parser
        $parts = preg_split('/\s+AND\s+/i', $whereStr);
        foreach ($parts as $part) {
            if (preg_match('/([a-zA-Z0-9_]+)\s*=\s*(.*)/', $part, $m)) {
                $key = trim($m[1]);
                $val = trim($m[2]);
                if ($val === '?') {
                    $where[$key] = array_shift($params);
                } else {
                    $where[$key] = trim($val, "'\"");
                }
            }
        }
        return $where;
    }

    private function parseSet(string $setStr, array &$params): array
    {
        $updates = [];
        $parts = explode(',', $setStr);
        foreach ($parts as $part) {
            if (preg_match('/([a-zA-Z0-9_]+)\s*=\s*(.*)/', $part, $m)) {
                $key = trim($m[1]);
                $val = trim($m[2]);
                if ($val === '?') {
                    $updates[$key] = array_shift($params);
                } else {
                    $updates[$key] = trim($val, "'\"");
                }
            }
        }
        return $updates;
    }
}
