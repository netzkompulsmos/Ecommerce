<?php
$nav_home_url = $nav_home_url ?? 'index.php';
$nav_about_url = $nav_about_url ?? 'index.php?page=about';
$nav_support_url = $nav_support_url ?? '../customer-service/index.php';
$nav_payments_url = $nav_payments_url ?? '#payments';
$nav_brand_logo = $nav_brand_logo ?? '';
$nav_store_name = $nav_store_name ?? '4K STORE';
$nav_contact_url = $nav_contact_url ?? 'https://t.me/netstillloser';
$nav_admin_url = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/') . '/admin/index.php';
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
                <img src="<?php echo htmlspecialchars($nav_brand_logo !== '' ? $nav_brand_logo : (dirname($nav_home_url) === '.' ? './logo/icon.png' : dirname($nav_home_url) . '/logo/icon.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($nav_store_name, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.src='<?php echo htmlspecialchars(dirname($nav_home_url) === '.' ? './logo/logome.png' : dirname($nav_home_url) . '/logo/logome.png', ENT_QUOTES, 'UTF-8'); ?>'">
            </div>
            <div class="brand-titles">
                <h1><?php echo htmlspecialchars($nav_store_name, ENT_QUOTES, 'UTF-8'); ?> <span class="dna-badge">V.1.2</span></h1>
                <span>ហាងសម្លៀកបំពាក់ និងម៉ូដ</span>
            </div>
        </a>

        <div class="nav-hologram-links">
            <?php
            $nav_link('home', 'ទំព័រដើម', 'fa-microchip', $nav_home_url);
            $nav_link('about', 'អំពីយើង', 'fa-atom', $nav_about_url);
            $nav_link('support', 'ជំនួយការ', 'fa-satellite-dish', $nav_support_url);
            $nav_link('payments', 'ការទូទាត់', 'fa-credit-card', $nav_payments_url);
            ?>
            <div class="theme-mode-control theme-mode-nav">
                <label class="theme-mode-label" for="themeModeSelect">Theme</label>
                <select id="themeModeSelect" data-theme-mode aria-label="Color theme">
                    <option value="system">System</option>
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                </select>
            </div>
        </div>

        <div class="nav-actions">
            <a href="<?php echo htmlspecialchars($nav_contact_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="cyber-glow-btn" style="padding: 7px 14px; font-size: 13px;">
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

<div id="mobileMenuDrawer" class="mobile-menu-drawer" hidden>
    <div class="mobile-menu-links">
        <?php
        $nav_link('home', 'ទំព័រដើម', 'fa-microchip', $nav_home_url);
        $nav_link('about', 'អំពីយើង', 'fa-atom', $nav_about_url);
        $nav_link('support', 'ជំនួយការ', 'fa-satellite-dish', $nav_support_url);
        $nav_link('payments', 'ការទូទាត់', 'fa-credit-card', $nav_payments_url);
        ?>
        <div class="theme-mode-control theme-mode-mobile">
            <label class="theme-mode-label" for="mobileThemeModeSelect">Theme</label>
            <select id="mobileThemeModeSelect" data-theme-mode aria-label="Color theme">
                <option value="system">System</option>
                <option value="light">Light</option>
                <option value="dark">Dark</option>
            </select>
        </div>
        <a href="<?php echo htmlspecialchars($nav_admin_url, ENT_QUOTES, 'UTF-8'); ?>" class="nav-tab-btn" style="color: var(--cyber-cyan);"><i class="fas fa-shield-halved"></i> Admin</a>
    </div>
</div>

<script>
    const mobileToggle = document.getElementById("mobileNavToggle");
    const mobileDrawer = document.getElementById("mobileMenuDrawer");
    if (mobileToggle && mobileDrawer) {
        mobileToggle.addEventListener("click", () => {
            const isOpen = mobileToggle.getAttribute("aria-expanded") === "true";
            mobileDrawer.hidden = isOpen;
            mobileToggle.setAttribute("aria-expanded", String(!isOpen));
        });
    }
</script>
