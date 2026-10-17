<?php
include '../api/db.php';

$static_products = [
    'yt' => ['name' => 'YouTube Premium', 'price' => '12.85', 'img' => '../img/yt.png', 'desc' => 'គ្មានការរំខានដោយពាណិជ្ជកម្ម & ភ្ជាប់មកជាមួយ YouTube Music'],
    'mt' => ['name' => 'Meta Verified', 'price' => '13.50', 'img' => '../img/mt.png', 'desc' => 'Badge ធានាភាពត្រឹមត្រូវលើ Facebook & Instagram'],
    'tl' => ['name' => 'Telegram Premium', 'price' => '3.75', 'img' => '../img/tl.png', 'desc' => 'Upload ឯកសារបានរហូតដល់ 4GB & Stickers ពិសេសៗ'],
    'cv' => ['name' => 'Canva Pro', 'price' => '29.85', 'img' => '../img/cv.png', 'desc' => 'ប្រើប្រាស់ Templates & រូបភាព Pro ដោយឥតដែនកំណត់'],
    'ct' => ['name' => 'CapCut Pro', 'price' => '6.75', 'img' => '../img/ct.png', 'desc' => 'ដោះសោ Effects, Filters និង Transition ពិសេសៗទាំងអស់'],
    'gn' => ['name' => 'Gemini Advanced Pro', 'price' => '18.85', 'img' => '../img/gn.png', 'desc' => 'Google AI ជំនាន់ចុងក្រោយបង្អស់ ជាមួយ 2M Context Window']
];

$item = $_POST['item'] ?? $_GET['item'] ?? '';
$product = null;

if (!empty($item)) {
    if (isset($static_products[$item])) {
        $product = $static_products[$item];
    } elseif (isset($conn) && !$conn->connect_error) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $item);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $product = [
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'img' => !empty($row['image']) ? '../' . $row['image'] : './logo/logome.png',
                    'desc' => 'Account កម្មវិធីដែលមានគុណភាពខ្ពស់ និងធានាជូនពេញមួយខែ'
                ];
            }
        }
    }
}

if (!$product) {
    $product = [
        'name' => 'Digital Account Plan',
        'price' => 'Special Offer',
        'img' => './logo/logome.png',
        'desc' => 'សូមទាក់ទងមកកាន់ Admin តាម Telegram ដើម្បីពិនិត្យតម្លៃ និងទិញ'
    ];
}

