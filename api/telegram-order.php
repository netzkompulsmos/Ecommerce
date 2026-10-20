<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function restoreReservedProductStock(mysqli $conn, int $productId): void
{
    $statement = $conn->prepare('UPDATE products SET stock = stock + 1 WHERE id = ?');
    if (!$statement) {
        error_log('Could not prepare product stock restoration after Telegram order failure.');
        return;
    }

    $statement->bind_param('i', $productId);
    if (!$statement->execute()) {
        error_log('Could not restore product stock after Telegram order failure.');
    }
    $statement->close();
}

function readConfigValue(array $keys): ?string
{
    foreach ($keys as $key) {
        $value = getenv($key);
        if ($value !== false && trim($value) !== '') {
            return trim($value);
        }
    }

    $envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
    if (!is_readable($envFile)) {
        return null;
    }

    $config = [];
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (preg_match('/^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*?)\s*$/', $line, $matches)) {
            $value = trim($matches[2]);
            if (strlen($value) >= 2 && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
                $value = substr($value, 1, -1);
            }
            $config[$matches[1]] = $value;
        }
    }

    foreach ($keys as $key) {
        if (isset($config[$key]) && $config[$key] !== '') {
            return $config[$key];
        }
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['success' => false, 'message' => 'Method not allowed.']);
}

if (session_status() !== PHP_SESSION_ACTIVE && !session_start()) {
    respond(500, ['success' => false, 'message' => 'មិនអាចចាប់ផ្ដើម session បានទេ។ សូមព្យាយាមម្តងទៀត។']);
}

$submittedToken = $_POST['csrf_token'] ?? '';
$sessionToken = $_SESSION['order_csrf_token'] ?? '';
if (!is_string($submittedToken) || !is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $submittedToken)) {
    respond(403, ['success' => false, 'message' => 'សំណើមិនត្រឹមត្រូវទេ។ សូមបិទហើយបើកផ្ទាំងទិញម្ដងទៀត។']);
}

$item = $_POST['item'] ?? '';
$customerNameInput = $_POST['customer_name'] ?? '';
$phoneNumberInput = $_POST['phone_number'] ?? '';
$locationInput = $_POST['location'] ?? '';
if (
    !is_string($item)
    || !ctype_digit($item)
    || (int) $item < 1
    || !is_string($customerNameInput)
    || !is_string($phoneNumberInput)
    || !is_string($locationInput)
) {
    respond(422, ['success' => false, 'message' => 'សូមបំពេញឈ្មោះ លេខទូរស័ព្ទ និងទីតាំងឱ្យបានត្រឹមត្រូវ។']);
}

$customerName = trim($customerNameInput);
$phoneNumber = trim($phoneNumberInput);
$location = trim($locationInput);
if (
    $customerName === ''
    || strlen($customerName) > 100
    || $phoneNumber === ''
    || strlen($phoneNumber) > 32
    || $location === ''
    || strlen($location) > 300
) {
    respond(422, ['success' => false, 'message' => 'សូមបំពេញឈ្មោះ លេខទូរស័ព្ទ និងទីតាំងឱ្យបានត្រឹមត្រូវ។']);
}

require __DIR__ . '/db.php';
if (!isset($conn) || !$conn instanceof mysqli) {
    respond(500, ['success' => false, 'message' => 'មិនអាចភ្ជាប់ទៅមូលដ្ឋានទិន្នន័យបានទេ។']);
}
if (!ensureProductStockColumn($conn)) {
    respond(500, ['success' => false, 'message' => 'មិនអាចពិនិត្យស្តុកផលិតផលបានទេ។ សូមទាក់ទង Admin។']);
}
if (!ensureSalesAnalyticsSchema($conn)) {
    respond(500, ['success' => false, 'message' => 'មិនអាចរក្សាទុកប្រវត្តិការបញ្ជាទិញបានទេ។ សូមទាក់ទង Admin។']);
}

