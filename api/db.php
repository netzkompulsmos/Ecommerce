<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ecommerce";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
?>
