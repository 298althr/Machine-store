<?php

declare(strict_types=1);

namespace Streicher\App\Services;

use RuntimeException;

/**
 * CsvDb - A lightweight flat-file database engine using CSV.
 * Supports CRUD operations with file locking for concurrency.
 */
class CsvDb
{
    private string $basePath;
    private string $separator;

    public function __construct(?string $basePath = null)
    {
        $this->basePath = $basePath ? rtrim($basePath, '/') : rtrim($_ENV['DB_CSV_PATH'] ?? 'data/db', '/');
        $this->separator = $_ENV['DB_CSV_SEPARATOR'] ?? ',';
        
        // Ensure directory exists
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0755, true);
        }
    }

    /**
     * Get all records from a table.
     */
    public function all(string $table): array
    {
        return $this->read($table);
    }

    /**
     * Find a record by its primary key (id).
     */
    public function find(string $table, $id): ?array
    {
        $records = $this->read($table);
        foreach ($records as $record) {
            if (isset($record['id']) && (string)$record['id'] === (string)$id) {
                return $record;
            }
        }
        return null;
    }

    /**
     * Select records based on where criteria.
     * Simple key-value matching.
     */
    public function select(string $table, array $where = []): array
    {
        $records = $this->read($table);
        if (empty($where)) {
            return $records;
        }

        return array_filter($records, function ($record) use ($where) {
            foreach ($where as $key => $value) {
                if (!isset($record[$key]) || (string)$record[$key] !== (string)$value) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Insert a new record.
     */
    public function insert(string $table, array $data): array
    {
        return $this->lockAndWrite($table, function (&$records) use ($data) {
            // Auto-increment ID if not provided
            if (!isset($data['id'])) {
                $maxId = 0;
                foreach ($records as $record) {
                    $maxId = max($maxId, (int)($record['id'] ?? 0));
                }
                $data['id'] = $maxId + 1;
            }

            // Ensure all keys exist (based on headers)
            $records[] = $data;
            return $data;
        });
    }

    /**
     * Update existing records matching where criteria.
     */
    public function update(string $table, array $updates, array $where): int
    {
        $count = 0;
        $this->lockAndWrite($table, function (&$records) use ($updates, $where, &$count) {
            foreach ($records as &$record) {
                $match = true;
                foreach ($where as $wKey => $wValue) {
                    if (!isset($record[$wKey]) || (string)$record[$wKey] !== (string)$wValue) {
                        $match = false;
                        break;
                    }
                }

                if ($match) {
                    foreach ($updates as $uKey => $uValue) {
                        $record[$uKey] = $uValue;
                    }
                    $count++;
                }
            }
        });
        return $count;
    }

    /**
     * Delete records matching where criteria.
     */
    public function delete(string $table, array $where): int
    {
        $count = 0;
        $this->lockAndWrite($table, function (&$records) use ($where, &$count) {
            $records = array_filter($records, function ($record) use ($where, &$count) {
                $match = true;
                foreach ($where as $wKey => $wValue) {
                    if (!isset($record[$wKey]) || (string)$record[$wKey] !== (string)$wValue) {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    $count++;
                    return false; // Remove
                }
                return true; // Keep
            });
            $records = array_values($records); // Re-index
        });
        return $count;
    }

    /**
     * Core reading logic.
     */
    private function read(string $table): array
    {
        $filePath = "{$this->basePath}/{$table}.csv";
        if (!file_exists($filePath)) {
            return [];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return [];
        }

        $headers = fgetcsv($handle, 0, $this->separator);
        if (!$headers) {
            fclose($handle);
            return [];
        }

        $records = [];
        while (($row = fgetcsv($handle, 0, $this->separator)) !== false) {
            if (count($row) === count($headers)) {
                $records[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $records;
    }

    /**
     * Handle file locking and writing back to CSV.
     */
    private function lockAndWrite(string $table, callable $callback)
    {
        $filePath = "{$this->basePath}/{$table}.csv";
        if (!file_exists($filePath)) {
            throw new RuntimeException("Table file not found: {$filePath}");
        }

        $handle = fopen($filePath, 'r+');
        if (!$handle) {
            throw new RuntimeException("Could not open table file: {$filePath}");
        }

        // Exclusive lock
        flock($handle, LOCK_EX);

        try {
            // Read current data
            rewind($handle);
            $headers = fgetcsv($handle, 0, $this->separator);
            $records = [];
            while (($row = fgetcsv($handle, 0, $this->separator)) !== false) {
                if (count($row) === count($headers)) {
                    $records[] = array_combine($headers, $row);
                }
            }

            // Perform operation
            $result = $callback($records);

            // Write back
            ftruncate($handle, 0);
            rewind($handle);
            fputcsv($handle, $headers, $this->separator);
            foreach ($records as $record) {
                $row = [];
                foreach ($headers as $header) {
                    $row[] = $record[$header] ?? '';
                }
                fputcsv($handle, $row, $this->separator);
            }

            return $result;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