$telegram_message = urlencode("សួស្តី Admin! ខ្ញុំចង់ទិញ Account: " . $product['name'] . " (តម្លៃ: $" . $product['price'] . ")");
$telegram_url = "https://t.me/netisnet?text=" . $telegram_message;
?>
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បង់ប្រាក់ • <?php echo htmlspecialchars($product['name']); ?> • DNA Cyber Edition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="./logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="./page.css">
    <style>
        .checkout-page-layer {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .checkout-cyber-card {
            width: 100%;
            max-width: 520px;
            background: rgba(10, 16, 32, 0.78);
            backdrop-filter: blur(28px) saturate(200%);
            -webkit-backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid var(--cyber-cyan);
            border-radius: 32px;
            padding: 36px 28px;
            text-align: center;
            box-shadow: 
                0 25px 60px -10px rgba(0, 0, 0, 0.9),
                0 0 35px rgba(0, 240, 255, 0.3),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
            position: relative;
        }

        .qr-cyber-frame {
            width: 210px;
            height: 210px;
            margin: 20px auto;
            border-radius: 24px;
            background: #ffffff;
            padding: 12px;
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.5);
            border: 2px solid var(--cyber-cyan);
            position: relative;
            overflow: hidden;
        }

        .qr-cyber-frame img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .qr-cyber-laser {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, #00f0ff, #00ffaa, transparent);
            box-shadow: 0 0 15px #00f0ff;
            animation: laserScan 2.4s infinite ease-in-out;
        }

        @keyframes laserScan {
            0% { top: 5%; }
            50% { top: 90%; }
            100% { top: 5%; }
        }

        .bank-selector-pills {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 16px 0;
            flex-wrap: wrap;
        }

        .bank-sel-btn {
            padding: 6px 14px;
            border-radius: 9999px;
            background: rgba(0, 240, 255, 0.08);
            border: 1px solid rgba(0, 240, 255, 0.25);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .bank-sel-btn.active {
            background: rgba(0, 240, 255, 0.3);
            border-color: var(--cyber-cyan);
            color: var(--cyber-cyan);
            box-shadow: 0 0 12px rgba(0, 240, 255, 0.4);
        }
    </style>
</head>

<body>
    <!-- Live 3D DNA Helix Canvas Background -->
    <canvas id="dna-canvas"></canvas>
    <div class="canvas-overlay-vignette"></div>
    <div class="cyber-grid-lines"></div>

    <div class="checkout-page-layer">
        <div class="checkout-cyber-card">
            <!-- Header bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <a href="index.php" class="cyber-glow-btn" style="padding: 6px 16px; font-size: 13px;">
                    <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                </a>
                <span class="dna-badge" style="font-size: 11px; padding: 4px 10px;">
                    <i class="fas fa-shield-halved"></i> 100% Secure Node
                </span>
            </div>

            <!-- Product Icon -->
            <div class="card-hologram-frame" style="width: 100px; height: 100px; margin: 0 auto 14px; border-radius: 20px;">
                <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="Product">
            </div>

            <h2 style="font-size: 24px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif;">
                <?php echo htmlspecialchars($product['name']); ?>
            </h2>

            <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;">
                <?php echo htmlspecialchars($product['desc']); ?>
            </p>

            <div class="cyber-price-pill" style="margin: 14px auto; font-size: 22px;">
                $<?php echo htmlspecialchars($product['price']); ?> <span>/ ខែ (Per Month)</span>
            </div>

            <!-- Bank Selector -->
            <div class="bank-selector-pills">
                <span class="bank-sel-btn active"><i class="fas fa-qrcode"></i> Bakong KHQR</span>
                <span class="bank-sel-btn"><i class="fas fa-building-columns"></i> ABA Bank</span>
                <span class="bank-sel-btn"><i class="fas fa-wallet"></i> Wing Bank</span>
            </div>

            <!-- QR Code with Cyber Laser Scan -->
            <div class="qr-cyber-frame">
                <div class="qr-cyber-laser"></div>
                <img src="../img/khqr.png" alt="KHQR Payment" onerror="this.src='./logo/icon.png'">
            </div>

            <div style="background: rgba(0, 240, 255, 0.08); border: 1px solid rgba(0, 240, 255, 0.25); border-radius: 18px; padding: 14px 18px; margin: 18px 0; font-size: 13px; color: rgba(255,255,255,0.9); text-align: left; line-height: 1.5;">
                <div style="font-weight: 800; color: var(--cyber-cyan); margin-bottom: 4px;">
                    <i class="fas fa-info-circle"></i> របៀបទូទាត់ប្រាក់៖
                </div>
                1. ស្កេន QR Code ឬថតរូបអេក្រង់ (Screenshot)<br>
                2. ចុចប៊ូតុងខាងក្រោមដើម្បីផ្ញើវិក្កយបត្រទៅកាន់ Telegram Admin ភ្លាមៗ
            </div>

            <a href="<?php echo $telegram_url; ?>" target="_blank" rel="noopener noreferrer" class="cyber-buy-action" style="text-decoration: none; padding: 14px; font-size: 15px;">
                <i class="fab fa-telegram-plane" style="font-size: 18px;"></i>
                ផ្ញើវិក្កយបត្រទៅ Telegram (@netisnet)
            </a>

            <div style="margin-top: 16px; font-size: 11.5px; color: var(--text-dim);">
                <i class="fas fa-clock"></i> Admin នឹងប្រគល់ Account ជូនលោកអ្នកក្នុងរយៈពេល 1-2 នាទី
            </div>
        </div>
    </div>

    <!-- 3D DNA Canvas Script -->
    <script>
        const canvas = document.getElementById("dna-canvas");
        const ctx = canvas.getContext("2d");
        let width, height;
        let rotationTime = 0;

        function resizeCanvas() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        const numBasePairs = 45;
        const helixRadius = 140;
        const helixLength = 1600;
        const fov = 600;

        function renderDNA() {
            ctx.clearRect(0, 0, width, height);
            rotationTime += 0.015;
            const centerX = width / 2;
            const centerY = height / 2;

            for (let i = 0; i < numBasePairs; i++) {
                const progress = i / numBasePairs;
                const rawY = (progress - 0.5) * helixLength;
                const theta = progress * Math.PI * 7 + rotationTime;

                const x1 = Math.cos(theta) * helixRadius;
                const z1 = Math.sin(theta) * helixRadius;
                const x2 = Math.cos(theta + Math.PI) * helixRadius;
                const z2 = Math.sin(theta + Math.PI) * helixRadius;

                const scale1 = fov / (fov + z1 + 500);
                const scale2 = fov / (fov + z2 + 500);

                if (scale1 > 0 && scale2 > 0) {
                    const px1 = centerX + x1 * scale1;
                    const py1 = centerY + rawY * scale1;
                    const px2 = centerX + x2 * scale2;
                    const py2 = centerY + rawY * scale2;

                    const depthAlpha = Math.max(0.15, Math.min(0.85, (z1 + 200) / 400));

                    ctx.beginPath();
                    ctx.moveTo(px1, py1);
                    ctx.lineTo(px2, py2);
                    ctx.strokeStyle = `rgba(0, 240, 255, ${depthAlpha * 0.7})`;
                    ctx.lineWidth = Math.max(1, 2.5 * scale1);
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.arc(px1, py1, Math.max(2, 5 * scale1), 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(0, 240, 255, ${depthAlpha})`;
                    ctx.fill();

                    ctx.beginPath();
                    ctx.arc(px2, py2, Math.max(2, 5 * scale2), 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 42, 133, ${depthAlpha})`;
                    ctx.fill();
                }
            }
            requestAnimationFrame(renderDNA);
        }
        renderDNA();
    </script>
</body>

</html>