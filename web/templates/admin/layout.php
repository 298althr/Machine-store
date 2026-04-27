<?php
$title = $title ?? 'Admin - Streicher';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
<!-- Mobile Admin Topbar (outside grid) -->
<div class="admin-topbar">
  <button class="admin-menu-toggle" aria-label="Toggle admin menu">☰</button>
  <div style="font-weight: 700; letter-spacing: 0.5px;">STREICHER Admin</div>
  <a href="/" style="font-size: 0.85rem; color: #e2e8f0; text-decoration: none;">← Back to Store</a>
</div>

<div class="admin-overlay"></div>

<div class="admin-layout">
  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-header">
      <a href="/admin" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: white;">
        <div class="logo-icon" style="width: 40px; height: 40px; font-size: 1.25rem;">S</div>
        <div>
          <div style="font-weight: 700;">STREICHER</div>
          <div style="font-size: 0.75rem; color: #94a3b8;">Admin Panel</div>
        </div>
      </a>
    </div>
    
    <nav class="admin-nav">
      <a href="/admin" class="<?= $_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '' ?>">
        📊 Dashboard
      </a>
      <a href="/admin/orders" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') === 0 ? 'active' : '' ?>">
        📦 Orders
      </a>
      <a href="/admin/orders?status=payment_uploaded" class="<?= strpos($_SERVER['REQUEST_URI'], 'payment_uploaded') !== false ? 'active' : '' ?>" style="padding-left: 48px; font-size: 0.9rem;">
        💳 Pending Payments
      </a>
      <a href="/admin/products" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/products') === 0 ? 'active' : '' ?>">
        🏭 Products
      </a>
      <a href="/admin/shipments" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/shipments') === 0 ? 'active' : '' ?>">
        🚚 Shipments
      </a>
      <a href="/admin/agents" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/agents') === 0 ? 'active' : '' ?>">
        🤝 Agents
      </a>
      <a href="/admin/tickets" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/tickets') === 0 ? 'active' : '' ?>">
        🎫 Support Tickets
      </a>
      <a href="/admin/software-activation" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/software-activation') === 0 && strpos($_SERVER['REQUEST_URI'], '/admin/software-activation/') === false ? 'active' : '' ?>">
        🔑 Software Activation
      </a>
      <div style="margin-left: 1rem; margin-bottom: 0.5rem;">
        <a href="/admin/software-activation/requests" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/software-activation/requests') === 0 ? 'active' : '' ?>" style="font-size: 0.875rem; color: #94a3b8;">
          📋 Requests
        </a>
        <a href="/admin/software-activation/products" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/software-activation/products') === 0 ? 'active' : '' ?>" style="font-size: 0.875rem; color: #94a3b8; display: block;">
          📦 Products
        </a>
      </div>
      <a href="/admin/customers" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/customers') === 0 ? 'active' : '' ?>">
        👥 Customers
      </a>
      <a href="/admin/reports" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/reports') === 0 ? 'active' : '' ?>">
        📈 Reports
      </a>
      <a href="/admin/settings" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') === 0 ? 'active' : '' ?>">
        ⚙️ Settings
      </a>
      <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 24px 0;"></div>
      <a href="/" target="_blank">
        🌐 View Store
      </a>
      <a href="/admin/logout">
        🚪 Logout
      </a>
    </nav>
  </aside>
  
  <!-- Main Content -->
  <main class="admin-content">
    <?= $content ?? '' ?>
  </main>
</div>

<script>
// Admin sidebar toggle
(function() {
  const toggle = document.querySelector('.admin-menu-toggle');
  const overlay = document.querySelector('.admin-overlay');
  const body = document.body;
  if (toggle) {
    toggle.addEventListener('click', () => {
      body.classList.toggle('admin-nav-open');
    });
  }
  if (overlay) {
    overlay.addEventListener('click', () => body.classList.remove('admin-nav-open'));
  }
})();
</script>
</body>
</html>