$statement = $conn->prepare('SELECT name, price, cost_price, stock FROM products WHERE id = ?');
if (!$statement) {
    error_log('Could not prepare Telegram order product lookup.');
    respond(500, ['success' => false, 'message' => 'មានបញ្ហាក្នុងការរៀបចំព័ត៌មានបញ្ជាទិញ។']);
}
$productId = (int) $item;
$statement->bind_param('i', $productId);
if (!$statement->execute()) {
    $statement->close();
    error_log('Could not execute Telegram order product lookup.');
    respond(500, ['success' => false, 'message' => 'មានបញ្ហាក្នុងការរៀបចំព័ត៌មានបញ្ជាទិញ។']);
}
$result = $statement->get_result();
$productRow = $result ? $result->fetch_assoc() : null;
$statement->close();
if (!$productRow) {
    respond(404, ['success' => false, 'message' => 'រកមិនឃើញផលិតផលនេះទេ។']);
}
if ((int) $productRow['stock'] < 1) {
    respond(409, ['success' => false, 'message' => 'ផលិតផលនេះអស់ស្តុកហើយ។ សូមជ្រើសរើសផលិតផលផ្សេង។']);
}

$receipt = $_FILES['receipt'] ?? null;
if (!is_array($receipt) || !isset($receipt['error']) || !is_int($receipt['error']) || $receipt['error'] === UPLOAD_ERR_NO_FILE) {
    respond(422, ['success' => false, 'message' => 'សូមភ្ជាប់វិក្កយបត្រ ឬរូបថតអេក្រង់បង់ប្រាក់។']);
}
if (
    $receipt['error'] !== UPLOAD_ERR_OK
    || !isset($receipt['tmp_name'], $receipt['size'])
    || !is_string($receipt['tmp_name'])
    || !is_int($receipt['size'])
    || !is_uploaded_file($receipt['tmp_name'])
) {
    $message = $receipt['error'] === UPLOAD_ERR_INI_SIZE || $receipt['error'] === UPLOAD_ERR_FORM_SIZE
        ? 'ឯកសារធំពេក។ សូមជ្រើសរើសឯកសារមិនលើស 5 MB។'
        : 'មិនអាចទទួលយកឯកសារបានទេ។ សូមព្យាយាមម្ដងទៀត។';
    respond(422, ['success' => false, 'message' => $message]);
}
if ($receipt['size'] < 1 || $receipt['size'] > 5 * 1024 * 1024) {
    respond(422, ['success' => false, 'message' => 'ឯកសារធំពេក។ សូមជ្រើសរើសឯកសារមិនលើស 5 MB។']);
}

$fileInfo = new finfo(FILEINFO_MIME_TYPE);
$receiptMime = $fileInfo->file($receipt['tmp_name']);
$receiptTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif',
    'image/heic' => 'heic',
    'image/heif' => 'heif',
    'application/pdf' => 'pdf',
];
if (!is_string($receiptMime) || !isset($receiptTypes[$receiptMime])) {
    respond(422, ['success' => false, 'message' => 'ប្រភេទឯកសារមិនត្រឹមត្រូវ។ អនុញ្ញាតតែ JPG, PNG, WEBP, GIF, HEIC ឬ PDF។']);
}
if (!function_exists('curl_init') || !class_exists('CURLFile')) {
    error_log('PHP cURL is required for Telegram order notifications.');
    respond(500, ['success' => false, 'message' => 'សេវាបញ្ជូន Telegram មិនទាន់អាចប្រើបានទេ។ សូមទាក់ទង Admin។']);
}

$botToken = readConfigValue(['Telegram_bot_token', 'TELEGRAM_BOT_TOKEN']);
$chatId = readConfigValue(['Telegram_chat_id', 'TELEGRAM_CHAT_ID']);
if ($botToken === null || $chatId === null) {
    error_log('Telegram order notification is missing bot token or chat ID configuration.');
    respond(503, ['success' => false, 'message' => 'ការជូនដំណឹង Telegram មិនទាន់បានកំណត់ទេ។ សូមទាក់ទង Admin។']);
}

$reserveStatement = $conn->prepare('UPDATE products SET stock = stock - 1 WHERE id = ? AND stock > 0');
if (!$reserveStatement) {
    error_log('Could not prepare product stock reservation.');
    respond(500, ['success' => false, 'message' => 'មានបញ្ហាក្នុងការកក់ស្តុកផលិតផល។']);
}
$reserveStatement->bind_param('i', $productId);
if (!$reserveStatement->execute()) {
    $reserveStatement->close();
    error_log('Could not reserve product stock.');
    respond(500, ['success' => false, 'message' => 'មានបញ្ហាក្នុងការកក់ស្តុកផលិតផល។']);
}
$stockReserved = $reserveStatement->affected_rows === 1;
$reserveStatement->close();
if (!$stockReserved) {
    respond(409, ['success' => false, 'message' => 'ផលិតផលនេះទើបតែអស់ស្តុក។ សូមជ្រើសរើសផលិតផលផ្សេង។']);
}

