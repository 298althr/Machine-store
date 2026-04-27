-- Streicher Agent Chat Feature
-- Adds agents, shipment assignment, and WhatsApp-style chat tables

CREATE TABLE IF NOT EXISTS agents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(50) NULL,
  telegram_chat_id BIGINT NULL,
  status ENUM('active','suspended') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE shipments
  ADD COLUMN assigned_agent_id INT NULL AFTER status,
  ADD CONSTRAINT fk_shipments_assigned_agent
    FOREIGN KEY (assigned_agent_id) REFERENCES agents(id)
    ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS agent_chats (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  shipment_id BIGINT NOT NULL,
  agent_id INT NOT NULL,
  tracking_number VARCHAR(100) NOT NULL,
  customer_name VARCHAR(255) NULL,
  customer_email VARCHAR(255) NULL,
  customer_phone VARCHAR(50) NULL,
  is_open TINYINT(1) DEFAULT 1,
  last_message_at TIMESTAMP NULL,
  closed_reason VARCHAR(255) NULL,
  closed_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_agent_chats_shipment FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE,
  CONSTRAINT fk_agent_chats_agent FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL,
  UNIQUE KEY uniq_agent_chat_shipment (shipment_id),
  INDEX idx_agent_chats_agent (agent_id),
  INDEX idx_agent_chats_tracking (tracking_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS agent_chat_messages (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  chat_id BIGINT NOT NULL,
  sender_type ENUM('customer','agent','admin','system') NOT NULL,
  sender_name VARCHAR(255) NULL,
  message TEXT NULL,
  attachment_name VARCHAR(255) NULL,
  attachment_path VARCHAR(500) NULL,
  attachment_type VARCHAR(100) NULL,
  attachment_size INT NULL,
  telegram_message_id BIGINT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  delivered_at TIMESTAMP NULL,
  read_at TIMESTAMP NULL,
  CONSTRAINT fk_agent_chat_messages_chat FOREIGN KEY (chat_id) REFERENCES agent_chats(id) ON DELETE CASCADE,
  INDEX idx_agent_chat_messages_chat (chat_id),
  INDEX idx_agent_chat_messages_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
