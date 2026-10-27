-- Apply with the ecommerce database selected. Safe to re-run; no existing rows are deleted.
SET @migration_sql = IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales_orders' AND COLUMN_NAME = 'coupon_id') = 0,
    'ALTER TABLE sales_orders ADD coupon_id BIGINT UNSIGNED NULL DEFAULT NULL',
    'SELECT 1'
);
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales_orders' AND COLUMN_NAME = 'coupon_code') = 0,
    'ALTER TABLE sales_orders ADD coupon_code VARCHAR(40) NULL DEFAULT NULL',
    'SELECT 1'
);
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

SET @migration_sql = IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales_orders' AND COLUMN_NAME = 'discount_amount') = 0,
    'ALTER TABLE sales_orders ADD discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00',
    'SELECT 1'
);
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

CREATE TABLE IF NOT EXISTS discount_coupons (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    coupon_code VARCHAR(40) NOT NULL,
    discount_type VARCHAR(12) NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    minimum_subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    max_uses INT UNSIGNED NULL DEFAULT NULL,
    uses_count INT UNSIGNED NOT NULL DEFAULT 0,
    starts_at DATETIME NULL DEFAULT NULL,
    expires_at DATETIME NULL DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_by INT NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_discount_coupon_code (coupon_code),
    KEY idx_discount_coupon_active_expiry (is_active, expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_notifications (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    notification_type VARCHAR(32) NOT NULL,
    title VARCHAR(160) NOT NULL,
    message VARCHAR(500) NOT NULL,
    target_url VARCHAR(255) NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    read_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_admin_notifications_unread (read_at, created_at),
    KEY idx_admin_notifications_type (notification_type, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
