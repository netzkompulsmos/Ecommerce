<?php
date_default_timezone_set('Asia/Phnom_Penh');
$hour = (int)date('H');
if ($hour < 12) {
    $greeting = "អរុណសួស្តីពេលព្រឹក";
    $greeting_en = "Good Morning";
    $greeting_icon = "☀️";
} elseif ($hour < 18) {
    $greeting = "ទិវាសួស្តីពេលរសៀល";
    $greeting_en = "Good Afternoon";
    $greeting_icon = "🌤️";
} else {
    $greeting = "រាត្រីសួស្តីពេលរាត្រី";
    $greeting_en = "Good Evening";
    $greeting_icon = "🌙";
}

$page = $_GET['page'] ?? '';
$is_checkout = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) || $page === 'checkout' || isset($_GET['item']);
$telegram_url = '';

if ($is_checkout) {
    $static_products = [
        'yt' => ['name' => 'YouTube Premium', 'price' => '12.85', 'img' => '../img/yt.png', 'desc' => 'គ្មានការរំខានដោយពាណិជ្ជកម្ម & ភ្ជាប់មកជាមួយ YouTube Music'],
        'mt' => ['name' => 'Meta Verified', 'price' => '13.50', 'img' => '../img/mt.png', 'desc' => 'Badge ធានាភាពត្រឹមត្រូវលើ Facebook & Instagram'],
        'tl' => ['name' => 'Telegram Premium', 'price' => '3.75', 'img' => '../img/tl.png', 'desc' => 'Upload ឯកសារបានរហូតដល់ 4GB & Stickers ពិសេសៗ'],
        'cv' => ['name' => 'Canva Pro', 'price' => '29.85', 'img' => '../img/cv.png', 'desc' => 'ប្រើប្រាស់ Templates & រូបភាព Pro ដោយឥតដែនកំណត់'],
        'ct' => ['name' => 'CapCut Pro', 'price' => '6.75', 'img' => '../img/ct.png', 'desc' => 'ដោះសោ Effects, Filters និង Transition ពិសេសៗទាំងអស់'],
        'gn' => ['name' => 'Gemini Advanced Pro', 'price' => '18.85', 'img' => '../img/gn.png', 'desc' => 'Google AI ជំនាន់ចុងក្រោយបង្អស់ ជាមួយ 2M Context Window']
    ];

    $item = $_POST['item'] ?? $_GET['item'] ?? '';
    if (!is_string($item) && !is_int($item)) {
        $item = '';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $checkout_url = $item === '' ? 'index.php' : 'index.php?item=' . rawurlencode((string) $item);
        header('Location: ' . $checkout_url, true, 303);
        exit;
    }

    $product = $static_products[$item] ?? null;
    if (!$product && $item !== '' && ctype_digit((string) $item)) {
        include_once '../api/db.php';
        if (isset($conn) && !$conn->connect_error) {
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
                $stmt->close();
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
}

if ($page === 'about' || ($is_checkout && $_SERVER['REQUEST_METHOD'] !== 'POST')) {
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_checkout ? 'បង់ប្រាក់ • ' . htmlspecialchars($product['name']) . ' • DNA Cyber Edition' : 'អំពី NET STORE • APP ACCOUNT SELLER'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="./logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="./page.css?v=<?php echo filemtime(__DIR__ . '/page.css'); ?>">
    <style>
        <?php if ($page === 'about' && !$is_checkout): ?>
        .about-cyber-layer { position: relative; z-index: 10; width: min(1120px, 100%); margin: 24px auto 70px; padding: 0 24px; }
        .about-hero, .about-section, .about-contact {
            background: rgba(10, 16, 32, 0.78); backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%); border: 1px solid rgba(0, 240, 255, 0.28);
            border-radius: 30px; box-shadow: 0 20px 55px rgba(0, 0, 0, 0.38), 0 0 28px rgba(0, 240, 255, 0.08);
        }
        .about-hero { position: relative; overflow: hidden; padding: clamp(30px, 6vw, 68px); text-align: center; }
        .about-hero::before { content: ""; position: absolute; width: 320px; height: 320px; border-radius: 50%; background: rgba(0, 240, 255, 0.09); filter: blur(70px); top: -180px; right: -90px; pointer-events: none; }
        .about-eyebrow { display: inline-flex; align-items: center; gap: 8px; color: var(--cyber-cyan); border: 1px solid rgba(0, 240, 255, 0.45); border-radius: 999px; padding: 8px 14px; font-size: 12px; font-weight: 700; letter-spacing: .04em; }
        .about-hero h2 { max-width: 800px; margin: 22px auto 16px; color: #fff; font-size: clamp(30px, 5vw, 52px); line-height: 1.25; font-weight: 800; letter-spacing: -.03em; }
        .about-hero h2 span { color: var(--cyber-cyan); }
        .about-lead { max-width: 780px; margin: 0 auto; color: var(--text-muted); font-size: 16px; line-height: 1.9; }
        .about-hero-actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; margin-top: 28px; }
        .about-hero-actions .cyber-glow-btn { padding: 12px 20px; }
        .about-facts { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 26px; }
        .about-fact { padding: 18px; border: 1px solid rgba(0, 240, 255, .18); border-radius: 18px; background: rgba(0, 240, 255, .045); }
        .about-fact strong { display: block; color: #fff; font: 700 18px 'Space Grotesk', sans-serif; margin-bottom: 5px; }
        .about-fact span { color: var(--text-muted); font-size: 12px; }
        .about-section { margin-top: 24px; padding: clamp(24px, 4vw, 42px); }
        .about-section-heading { max-width: 760px; margin: 0 auto 26px; text-align: center; }
        .about-section-heading span { display: block; color: var(--cyber-cyan); text-transform: uppercase; font: 700 11px 'Space Grotesk', sans-serif; letter-spacing: .16em; margin-bottom: 10px; }
        .about-section-heading h3 { color: #fff; font-size: clamp(23px, 3vw, 32px); margin-bottom: 10px; }
        .about-section-heading p { color: var(--text-muted); font-size: 14px; line-height: 1.8; }
        .about-story-grid, .about-values-grid, .about-services-grid, .about-steps-grid { display: grid; gap: 14px; }
        .about-story-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .about-info-card, .about-value-card, .about-service-card, .about-step-card {
            border: 1px solid rgba(0, 240, 255, .18); border-radius: 20px; background: rgba(0, 240, 255, .045); padding: 22px;
        }
        .about-info-card h4, .about-value-card h4, .about-service-card h4, .about-step-card h4 { color: #fff; font-size: 16px; margin: 12px 0 8px; }
        .about-info-card p, .about-value-card p, .about-service-card p, .about-step-card p { color: var(--text-muted); font-size: 13px; line-height: 1.8; }
        .about-icon { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 13px; color: var(--cyber-cyan); background: rgba(0, 240, 255, .1); border: 1px solid rgba(0, 240, 255, .2); }
        .about-values-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .about-services-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .about-service-card { text-align: center; }
        .about-service-card .about-icon { margin: 0 auto; }
        .about-steps-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); counter-reset: about-step; }
        .about-step-card { position: relative; }
        .about-step-card::before { counter-increment: about-step; content: "0" counter(about-step); color: var(--cyber-cyan); font: 700 13px 'Space Grotesk', sans-serif; }
        .about-contact { margin-top: 24px; padding: clamp(28px, 5vw, 48px); text-align: center; border-color: rgba(157, 78, 221, .55); background: linear-gradient(135deg, rgba(0, 240, 255, .08), rgba(157, 78, 221, .13)), rgba(10, 16, 32, .88); }
        .about-contact h3 { color: #fff; font-size: clamp(23px, 3vw, 32px); margin-bottom: 10px; }
        .about-contact p { max-width: 680px; margin: 0 auto; color: var(--text-muted); font-size: 14px; line-height: 1.8; }
        .about-contact .cyber-glow-btn { display: inline-flex; margin-top: 22px; padding: 12px 22px; }
        .about-page-footer { padding: 24px 10px 0; text-align: center; color: var(--text-dim); font-size: 12px; }
        @media (max-width: 760px) {
            .about-cyber-layer { padding: 0 14px; margin-top: 16px; }
            .about-story-grid, .about-values-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .about-services-grid, .about-steps-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 520px) {
            .about-cyber-layer { padding: 0 10px; }
            .about-hero, .about-section, .about-contact { border-radius: 22px; }
            .about-facts { gap: 8px; }
            .about-fact { padding: 13px 8px; }
            .about-fact strong { font-size: 15px; }
            .about-fact span { font-size: 10px; }
            .about-story-grid, .about-values-grid, .about-services-grid, .about-steps-grid { grid-template-columns: 1fr; }
        }
        <?php else: ?>
        .checkout-page-layer { position: relative; z-index: 10; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px 16px; }
        .checkout-cyber-card {
            width: 100%; max-width: 520px; background: rgba(10, 16, 32, 0.78);
            backdrop-filter: blur(28px) saturate(200%); -webkit-backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid var(--cyber-cyan); border-radius: 32px; padding: 36px 28px; text-align: center;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.9), 0 0 35px rgba(0, 240, 255, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.4); position: relative;
        }
        .qr-cyber-frame { width: 210px; height: 210px; margin: 20px auto; border-radius: 24px; background: #fff; padding: 12px; box-shadow: 0 0 25px rgba(0, 240, 255, 0.5); border: 2px solid var(--cyber-cyan); position: relative; overflow: hidden; }
        .qr-cyber-frame img { width: 100%; height: 100%; object-fit: contain; }
        .qr-cyber-laser { display: none; }
        .bank-selector-pills { display: flex; align-items: center; justify-content: center; gap: 8px; margin: 16px 0; flex-wrap: wrap; }
        .bank-sel-btn { padding: 6px 14px; border-radius: 9999px; background: rgba(0, 240, 255, 0.08); border: 1px solid rgba(0, 240, 255, 0.25); color: #fff; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; }
        .bank-sel-btn.active { background: rgba(0, 240, 255, 0.3); border-color: var(--cyber-cyan); color: var(--cyber-cyan); box-shadow: 0 0 12px rgba(0, 240, 255, 0.4); }
        <?php endif; ?>
    </style>
</head>
<body>
    <canvas id="dna-canvas"></canvas>
    <div class="canvas-overlay-vignette"></div>
    <div class="cyber-grid-lines"></div>

    <?php if ($page === 'about' && !$is_checkout): ?>
    <?php
    $nav_active = 'about';
    $nav_home_url = 'index.php';
    $nav_about_url = 'index.php?page=about';
    $nav_support_url = '../customer-service/index.php';
    $nav_payments_url = 'index.php#payments';
    $nav_admin_url = '../admin-login/index.php';
    require __DIR__ . '/navbar.php';
    ?>
    <div class="about-cyber-layer">
        <main>
            <section class="about-section">
                <div class="about-section-heading">
                    <span>Who We Are</span>
                    <h3>អំពី NET STORE</h3>
                    <p>យើងជាក្រុមការងារដែលចង់ធ្វើឱ្យការស្វែងរក និងការទិញសេវាកម្មឌីជីថលមានភាពសាមញ្ញ និងងាយយល់។</p>
                </div>
                <div class="about-story-grid">
                    <article class="about-info-card">
                        <div class="about-icon"><i class="fas fa-users"></i></div>
                        <h4>យើងជានរណា?</h4>
                        <p>NET STORE បម្រើអតិថិជនដែលត្រូវការសេវាកម្ម និងគណនីកម្មវិធីឌីជីថល ដូចជា YouTube, Telegram, Canva, CapCut និងសេវាកម្មផ្សេងៗ។ យើងផ្តល់ព័ត៌មានផលិតផល និងជួយណែនាំតាមតម្រូវការរបស់អ្នក។</p>
                    </article>
                    <article class="about-info-card">
                        <div class="about-icon"><i class="fas fa-bullseye"></i></div>
                        <h4>បេសកកម្មរបស់យើង</h4>
                        <p>បេសកកម្មរបស់យើងគឺផ្តល់ជូននូវបទពិសោធន៍ទិញសេវាកម្មឌីជីថលដែលងាយស្រួល មានព័ត៌មានច្បាស់ និងមានជំនួយនៅពេលត្រូវការ ដើម្បីឱ្យអតិថិជនអាចជ្រើសរើសដោយមានទំនុកចិត្ត។</p>
                    </article>
                    <article class="about-info-card">
                        <div class="about-icon"><i class="fas fa-eye"></i></div>
                        <h4>ចក្ខុវិស័យរបស់យើង</h4>
                        <p>យើងចង់ក្លាយជាហាងឌីជីថលដែលអតិថិជនក្នុងប្រទេសកម្ពុជាងាយស្រួលស្វែងរក សួរព័ត៌មាន និងទទួលបានការណែនាំសម្រាប់ការប្រើប្រាស់ផលិតផលឌីជីថល។</p>
                    </article>
                    <article class="about-info-card">
                        <div class="about-icon"><i class="fas fa-heart"></i></div>
                        <h4>អតិថិជនជាចម្បង</h4>
                        <p>យើងស្តាប់សំណួរ និងមតិយោបល់របស់អតិថិជន ដោយព្យាយាមឆ្លើយតបឱ្យបានច្បាស់ និងផ្តល់ព័ត៌មានដែលជួយឱ្យអ្នកដឹងពីជំហានបន្ទាប់ មុននិងក្រោយការទិញ។</p>
                    </article>
                </div>
            </section>

            <section class="about-section">
                <div class="about-section-heading">
                    <span>Our Values</span>
                    <h3>គោលការណ៍ដែលយើងប្រកាន់ខ្ជាប់</h3>
                    <p>គោលការណ៍ទាំងនេះណែនាំការងាររបស់យើង និងរបៀបដែលយើងទំនាក់ទំនងជាមួយអតិថិជន។</p>
                </div>
                <div class="about-values-grid">
                    <article class="about-value-card"><div class="about-icon"><i class="fas fa-circle-check"></i></div><h4>ភាពច្បាស់លាស់</h4><p>ព័ត៌មានផលិតផល តម្លៃ និងជំហានទូទាត់ត្រូវបានបង្ហាញឱ្យងាយយល់ ដើម្បីឱ្យអ្នកពិនិត្យមើលមុនពេលបញ្ជាទិញ។</p></article>
                    <article class="about-value-card"><div class="about-icon"><i class="fas fa-shield-halved"></i></div><h4>ការយកចិត្តទុកដាក់</h4><p>យើងគោរពព័ត៌មានដែលអតិថិជនផ្តល់ និងណែនាំឱ្យប្រើប្រាស់សេវាកម្មដោយប្រុងប្រយ័ត្ន និងមានសុវត្ថិភាព។</p></article>
                    <article class="about-value-card"><div class="about-icon"><i class="fas fa-handshake"></i></div><h4>ការទទួលខុសត្រូវ</h4><p>យើងខិតខំឆ្លើយសំណួរ និងផ្តល់ជំនួយពាក់ព័ន្ធនឹងការបញ្ជាទិញតាមព័ត៌មាន និងលក្ខខណ្ឌដែលបានបញ្ជាក់។</p></article>
                </div>
            </section>

            <section class="about-contact">
                <span class="about-eyebrow"><i class="fas fa-headset"></i> WE'RE HERE TO HELP</span>
                <h3 style="margin-top:16px;">មានសំណួរអំពីផលិតផល ឬការបញ្ជាទិញ?</h3>
                <p>ទាក់ទងក្រុមការងាររបស់យើងតាម Telegram សម្រាប់សួរព័ត៌មានអំពីផលិតផល ការទូទាត់ ឬជំនួយក្រោយការបញ្ជាទិញ។ សូមពិនិត្យព័ត៌មាន និងលក្ខខណ្ឌផលិតផលជាមុន ដើម្បីទទួលបានជំនួយត្រឹមត្រូវ។</p>
                <a href="https://t.me/netstillloser" target="_blank" rel="noopener noreferrer" class="cyber-glow-btn"><i class="fab fa-telegram-plane"></i> ទាក់ទង NET STORE តាម Telegram</a>
            </section>
        </main>
        <footer class="about-page-footer">&copy; <?php echo date('Y'); ?> NET STORE • សូមអរគុណដែលបានជ្រើសរើសយើង</footer>
    </div>
    <?php else: ?>
    <div class="checkout-page-layer">
        <div class="checkout-cyber-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <a href="index.php" class="cyber-glow-btn" style="padding: 6px 16px; font-size: 13px;"><i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ</a>
                <span class="dna-badge" style="font-size: 11px; padding: 4px 10px;"><i class="fas fa-shield-halved"></i> 100% Secure Node</span>
            </div>
            <div class="card-hologram-frame" style="width: 100px; height: 100px; margin: 0 auto 14px; border-radius: 20px;"><img src="<?php echo htmlspecialchars($product['img']); ?>" alt="Product"></div>
            <h2 style="font-size: 24px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif;"><?php echo htmlspecialchars($product['name']); ?></h2>
            <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;"><?php echo htmlspecialchars($product['desc']); ?></p>
            <div class="cyber-price-pill" style="margin: 14px auto; font-size: 22px;">$<?php echo htmlspecialchars($product['price']); ?> <span>/ ខែ (Per Month)</span></div>
            <div class="bank-selector-pills">
                <span class="bank-sel-btn active"><i class="fas fa-qrcode"></i> Bakong KHQR</span>
                <span class="bank-sel-btn"><i class="fas fa-building-columns"></i> ABA Bank</span>
                <span class="bank-sel-btn"><i class="fas fa-wallet"></i> Wing Bank</span>
            </div>
            <div class="qr-cyber-frame"><div class="qr-cyber-laser"></div><img src="../img/khqr.png" alt="KHQR Payment" onerror="this.src='./logo/icon.png'"></div>
            <div style="background: rgba(0, 240, 255, 0.08); border: 1px solid rgba(0, 240, 255, 0.25); border-radius: 18px; padding: 14px 18px; margin: 18px 0; font-size: 13px; color: rgba(255,255,255,0.9); text-align: left; line-height: 1.5;">
                <div style="font-weight: 800; color: var(--cyber-cyan); margin-bottom: 4px;"><i class="fas fa-info-circle"></i> របៀបទូទាត់ប្រាក់៖</div>
                1. ស្កេន QR Code ឬថតរូបអេក្រង់ (Screenshot)<br>
                2. ចុចប៊ូតុងខាងក្រោមដើម្បីផ្ញើវិក្កយបត្រទៅកាន់ Telegram Admin ភ្លាមៗ
            </div>
            <a href="<?php echo htmlspecialchars($telegram_url); ?>" target="_blank" rel="noopener noreferrer" class="cyber-buy-action" style="text-decoration: none; padding: 14px; font-size: 15px;"><i class="fab fa-telegram-plane" style="font-size: 18px;"></i> ផ្ញើវិក្កយបត្រទៅ Telegram (@netisnet)</a>
            <div style="margin-top: 16px; font-size: 11.5px; color: var(--text-dim);"><i class="fas fa-clock"></i> Admin នឹងប្រគល់ Account ជូនលោកអ្នកក្នុងរយៈពេល 1-2 នាទី</div>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
<?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>www.netstoreonline.com</title>
    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="./logo/logome.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="./logo/logome.png">
    <!-- Cyber DNA Stylesheet -->
    <link rel="stylesheet" href="./page.css?v=<?php echo filemtime(__DIR__ . '/page.css'); ?>">
</head>

<body class="<?php echo $is_checkout ? 'buy-modal-open' : ''; ?>">
    <!-- Live 3D DNA Helix Canvas Background -->
    <canvas id="dna-canvas"></canvas>
    <div class="canvas-overlay-vignette"></div>
    <div class="cyber-grid-lines"></div>

    <!-- Preloader -->
    <div id="cyber-preloader">
        <div class="dna-loader-core"></div>
        <div style="font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700; letter-spacing: 0.15em; color: var(--cyber-cyan); text-transform: uppercase;">
            Synthesizing DNA Helix Engine...
        </div>
    </div>

    <!-- Main Content Layer -->
    <div class="app-content-layer">
        <?php
        $nav_active = 'home';
        $nav_home_url = 'index.php';
        $nav_about_url = 'index.php?page=about';
        $nav_support_url = '../customer-service/index.php';
        $nav_payments_url = '#payments';
        $nav_admin_url = '../admin-login/index.php';
        require __DIR__ . '/navbar.php';
        ?>

        <!-- Hero Cyber Deck -->
        <main class="hero-deck">
            <!-- Cyber Status HUD Bar -->
            <div class="cyber-hud-bar">
                <div class="hud-item">
                    <span style="font-size: 16px;"><?php echo $greeting_icon; ?></span>
                    <span style="color: #fff; font-weight: 700;"><?php echo $greeting; ?>!</span>
                    <span style="color: var(--text-dim); font-size: 12px;">(<?php echo $greeting_en; ?>)</span>
                </div>
                <div class="hud-item">
                    <i class="far fa-clock" style="color: var(--cyber-cyan);"></i>
                    <span id="liveClockDisplay" style="font-family: 'Space Grotesk', monospace; color: #fff;">--:--:--</span>
                    <span style="color: var(--text-dim); font-size: 11px;">Phnom Penh</span>
                </div>
                <div class="hud-item">
                    <div class="dna-signal-pulse"></div>
                    <span style="color: var(--cyber-emerald); font-weight: 700;">60 FPS</span>
                </div>
            </div>

            <!-- Hero Centerpiece Card -->
            <div class="hero-centerpiece">
                <div class="hero-text-side">
                    <div class="hero-tag-pill">
                        <i class="fas fa-bolt"></i> Bio-Digital Account Matrix
                    </div>
                    <h2>គុណភាពខ្ពស់ 100%</h2>
                    <p>
                        ប្រព័ន្ធទិញ-លក់គណនីឌីជីថលលំដាប់ខ្ពស់ប្រកបដោយសុវត្ថិភាព ធានាជូនពេញមួយខែ និងគាំទ្រការទូទាត់គ្រប់ធនាគារក្នុងប្រទេសកម្ពុជា។
                    </p>
                    <div class="hero-stats-row">
                        <div class="stat-chip">
                            <div class="stat-num">99.9<span>%</span></div>
                            <div class="stat-label">Uptime</div>
                        </div>
                        <div class="stat-chip">
                            <div class="stat-num">100<span>%</span></div>
                            <div class="stat-label">Full Warranty</div>
                        </div>
                        <div class="stat-chip">
                            <div class="stat-num">10K<span>+</span></div>
                            <div class="stat-label">Customers</div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual-side">
                    <div class="hero-hologram-ring"></div>
                    <div class="hero-hologram-ring-2"></div>
                    <img src="./logo/logome.png" alt="NET Floating Emblem" class="hero-floating-logo">
                </div>
            </div>
        </main>

        <!-- Products Showcase Matrix -->
        <section class="catalog-header">
            <div class="hero-tag-pill" style="margin-bottom: 8px;">
                <i class="fas fa-layer-group"></i> Active Neural Catalog
            </div>
        </section>

        <div class="cyber-cards-matrix" id="productsCatalogMatrix">
            <!-- Static Top Products -->
            <div class="cyber-card" data-category="streaming" data-name="youtube premium">
                <span class="card-top-tag hot"><i class="fas fa-fire"></i> Best Seller</span>
                <div class="card-hologram-frame">
                    <img src="../img/yt.png" alt="YouTube Premium" onerror="this.src='./logo/logome.png'">
                </div>
                <h3 class="card-app-title">YouTube Premium</h3>
                <div class="card-feature-specs">
                    <span><i class="fas fa-check" style="color:var(--cyber-emerald);"></i> No Ads</span>
                    <span>•</span>
                    <span><i class="fas fa-music" style="color:var(--cyber-cyan);"></i> YT Music</span>
                </div>
                <div class="cyber-price-pill">
                    $12.85 <span>/ ខែ</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="index.php">
                        <input type="hidden" name="item" value="yt">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                        </button>
                    </form>
                </div>
            </div>

            <div class="cyber-card" data-category="social" data-name="meta verified facebook instagram">
                <span class="card-top-tag"><i class="fas fa-check-double"></i> Verified</span>
                <div class="card-hologram-frame">
                    <img src="../img/mt.png" alt="Meta Verified" onerror="this.src='./logo/logome.png'">
                </div>
                <h3 class="card-app-title">Meta Verified</h3>
                <div class="card-feature-specs">
                    <span><i class="fab fa-facebook" style="color:var(--cyber-cyan);"></i> FB & IG Blue Badge</span>
                </div>
                <div class="cyber-price-pill">
                    $13.50 <span>/ ខែ</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="index.php">
                        <input type="hidden" name="item" value="mt">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                        </button>
                    </form>
                </div>
            </div>

            <div class="cyber-card" data-category="social" data-name="telegram premium">
                <span class="card-top-tag hot"><i class="fas fa-bolt"></i> Popular</span>
                <div class="card-hologram-frame">
                    <img src="../img/tl.png" alt="Telegram Premium" onerror="this.src='./logo/logome.png'">
                </div>
                <h3 class="card-app-title">Telegram Premium</h3>
                <div class="card-feature-specs">
                    <span><i class="fas fa-star" style="color:var(--cyber-pink);"></i> Star Badge + 4GB File</span>
                </div>
                <div class="cyber-price-pill">
                    $3.75 <span>/ ខែ</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="index.php">
                        <input type="hidden" name="item" value="tl">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                        </button>
                    </form>
                </div>
            </div>

            <div class="cyber-card" data-category="design" data-name="canva pro">
                <span class="card-top-tag"><i class="fas fa-crown"></i> PRO</span>
                <div class="card-hologram-frame">
                    <img src="../img/cv.png" alt="Canva Pro" onerror="this.src='./logo/logome.png'">
                </div>
                <h3 class="card-app-title">Canva Pro</h3>
                <div class="card-feature-specs">
                    <span><i class="fas fa-magic" style="color:var(--cyber-cyan);"></i> Unlimited Templates</span>
                </div>
                <div class="cyber-price-pill">
                    $29.85 <span>/ ខែ</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="index.php">
                        <input type="hidden" name="item" value="cv">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                        </button>
                    </form>
                </div>
            </div>

            <div class="cyber-card" data-category="design" data-name="capcut pro">
                <span class="card-top-tag"><i class="fas fa-video"></i> VIP</span>
                <div class="card-hologram-frame">
                    <img src="../img/ct.png" alt="CapCut Pro" onerror="this.src='./logo/logome.png'">
                </div>
                <h3 class="card-app-title">CapCut Pro</h3>
                <div class="card-feature-specs">
                    <span><i class="fas fa-film" style="color:var(--cyber-amber);"></i> All Pro Effects</span>
                </div>
                <div class="cyber-price-pill">
                    $6.75 <span>/ ខែ</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="index.php">
                        <input type="hidden" name="item" value="ct">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                        </button>
                    </form>
                </div>
            </div>

            <div class="cyber-card" data-category="ai" data-name="gemini advanced pro google ai">
                <span class="card-top-tag hot"><i class="fas fa-brain"></i> AI PRO</span>
                <div class="card-hologram-frame">
                    <img src="../img/gn.png" alt="Gemini Pro" onerror="this.src='./logo/logome.png'">
                </div>
                <h3 class="card-app-title">Gemini Advanced Pro</h3>
                <div class="card-feature-specs">
                    <span><i class="fas fa-sparkles" style="color:var(--cyber-cyan);"></i> 2M Context Window</span>
                </div>
                <div class="cyber-price-pill">
                    $18.85 <span>/ ខែ</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="index.php">
                        <input type="hidden" name="item" value="gn">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                        </button>
                    </form>
                </div>
            </div>

            <!-- Database Dynamic Products from MySQL -->
            <?php
            include '../api/db.php';
            if (isset($conn) && !$conn->connect_error) {
                $db_result = $conn->query("SELECT * FROM products ORDER BY id DESC");
                if ($db_result && $db_result->num_rows > 0) {
                    while ($p = $db_result->fetch_assoc()) {
                        $p_name = htmlspecialchars($p['name']);
                        $p_price = htmlspecialchars($p['price']);
                        $p_img = !empty($p['image']) ? '../' . htmlspecialchars($p['image']) : './logo/logome.png';
                        $p_id = htmlspecialchars($p['id']);

                        $cat = "other";
                        $name_lower = strtolower($p['name']);
                        if (strpos($name_lower, 'gpt') !== false || strpos($name_lower, 'ai') !== false || strpos($name_lower, 'gemini') !== false) {
                            $cat = "ai";
                        } elseif (strpos($name_lower, 'capcut') !== false || strpos($name_lower, 'pic') !== false || strpos($name_lower, 'art') !== false || strpos($name_lower, 'motion') !== false) {
                            $cat = "design";
                        } elseif (strpos($name_lower, 'sportify') !== false || strpos($name_lower, 'spotify') !== false || strpos($name_lower, 'youtube') !== false || strpos($name_lower, 'netflix') !== false) {
                            $cat = "streaming";
                        } elseif (strpos($name_lower, 'tiktok') !== false || strpos($name_lower, 'telegram') !== false || strpos($name_lower, 'meta') !== false) {
                            $cat = "social";
                        }
                        ?>
                        <div class="cyber-card" data-category="<?php echo $cat; ?>" data-name="<?php echo strtolower($p_name); ?>">
                            <span class="card-top-tag"><i class="fas fa-check"></i> Stock</span>
                            <div class="card-hologram-frame">
                                <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" onerror="this.src='./logo/logome.png'">
                            </div>
                            <h3 class="card-app-title"><?php echo $p_name; ?></h3>
                            <div class="card-feature-specs">
                                <span><i class="fas fa-shield-alt" style="color:var(--cyber-emerald);"></i> ធានាគុណភាព 100%</span>
                            </div>
                            <div class="cyber-price-pill">
                                $<?php echo $p_price; ?> <span>/ ខែ</span>
                            </div>
                            <div class="card-action-wrap">
                                <form method="post" action="index.php">
                                    <input type="hidden" name="item" value="<?php echo $p_id; ?>">
                                    <button type="submit" class="cyber-buy-action">
                                        <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
            
        <!-- Capabilities Grid -->
            <div class="cyber-trust-grid">
                <div class="cyber-trust-card">
                    <div class="trust-glow-icon"><i class="fas fa-bolt"></i></div>
                    <div class="trust-texts">
                        <h4>ដឹកជញ្ជូនរហ័ស</h4>
                        <p>Instant Auto-Delivery</p>
                    </div>
                </div>
                <div class="cyber-trust-card">
                    <div class="trust-glow-icon" style="color: var(--cyber-emerald); border-color: var(--cyber-emerald);"><i class="fas fa-shield-halved"></i></div>
                    <div class="trust-texts">
                        <h4>ធានាសុវត្ថិភាព 100%</h4>
                        <p>100% Verified & Safe</p>
                    </div>
                </div>
                <div class="cyber-trust-card">
                    <div class="trust-glow-icon" style="color: var(--cyber-pink); border-color: var(--cyber-pink);"><i class="fas fa-qrcode"></i></div>
                    <div class="trust-texts">
                        <h4>ទូទាត់រហ័ស KHQR</h4>
                        <p>Bakong & All Banks</p>
                    </div>
                </div>
                <div class="cyber-trust-card">
                    <div class="trust-glow-icon" style="color: var(--cyber-amber); border-color: var(--cyber-amber);"><i class="fas fa-headset"></i></div>
                    <div class="trust-texts">
                        <h4>ជំនួយ 24/7</h4>
                        <p>Telegram Support Desk</p>
                    </div>
                </div>
            </div>

        <!-- Cyber Floating Footer -->
        <footer class="cyber-footer" id="payments">
            <h3>
                <i class="fas fa-credit-card" style="color: var(--cyber-cyan);"></i>
                ដៃគូសហការ និងវិធីទូទាត់ប្រាក់
            </h3>
            <p style="font-size: 13.5px; color: var(--text-muted); max-width: 600px;">
                ទទួលការទូទាត់យ៉ាងរហ័សតាមរយៈប្រព័ន្ធ KHQR និងគ្រប់ធនាគារក្នុងប្រទេសកម្ពុជា
            </p>
            <div class="bank-capsules-row">
                <div class="bank-cyber-chip">
                    <img src="../img/khqr.png" alt="KHQR" onerror="this.style.display='none'">
                    <span>Bakong KHQR</span>
                </div>
                <div class="bank-cyber-chip">
                    <img src="../img/aba.png" alt="ABA Bank" onerror="this.style.display='none'">
                    <span>ABA Bank</span>
                </div>
                <div class="bank-cyber-chip">
                    <img src="../img/wink.png" alt="Wing Bank" onerror="this.style.display='none'">
                    <span>Wing Bank</span>
                </div>
                <div class="bank-cyber-chip">
                    <img src="../img/aceleda.png" alt="ACLEDA Bank" onerror="this.style.display='none'">
                    <span>ACLEDA</span>
                </div>
                <div class="bank-cyber-chip">
                    <img src="../img/canadia.png" alt="Canadia Bank" onerror="this.style.display='none'">
                    <span>Canadia</span>
                </div>
            </div>
            <div style="font-size: 12px; color: var(--text-dim); border-top: 1px solid rgba(0, 240, 255, 0.15); padding-top: 18px; width: 100%; font-family: 'Space Grotesk', sans-serif;">
                &copy; <?php echo date('Y'); ?> NET APP SELLER • All rights reserved.
            </div>
        </footer>
    </div>

    <?php if ($is_checkout): ?>
    <div class="buy-checkout-modal" id="buyCheckoutModal" role="presentation">
        <section class="checkout-cyber-card" role="dialog" aria-modal="true" aria-labelledby="buyCheckoutTitle" tabindex="-1">
            <div class="checkout-modal-heading">
                <span class="dna-badge"><i class="fas fa-shield-halved"></i> 100% Secure Node</span>
                <button type="button" class="checkout-modal-close" aria-label="បិទផ្ទាំងទូទាត់" data-close-checkout>
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="card-hologram-frame checkout-product-image"><img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
            <h2 id="buyCheckoutTitle"><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="checkout-product-description"><?php echo htmlspecialchars($product['desc']); ?></p>
            <div class="cyber-price-pill checkout-product-price">$<?php echo htmlspecialchars($product['price']); ?> <span>/ ខែ (Per Month)</span></div>
            <div class="bank-selector-pills">
                <span class="bank-sel-btn active"><i class="fas fa-qrcode"></i> Bakong KHQR</span>
                <span class="bank-sel-btn"><i class="fas fa-building-columns"></i> ABA Bank</span>
                <span class="bank-sel-btn"><i class="fas fa-wallet"></i> Wing Bank</span>
            </div>
            <div class="qr-cyber-frame"><img src="../img/khqr.png" alt="KHQR Payment" onerror="this.src='./logo/icon.png'"></div>
            <div class="checkout-payment-instructions">
                <div><i class="fas fa-info-circle"></i> របៀបទូទាត់ប្រាក់៖</div>
                1. ស្កេន QR Code ឬថតរូបអេក្រង់ (Screenshot)<br>
                2. ចុចប៊ូតុងខាងក្រោមដើម្បីផ្ញើវិក្កយបត្រទៅកាន់ Telegram Admin ភ្លាមៗ
            </div>
            <a href="<?php echo htmlspecialchars($telegram_url); ?>" target="_blank" rel="noopener noreferrer" class="cyber-buy-action checkout-telegram-action"><i class="fab fa-telegram-plane"></i> ផ្ញើវិក្កយបត្រទៅ Telegram (@netisnet)</a>
            <div class="checkout-delivery-note"><i class="fas fa-clock"></i> Admin នឹងប្រគល់ Account ជូនលោកអ្នកក្នុងរយៈពេល 1-2 នាទី</div>
        </section>
    </div>
    <script>
        (() => {
            const modal = document.getElementById('buyCheckoutModal');
            const dialog = modal.querySelector('[role="dialog"]');
            const closeModal = () => {
                modal.hidden = true;
                document.body.classList.remove('buy-modal-open');
            };

            modal.querySelector('[data-close-checkout]').addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) closeModal();
            });
            window.history.replaceState({}, document.title, 'index.php');
            dialog.focus();
        })();
    </script>
    <?php endif; ?>

    <!-- Scroll Up Floating Button -->
    <button id="cyberScrollTop" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Interactive DNA Canvas & Client Engine -->
    <script>
        // Dismiss preloader
        window.addEventListener("load", () => {
            const preloader = document.getElementById("cyber-preloader");
            if (preloader) {
                preloader.style.opacity = "0";
                preloader.style.visibility = "hidden";
            }
        });

        // Live Clock
        function updateLiveClock() {
            const clockEl = document.getElementById("liveClockDisplay");
            if (!clockEl) return;
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Live Product Search Filter
        const searchInput = document.getElementById("liveSearchInput");
        const cards = document.querySelectorAll(".cyber-card");

        function filterProducts() {
            const query = (searchInput ? searchInput.value : "").trim().toLowerCase();
            const activeTab = document.querySelector(".cyber-category-pill.active");
            const activeCat = activeTab ? activeTab.getAttribute("data-category") : "all";

            cards.forEach((card) => {
                const name = card.getAttribute("data-name") || "";
                const cat = card.getAttribute("data-category") || "all";
                const matchSearch = query === "" || name.includes(query);
                const matchCategory = activeCat === "all" || cat === activeCat;

                if (matchSearch && matchCategory) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener("input", filterProducts);
        }

        // Category Pills
        const categoryPills = document.querySelectorAll(".cyber-category-pill");
        categoryPills.forEach((pill) => {
            pill.addEventListener("click", () => {
                categoryPills.forEach(p => p.classList.remove("active"));
                pill.classList.add("active");
                filterProducts();
            });
        });

        // Scroll to Top
        const scrollBtn = document.getElementById("cyberScrollTop");
        window.addEventListener("scroll", () => {
            if (!scrollBtn) return;
            if (window.scrollY > 300) {
                scrollBtn.style.display = "flex";
            } else {
                scrollBtn.style.display = "none";
            }
        });
        if (scrollBtn) {
            scrollBtn.addEventListener("click", () => {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
    </script>
</body>

</html>