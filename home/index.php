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
    <link rel="stylesheet" href="./page.css">
</head>

<body>
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
        <!-- Floating Cyber Island Nav -->
        <header class="cyber-nav-container">
            <nav class="cyber-island-nav">
                <!-- Brand Emblem -->
                <a href="index.php" class="brand-emblem">
                    <div class="brand-hologram-cube">
                        <img src="./logo/icon.png" alt="NET Brand Icon" onerror="this.src='./logo/logome.png'">
                    </div>
                    <div class="brand-titles">
                        <h1>
                            NET STORE
                            <span class="dna-badge">V.1.2</span>
                        </h1>
                        <span>បម្រើការសេវាកម្មឌីជីថល</span>
                    </div>
                </a>

                <!-- Navigation Tabs -->
                <div class="nav-hologram-links">
                    <a href="index.php" class="nav-tab-btn active"><i class="fas fa-microchip"></i> ទំព័រដើម</a>
                    <a href="about.php" class="nav-tab-btn"><i class="fas fa-atom"></i> អំពីយើង</a>
                    <a href="../customer-service/index.php" class="nav-tab-btn"><i class="fas fa-satellite-dish"></i> ជំនួយការ</a>
                    <a href="#payments" class="nav-tab-btn"><i class="fas fa-credit-card"></i> ការទូទាត់</a>
                </div>

                <!-- Action Hub -->
                <div class="nav-actions">
                    <a href="https://t.me/netstillloser" target="_blank" rel="noopener noreferrer" class="cyber-glow-btn" style="padding: 7px 14px; font-size: 13px;">
                        <i class="fab fa-telegram-plane" style="color: #00f0ff;"></i>
                        <span class="hide-sm">Telegram</span>
                    </a>
                    <a href="../admin-login/index.php" class="cyber-glow-btn" style="border-color: var(--cyber-purple); background: linear-gradient(135deg, rgba(157,78,221,0.4), rgba(255,42,133,0.3));">
                        <i class="fas fa-shield-halved"></i>
                        <span>Admin</span>
                    </a>
                    <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </nav>
        </header>

        <!-- Mobile Drawer -->
        <div id="mobileMenuDrawer" style="display: none; position: fixed; top: 80px; left: 16px; right: 16px; z-index: 999; background: rgba(8, 14, 28, 0.95); backdrop-filter: blur(30px); border: 1px solid var(--cyber-cyan); border-radius: 24px; padding: 22px; box-shadow: 0 0 35px rgba(0,240,255,0.3);">
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="index.php" class="nav-tab-btn active"><i class="fas fa-microchip"></i> ទំព័រដើម</a>
                <a href="about.php" class="nav-tab-btn"><i class="fas fa-atom"></i> អំពីយើង</a>
                <a href="../customer-service/index.php" class="nav-tab-btn"><i class="fas fa-satellite-dish"></i> ជំនួយការ</a>
                <a href="#payments" class="nav-tab-btn"><i class="fas fa-credit-card"></i> ការទូទាត់</a>
                <a href="../admin-login/index.php" class="nav-tab-btn" style="color: var(--cyber-cyan);"><i class="fas fa-shield-halved"></i>Admin</a>
            </div>
        </div>

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

        <!-- Neural Search & Category Filter Strip -->
        <section class="neural-filter-section">
            <div class="cyber-search-box">
                <i class="fas fa-magnifying-glass search-beam-icon"></i>
                <input type="text" id="liveSearchInput" placeholder="ស្វែងរកកម្មវិធី..." autocomplete="off">
            </div>
    
            <div class="category-tab-strip" id="categoryTabStrip">
                <button class="cyber-category-pill active" data-category="all"><i class="fas fa-globe"></i> ទាំងអស់</button>
                <button class="cyber-category-pill" data-category="streaming"><i class="fas fa-play-circle"></i> Streaming & Music</button>
                <button class="cyber-category-pill" data-category="ai"><i class="fas fa-brain"></i> AI Neural Tools</button>
                <button class="cyber-category-pill" data-category="design"><i class="fas fa-palette"></i> Design & Video</button>
                <button class="cyber-category-pill" data-category="social"><i class="fas fa-paper-plane"></i> Social VIP</button>
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
                    $12.85 <span>/ ខែ (Mo)</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="buy.php">
                        <input type="hidden" name="item" value="yt">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
                    $13.50 <span>/ ខែ (Mo)</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="buy.php">
                        <input type="hidden" name="item" value="mt">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
                    $3.75 <span>/ ខែ (Mo)</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="buy.php">
                        <input type="hidden" name="item" value="tl">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
                    $29.85 <span>/ ខែ (Mo)</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="buy.php">
                        <input type="hidden" name="item" value="cv">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
                    $6.75 <span>/ ខែ (Mo)</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="buy.php">
                        <input type="hidden" name="item" value="ct">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
                    $18.85 <span>/ ខែ (Mo)</span>
                </div>
                <div class="card-action-wrap">
                    <form method="post" action="buy.php">
                        <input type="hidden" name="item" value="gn">
                        <button type="submit" class="cyber-buy-action">
                            <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
                                $<?php echo $p_price; ?> <span>/ ខែ (Mo)</span>
                            </div>
                            <div class="card-action-wrap">
                                <form method="post" action="buy.php">
                                    <input type="hidden" name="item" value="<?php echo $p_id; ?>">
                                    <button type="submit" class="cyber-buy-action">
                                        <i class="fas fa-bolt"></i> ទិញឥឡូវនេះ (Buy Now)
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
        <footer class="cyber-footer">
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

        // Mobile Nav Drawer
        const mobileToggle = document.getElementById("mobileNavToggle");
        const mobileDrawer = document.getElementById("mobileMenuDrawer");
        if (mobileToggle && mobileDrawer) {
            mobileToggle.addEventListener("click", () => {
                const isOpen = mobileDrawer.style.display === "block";
                mobileDrawer.style.display = isOpen ? "none" : "block";
            });
        }

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
        }

        // =========================================================================
        // 3D DNA Helix Live Canvas Animation Engine
        // =========================================================================
        const canvas = document.getElementById("dna-canvas");
        const ctx = canvas.getContext("2d");

        let width, height;
        let mouseX = 0, mouseY = 0;
        let targetAngleX = 0, targetAngleY = 0;
        let angleX = 0, angleY = 0;
        let rotationTime = 0;

        function resizeCanvas() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        window.addEventListener("mousemove", (e) => {
            mouseX = (e.clientX - width / 2) / (width / 2);
            mouseY = (e.clientY - height / 2) / (height / 2);
            targetAngleY = mouseX * 0.4;
            targetAngleX = mouseY * 0.2;
        });

        // Ambient cyber dust particles
        const numParticles = 65;
        const particles = [];
        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * 2000 - 1000,
                y: Math.random() * 2000 - 1000,
                z: Math.random() * 1500 - 500,
                size: Math.random() * 2 + 0.8,
                color: Math.random() > 0.5 ? 'rgba(0, 240, 255, ' : 'rgba(157, 78, 221, ',
                alpha: Math.random() * 0.5 + 0.2,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3
            });
        }

        // DNA Helix Configuration
        const numBasePairs = 50;
        const helixRadius = 150;
        const helixLength = 1800;
        const fov = 650;

        function renderDNA() {
            ctx.clearRect(0, 0, width, height);

            rotationTime += 0.015;
            angleY += (targetAngleY - angleY) * 0.05;
            angleX += (targetAngleX - angleX) * 0.05;

            const centerX = width / 2;
            const centerY = height / 2;

            // 1. Render ambient floating cyber particles
            for (let i = 0; i < numParticles; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < -1000) p.x = 1000;
                if (p.x > 1000) p.x = -1000;
                if (p.y < -1000) p.y = 1000;
                if (p.y > 1000) p.y = -1000;

                const pScale = fov / (fov + p.z + 500);
                if (pScale > 0) {
                    const px = centerX + p.x * pScale;
                    const py = centerY + p.y * pScale;
                    ctx.beginPath();
                    ctx.arc(px, py, p.size * pScale, 0, Math.PI * 2);
                    ctx.fillStyle = p.color + p.alpha * pScale + ')';
                    ctx.fill();
                }
            }

            // 2. Generate DNA nodes
            const basePairs = [];
            for (let i = 0; i < numBasePairs; i++) {
                const progress = i / numBasePairs;
                const rawY = (progress - 0.5) * helixLength;
                const theta = progress * Math.PI * 8 + rotationTime;

                // Strand 1
                const x1 = Math.cos(theta) * helixRadius;
                const z1 = Math.sin(theta) * helixRadius;
                const y1 = rawY;

                // Strand 2 (offset by PI)
                const x2 = Math.cos(theta + Math.PI) * helixRadius;
                const z2 = Math.sin(theta + Math.PI) * helixRadius;
                const y2 = rawY;

                // Rotate around dynamic mouse angles
                // Rotate Y
                const cosY = Math.cos(angleY + 0.3);
                const sinY = Math.sin(angleY + 0.3);

                const rx1 = x1 * cosY - z1 * sinY;
                const rz1 = x1 * sinY + z1 * cosY;

                const rx2 = x2 * cosY - z2 * sinY;
                const rz2 = x2 * sinY + z2 * cosY;

                // Rotate X
                const cosX = Math.cos(angleX);
                const sinX = Math.sin(angleX);

                const ry1 = y1 * cosX - rz1 * sinX;
                const finalZ1 = y1 * sinX + rz1 * cosX;

                const ry2 = y2 * cosX - rz2 * sinX;
                const finalZ2 = y2 * sinX + rz2 * cosX;

                // Average Z for depth sorting rungs
                const avgZ = (finalZ1 + finalZ2) / 2;

                basePairs.push({
                    x1: rx1, y1: ry1, z1: finalZ1,
                    x2: rx2, y2: ry2, z2: finalZ2,
                    avgZ: avgZ,
                    index: i
                });
            }

            // Depth sorting (render furthest elements first)
            basePairs.sort((a, b) => b.avgZ - a.avgZ);

            // 3. Draw Base-Pair Rungs & Nodes with 3D glowing gradients
            for (let i = 0; i < basePairs.length; i++) {
                const bp = basePairs[i];

                const scale1 = fov / (fov + bp.z1 + 500);
                const scale2 = fov / (fov + bp.z2 + 500);

                if (scale1 <= 0 || scale2 <= 0) continue;

                const projX1 = centerX + bp.x1 * scale1;
                const projY1 = centerY + bp.y1 * scale1;

                const projX2 = centerX + bp.x2 * scale2;
                const projY2 = centerY + bp.y2 * scale2;

                // Normalized depth for lighting (0 to 1)
                const depthAlpha = Math.max(0.15, Math.min(0.9, (bp.avgZ + 300) / 600));

                // Draw Connecting Hydrogen Bond Rung
                const lineGrad = ctx.createLinearGradient(projX1, projY1, projX2, projY2);
                lineGrad.addColorStop(0, `rgba(0, 240, 255, ${depthAlpha * 0.7})`);
                lineGrad.addColorStop(0.5, `rgba(157, 78, 221, ${depthAlpha * 0.9})`);
                lineGrad.addColorStop(1, `rgba(255, 42, 133, ${depthAlpha * 0.7})`);

                ctx.beginPath();
                ctx.moveTo(projX1, projY1);
                ctx.lineTo(projX2, projY2);
                ctx.strokeStyle = lineGrad;
                ctx.lineWidth = Math.max(1, 2.8 * ((scale1 + scale2) / 2));
                ctx.stroke();

                // Draw Strand 1 Node (Cyan Glow)
                const r1 = Math.max(2, 6 * scale1);
                ctx.beginPath();
                ctx.arc(projX1, projY1, r1, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(0, 240, 255, ${depthAlpha})`;
                ctx.shadowColor = '#00f0ff';
                ctx.shadowBlur = 15 * scale1;
                ctx.fill();
                ctx.shadowBlur = 0; // reset

                // Draw Strand 2 Node (Pink/Violet Glow)
                const r2 = Math.max(2, 6 * scale2);
                ctx.beginPath();
                ctx.arc(projX2, projY2, r2, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 42, 133, ${depthAlpha})`;
                ctx.shadowColor = '#ff2a85';
                ctx.shadowBlur = 15 * scale2;
                ctx.fill();
                ctx.shadowBlur = 0; // reset
            }

            requestAnimationFrame(renderDNA);
        }

        renderDNA();
    </script>
</body>

</html>