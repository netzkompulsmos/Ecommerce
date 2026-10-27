<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ecommerce";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!$conn->query("SET time_zone = '+07:00'")) {
    error_log('Could not set the application database time zone: ' . $conn->error);
    die('Could not initialize the application database time zone.');
}

if (!function_exists('ensureProductStockColumn')) {
    function ensureProductStockColumn(mysqli $connection): bool
    {
        $stockColumn = $connection->query("SHOW COLUMNS FROM products LIKE 'stock'");
        if (!$stockColumn) {
            error_log('Could not check product stock schema: ' . $connection->error);
            return false;
        }

        if ($stockColumn->num_rows === 0 && !$connection->query('ALTER TABLE products ADD stock INT UNSIGNED NOT NULL DEFAULT 0')) {
            error_log('Could not add product stock column: ' . $connection->error);
            return false;
        }

        return true;
    }
}

if (!function_exists('ensureSalesAnalyticsSchema')) {
    function ensureSalesAnalyticsSchema(mysqli $connection): bool
    {
        $costColumn = $connection->query("SHOW COLUMNS FROM products LIKE 'cost_price'");
        if (!$costColumn) {
            error_log('Could not check product cost schema: ' . $connection->error);
            return false;
        }

        if ($costColumn->num_rows === 0 && !$connection->query('ALTER TABLE products ADD cost_price DECIMAL(10,2) NULL DEFAULT NULL')) {
            error_log('Could not add product cost column: ' . $connection->error);
            return false;
        }

        $salesTable = "CREATE TABLE IF NOT EXISTS sales_orders (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id INT NULL,
            product_name VARCHAR(255) NOT NULL,
            customer_name VARCHAR(100) NOT NULL,
            phone_number VARCHAR(32) NOT NULL,
            location VARCHAR(300) NOT NULL,
            quantity INT UNSIGNED NOT NULL DEFAULT 1,
            unit_price DECIMAL(10,2) NOT NULL,
            unit_cost DECIMAL(10,2) NULL DEFAULT NULL,
            order_reference VARCHAR(40) NULL DEFAULT NULL,
            payment_method VARCHAR(20) NULL DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_sales_created_at (created_at),
            KEY idx_sales_product_id (product_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        if (!$connection->query($salesTable)) {
            error_log('Could not create sales history table: ' . $connection->error);
            return false;
        }

        $orderReferenceColumn = $connection->query("SHOW COLUMNS FROM sales_orders LIKE 'order_reference'");
        if (!$orderReferenceColumn) {
            error_log('Could not check sales order reference schema: ' . $connection->error);
            return false;
        }
        if ($orderReferenceColumn->num_rows === 0 && !$connection->query('ALTER TABLE sales_orders ADD order_reference VARCHAR(40) NULL DEFAULT NULL')) {
            error_log('Could not add sales order reference column: ' . $connection->error);
            return false;
        }

        $paymentMethodColumn = $connection->query("SHOW COLUMNS FROM sales_orders LIKE 'payment_method'");
        if (!$paymentMethodColumn) {
            error_log('Could not check sales payment method schema: ' . $connection->error);
            return false;
        }
        if ($paymentMethodColumn->num_rows === 0 && !$connection->query('ALTER TABLE sales_orders ADD payment_method VARCHAR(20) NULL DEFAULT NULL')) {
            error_log('Could not add sales payment method column: ' . $connection->error);
            return false;
        }

        $activityTable = "CREATE TABLE IF NOT EXISTS product_activity (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id INT NULL,
            product_name VARCHAR(255) NOT NULL,
            action VARCHAR(20) NOT NULL,
            actor VARCHAR(100) NOT NULL,
            old_values TEXT NULL,
            new_values TEXT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_product_activity_created_at (created_at),
            KEY idx_product_activity_product_id (product_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        if (!$connection->query($activityTable)) {
            error_log('Could not create product activity table: ' . $connection->error);
            return false;
        }

        return true;
    }
}

require_once __DIR__ . '/admin-operations.php';
?>
