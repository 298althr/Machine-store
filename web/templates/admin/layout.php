<?php
$title = $title ?? 'STREICHER - Institutional Terminal';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="/assets/modern.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --admin-sidebar-width: 320px;
      --admin-header-height: 80px;
    }
    
    body {
      background: #f1f5f9;
      color: var(--primary);
      overflow-x: hidden;
    }
    
    .admin-layout {
      display: grid;
      grid-template-columns: var(--admin-sidebar-width) 1fr;
      min-height: 100vh;
    }
    
    .admin-sidebar {
      background: #0f172a;
      color: white;
      padding: 40px;
      height: 100vh;
      position: sticky;
      top: 0;
      display: flex;
      flex-direction: column;
      z-index: 1000;
      box-shadow: 20px 0 50px rgba(0,0,0,0.1);
    }
    
    .admin-sidebar-header {
      margin-bottom: 60px;
    }
    
    .admin-nav {
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1;
      overflow-y: auto;
    }
    
    .admin-nav::-webkit-scrollbar {
      width: 4px;
    }
    
    .admin-nav::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.1);
      border-radius: 2px;
    }
    
    .admin-nav a {
      color: rgba(255,255,255,0.6);
      text-decoration: none;
      padding: 16px 24px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    
    .admin-nav a:hover {
      color: white;
      background: rgba(255,255,255,0.05);
    }
    
    .admin-nav a.active {
      color: white;
      background: var(--accent);
      box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
    }
    
    .admin-content {
      padding: 60px;
      max-width: 1600px;
      margin: 0 auto;
      width: 100%;
    }
    
    .admin-topbar {
      display: none;
      background: #0f172a;
      color: white;
      padding: 20px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1001;
      justify-content: space-between;
      align-items: center;
    }
    
    @media (max-width: 1024px) {
      .admin-layout {
        grid-template-columns: 1fr;
      }
      
      .admin-sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        bottom: 0;
        width: 300px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      body.admin-nav-open .admin-sidebar {
        left: 0;
      }
      
      .admin-topbar {
        display: flex;
      }
      
      .admin-content {
        padding-top: 100px;
      }
      
      .admin-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.8);
        z-index: 999;
        backdrop-filter: blur(8px);
      }
      
      body.admin-nav-open .admin-overlay {
        display: block;
      }
    }
  </style>
</head>
<body>
<div class="admin-topbar">
  <button class="admin-menu-toggle" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">☰</button>
  <div style="font-family: 'Outfit', sans-serif; font-weight: 900; letter-spacing: 1px;">STREICHER TERMINAL</div>
  <a href="/admin/logout" style="font-size: 1.25rem;">🚪</a>
</div>

<div class="admin-overlay"></div>

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="admin-sidebar-header">
      <a href="/admin" style="display: flex; align-items: center; gap: 16px; text-decoration: none; color: white;">
        <div style="width: 48px; height: 48px; background: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 900; font-family: 'Outfit', sans-serif; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);">S</div>
        <div>
          <div style="font-family: 'Outfit', sans-serif; font-weight: 900; letter-spacing: 1px; font-size: 1.25rem;">STREICHER</div>
          <div style="font-size: 0.75rem; color: rgba(255,255,255,0.4); font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Institutional Terminal</div>
        </div>
      </a>
    </div>
    
    <nav class="admin-nav">
      <a href="/admin" class="<?= $_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '' ?>">
        <span>📊</span> Dashboard
      </a>
      <a href="/admin/orders" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') === 0 ? 'active' : '' ?>">
        <span>📦</span> Orders
      </a>
      <a href="/admin/products" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/products') === 0 ? 'active' : '' ?>">
        <span>🏭</span> Products
      </a>
      <a href="/admin/shipments" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/shipments') === 0 ? 'active' : '' ?>">
        <span>🚚</span> Shipments
      </a>
      <a href="/admin/agents" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/agents') === 0 ? 'active' : '' ?>">
        <span>🤝</span> Agents
      </a>
      <a href="/admin/tickets" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/tickets') === 0 ? 'active' : '' ?>">
        <span>🎫</span> Support Tickets
      </a>
      <a href="/admin/software-activation" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/software-activation') === 0 ? 'active' : '' ?>">
        <span>🔑</span> Activation
      </a>
      <a href="/admin/customers" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/customers') === 0 ? 'active' : '' ?>">
        <span>👥</span> Customers
      </a>
      <a href="/admin/reports" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/reports') === 0 ? 'active' : '' ?>">
        <span>📈</span> Reports
      </a>
      <a href="/admin/settings" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') === 0 ? 'active' : '' ?>">
        <span>⚙️</span> Settings
      </a>
      
      <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 32px 0;"></div>
      
      <a href="/" target="_blank">
        <span>🌐</span> Corporate Portal
      </a>
      <a href="/admin/logout">
        <span>🚪</span> Terminate Session
      </a>
    </nav>
    
    <div style="margin-top: 40px; padding: 24px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
      <div style="font-size: 0.7rem; font-weight: 900; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">System Integrity</div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981;"></div>
        <div style="font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.6);">Operational</div>
      </div>
    </div>
  </aside>
  
  <main class="admin-content">
    <?= $content ?? '' ?>
  </main>
</div>

<script>
(function() {
  const toggle = document.querySelector('.admin-menu-toggle');
  const overlay = document.querySelector('.admin-overlay');
  const body = document.body;
  if (toggle) {
    toggle.addEventListener('click', () => body.classList.toggle('admin-nav-open'));
  }
  if (overlay) {
    overlay.addEventListener('click', () => body.classList.remove('admin-nav-open'));
  }
})();
</script>
</body>
</html>
