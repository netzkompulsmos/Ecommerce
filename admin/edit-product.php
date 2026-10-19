<?php
include '../api/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $iconPath = $product['image'];
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../img/products/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['icon']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            if (!empty($product['image']) && file_exists("../" . $product['image'])) {
                unlink("../" . $product['image']);
            }

            $ext = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $iconName = uniqid('icon_', true) . '.' . $ext;
            $iconPath = $targetDir . $iconName;

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $iconPath)) {
                $iconPath = str_replace('../', '', $iconPath);
            } else {
                die("Failed to upload icon");
            }
        } else {
            die("Invalid file type. Only JPG, PNG, GIF, or WEBP allowed.");
        }
    }
    $sql = "UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $name, $desc, $price, $iconPath, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Update error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កែសម្រួលកម្មវិធី • Edit Product • NET STORE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../home/logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="../home/page.css?v=<?php echo filemtime(__DIR__ . '/../home/page.css'); ?>">
    <style>
        .admin-form-layer {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .admin-form-card {
            width: 100%;
            max-width: 520px;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 27, 75, 0.8) 50%, rgba(15, 23, 42, 0.92) 100%);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(99, 102, 241, 0.38);
            border-radius: 32px;
            padding: 38px 32px;
            box-shadow: 
                0 25px 60px -10px rgba(0, 0, 0, 0.9),
                0 0 35px rgba(99, 102, 241, 0.25),
                inset 0 1px 1px rgba(255, 255, 255, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
        }

        .cyber-input {
            width: 100%;
            padding: 13px 18px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(99, 102, 241, 0.25);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            outline: none;
            transition: all 0.25s ease;
        }

        .cyber-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--cyber-cyan);
            box-shadow: 0 0 16px rgba(6, 182, 212, 0.35);
        }

        textarea.cyber-input {
            min-height: 100px;
            resize: vertical;
        }

        .current-app-preview {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 16px;
            margin-bottom: 12px;
        }

        .current-app-preview img {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid var(--cyber-cyan);
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.3);
        }

        .file-upload-box {
            position: relative;
            border: 2px dashed rgba(99, 102, 241, 0.35);
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: rgba(255, 255, 255, 0.02);
        }

        .file-upload-box:hover {
            border-color: var(--cyber-cyan);
            background: rgba(6, 182, 212, 0.05);
        }

        .file-upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .file-preview-wrap {
            display: none;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
            padding: 8px 14px;
            border-radius: 12px;
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid var(--cyber-emerald);
            color: var(--cyber-emerald);
            font-size: 13px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="cyber-grid-lines"></div>

    <div class="admin-form-layer">
        <div class="admin-form-card">
            <!-- Header bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <a href="index.php" class="cyber-glow-btn" style="padding: 6px 16px; font-size: 13px;">
                    <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                </a>
                <span class="dna-badge" style="font-size: 11px; padding: 4px 10px;">
                    <i class="fas fa-pen"></i> Edit Product
                </span>
            </div>

            <div style="text-align: center; margin-bottom: 24px;">
                <h2 style="font-size: 26px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif;">
                    កែសម្រួលកម្មវិធី
                </h2>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;">
                    ផ្លាស់ប្តូរឈ្មោះ ព័ត៌មានលម្អិត តម្លៃ ឬរូបភាពតំណាង
                </p>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-tag" style="color: var(--cyber-cyan); margin-right: 6px;"></i> ឈ្មោះកម្មវិធី (Product Name)</label>
                    <input type="text" name="name" class="cyber-input" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left" style="color: var(--cyber-purple); margin-right: 6px;"></i> ព័ត៌មានលម្អិត (Description)</label>
                    <textarea name="description" class="cyber-input" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-dollar-sign" style="color: var(--cyber-emerald); margin-right: 6px;"></i> តម្លៃជាដុល្លារ (Price in USD)</label>
                    <input type="number" step="0.01" name="price" class="cyber-input" value="<?= htmlspecialchars($product['price']) ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image" style="color: var(--cyber-pink); margin-right: 6px;"></i> រូបភាពតំណាងកម្មវិធី (App Icon)</label>
                    <?php if (!empty($product['image'])): ?>
                        <div class="current-app-preview" id="currentIconBox">
                            <img src="../<?= htmlspecialchars($product['image']) ?>" alt="Current Icon">
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #fff;">រូបភាពបច្ចុប្បន្ន</div>
                                <div style="font-size: 12px; color: var(--text-dim);">ជ្រើសរើសខាងក្រោមប្រសិនបើចង់ប្តូរថ្មី</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="file-upload-box">
                        <input type="file" name="icon" id="iconFileInput" accept="image/*">
                        <i class="fas fa-cloud-arrow-up" style="font-size: 32px; color: var(--cyber-cyan); margin-bottom: 8px;"></i>
                        <div style="font-size: 14px; font-weight: 700; color: #fff;">
                            ចុចទីនេះដើម្បីជ្រើសរើសរូបភាពថ្មី
                        </div>
                        <div style="font-size: 12px; color: var(--text-dim); margin-top: 4px;">
                            គាំទ្រ: PNG, JPG, WEBP ឬ GIF (ទុកនៅទទេបើមិនចង់ប្តូរ)
                        </div>
                        <div class="file-preview-wrap" id="filePreviewWrap">
                            <i class="fas fa-check"></i>
                            <span id="fileNameLabel"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="cyber-buy-action" style="width: 100%; padding: 14px; margin-top: 12px; font-size: 15px;">
                    <i class="fas fa-save"></i> រក្សាទុកការកែប្រែ (Save Changes)
                </button>
            </form>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById("iconFileInput");
        const previewWrap = document.getElementById("filePreviewWrap");
        const nameLabel = document.getElementById("fileNameLabel");
        fileInput.addEventListener("change", function() {
            if (this.files && this.files[0]) {
                nameLabel.textContent = this.files[0].name;
                previewWrap.style.display = "inline-flex";
            }
        });
    </script>
</body>

</html>