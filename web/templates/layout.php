<?php
$title = $title ?? 'Streicher GmbH - Industrial Parts & Equipment';
$lang = $_SESSION['lang'] ?? 'de';
$cartCount = 0;
if (isset($_SESSION['cart_id']) && isset($pdo)) {
    $stmt = $pdo->prepare('SELECT SUM(quantity) as count FROM cart_items WHERE cart_id = ?');
    $stmt->execute([$_SESSION['cart_id']]);
    $cartCount = (int)($stmt->fetchColumn() ?: 0);
} elseif (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = array_sum(array_column($_SESSION['cart'], 'qty'));
}
?>
<!doctype html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($title) ?></title>
  <meta name="description" content="Streicher GmbH - Premium industrial parts and equipment for petroleum, mechanical engineering, and heavy industry. German engineering excellence.">
  <link rel="stylesheet" href="/assets/styles.css?v=<?= time() ?>">
  <link rel="stylesheet" href="/assets/modern.css?v=<?= time() ?>">
  <link rel="icon" type="image/png" href="/assets/favicon.png">
  <link rel="apple-touch-icon" href="/assets/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="modern-theme">

  <div class="mobile-nav-overlay"></div>
  <nav class="mobile-nav">
    <div class="mobile-nav-header">
      <span style="font-family: 'Outfit', sans-serif; font-weight: 800; letter-spacing: 1px;">STREICHER</span>
      <button class="mobile-nav-close">✕</button>
    </div>
    <div class="mobile-nav-links">
      <a href="/"><?= __('home') ?></a>
      <a href="/profile"><?= __('profile') ?></a>
      <a href="/catalog"><?= __('products') ?></a>
      <a href="/reference-projects"><?= __('references') ?></a>
      <a href="/track"><?= __('track_order') ?></a>
      <a href="/cart" class="btn-modern btn-accent" style="margin-top: 20px; color: white !important;">
        🛒 <?= __('cart') ?> (<?= $cartCount ?>)
      </a>
    </div>
    <div style="padding: 40px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto;">
      <div style="display: flex; gap: 20px; font-weight: 800;">
        <a href="?lang=de" style="color: white; text-decoration: none;">DE</a>
        <a href="?lang=en" style="color: white; text-decoration: none;">EN</a>
      </div>
    </div>
  </nav>

  <header class="site-header">
    <div class="header-top desktop-only">
      <div class="header-top-inner container-modern">
        <div class="header-contact-info">
          <span style="font-weight: 800; color: var(--accent);">STREICHER</span>
          <span class="separator"></span>
          <span>🇩🇪 <?= __('made_in_germany') ?></span>
          <span class="separator"></span>
          <span class="email-link">✉️ store@streichergmbh.com</span>
        </div>
        <div class="header-utility-links">
          <a href="/news"><?= __('news') ?></a>
          <a href="/contact"><?= __('contact') ?></a>
          <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="/account" style="font-weight: 700; color: var(--accent);"><?= __('my_account') ?></a>
            <a href="/logout"><?= __('logout') ?></a>
          <?php else: ?>
            <a href="/login"><?= __('login') ?></a>
          <?php endif; ?>
          <div class="lang-switcher">
            <a href="?lang=de" class="lang-btn <?= $lang === 'de' ? 'active' : '' ?>">DE</a>
            <a href="?lang=en" class="lang-btn <?= $lang === 'en' ? 'active' : '' ?>">EN</a>
          </div>
        </div>
      </div>
    </div>

    <div class="mobile-header">
      <button class="mobile-menu-toggle" aria-label="Menu">
        <span class="hamburger-icon"></span>
      </button>
      <a href="/" class="mobile-logo" style="display: block; flex: 1; text-align: center;">
        <img src="/assets/logo.png" alt="Streicher" style="height: 28px; width: auto; filter: brightness(0) invert(1);">
      </a>
      <a href="/cart" style="color: white; text-decoration: none; position: relative; width: 40px; text-align: right;">
        <span style="font-size: 1.2rem;">🛒</span>
        <?php if ($cartCount > 0): ?>
          <span style="position: absolute; top: -5px; right: -5px; background: var(--accent); color: white; font-size: 0.65rem; padding: 1px 4px; border-radius: 50%; font-weight: 900;"><?= $cartCount ?></span>
        <?php endif; ?>
      </a>
    </div>

    <div class="header-main container-modern desktop-only">
      <a href="/" class="logo">
        <img src="/assets/logo.png" alt="Streicher" class="logo-img">
      </a>
      <nav class="header-nav">
        <a href="/profile"><?= __('profile') ?></a>
        <a href="/catalog"><?= __('products') ?></a>
        <a href="/reference-projects"><?= __('references') ?></a>
        <a href="/hse-q">HSE-Q</a>
        <a href="/software-activation" class="software-link">🔑 Software Activation</a>
        <a href="/cart" class="cart-link" style="background: var(--primary); color: white; border-radius: 8px; padding: 8px 20px;">
          🛒 <?= __('cart') ?>
          <?php if ($cartCount > 0): ?>
            <span style="background: var(--accent); padding: 2px 6px; border-radius: 4px; margin-left: 8px; font-size: 0.8rem;"><?= $cartCount ?></span>
          <?php endif; ?>
        </a>
      </nav>
    </div>
  </header>

