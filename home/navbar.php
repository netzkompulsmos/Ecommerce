<?php
$nav_home_url = $nav_home_url ?? 'index.php';
$nav_about_url = $nav_about_url ?? 'index.php?page=about';
$nav_support_url = $nav_support_url ?? '../customer-service/index.php';
$nav_payments_url = $nav_payments_url ?? '#payments';
$nav_admin_url = $nav_admin_url ?? '../admin-login/index.php';
$nav_active = $nav_active ?? 'home';
$nav_link = static function ($page, $label, $icon, $url) use ($nav_active) {
    $active_class = $nav_active === $page ? ' active' : '';
    echo '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" class="nav-tab-btn' . $active_class . '"><i class="fas ' . $icon . '"></i> ' . $label . '</a>';
};
?>
<header class="cyber-nav-container">
    <nav class="cyber-island-nav">
        <a href="<?php echo htmlspecialchars($nav_home_url, ENT_QUOTES, 'UTF-8'); ?>" class="brand-emblem">
            <div class="brand-hologram-cube">
                <img src="<?php echo htmlspecialchars(dirname($nav_home_url) === '.' ? './logo/icon.png' : dirname($nav_home_url) . '/logo/icon.png', ENT_QUOTES, 'UTF-8'); ?>" alt="NET Brand Icon" onerror="this.src='<?php echo htmlspecialchars(dirname($nav_home_url) === '.' ? './logo/logome.png' : dirname($nav_home_url) . '/logo/logome.png', ENT_QUOTES, 'UTF-8'); ?>'">
            </div>
            <div class="brand-titles">
                <h1>NET STORE <span class="dna-badge">V.1.2</span></h1>
                <span>បម្រើការសេវាកម្មឌីជីថល</span>
            </div>
        </a>

        <div class="nav-hologram-links">
            <?php
            $nav_link('home', 'ទំព័រដើម', 'fa-microchip', $nav_home_url);
            $nav_link('about', 'អំពីយើង', 'fa-atom', $nav_about_url);
            $nav_link('support', 'ជំនួយការ', 'fa-satellite-dish', $nav_support_url);
            $nav_link('payments', 'ការទូទាត់', 'fa-credit-card', $nav_payments_url);
            ?>
        </div>

        <div class="nav-actions">
            <a href="https://t.me/netstillloser" target="_blank" rel="noopener noreferrer" class="cyber-glow-btn" style="padding: 7px 14px; font-size: 13px;">
                <i class="fab fa-telegram-plane" style="color: #00f0ff;"></i>
                <span class="nav-action-label">Telegram</span>
            </a>
            <a href="<?php echo htmlspecialchars($nav_admin_url, ENT_QUOTES, 'UTF-8'); ?>" class="cyber-glow-btn" style="border-color: var(--cyber-purple); background: linear-gradient(135deg, rgba(157,78,221,0.4), rgba(255,42,133,0.3));">
                <i class="fas fa-shield-halved"></i>
                <span class="nav-action-label">Admin</span>
            </a>
            <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
</header>

<div id="mobileMenuDrawer" style="display: none; position: fixed; top: 80px; left: 16px; right: 16px; z-index: 999; background: rgba(8, 14, 28, 0.95); backdrop-filter: blur(30px); border: 1px solid var(--cyber-cyan); border-radius: 24px; padding: 22px; box-shadow: 0 0 35px rgba(0,240,255,0.3);">
    <div style="display: flex; flex-direction: column; gap: 12px;">
        <?php
        $nav_link('home', 'ទំព័រដើម', 'fa-microchip', $nav_home_url);
        $nav_link('about', 'អំពីយើង', 'fa-atom', $nav_about_url);
        $nav_link('support', 'ជំនួយការ', 'fa-satellite-dish', $nav_support_url);
        $nav_link('payments', 'ការទូទាត់', 'fa-credit-card', $nav_payments_url);
        ?>
        <a href="<?php echo htmlspecialchars($nav_admin_url, ENT_QUOTES, 'UTF-8'); ?>" class="nav-tab-btn" style="color: var(--cyber-cyan);"><i class="fas fa-shield-halved"></i> Admin</a>
    </div>
</div>

<script>
    const mobileToggle = document.getElementById("mobileNavToggle");
    const mobileDrawer = document.getElementById("mobileMenuDrawer");
    if (mobileToggle && mobileDrawer) {
        mobileToggle.addEventListener("click", () => {
            const isOpen = mobileDrawer.style.display === "block";
            mobileDrawer.style.display = isOpen ? "none" : "block";
            mobileToggle.setAttribute("aria-expanded", String(!isOpen));
        });
    }
</script>
