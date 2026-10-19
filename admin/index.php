<?php
include '../api/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';
$validActions = ['list', 'add', 'edit', 'delete'];
if (!in_array($action, $validActions, true)) {
    $action = 'list';
}

$error_msg = '';
$product = null;
$id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($action === 'edit' || $action === 'delete') {
    if (!$id || $id < 1) {
        header('Location: index.php');
        exit;
    }

    $stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: index.php');
        exit;
    }

    $error_msg = 'Error deleting product: ' . $stmt->error;
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priceInput = $_POST['price'] ?? '';

    if ($name === '' || $description === '' || !is_numeric($priceInput) || (float) $priceInput < 0) {
        $error_msg = 'Please provide a name, description, and valid non-negative price.';
    }

    $imagePath = $action === 'edit' ? $product['image'] : '';
    $uploadedImagePath = '';
    $imageChanged = false;
    $imageSource = $_POST['image_source'] ?? 'upload';
    $imageUrlInput = $_POST['image_url'] ?? '';
    $imageUrl = is_string($imageUrlInput) ? trim($imageUrlInput) : '';
    $upload = $_FILES['icon'] ?? null;

    if (!in_array($imageSource, ['upload', 'url'], true)) {
        $error_msg = 'Please choose an image upload method.';
    }

    if ($error_msg === '' && $imageSource === 'url' && !is_string($imageUrlInput)) {
        $error_msg = 'Please provide a valid HTTP(S) image URL no longer than 255 characters.';
    }

    if ($error_msg === '' && $imageSource === 'url' && $imageUrl !== '') {
        if (strlen($imageUrl) > 255 || filter_var($imageUrl, FILTER_VALIDATE_URL) === false) {
            $error_msg = 'Please provide a valid HTTP(S) image URL no longer than 255 characters.';
        } else {
            $urlParts = parse_url($imageUrl);
            $urlScheme = is_array($urlParts) ? strtolower($urlParts['scheme'] ?? '') : '';
            if (
                !in_array($urlScheme, ['http', 'https'], true)
                || empty($urlParts['host'])
                || isset($urlParts['user'])
                || isset($urlParts['pass'])
            ) {
                $error_msg = 'Please provide a valid HTTP(S) image URL no longer than 255 characters.';
            } else {
                $imagePath = $imageUrl;
                $imageChanged = true;
            }
        }
    } elseif ($error_msg === '' && $imageSource === 'url' && $action === 'add') {
        $error_msg = 'Please provide an image URL.';
    } elseif ($error_msg === '' && $imageSource === 'upload' && $upload && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($upload['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) {
            $error_msg = 'Failed to upload icon.';
        } else {
            $imageTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];
            $fileType = mime_content_type($upload['tmp_name']);

            if (!isset($imageTypes[$fileType])) {
                $error_msg = 'Invalid file type. Only JPG, PNG, GIF, or WEBP allowed.';
            } else {
                $targetDirectory = __DIR__ . '/../img/products/';
                if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
                    $error_msg = 'Failed to create the product image directory.';
                } else {
                    $imageName = 'icon_' . bin2hex(random_bytes(16)) . '.' . $imageTypes[$fileType];
                    $targetPath = $targetDirectory . $imageName;

                    if (!move_uploaded_file($upload['tmp_name'], $targetPath)) {
                        $error_msg = 'Failed to upload icon.';
                    } else {
                        $uploadedImagePath = 'img/products/' . $imageName;
                        $imagePath = $uploadedImagePath;
                        $imageChanged = true;
                    }
                }
            }
        }
    } elseif ($error_msg === '' && $imageSource === 'upload' && $action === 'add') {
        $error_msg = 'Please select a product icon.';
    }

    if ($error_msg === '') {
        if ($action === 'add') {
            $stmt = $conn->prepare('INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)');
            $price = (float) $priceInput;
            $stmt->bind_param('ssds', $name, $description, $price, $imagePath);
        } else {
            $stmt = $conn->prepare('UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?');
            $price = (float) $priceInput;
            $stmt->bind_param('ssdsi', $name, $description, $price, $imagePath, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();

            if ($action === 'edit' && $imageChanged && !empty($product['image'])) {
                $oldImage = $product['image'];
                if ($oldImage === 'img/products/' . basename($oldImage)) {
                    $oldImagePath = __DIR__ . '/../' . $oldImage;
                    if (is_file($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            header('Location: index.php');
            exit;
        }

        $error_msg = 'Error saving product: ' . $stmt->error;
        $stmt->close();
        if ($uploadedImagePath !== '') {
            unlink(__DIR__ . '/../' . $uploadedImagePath);
        }
    }

    if ($action === 'edit') {
        $product['name'] = $_POST['name'] ?? $name;
        $product['description'] = $_POST['description'] ?? $description;
        $product['price'] = $priceInput;
        $product['image'] = $uploadedImagePath !== '' ? ($product['image'] ?? '') : $imagePath;
    }
}

$result = $conn->query('SELECT * FROM products ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គ្រប់គ្រងព័ត៏មានកម្មវិធី • Admin Dashboard • iOS 27 Liquid Glass</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../home/logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="../home/page.css?v=<?php echo filemtime(__DIR__ . '/../home/page.css'); ?>">
    <style>
        .admin-dashboard-container {
            max-width: 1140px;
            margin: 40px auto 60px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .admin-header-glass {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 27, 75, 0.8) 50%, rgba(15, 23, 42, 0.92) 100%);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(99, 102, 241, 0.35);
            border-radius: 28px;
            padding: 24px 30px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .admin-table-glass {
            width: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(20, 25, 55, 0.8) 100%);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(99, 102, 241, 0.28);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 30px 60px -10px rgba(0, 0, 0, 0.65);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead {
            background: rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }

        th {
            padding: 16px 20px;
            font-size: 13.5px;
            font-weight: 700;
            color: #00f2fe;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.88);
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .app-icon-img {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-table {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: rgba(0, 242, 254, 0.15);
            border-color: rgba(0, 242, 254, 0.4);
            color: #00f2fe;
        }

        .btn-edit:hover {
            background: rgba(0, 242, 254, 0.3);
            transform: translateY(-2px);
        }

        .btn-delete {
            background: rgba(255, 68, 68, 0.15);
            border-color: rgba(255, 68, 68, 0.4);
            color: #ff5555;
            margin-left: 6px;
        }

        .btn-delete:hover {
            background: rgba(255, 68, 68, 0.3);
            transform: translateY(-2px);
        }

        .delete-confirmation-backdrop {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(3, 6, 17, 0.76);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .delete-confirmation-backdrop[hidden] {
            display: none;
        }

        .delete-confirmation-dialog {
            width: min(100%, 420px);
            padding: 30px;
            border: 1px solid rgba(255, 85, 85, 0.55);
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(19, 25, 45, 0.99), rgba(10, 15, 30, 0.99));
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.6), 0 0 28px rgba(255, 68, 68, 0.16);
            color: #fff;
            text-align: center;
        }

        .delete-warning-icon {
            display: grid;
            width: 64px;
            height: 64px;
            place-items: center;
            margin: 0 auto 18px;
            border: 1px solid rgba(255, 85, 85, 0.4);
            border-radius: 50%;
            background: rgba(255, 68, 68, 0.13);
            color: #ff6262;
            font-size: 26px;
        }

        .delete-confirmation-dialog h2 {
            margin-bottom: 10px;
            color: #fff;
            font-size: 21px;
        }

        .delete-confirmation-dialog p {
            color: rgba(226, 232, 240, 0.78);
            font-size: 14px;
            line-height: 1.65;
        }

        .delete-product-name {
            display: block;
            margin: 12px 0 22px;
            overflow-wrap: anywhere;
            color: #fff;
            font-weight: 700;
        }

        .delete-confirmation-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .delete-confirmation-actions button,
        .delete-confirmation-actions a {
            min-height: 42px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .delete-cancel-button {
            border-color: rgba(148, 163, 184, 0.35) !important;
            background: rgba(148, 163, 184, 0.12);
            color: #e2e8f0;
        }

        .delete-confirm-button {
            background: #dc3d4b;
            color: #fff;
        }

        .delete-cancel-button:hover {
            background: rgba(148, 163, 184, 0.22);
        }

        .delete-confirm-button:hover {
            background: #f04452;
        }

        .admin-form-layer {
            position: fixed;
            inset: 0;
            z-index: 3000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: max(24px, env(safe-area-inset-top)) 16px max(24px, env(safe-area-inset-bottom));
            background: rgba(3, 6, 17, 0.38);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            animation: overlay-fade-in 220ms ease-out both;
            overscroll-behavior: contain;
        }

        .admin-form-dashboard-background {
            position: fixed;
            inset: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
            border: 0;
            pointer-events: none;
            animation: dashboard-reveal 500ms ease-out both;
        }

        .admin-form-card {
            box-sizing: border-box;
            width: 100%;
            max-width: 520px;
            max-height: calc(100vh - 48px);
            max-height: calc(100dvh - 48px);
            overflow-y: auto;
            padding: 38px 32px;
            border: 1px solid rgba(99, 102, 241, 0.38);
            border-radius: 32px;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(30, 27, 75, 0.94), rgba(15, 23, 42, 0.97));
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.9), 0 0 35px rgba(99, 102, 241, 0.25);
            animation: form-card-enter 300ms cubic-bezier(0.2, 0.75, 0.25, 1) both;
            overscroll-behavior: contain;
            scrollbar-width: thin;
        }

        .admin-form-card h2 {
            margin-bottom: 4px;
            color: #fff;
            font-size: 26px;
            text-align: center;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .admin-form-card .form-description {
            margin-bottom: 24px;
            color: var(--text-muted);
            font-size: 13.5px;
            text-align: center;
        }

        .admin-form-card .form-group {
            margin-bottom: 20px;
        }

        .admin-form-card .form-group label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13.5px;
            font-weight: 700;
        }

        .admin-form-card .cyber-input {
            box-sizing: border-box;
            width: 100%;
            padding: 13px 18px;
            border: 1px solid rgba(0, 240, 255, 0.25);
            border-radius: 16px;
            outline: none;
            background: rgba(0, 240, 255, 0.06);
            color: #fff;
            font: inherit;
            font-size: 16px;
        }

        .admin-form-card textarea.cyber-input {
            min-height: 95px;
            resize: vertical;
        }

        .admin-form-card .file-upload-box {
            position: relative;
            padding: 22px;
            border: 2px dashed rgba(0, 240, 255, 0.35);
            border-radius: 20px;
            background: rgba(0, 240, 255, 0.04);
            text-align: center;
        }

        .image-source-options {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }

        .image-source-option {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.035);
            color: rgba(255, 255, 255, 0.85);
            cursor: pointer;
            text-align: center;
            transition: border-color 160ms ease, background-color 160ms ease;
        }

        .image-source-option:has(input:checked) {
            border-color: var(--cyber-cyan);
            background: rgba(0, 240, 255, 0.1);
        }

        .image-source-option input {
            margin-right: 6px;
            accent-color: var(--cyber-cyan);
        }

        .image-source-panel[hidden] {
            display: none;
        }

        .image-url-hint {
            margin-top: 6px;
            color: var(--text-dim);
            font-size: 12px;
        }

        .admin-form-card .file-upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .admin-form-card .current-app-preview {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
            padding: 12px 16px;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
        }

        .admin-form-card .current-app-preview img {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            object-fit: cover;
        }

        .admin-form-card .file-preview-wrap {
            display: none;
            margin-top: 10px;
            color: var(--cyber-emerald);
        }

        .admin-error {
            margin-bottom: 18px;
            padding: 12px 18px;
            border: 1px solid var(--cyber-pink);
            border-radius: 16px;
            background: rgba(255, 42, 133, 0.18);
            color: #ff66b2;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }

        @keyframes overlay-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes dashboard-reveal {
            from { opacity: 0.7; }
            to { opacity: 1; }
        }

        @keyframes form-card-enter {
            from { opacity: 0; transform: translateY(14px) scale(0.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 600px) {
            .admin-form-layer {
                align-items: flex-start;
                padding: max(12px, env(safe-area-inset-top)) 12px max(12px, env(safe-area-inset-bottom));
            }

            .admin-form-card {
                max-height: calc(100vh - 24px);
                max-height: calc(100dvh - 24px);
                padding: 24px 20px;
                border-radius: 24px;
            }

            .admin-form-card h2 {
                font-size: 23px;
            }

            .admin-form-card .form-group {
                margin-bottom: 16px;
            }

            .admin-form-card .cyber-input {
                padding: 12px 14px;
            }

            .admin-form-card .file-upload-box {
                padding: 18px 14px;
            }
        }

        @media (max-width: 360px) {
            .admin-form-layer {
                padding-right: 8px;
                padding-left: 8px;
            }

            .admin-form-card {
                padding: 20px 14px;
                border-radius: 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-form-layer,
            .admin-form-dashboard-background,
            .admin-form-card {
                animation: none;
            }
        }

        @media (max-width: 480px) {
            .delete-confirmation-dialog {
                padding: 24px 18px;
            }

            .delete-confirmation-actions {
                flex-direction: column-reverse;
            }
        }
    </style>
</head>

<body>
    <div class="cyber-grid-lines"></div>

    <?php if ($action === 'add' || $action === 'edit'): ?>
        <?php $isEditing = $action === 'edit'; ?>
        <iframe class="admin-form-dashboard-background" src="index.php?action=list" title="Product dashboard background" aria-hidden="true" tabindex="-1"></iframe>
        <div class="admin-form-layer">
            <div class="admin-form-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <a href="index.php" class="cyber-glow-btn" style="padding: 6px 16px; font-size: 13px;">
                        <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                    </a>
                    <span class="dna-badge" style="font-size: 11px; padding: 4px 10px;">
                        <i class="fas fa-<?= $isEditing ? 'pen' : 'plus-circle' ?>"></i> <?= $isEditing ? 'Edit Product' : 'New Product' ?>
                    </span>
                </div>
                <h2><?= $isEditing ? 'កែសម្រួលកម្មវិធី' : 'បន្ថែមកម្មវិធីថ្មី' ?></h2>
                <p class="form-description"><?= $isEditing ? 'ផ្លាស់ប្តូរឈ្មោះ ព័ត៌មានលម្អិត តម្លៃ ឬរូបភាពតំណាង' : 'សូមបំពេញព័ត៌មានលម្អិតនៃ Account ឬកម្មវិធីដែលត្រូវដាក់លក់' ?></p>

                <?php if ($error_msg !== ''): ?>
                    <div class="admin-error"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?= $isEditing ? 'edit' : 'add' ?>">
                    <?php if ($isEditing): ?>
                        <input type="hidden" name="id" value="<?= (int) $id ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label><i class="fas fa-tag" style="color: var(--cyber-cyan); margin-right: 6px;"></i> ឈ្មោះកម្មវិធី (Product Name)</label>
                        <input type="text" name="name" class="cyber-input" value="<?= htmlspecialchars($_POST['name'] ?? $product['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left" style="color: var(--cyber-purple); margin-right: 6px;"></i> ព័ត៌មានលម្អិត (Description)</label>
                        <textarea name="description" class="cyber-input" required><?= htmlspecialchars($_POST['description'] ?? $product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign" style="color: var(--cyber-emerald); margin-right: 6px;"></i> តម្លៃជាដុល្លារ (Price in USD)</label>
                        <input type="number" step="0.01" min="0" name="price" class="cyber-input" value="<?= htmlspecialchars((string) ($_POST['price'] ?? $product['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image" style="color: var(--cyber-pink); margin-right: 6px;"></i> រូបភាពតំណាងកម្មវិធី (App Icon)</label>
                        <?php if ($isEditing && !empty($product['image'])): ?>
                            <div class="current-app-preview">
                                <img src="<?= htmlspecialchars(preg_match('~^https?://~i', $product['image']) ? $product['image'] : '../' . $product['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Current Icon">
                                <span>រូបភាពបច្ចុប្បន្ន — ទុកនៅទទេបើមិនចង់ប្តូរ</span>
                            </div>
                        <?php endif; ?>
                        <?php
                        $currentImageIsUrl = !empty($product['image']) && preg_match('~^https?://~i', $product['image']);
                        $selectedImageSource = $_POST['image_source'] ?? ($currentImageIsUrl ? 'url' : 'upload');
                        ?>
                        <div class="image-source-options" role="radiogroup" aria-label="Image source">
                            <label class="image-source-option">
                                <input type="radio" name="image_source" value="upload" <?= $selectedImageSource === 'upload' ? 'checked' : '' ?>>
                                Upload file
                            </label>
                            <label class="image-source-option">
                                <input type="radio" name="image_source" value="url" <?= $selectedImageSource === 'url' ? 'checked' : '' ?>>
                                Image link (URL)
                            </label>
                        </div>
                        <div class="image-source-panel" id="imageUploadPanel">
                            <div class="file-upload-box">
                                <input type="file" name="icon" id="iconFileInput" accept="image/jpeg,image/png,image/gif,image/webp">
                                <i class="fas fa-cloud-arrow-up" style="font-size: 32px; color: var(--cyber-cyan); margin-bottom: 8px;"></i>
                                <div style="font-size: 14px; font-weight: 700; color: #fff;">ចុចទីនេះដើម្បីជ្រើសរើសរូបភាព</div>
                                <div style="font-size: 12px; color: var(--text-dim); margin-top: 4px;">គាំទ្រ: PNG, JPG, WEBP ឬ GIF</div>
                                <div class="file-preview-wrap" id="filePreviewWrap"><i class="fas fa-check"></i> <span id="fileNameLabel"></span></div>
                            </div>
                        </div>
                        <div class="image-source-panel" id="imageUrlPanel" hidden>
                            <input type="url" name="image_url" id="imageUrlInput" class="cyber-input" maxlength="255" placeholder="https://example.com/product-image.jpg" value="<?= htmlspecialchars($_POST['image_url'] ?? ($currentImageIsUrl ? $product['image'] : ''), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="image-url-hint">Use a direct image link starting with http:// or https://.</div>
                        </div>
                    </div>
                    <button type="submit" class="cyber-buy-action" style="width: 100%; padding: 14px; margin-top: 12px; font-size: 15px;">
                        <i class="fas fa-<?= $isEditing ? 'save' : 'check-circle' ?>"></i>
                        <?= $isEditing ? 'រក្សាទុកការកែប្រែ (Save Changes)' : 'រក្សាទុក និងដាក់លក់ (Add Product)' ?>
                    </button>
                </form>
            </div>
        </div>
    <?php elseif ($action === 'delete'): ?>
        <div class="admin-form-layer">
            <section class="delete-confirmation-dialog" role="alertdialog" aria-modal="true">
                <div class="delete-warning-icon"><i class="fas fa-trash-can"></i></div>
                <h2>បញ្ជាក់ការលុបកម្មវិធី</h2>
                <p>តើអ្នកពិតជាចង់លុបកម្មវិធីនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។</p>
                <span class="delete-product-name"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php if ($error_msg !== ''): ?><div class="admin-error"><?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                <form action="index.php" method="POST" class="delete-confirmation-actions">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $id ?>">
                    <a href="index.php" class="delete-cancel-button" style="display: inline-flex; align-items: center; text-decoration: none;">បោះបង់</a>
                    <button type="submit" class="delete-confirm-button"><i class="fas fa-trash"></i> លុបកម្មវិធី</button>
                </form>
            </section>
        </div>
    <?php else: ?>
    <div class="admin-dashboard-container">
        <!-- Top bar -->
        <div class="admin-header-glass">
            <div>
                <span class="section-tag"><i class="fas fa-shield-alt"></i> Control Center</span>
                <h2 style="font-size: 24px; font-weight: 800; color: #fff; margin-top: 4px;">គ្រប់គ្រងព័ត៏មានកម្មវិធី (Products)</h2>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="index.php?action=add" class="ios-glass-btn btn-aurora">
                    <i class="fas fa-plus"></i> + បន្ថែមកម្មវិធីថ្មី
                </a>
                <a href="../customer-service/dashboard.php" class="ios-glass-btn">
                    <i class="fas fa-comments"></i> មើលសារអតិថិជន
                </a>
                <a href="../home/index.php" class="ios-glass-btn">
                    <i class="fas fa-external-link-alt"></i> មើលទំព័រមុខ
                </a>
                <a href="../home/index.php" class="ios-glass-btn" style="border-color: rgba(255,75,75,0.4); color: #ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i> ចាកចេញ
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="admin-table-glass">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">ល.រ</th>
                        <th style="width: 80px;">រូបភាព</th>
                        <th>ឈ្មោះកម្មវិធី</th>
                        <th>ព័ត៌មានលម្អិត</th>
                        <th>តម្លៃ</th>
                        <th style="width: 180px; text-align: center;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td style="font-weight: 700; color: #00f2fe;"><?= $row['id'] ?></td>
                                <td>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="<?= htmlspecialchars(preg_match('~^https?://~i', $row['image']) ? $row['image'] : '../' . $row['image'], ENT_QUOTES, 'UTF-8') ?>" class="app-icon-img" alt="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?php else: ?>
                                        <div class="app-icon-img" style="display:flex;align-items:center;justify-content:center;color:#666;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 700; color: #fff;"><?= htmlspecialchars($row['name']) ?></td>
                                <td style="color: var(--text-muted); font-size: 13px;"><?= htmlspecialchars($row['description'] ?? '') ?></td>
                                <td>
                                    <span style="display:inline-block; padding: 4px 10px; border-radius: 9999px; background: rgba(0,245,160,0.15); border: 1px solid rgba(0,245,160,0.3); color: #00f5a0; font-weight: 700;">
                                        $<?= number_format($row['price'], 2) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <a class="btn-table btn-edit" href="index.php?action=edit&amp;id=<?= (int) $row['id'] ?>">
                                        <i class="fas fa-pen"></i> កែប្រែ
                                    </a>
                                    <a class="btn-table btn-delete" href="index.php?action=delete&amp;id=<?= (int) $row['id'] ?>" data-delete-product="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>" data-product-id="<?= (int) $row['id'] ?>">
                                        <i class="fas fa-trash"></i> លុប
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                មិនទាន់មានទិន្នន័យកម្មវិធីនៅឡើយទេ (No products found)
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="delete-confirmation-backdrop" id="deleteConfirmation" hidden>
        <section class="delete-confirmation-dialog" role="alertdialog" aria-modal="true" aria-labelledby="deleteConfirmationTitle" aria-describedby="deleteConfirmationMessage" tabindex="-1">
            <div class="delete-warning-icon"><i class="fas fa-trash-can"></i></div>
            <h2 id="deleteConfirmationTitle">បញ្ជាក់ការលុបកម្មវិធី</h2>
            <p id="deleteConfirmationMessage">តើអ្នកពិតជាចង់លុបកម្មវិធីនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។</p>
            <span class="delete-product-name" id="deleteProductName"></span>
            <div class="delete-confirmation-actions">
                <button type="button" class="delete-cancel-button" id="cancelProductDelete">បោះបង់</button>
                <button type="button" class="delete-confirm-button" id="confirmProductDelete"><i class="fas fa-trash"></i> លុបកម្មវិធី</button>
            </div>
        </section>
    </div>
    <form action="index.php" method="POST" id="deleteProductForm" hidden>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteProductId">
    </form>
    <script>
        (() => {
            const modal = document.getElementById('deleteConfirmation');
            const dialog = modal.querySelector('[role="alertdialog"]');
            const productName = document.getElementById('deleteProductName');
            const cancelButton = document.getElementById('cancelProductDelete');
            const confirmButton = document.getElementById('confirmProductDelete');
            const deleteForm = document.getElementById('deleteProductForm');
            const deleteProductId = document.getElementById('deleteProductId');
            let previouslyFocusedElement = null;

            const closeModal = () => {
                modal.hidden = true;
                if (previouslyFocusedElement) previouslyFocusedElement.focus();
            };

            document.querySelectorAll('[data-delete-product]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    deleteProductId.value = link.dataset.productId;
                    previouslyFocusedElement = link;
                    productName.textContent = link.dataset.deleteProduct;
                    modal.hidden = false;
                    cancelButton.focus();
                });
            });

            cancelButton.addEventListener('click', closeModal);
            confirmButton.addEventListener('click', () => {
                deleteForm.submit();
            });
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) closeModal();
            });
        })();
    </script>
    <?php endif; ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <script>
            const fileInput = document.getElementById("iconFileInput");
            const previewWrap = document.getElementById("filePreviewWrap");
            const nameLabel = document.getElementById("fileNameLabel");
            const uploadPanel = document.getElementById("imageUploadPanel");
            const urlPanel = document.getElementById("imageUrlPanel");
            const urlInput = document.getElementById("imageUrlInput");
            const imageSourceOptions = document.querySelectorAll('input[name="image_source"]');

            const updateImageSource = () => {
                const selectedSource = document.querySelector('input[name="image_source"]:checked').value;
                const useUrl = selectedSource === 'url';
                uploadPanel.hidden = useUrl;
                urlPanel.hidden = !useUrl;
                fileInput.required = !useUrl && <?= $isEditing ? 'false' : 'true' ?>;
                urlInput.required = useUrl && <?= $isEditing ? 'false' : 'true' ?>;
            };

            imageSourceOptions.forEach((option) => option.addEventListener('change', updateImageSource));
            updateImageSource();

            fileInput.addEventListener("change", function() {
                if (this.files && this.files[0]) {
                    nameLabel.textContent = this.files[0].name;
                    previewWrap.style.display = "inline-flex";
                }
            });
        </script>
    <?php endif; ?>
</body>

</html>