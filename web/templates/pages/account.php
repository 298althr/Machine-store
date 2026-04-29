<div class="container-modern section-padding account-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <span class="current"><?= $lang === 'de' ? 'Mein Konto' : 'Partner Portal' ?></span>
  </div>

  <section class="page-hero-modern bg-primary color-white mb-60">
    <div class="hero-bg-overlay account"></div>
    <div class="hero-content-modern relative z-1 p-60">
      <div class="badge badge-accent mb-24 tracking-widest">
        Institutional Access Hub
      </div>
      <h1 class="text-5xl font-black mb-12"><?= $lang === 'de' ? 'Portal Dashboard' : 'Institutional Portal' ?></h1>
      <p class="text-xl opacity-90 font-medium">
        <?= $lang === 'de' ? 'Willkommen zurück,' : 'Welcome back,' ?> <strong class="text-accent"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Authorized Representative') ?></strong>.
      </p>
    </div>
  </section>

  <div class="grid grid-sidebar items-start mb-120">
    <!-- Institutional Sidebar -->
    <aside class="card-modern no-padding overflow-hidden sticky-top-120">
      <div class="p-40 bg-light border-bottom text-center">
        <div class="user-avatar-modern bg-primary color-accent mb-24 shadow-lg">
          <?= strtoupper(substr($_SESSION['user_name'] ?? 'P', 0, 1)) ?>
        </div>
        <div class="text-xl font-black color-primary mb-4"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Authorized Partner') ?></div>
        <div class="text-xs font-black text-muted uppercase tracking-widest mt-8">Registry ID: #<?= str_pad($_SESSION['user_id'] ?? '0', 6, '0', STR_PAD_LEFT) ?></div>
      </div>
      <nav class="flex flex-col">
        <a href="/account" class="nav-link-modern active">
          <i data-lucide="package"></i>
          <span><?= $lang === 'de' ? 'Meine Bestellungen' : 'Procured Assets' ?></span>
        </a>
        <a href="/account/profile" class="nav-link-modern">
          <i data-lucide="user"></i>
          <span><?= $lang === 'de' ? 'Profil-Einstellungen' : 'Profile Integrity' ?></span>
        </a>
        <a href="/account/quotes" class="nav-link-modern">
          <i data-lucide="file-text"></i>
          <span><?= $lang === 'de' ? 'Meine Angebote' : 'Open Quotes' ?></span>
        </a>
        <a href="/logout" class="nav-link-modern text-muted">
          <i data-lucide="log-out"></i>
          <span><?= $lang === 'de' ? 'Abmelden' : 'Secure Logout' ?></span>
        </a>
      </nav>
    </aside>

    <!-- Content Intelligence -->
    <main>
      <!-- Institutional Intelligence Stats -->
      <div class="grid grid-3 mb-60">
        <div class="stat-card card-modern">
          <div class="stat-icon-bg">01</div>
          <div class="stat-value"><?= count($orders ?? []) ?></div>
          <div class="stat-label">Asset Acquisitions</div>
        </div>
        <div class="stat-card card-modern">
          <div class="stat-icon-bg">02</div>
          <div class="stat-value">0</div>
          <div class="stat-label">Pending Quotes</div>
        </div>
        <div class="stat-card card-modern">
          <div class="stat-icon-bg">03</div>
          <div class="stat-value">0</div>
          <div class="stat-label">Technical Protocols</div>
        </div>
      </div>

      <!-- Asset Registry matrix -->
      <div class="card-modern no-padding overflow-hidden shadow-xl">
        <div class="px-48 py-32 bg-light border-bottom flex-between items-center">
          <h3 class="mb-0 text-xl font-black color-primary"><?= $lang === 'de' ? 'Beschaffungshistorie' : 'Procurement Registry' ?></h3>
          <?php render_component('button', [
            'href' => '/catalog',
            'variant' => 'accent',
            'size' => 'sm',
            'label' => 'Initialize Acquisition +',
            'class' => 'uppercase tracking-widest font-black'
          ]); ?>
        </div>

        <?php if (empty($orders)): ?>
        <div class="text-center py-100 px-60">
          <div class="empty-icon mb-32 opacity-10">
            <i data-lucide="briefcase" size="80"></i>
          </div>
          <h4 class="text-2xl font-black color-primary mb-16">Institutional Records Unavailable</h4>
          <p class="text-muted text-lg mb-48 max-w-500 mx-auto font-medium">No interdisciplinary procurement protocols have been initialized through this institutional account matrix.</p>
          <?php render_component('button', [
            'href' => '/catalog',
            'variant' => 'accent',
            'label' => 'Access Global Asset Catalog',
            'class' => 'px-60 py-20 font-black uppercase tracking-widest'
          ]); ?>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
          <table class="admin-table">
            <thead>
              <tr class="bg-light">
                <th>Protocol Ref</th>
                <th>Initialization</th>
                <th>Valuation</th>
                <th>Lifecycle Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
              <tr class="hover-bg-subtle transition">
                <td class="font-black color-primary text-lg">#<?= htmlspecialchars($order['order_number']) ?></td>
                <td class="text-muted font-bold"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td class="font-black color-primary text-lg"><?= format_price((float)$order['total_amount']) ?></td>
                <td>
                  <?php 
                    $variant = 'info';
                    if ($order['status'] === 'delivered') $variant = 'success';
                    if ($order['status'] === 'payment_uploaded') $variant = 'warning';
                    render_component('badge', [
                      'label' => str_replace('_', ' ', $order['status']),
                      'variant' => $variant
                    ]); 
                  ?>
                </td>
                <td class="text-right">
                  <?php render_component('button', [
                    'href' => '/order/' . $order['id'],
                    'variant' => 'accent',
                    'size' => 'sm',
                    'label' => 'Review Matrix'
                  ]); ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>