<main id="main-content" class="<?= ($isHomePage ?? false) ? 'home-page' : 'page-content' ?>">
  <?= $content ?? '' ?>
</main>

<footer class="site-footer">
  <div class="footer-inner container-modern">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="/" class="footer-logo">
          <img src="/assets/logo.png" alt="Streicher" style="height: 56px; width: auto; margin-bottom: 32px;">
        </a>
        <p class="footer-brand-text">
          <?= $lang === 'de' 
            ? 'Die STREICHER Gruppe steht für interdisziplinäre Lösungen und höchste Präzision im internationalen Anlagen- und Maschinenbau.'
            : 'The STREICHER Group represents interdisciplinary solutions and maximum precision in international plant and mechanical engineering.' ?>
        </p>
        <div class="footer-contact-details" style="margin-top: 32px; font-size: 0.9rem; line-height: 1.8; color: var(--text-muted);">
          <strong>STREICHER HQ:</strong><br>
          Schwaigerbreite 17<br>
          94469 Deggendorf, Germany
        </div>
      </div>
      
      <div class="footer-nav-col">
        <h4 class="footer-title"><?= $lang === 'de' ? 'Leistungen' : 'Services' ?></h4>
        <ul class="footer-links">
          <li><a href="/catalog">Pipeline Construction</a></li>
          <li><a href="/catalog">Plant Engineering</a></li>
          <li><a href="/catalog">Mechanical Engineering</a></li>
          <li><a href="/catalog">Civil Structures</a></li>
        </ul>
      </div>
      
      <div class="footer-nav-col">
        <h4 class="footer-title"><?= $lang === 'de' ? 'Unternehmen' : 'Company' ?></h4>
        <ul class="footer-links">
          <li><a href="/profile"><?= __('about_us') ?></a></li>
          <li><a href="/reference-projects">Global Projects</a></li>
          <li><a href="/careers"><?= __('careers') ?></a></li>
          <li><a href="/contact"><?= __('contact') ?></a></li>
        </ul>
      </div>
      
      <div class="footer-nav-col">
        <h4 class="footer-title"><?= $lang === 'de' ? 'Bestellungen' : 'Ordering' ?></h4>
        <ul class="footer-links">
          <li><a href="/catalog"><?= __('catalog') ?></a></li>
          <li><a href="/cart"><?= __('shopping_cart') ?></a></li>
          <li><a href="/track"><?= __('track_order') ?></a></li>
          <li><a href="/shipping"><?= __('shipping_info') ?></a></li>
        </ul>
      </div>
      
      <div class="footer-nav-col">
        <h4 class="footer-title">Legal</h4>
        <ul class="footer-links">
          <li><a href="/privacy"><?= __('privacy_policy') ?></a></li>
          <li><a href="/terms"><?= __('terms_conditions') ?></a></li>
          <li><a href="/support">HSE-Q Compliance</a></li>
        </ul>
      </div>
    </div>
    
    <div class="footer-bottom">
      <div class="footer-bottom-left">
        © <?= date('Y') ?> MAX STREICHER GmbH & Co. KG aA. <?= __('all_rights_reserved') ?> 
      </div>
      <div class="footer-bottom-right">
        <span>ISO 9001:2015 CERTIFIED</span>
        <span class="separator-dot"></span>
        <span>GLOBAL OPERATIONS</span>
      </div>
    </div>
  </div>
</footer>

<script>
window.StreicherCart = {
  async add(sku, qty = 1) {
    const res = await fetch('/api/cart', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({sku, qty})
    });
    if (res.ok) {
      const data = await res.json();
      this.updateCount(data.cart_count || 0);
      return true;
    }
    return false;
  },
  updateCount(count) {
    const badge = document.querySelector('.cart-count');
    if (badge) {
      badge.textContent = count;
      badge.style.display = count > 0 ? 'inline' : 'none';
    }
  }
};

(function() {
  const toggle = document.querySelector('.mobile-menu-toggle');
  const close = document.querySelector('.mobile-nav-close');
  const overlay = document.querySelector('.mobile-nav-overlay');
  const body = document.body;
  
  if (toggle) toggle.onclick = () => body.classList.add('nav-open');
  if (close) close.onclick = () => body.classList.remove('nav-open');
  if (overlay) overlay.onclick = () => body.classList.remove('nav-open');
})();
</script>
</body>
</html>
