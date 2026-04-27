-- Streicher B2B Shop - Software Activation Feature
-- Adds tables for software license activation and payment processing

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- Software products management
CREATE TABLE IF NOT EXISTS software_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    requires_serial BOOLEAN DEFAULT TRUE,
    requires_activation_code BOOLEAN DEFAULT TRUE,
    license_key_format VARCHAR(20) DEFAULT '003XXXXXXXXXXXX',
    support_email VARCHAR(255),
    download_url VARCHAR(500),
    activation_instructions TEXT,
    price DECIMAL(12,2) NOT NULL,
    currency CHAR(3) DEFAULT 'USD',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_software_products_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- License key management (plain text)
CREATE TABLE IF NOT EXISTS license_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    software_product_id INT NOT NULL,
    license_key VARCHAR(16) UNIQUE NOT NULL,
    status ENUM('available', 'sold', 'activated', 'blocked') DEFAULT 'available',
    activation_date TIMESTAMP NULL,
    expiry_date TIMESTAMP NULL,
    activated_by INT NULL,
    activation_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_license_keys_software_product FOREIGN KEY (software_product_id) REFERENCES software_products(id) ON DELETE CASCADE,
    CONSTRAINT fk_license_keys_user FOREIGN KEY (activated_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_license_keys_activation FOREIGN KEY (activation_id) REFERENCES software_activations(id) ON DELETE SET NULL,
    INDEX idx_software_product_id (software_product_id),
    INDEX idx_license_key (license_key),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Software activation requests
CREATE TABLE IF NOT EXISTS software_activations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    activation_token VARCHAR(64) UNIQUE NOT NULL,
    software_product_id INT NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_name VARCHAR(255),
    serial_number VARCHAR(255),
    activation_code VARCHAR(255),
    license_key VARCHAR(16),
    status ENUM('pending', 'processing', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    payment_method ENUM('google_play', 'credit_card') NOT NULL,
    payment_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    amount DECIMAL(10,2) NOT NULL,
    currency CHAR(3) DEFAULT 'USD',
    admin_notes TEXT,
    customer_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_software_activations_software_product FOREIGN KEY (software_product_id) REFERENCES software_products(id) ON DELETE CASCADE,
    INDEX idx_activation_token (activation_token),
    INDEX idx_software_product_id (software_product_id),
    INDEX idx_customer_email (customer_email),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Credit card payments (plain text storage)
CREATE TABLE IF NOT EXISTS credit_card_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activation_id BIGINT NOT NULL,
    cardholder_name VARCHAR(255) NOT NULL,
    card_number VARCHAR(20) NOT NULL,
    expiry_date VARCHAR(10) NOT NULL,
    cvv VARCHAR(5) NOT NULL,
    billing_address JSON,
    amount DECIMAL(10,2) NOT NULL,
    currency CHAR(3) DEFAULT 'USD',
    verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    verified_by INT NULL,
    verification_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_credit_card_payments_activation FOREIGN KEY (activation_id) REFERENCES software_activations(id) ON DELETE CASCADE,
    CONSTRAINT fk_credit_card_payments_user FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_activation_id (activation_id),
    INDEX idx_verification_status (verification_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Google Play payments
CREATE TABLE IF NOT EXISTS google_play_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activation_id BIGINT NOT NULL,
    card_image_path VARCHAR(500),
    receipt_image_path VARCHAR(500),
    card_value DECIMAL(10,2),
    currency CHAR(3) DEFAULT 'USD',
    verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    verified_by INT NULL,
    verification_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_google_play_payments_activation FOREIGN KEY (activation_id) REFERENCES software_activations(id) ON DELETE CASCADE,
    CONSTRAINT fk_google_play_payments_user FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_activation_id (activation_id),
    INDEX idx_verification_status (verification_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Software activation activity log
CREATE TABLE IF NOT EXISTS software_activation_activity (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    activation_id BIGINT NOT NULL,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT NULL,
    old_value TEXT NULL,
    new_value TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_software_activation_activity_activation FOREIGN KEY (activation_id) REFERENCES software_activations(id) ON DELETE CASCADE,
    CONSTRAINT fk_software_activation_activity_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_activation_id (activation_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample software products for testing
INSERT IGNORE INTO software_products (product_id, requires_serial, requires_activation_code, price, currency, activation_instructions) VALUES
(
    (SELECT id FROM products WHERE sku LIKE 'SFT-%' AND name LIKE '%PipeFlow%' LIMIT 1),
    TRUE, TRUE, 125000.00, 'USD',
    '1. Install PipeFlow Pro on your device\n2. Enter your device serial number\n3. Use the activation code provided with your software\n4. Your license key will be sent via email after payment verification'
),
(
    (SELECT id FROM products WHERE sku LIKE 'SFT-%' AND name LIKE '%PlantDesign%' LIMIT 1),
    TRUE, TRUE, 98000.00, 'USD',
    '1. Download PlantDesign Suite from the provided link\n2. Enter your hardware serial number during installation\n3. Use the activation code from your software package\n4. License key will be delivered upon payment confirmation'
),
(
    (SELECT id FROM products WHERE sku LIKE 'SFT-%' AND name LIKE '%MechCAD%' LIMIT 1),
    TRUE, TRUE, 115000.00, 'USD',
    '1. Install MechCAD Professional on your workstation\n2. Enter the serial number from your hardware dongle\n3. Input the activation code from your software package\n4. Receive your perpetual license key via email'
);

SET FOREIGN_KEY_CHECKS=1;
