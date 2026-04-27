<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Mein Konto' : 'Partner Portal' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 60px; min-height: 250px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1553034190-c8235a05eb7a?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Access Hub
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 12px; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Portal Dashboard' : 'Institutional Portal' ?></h1>
      <p style="font-size: 1.25rem; opacity: 0.9; font-weight: 500;">
        <?= $lang === 'de' ? 'Willkommen zurück,' : 'Welcome back,' ?> <strong style="color: var(--accent);"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Authorized Representative') ?></strong>.
      </p>
    </div>
  </section>

  <div style="display: grid; grid-template-columns: 350px 1fr; gap: 60px; align-items: start; margin-bottom: 120px;">
    <!-- Institutional Sidebar -->
    <aside style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; position: sticky; top: 120px;">
      <div style="padding: 40px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; text-align: center;">
        <div style="width: 100px; height: 100px; background: var(--primary); color: var(--accent); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 900; margin: 0 auto 24px; box-shadow: var(--shadow-lg); font-family: 'Outfit', sans-serif;">
          <?= strtoupper(substr($_SESSION['user_name'] ?? 'P', 0, 1)) ?>
        </div>
        <div style="font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); font-size: 1.4rem; letter-spacing: -0.5px;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Authorized Partner') ?></div>
        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-top: 8px;">Registry ID: #<?= str_pad($_SESSION['user_id'] ?? '0', 6, '0', STR_PAD_LEFT) ?></div>
      </div>
      <nav style="display: flex; flex-direction: column;">
        <a href="/account" style="padding: 24px 40px; text-decoration: none; color: var(--accent); font-weight: 900; background: rgba(220, 38, 38, 0.05); border-left: 6px solid var(--accent); display: flex; align-items: center; gap: 20px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
          <span style="font-size: 1.5rem;">📦</span> <?= $lang === 'de' ? 'Meine Bestellungen' : 'Procured Assets' ?>
        </a>
        <a href="/account/profile" class="nav-link-modern" style="padding: 24px 40px; text-decoration: none; color: var(--primary); font-weight: 800; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 20px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;">
          <span style="font-size: 1.5rem;">👤</span> <?= $lang === 'de' ? 'Profil-Einstellungen' : 'Profile Integrity' ?>
        </a>
        <a href="/account/quotes" class="nav-link-modern" style="padding: 24px 40px; text-decoration: none; color: var(--primary); font-weight: 800; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 20px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;">
          <span style="font-size: 1.5rem;">📋</span> <?= $lang === 'de' ? 'Meine Angebote' : 'Open Quotes' ?>
        </a>
        <a href="/logout" style="padding: 32px 40px; text-decoration: none; color: var(--text-muted); font-weight: 900; display: flex; align-items: center; gap: 20px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
          <span style="font-size: 1.5rem;">🚪</span> <?= $lang === 'de' ? 'Abmelden' : 'Secure Logout' ?>
        </a>
      </nav>
    </aside>

    <!-- Content Intelligence -->
    <main>
      <!-- Institutional Intelligence Stats -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; margin-bottom: 60px;">
        <div style="background: white; padding: 48px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); text-align: center; position: relative; overflow: hidden;">
          <div style="position: absolute; top: -10px; right: -10px; font-size: 6rem; opacity: 0.03; font-weight: 900;">01</div>
          <div style="font-size: 3.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; margin-bottom: 12px; line-height: 1; letter-spacing: -2px;"><?= count($orders ?? []) ?></div>
          <div style="color: var(--text-muted); font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Asset Acquisitions</div>
        </div>
        <div style="background: white; padding: 48px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); text-align: center; position: relative; overflow: hidden;">
          <div style="position: absolute; top: -10px; right: -10px; font-size: 6rem; opacity: 0.03; font-weight: 900;">02</div>
          <div style="font-size: 3.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; margin-bottom: 12px; line-height: 1; letter-spacing: -2px;">0</div>
          <div style="color: var(--text-muted); font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Pending Quotes</div>
        </div>
        <div style="background: white; padding: 48px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); text-align: center; position: relative; overflow: hidden;">
          <div style="position: absolute; top: -10px; right: -10px; font-size: 6rem; opacity: 0.03; font-weight: 900;">03</div>
          <div style="font-size: 3.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; margin-bottom: 12px; line-height: 1; letter-spacing: -2px;">0</div>
          <div style="color: var(--text-muted); font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Technical Protocols</div>
        </div>
      </div>

      <!-- Asset Registry matrix -->
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 40px 48px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1px;"><?= $lang === 'de' ? 'Beschaffungshistorie' : 'Procurement Registry' ?></h3>
          <a href="/catalog" class="btn-modern btn-accent" style="padding: 16px 32px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Initialize Acquisition +</a>
        </div>

        <?php if (empty($orders)): ?>
        <div style="padding: 120px 60px; text-align: center;">
          <div style="font-size: 6rem; margin-bottom: 32px; filter: grayscale(1) opacity(0.1);">💼</div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; color: var(--primary); margin-bottom: 16px; font-weight: 900; letter-spacing: -0.5px;">Institutional Records Unavailable</h4>
          <p style="color: var(--text-muted); margin-bottom: 48px; font-size: 1.1rem; max-width: 500px; margin-left: auto; margin-right: auto; font-weight: 500; line-height: 1.6;">No interdisciplinary procurement protocols have been initialized through this institutional account matrix.</p>
          <a href="/catalog" class="btn-modern btn-accent" style="padding: 24px 60px; font-weight: 900; font-size: 1.1rem; border-radius: 8px;">Access Global Asset Catalog</a>
        </div>
        <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: white; border-bottom: 2px solid #f1f5f9;">
              <th style="padding: 28px 48px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 900;">Protocol Ref</th>
              <th style="padding: 28px 48px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 900;">Initialization</th>
              <th style="padding: 28px 48px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 900;">Valuation</th>
              <th style="padding: 28px 48px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 900;">Lifecycle</th>
              <th style="padding: 28px 48px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
              <td style="padding: 36px 48px; font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;">#<?= htmlspecialchars($order['order_number']) ?></td>
              <td style="padding: 36px 48px; color: var(--text-muted); font-size: 1.05rem; font-weight: 600;"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
              <td style="padding: 36px 48px; font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;"><?= format_price((float)$order['total_amount']) ?></td>
              <td style="padding: 36px 48px;">
                <span style="padding: 8px 16px; border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; background: var(--primary); color: white; letter-spacing: 1.5px; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);">
                  <?= str_replace('_', ' ', $order['status']) ?>
                </span>
              </td>
              <td style="padding: 36px 48px; text-align: right;">
                <a href="/order/<?= $order['id'] ?>" class="btn-modern btn-accent" style="padding: 12px 32px; font-size: 0.85rem; font-weight: 900; letter-spacing: 1px; border-radius: 6px; text-transform: uppercase;">Review Matrix</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>

<style>
.nav-link-modern:hover {
  background: #f8fafc;
  color: var(--accent) !important;
  padding-left: 56px !important;
}
</style>

<style>
.nav-link-modern:hover {
  background: #f8fafc;
  color: var(--accent) !important;
  padding-left: 48px !important;
}
</style>
