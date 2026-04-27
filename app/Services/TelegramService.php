<?php

declare(strict_types=1);

namespace Streicher\App\Services;

class TelegramService
{
    private $botToken;
    private $db;

    public function __construct(string $botToken, $db)
    {
        $this->botToken = $botToken;
        $this->db = $db;
    }

    public function handleWebhook(array $update): void
    {
        if (!isset($update['message'])) {
            return;
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        if (preg_match('/^\/reply\s+([A-Z0-9]+)\s+(.+)$/is', $text, $matches)) {
            $this->handleReply($chatId, strtoupper(trim($matches[1])), trim($matches[2]));
        } elseif (preg_match('/^\/agentreply\s+([A-Z0-9]+)\s+(.+)$/is', $text, $matches)) {
            $this->handleAgentReply($chatId, strtoupper(trim($matches[1])), trim($matches[2]));
        } elseif ($text === '/start' || $text === '/help') {
            $this->sendHelp($chatId);
        }
    }

    private function handleReply(int $chatId, string $trackingNumber, string $replyMessage): void
    {
        $stmt = $this->db->prepare('SELECT * FROM shipments WHERE tracking_number = ?');
        $stmt->execute([$trackingNumber]);
        $shipment = $stmt->fetch();

        if ($shipment) {
            $stmt = $this->db->prepare(
                'INSERT INTO tracking_communications (order_id, tracking_number, sender_type, sender_name, message_type, message, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $shipment['order_id'],
                $trackingNumber,
                'admin',
                'Streicher Support',
                'message',
                $replyMessage,
                date('Y-m-d H:i:s')
            ]);

            $this->sendMessage($chatId, "✅ Reply sent to tracking $trackingNumber:\n\n$replyMessage");
        } else {
            $this->sendMessage($chatId, "❌ Tracking number not found: $trackingNumber");
        }
    }

    private function handleAgentReply(int $chatId, string $trackingNumber, string $replyMessage): void
    {
        $stmt = $this->db->prepare('SELECT * FROM shipments WHERE tracking_number = ?');
        $stmt->execute([$trackingNumber]);
        $shipment = $stmt->fetch();

        if ($shipment) {
            // Get active agent
            $agentStmt = $this->db->prepare('SELECT * FROM agents WHERE status = ? LIMIT 1');
            $agentStmt->execute(['active']);
            $agent = $agentStmt->fetch();

            if ($agent) {
                // Get or create agent chat
                $chatStmt = $this->db->prepare('SELECT * FROM agent_chats WHERE shipment_id = ? LIMIT 1');
                $chatStmt->execute([$shipment['id']]);
                $chat = $chatStmt->fetch();

                if (!$chat) {
                    $stmt = $this->db->prepare(
                        'INSERT INTO agent_chats (shipment_id, agent_id, tracking_number, is_open, created_at, updated_at)
                         VALUES (?, ?, ?, 1, ?, ?)'
                    );
                    $now = date('Y-m-d H:i:s');
                    $stmt->execute([$shipment['id'], $agent['id'], $trackingNumber, $now, $now]);
                    $chatIdDb = $this->db->lastInsertId();
                    
                    $chatStmt = $this->db->prepare('SELECT * FROM agent_chats WHERE id = ?');
                    $chatStmt->execute([$chatIdDb]);
                    $chat = $chatStmt->fetch();
                }

                $stmt = $this->db->prepare(
                    'INSERT INTO agent_chat_messages (chat_id, sender_type, sender_name, message, created_at)
                     VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->execute([$chat['id'], 'agent', $agent['name'], $replyMessage, date('Y-m-d H:i:s')]);

                $this->db->prepare('UPDATE agent_chats SET last_message_at = ?, updated_at = ? WHERE id = ?')
                    ->execute([date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $chat['id']]);

                $this->sendMessage($chatId, "✅ Agent reply sent to tracking $trackingNumber:\n\n$replyMessage");
            } else {
                $this->sendMessage($chatId, "❌ No active agent found");
            }
        } else {
            $this->sendMessage($chatId, "❌ Tracking number not found: $trackingNumber");
        }
    }

    private function sendHelp(int $chatId): void
    {
        $helpMsg = "🤖 Streicher Admin Bot\n\nI notify you when customers send messages.\n\n<b>Commands:</b>\n/reply TRACKING Your message - Reply to tracking communications\n/agentreply TRACKING Your message - Reply to agent chat\n/help - Show this help";
        $this->sendMessage($chatId, $helpMsg, 'HTML');
    }

    private function sendMessage(int $chatId, string $text, string $parseMode = null): void
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        $data = ['chat_id' => $chatId, 'text' => $text];
        if ($parseMode) {
            $data['parse_mode'] = $parseMode;
        }
        
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/json',
                'content' => json_encode($data),
                'timeout' => 5
            ]
        ];
        $context = stream_context_create($options);
        @file_get_contents($url, false, $context);
    }
}