$productName = (string) $productRow['name'];
$productPrice = (string) $productRow['price'];
$message = "ការបញ្ជាទិញថ្មី\nផលិតផល: {$productName}\nតម្លៃ: \${$productPrice}\nឈ្មោះ: {$customerName}\nលេខទូរស័ព្ទ: {$phoneNumber}\nទីតាំង: {$location}";
$originalName = basename((string) ($receipt['name'] ?? 'payment-receipt'));
$safeBaseName = preg_replace('/[^A-Za-z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
$safeBaseName = is_string($safeBaseName) && $safeBaseName !== '' ? substr($safeBaseName, 0, 100) : 'payment-receipt';
$safeFileName = $safeBaseName . '.' . $receiptTypes[$receiptMime];
$curl = curl_init('https://api.telegram.org/bot' . $botToken . '/sendDocument');
if ($curl === false) {
    restoreReservedProductStock($conn, $productId);
    error_log('Could not initialize Telegram document upload.');
    respond(500, ['success' => false, 'message' => 'មិនអាចចាប់ផ្តើមការបញ្ជូន Telegram បានទេ។ សូមព្យាយាមម្ដងទៀត។']);
}
curl_setopt_array($curl, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'chat_id' => $chatId,
        'document' => new CURLFile($receipt['tmp_name'], $receiptMime, $safeFileName),
        'caption' => $message,
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2
]);
$responseBody = curl_exec($curl);
$curlError = curl_error($curl);
$httpStatus = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
curl_close($curl);

if ($responseBody === false) {
    restoreReservedProductStock($conn, $productId);
    error_log('Telegram order notification request failed: ' . $curlError);
    respond(502, ['success' => false, 'message' => 'មិនអាចភ្ជាប់ទៅ Telegram បានទេ។ សូមព្យាយាមម្តងទៀត។']);
}

$telegramResponse = json_decode($responseBody, true);
if ($httpStatus < 200 || $httpStatus >= 300 || !is_array($telegramResponse) || ($telegramResponse['ok'] ?? false) !== true) {
    restoreReservedProductStock($conn, $productId);
    error_log('Telegram rejected an order notification (HTTP ' . $httpStatus . ').');
    respond(502, ['success' => false, 'message' => 'Telegram មិនអាចទទួលព័ត៌មានបានទេ។ សូមព្យាយាមម្តងទៀត។']);
}

$saleStatement = $conn->prepare('INSERT INTO sales_orders (product_id, product_name, customer_name, phone_number, location, quantity, unit_price, unit_cost) VALUES (?, ?, ?, ?, ?, 1, ?, ?)');
if (!$saleStatement) {
    error_log('Could not prepare accepted Telegram order record.');
    respond(500, ['success' => false, 'message' => 'ការបញ្ជាទិញត្រូវបានផ្ញើ ប៉ុន្តែមិនអាចរក្សាទុកប្រវត្តិបានទេ។ សូមទាក់ទង Admin។']);
}
$unitCost = $productRow['cost_price'] === null ? null : (float) $productRow['cost_price'];
$saleStatement->bind_param('issssdd', $productId, $productName, $customerName, $phoneNumber, $location, $productPrice, $unitCost);
if (!$saleStatement->execute()) {
    error_log('Could not save accepted Telegram order: ' . $saleStatement->error);
    $saleStatement->close();
    respond(500, ['success' => false, 'message' => 'ការបញ្ជាទិញត្រូវបានផ្ញើ ប៉ុន្តែមិនអាចរក្សាទុកប្រវត្តិបានទេ។ សូមទាក់ទង Admin។']);
}
$saleStatement->close();

unset($_SESSION['order_csrf_token']);
respond(200, ['success' => true, 'message' => 'បានបញ្ជូនព័ត៌មានបញ្ជាទិញទៅ Telegram រួចរាល់។']);
