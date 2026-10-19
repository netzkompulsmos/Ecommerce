<?php
include '../api/db.php';

$error_msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);

    $iconPath = '';
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../img/products/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['icon']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            $ext = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $iconName = uniqid('icon_', true) . '.' . $ext;
            $iconPath = $targetDir . $iconName;
            
            if (move_uploaded_file($_FILES['icon']['tmp_name'], $iconPath)) {
                $iconPath = str_replace('../', '', $iconPath);
            } else {
                $error_msg = "បរាជ័យក្នុងការ Upload រូបភាព (Failed to upload icon)";
            }
        } else {
            $error_msg = "ប្រភេទ file មិនត្រឹមត្រូវ (JPG, PNG, GIF, WEBP only)";
        }
    } else {
        $error_msg = "សូមជ្រើសរើសរូបភាពកម្មវិធី (Icon is required)";
    }

    if (empty($error_msg)) {
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $desc, $price, $iconPath);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error_msg = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បន្ថែមកម្មវិធីថ្មី • Add Product • DNA Cyber Edition</title>
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
            background: rgba(0, 240, 255, 0.06);
            border: 1px solid rgba(0, 240, 255, 0.25);
            color: #fff;
            font-family: inherit;
            font-size: 14.5px;
            outline: none;
            transition: all 0.25s ease;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .cyber-input:focus {
            background: rgba(0, 240, 255, 0.12);
            border-color: var(--cyber-cyan);
            box-shadow: 0 0 16px rgba(0, 240, 255, 0.35);
        }

        textarea.cyber-input {
            min-height: 95px;
            resize: vertical;
        }

        .file-upload-box {
            position: relative;
            border: 2px dashed rgba(0, 240, 255, 0.35);
            background: rgba(0, 240, 255, 0.04);
            border-radius: 20px;
            padding: 22px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .file-upload-box:hover {
            border-color: var(--cyber-cyan);
            background: rgba(0, 240, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
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
            background: rgba(0, 255, 170, 0.12);
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
                    <i class="fas fa-plus-circle"></i> New Product
                </span>
            </div>

            <div style="text-align: center; margin-bottom: 24px;">
                <h2 style="font-size: 26px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif;">
                    បន្ថែមកម្មវិធីថ្មី
                </h2>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;">
                    សូមបំពេញព័ត៌មានលម្អិតនៃ Account ឬកម្មវិធីដែលត្រូវដាក់លក់
                </p>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div style="background: rgba(255,42,133,0.18); border: 1px solid var(--cyber-pink); color: #ff66b2; padding: 12px 18px; border-radius: 16px; font-size: 13px; font-weight: 700; margin-bottom: 18px; text-align: center;">
                    <i class="fas fa-triangle-exclamation"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="add-product.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-tag" style="color: var(--cyber-cyan); margin-right: 6px;"></i> ឈ្មោះកម្មវិធី (Product Name)</label>
                    <input type="text" name="name" class="cyber-input" placeholder="ឧទាហរណ៍: NETFLIX 4K ULTRA" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left" style="color: var(--cyber-purple); margin-right: 6px;"></i> ព័ត៌មានលម្អិត (Description)</label>
                    <textarea name="description" class="cyber-input" placeholder="ឧទាហរណ៍: គណនី Premium គុណភាព 4K UHD ធានាពេញ 30 ថ្ងៃ..." required></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-dollar-sign" style="color: var(--cyber-emerald); margin-right: 6px;"></i> តម្លៃជាដុល្លារ (Price in USD)</label>
                    <input type="number" step="0.01" name="price" class="cyber-input" placeholder="ឧទាហរណ៍: 4.99" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image" style="color: var(--cyber-pink); margin-right: 6px;"></i> រូបភាពតំណាងកម្មវិធី (App Icon)</label>
                    <div class="file-upload-box">
                        <input type="file" name="icon" id="iconFileInput" accept="image/*" required>
                        <i class="fas fa-cloud-arrow-up" style="font-size: 32px; color: var(--cyber-cyan); margin-bottom: 8px;"></i>
                        <div style="font-size: 14px; font-weight: 700; color: #fff;">
                            ចុចទីនេះដើម្បីជ្រើសរើសរូបភាព
                        </div>
                        <div style="font-size: 12px; color: var(--text-dim); margin-top: 4px;">
                            គាំទ្រ: PNG, JPG, WEBP ឬ GIF
                        </div>
                        <div class="file-preview-wrap" id="filePreviewWrap">
                            <i class="fas fa-check"></i>
                            <span id="fileNameLabel"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="cyber-buy-action" style="width: 100%; padding: 14px; margin-top: 12px; font-size: 15px;">
                    <i class="fas fa-check-circle"></i> រក្សាទុក និងដាក់លក់ (Add Product)
                </button>
            </form>
        </div>
    </div>

    <!-- 3D DNA Canvas Script -->
    <script>
        // File selection name preview
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