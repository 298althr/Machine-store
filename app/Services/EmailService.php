<?php

declare(strict_types=1);

namespace Streicher\App\Services;

use Exception;
use PDO;

/**
 * Email Service for Streicher Platform
 * Consolidates Mailer and old web/email_service.php
 */
class EmailService
{
    private string $host;
    private int $port;
    private ?string $username;
    private ?string $password;
    private string $fromAddress;
    private string $fromName;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->host = $_ENV['MAIL_HOST'] ?? 'localhost';
        $this->port = (int)($_ENV['MAIL_PORT'] ?? 1025);
        $this->username = $_ENV['MAIL_USERNAME'] ?: null;
        $this->password = $_ENV['MAIL_PASSWORD'] ?: null;
        $this->fromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@streicher.de';
        $this->fromName = $_ENV['MAIL_FROM_NAME'] ?? 'Streicher GmbH';
    }

    /**
     * Send an email.
     */
    public function send(string $to, string $subject, string $body, bool $isHtml = false): bool
    {
        $this->logEmail($to, $subject, $body, 'pending');

        try {
            $result = $this->sendWithSmtp($to, $subject, $body, $isHtml);
            $this->updateEmailStatus($to, $subject, $result ? 'sent' : 'failed');
            return $result;
        } catch (Exception $e) {
            error_log("EmailService Error: " . $e->getMessage());
            $this->updateEmailStatus($to, $subject, 'failed', $e->getMessage());
            return false;
        }
    }

    /**
     * SMTP implementation using sockets.
     */
    private function sendWithSmtp(string $to, string $subject, string $body, bool $isHtml): bool
    {
        $socket = @fsockopen($this->host, $this->port, $errno, $errstr, 30);
        if (!$socket) {
            throw new Exception("Could not connect to SMTP server: $errstr ($errno)");
        }

        $this->smtpRead($socket); // Greeting
        $this->smtpWrite($socket, "EHLO localhost");
        $this->smtpRead($socket);

        if ($this->username && $this->password) {
            $this->smtpWrite($socket, "AUTH LOGIN");
            $this->smtpRead($socket);
            $this->smtpWrite($socket, base64_encode($this->username));
            $this->smtpRead($socket);
            $this->smtpWrite($socket, base64_encode($this->password));
            $this->smtpRead($socket);
        }

        $this->smtpWrite($socket, "MAIL FROM:<{$this->fromAddress}>");
        $this->smtpRead($socket);
        $this->smtpWrite($socket, "RCPT TO:<{$to}>");
        $this->smtpRead($socket);
        $this->smtpWrite($socket, "DATA");
        $this->smtpRead($socket);

        $contentType = $isHtml ? 'text/html' : 'text/plain';
        $message = "From: {$this->fromName} <{$this->fromAddress}>\r\n";
        $message .= "To: {$to}\r\n";
        $message .= "Subject: {$subject}\r\n";
        $message .= "MIME-Version: 1.0\r\n";
        $message .= "Content-Type: {$contentType}; charset=UTF-8\r\n";
        $message .= "\r\n";
        $message .= $body;
        $message .= "\r\n.";

        $this->smtpWrite($socket, $message);
        $this->smtpRead($socket);
        $this->smtpWrite($socket, "QUIT");
        fclose($socket);

        return true;
    }

    private function smtpWrite($socket, string $data): void
    {
        fwrite($socket, $data . "\r\n");
    }

    private function smtpRead($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        return $response;
    }

    private function logEmail(string $to, string $subject, string $body, string $status): void
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO email_logs (to_email, subject, body, status) VALUES (?, ?, ?, ?)');
            $stmt->execute([$to, $subject, $body, $status]);
        } catch (Exception $e) {
            // Ignore if table missing
        }
    }

    private function updateEmailStatus(string $to, string $subject, string $status, string $error = null): void
    {
        try {
            $stmt = $this->db->prepare('UPDATE email_logs SET status = ?, error = ?, sent_at = NOW() WHERE to_email = ? AND subject = ? ORDER BY id DESC LIMIT 1');
            $stmt->execute([$status, $error, $to, $subject]);
        } catch (Exception $e) {
            // Ignore
        }
    }

    // --- Templates ---

    public function getOrderConfirmationHtml(array $order): string
    {
        $orderNumber = $order['order_number'];
        $total = number_format((float)$order['total_amount'], 2);
        $currency = $order['currency'] ?? 'EUR';
        $symbol = ($currency === 'USD') ? '$' : '€';

        return "<h1>Order Confirmation</h1><p>Thank you for your order <b>{$orderNumber}</b>.</p><p>Total: {$symbol}{$total}</p>";
    }
}
