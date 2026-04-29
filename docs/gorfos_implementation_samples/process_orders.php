<?php
/**
 * Gorfos CSV Processor
 * Reads orders.csv from Streicher, processes new orders, writes tracking_events.csv
 */

// Configuration
define('REPO_DIR', '/var/www/gorfos-sync');
define('ORDERS_CSV', REPO_DIR . '/data/db/orders.csv');
define('TRACKING_CSV', REPO_DIR . '/data/db/tracking_events.csv');
define('PROCESSED_LOG', REPO_DIR . '/processed_orders.txt');
define('LOG_FILE', REPO_DIR . '/logs/processing.log');

class GorfosCsvProcessor {
    private $processedOrders = [];
    private $logEntries = [];
    
    public function __construct() {
        $this->loadProcessedLog();
    }
    
    /**
     * Main processing loop
     */
    public function process() {
        $this->log("Starting order processing");
        
        // Read orders
        $orders = $this->readCsv(ORDERS_CSV);
        if (empty($orders)) {
            $this->log("No orders found in CSV");
            return 0;
        }
        
        $newOrders = 0;
        
        foreach ($orders as $order) {
            // Skip already processed
            if ($this->isProcessed($order['order_number'])) {
                continue;
            }
            
            // Only process orders ready for shipping
            if (!in_array($order['status'], ['payment_confirmed', 'ready_to_ship'])) {
                continue;
            }
            
            // Check if already processed by someone else (via CSV column)
            if (!empty($order['processed_by']) && $order['processed_by'] !== 'gorfos') {
                $this->log("Order {$order['order_number']} already processed by {$order['processed_by']}");
                continue;
            }
            
            $this->processOrder($order);
            $newOrders++;
        }
        
        $this->log("Processed {$newOrders} new orders");
        $this->flushLogs();
        
        return $newOrders;
    }
    
    /**
     * Process a single order
     */
    private function processOrder(array $order) {
        $orderNumber = $order['order_number'];
        
        $this->log("Processing order: {$orderNumber}");
        
        // TODO: Your internal processing logic
        // Examples:
        // - Create shipment in your system
        // - Generate tracking number
        // - Notify warehouse
        
        // For now, we'll create a sample tracking event
        $this->createInitialTrackingEvent($order);
        
        // Mark as processed
        $this->markProcessed($orderNumber);
        
        $this->log("Order {$orderNumber} processed successfully");
    }
    
    /**
     * Create initial tracking event when order is received
     */
    private function createInitialTrackingEvent(array $order) {
        $event = [
            'id' => $this->getNextTrackingId(),
            'tracking_number' => $this->generateTrackingNumber($order),
            'order_id' => $order['id'],
            'status' => 'label_created',
            'location' => 'Gorfos Warehouse',
            'event_time' => date('Y-m-d H:i:s'),
            'notes' => 'Order received from Streicher, preparing shipment'
        ];
        
        $this->appendToCsv(TRACKING_CSV, $event);
        
        $this->log("Created tracking event for order {$order['order_number']}");
    }
    
    /**
     * Generate a tracking number
     */
    private function generateTrackingNumber(array $order): string {
        // Your tracking number format
        // Example: GF-2026-ST-441BAF
        return 'GF-' . date('Y') . '-' . substr($order['order_number'], 3);
    }
    
    /**
     * Read CSV file into array
     */
    private function readCsv(string $filepath): array {
        if (!file_exists($filepath)) {
            return [];
        }
        
        $handle = fopen($filepath, 'r');
        if (!$handle) {
            throw new RuntimeException("Cannot open {$filepath}");
        }
        
        $headers = fgetcsv($handle, 0, ',', '"', '');
        if (!$headers) {
            fclose($handle);
            return [];
        }
        
        $records = [];
        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            if (count($row) === count($headers)) {
                $records[] = array_combine($headers, $row);
            }
        }
        
        fclose($handle);
        return $records;
    }
    
    /**
     * Append record to CSV
     */
    private function appendToCsv(string $filepath, array $record) {
        $fileExists = file_exists($filepath);
        $handle = fopen($filepath, 'a');
        
        if (!$handle) {
            throw new RuntimeException("Cannot open {$filepath} for writing");
        }
        
        // Lock file during write
        flock($handle, LOCK_EX);
        
        try {
            // Write headers if new file
            if (!$fileExists) {
                fputcsv($handle, array_keys($record), ',', '"', '');
            }
            
            fputcsv($handle, array_values($record), ',', '"', '');
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
    
    /**
     * Get next tracking event ID
     */
    private function getNextTrackingId(): int {
        if (!file_exists(TRACKING_CSV)) {
            return 1;
        }
        
        $records = $this->readCsv(TRACKING_CSV);
        $maxId = 0;
        foreach ($records as $record) {
            $maxId = max($maxId, (int)($record['id'] ?? 0));
        }
        
        return $maxId + 1;
    }
    
    /**
     * Load processed orders log
     */
    private function loadProcessedLog() {
        if (file_exists(PROCESSED_LOG)) {
            $lines = file(PROCESSED_LOG, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $parts = explode(',', $line);
                $this->processedOrders[$parts[0]] = $parts[1] ?? date('Y-m-d H:i:s');
            }
        }
    }
    
    /**
     * Check if order is already processed
     */
    private function isProcessed(string $orderNumber): bool {
        return isset($this->processedOrders[$orderNumber]);
    }
    
    /**
     * Mark order as processed
     */
    private function markProcessed(string $orderNumber) {
        $timestamp = date('Y-m-d H:i:s');
        $this->processedOrders[$orderNumber] = $timestamp;
        
        $line = "{$orderNumber},{$timestamp}\n";
        file_put_contents(PROCESSED_LOG, $line, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Log a message
     */
    private function log(string $message) {
        $this->logEntries[] = date('Y-m-d H:i:s') . ' | ' . $message;
        echo $message . "\n";
    }
    
    /**
     * Flush logs to file
     */
    private function flushLogs() {
        if (empty($this->logEntries)) {
            return;
        }
        
        $logDir = dirname(LOG_FILE);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents(LOG_FILE, implode("\n", $this->logEntries) . "\n", FILE_APPEND | LOCK_EX);
    }
}

// Run the processor
try {
    $processor = new GorfosCsvProcessor();
    $count = $processor->process();
    exit($count > 0 ? 0 : 0);  // 0 = success, even if no new orders
} catch (Exception $e) {
    error_log("Order processing failed: " . $e->getMessage());
    exit(1);
}
