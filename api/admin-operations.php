<?php
declare(strict_types=1);

if (!function_exists('ensureAdminOperationsSchema')) {
    function ensureAdminOperationsSchema(mysqli $connection): bool
    {
        $columns = [
            'products' => [
                'low_stock_threshold' => 'INT UNSIGNED NOT NULL DEFAULT 5',
            ],
            'sales_orders' => [
                'status' => "VARCHAR(20) NOT NULL DEFAULT 'Pending'",
                'payment_status' => "VARCHAR(20) NOT NULL DEFAULT 'pending'",
                'payment_transaction_id' => 'VARCHAR(120) NULL DEFAULT NULL',
                'paid_amount' => 'DECIMAL(10,2) NULL DEFAULT NULL',
                'receipt_filename' => 'VARCHAR(100) NULL DEFAULT NULL',
                'receipt_mime' => 'VARCHAR(100) NULL DEFAULT NULL',
                'inventory_reserved' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'phone_normalized' => 'VARCHAR(32) NULL DEFAULT NULL',
            ],
        ];

        foreach ($columns as $table => $tableColumns) {
            foreach ($tableColumns as $column => $definition) {
                $columnResult = $connection->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
                if (!$columnResult) {
                    error_log("Could not check $table.$column schema: " . $connection->error);
                    return false;
                }
                if ($columnResult->num_rows === 0 && !$connection->query("ALTER TABLE `$table` ADD `$column` $definition")) {
                    error_log("Could not add $table.$column schema: " . $connection->error);
                    return false;
                }
            }
        }

        $tables = [
            "CREATE TABLE IF NOT EXISTS customer_profiles (
                phone_normalized VARCHAR(32) NOT NULL,
                phone_number VARCHAR(32) NOT NULL,
                customer_name VARCHAR(100) NOT NULL,
                notes TEXT NULL,
                is_blocked TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (phone_normalized),
                KEY idx_customer_name (customer_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS sales_order_history (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                order_id BIGINT UNSIGNED NOT NULL,
                event_type VARCHAR(32) NOT NULL,
                old_value VARCHAR(120) NULL DEFAULT NULL,
                new_value VARCHAR(120) NULL DEFAULT NULL,
                note VARCHAR(500) NULL DEFAULT NULL,
                transaction_id VARCHAR(120) NULL DEFAULT NULL,
                amount DECIMAL(10,2) NULL DEFAULT NULL,
                actor VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_order_history_order_id (order_id),
                KEY idx_order_history_type (event_type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS inventory_adjustments (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                product_id INT NULL DEFAULT NULL,
                product_name VARCHAR(255) NOT NULL,
                change_amount INT NOT NULL,
                stock_before INT UNSIGNED NOT NULL,
                stock_after INT UNSIGNED NOT NULL,
                reason VARCHAR(255) NOT NULL,
                actor VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_inventory_adjustments_product (product_id),
                KEY idx_inventory_adjustments_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];

        foreach ($tables as $sql) {
            if (!$connection->query($sql)) {
                error_log('Could not create an admin operations table: ' . $connection->error);
                return false;
            }
        }

        $phoneIndex = $connection->query("SHOW INDEX FROM sales_orders WHERE Key_name = 'idx_sales_phone_normalized'");
        if (!$phoneIndex) {
            error_log('Could not check customer phone index: ' . $connection->error);
            return false;
        }
        if ($phoneIndex->num_rows === 0 && !$connection->query('ALTER TABLE sales_orders ADD INDEX idx_sales_phone_normalized (phone_normalized)')) {
            error_log('Could not add customer phone index: ' . $connection->error);
            return false;
        }

        $statusIndex = $connection->query("SHOW INDEX FROM sales_orders WHERE Key_name = 'idx_sales_status_created'");
        if (!$statusIndex) {
            error_log('Could not check order status index: ' . $connection->error);
            return false;
        }
        if ($statusIndex->num_rows === 0 && !$connection->query('ALTER TABLE sales_orders ADD INDEX idx_sales_status_created (status, created_at)')) {
            error_log('Could not add order status index: ' . $connection->error);
            return false;
        }

        $legacyOrders = $connection->query('SELECT COUNT(*) AS remaining FROM sales_orders WHERE phone_normalized IS NULL');
        if (!$legacyOrders) {
            error_log('Could not check legacy customer orders: ' . $connection->error);
            return false;
        }
        $legacyCount = (int) $legacyOrders->fetch_assoc()['remaining'];
        $legacyOrders->free();
        if ($legacyCount > 0) {
            if (!$connection->query("UPDATE sales_orders SET phone_normalized = REGEXP_REPLACE(phone_number, '[^0-9]', '') WHERE phone_normalized IS NULL")) {
                error_log('Could not normalize legacy customer phone numbers: ' . $connection->error);
                return false;
            }
            if (!$connection->query("INSERT IGNORE INTO customer_profiles (phone_normalized, phone_number, customer_name)
                SELECT phone_normalized, MAX(phone_number), MAX(customer_name)
                FROM sales_orders
                WHERE phone_normalized IS NOT NULL AND phone_normalized <> ''
                GROUP BY phone_normalized")) {
                error_log('Could not migrate legacy customer profiles: ' . $connection->error);
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('normalizeCustomerPhone')) {
    function normalizeCustomerPhone(string $phoneNumber): string
    {
        return preg_replace('/\D+/', '', $phoneNumber) ?? '';
    }
}

if (!function_exists('privateReceiptDirectory')) {
    function privateReceiptDirectory(): string
    {
        $configuredDirectory = getenv('ECOMMERCE_PRIVATE_RECEIPT_DIR');
        if ($configuredDirectory !== false && trim($configuredDirectory) !== '') {
            return trim($configuredDirectory);
        }

        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $publicDirectory = is_string($documentRoot) && $documentRoot !== '' ? realpath($documentRoot) : false;
        $parentDirectory = $publicDirectory !== false ? dirname($publicDirectory) : sys_get_temp_dir();
        return $parentDirectory . DIRECTORY_SEPARATOR . 'ecommerce-private-receipts';
    }
}

if (!function_exists('recordSalesOrderHistory')) {
    function recordSalesOrderHistory(
        mysqli $connection,
        int $orderId,
        string $eventType,
        ?string $oldValue,
        ?string $newValue,
        ?string $note,
        ?string $transactionId,
        ?float $amount,
        string $actor
    ): bool {
        $statement = $connection->prepare('INSERT INTO sales_order_history (order_id, event_type, old_value, new_value, note, transaction_id, amount, actor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        if (!$statement) {
            error_log('Could not prepare order history entry: ' . $connection->error);
            return false;
        }

        $statement->bind_param('isssssds', $orderId, $eventType, $oldValue, $newValue, $note, $transactionId, $amount, $actor);
        $saved = $statement->execute();
        if (!$saved) {
            error_log('Could not save order history entry: ' . $statement->error);
        }
        $statement->close();
        return $saved;
    }
}

if (!function_exists('recordInventoryAdjustment')) {
    function recordInventoryAdjustment(
        mysqli $connection,
        ?int $productId,
        string $productName,
        int $changeAmount,
        int $stockBefore,
        int $stockAfter,
        string $reason,
        string $actor
    ): bool {
        $statement = $connection->prepare('INSERT INTO inventory_adjustments (product_id, product_name, change_amount, stock_before, stock_after, reason, actor) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if (!$statement) {
            error_log('Could not prepare inventory adjustment: ' . $connection->error);
            return false;
        }

        $statement->bind_param('isiiiss', $productId, $productName, $changeAmount, $stockBefore, $stockAfter, $reason, $actor);
        $saved = $statement->execute();
        if (!$saved) {
            error_log('Could not save inventory adjustment: ' . $statement->error);
        }
        $statement->close();
        return $saved;
    }
}
