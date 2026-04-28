<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a>
    <span style="margin: 0 8px;">/</span>
    <span style="color: var(--text-main); font-weight: 600;">Order Lookup</span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 60px; min-height: 250px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Recovery
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 12px;">Find Your Order</h1>
      <p style="font-size: 1.25rem; opacity: 0.9;">
        Retrieve your procurement protocol using your order identifier and registered email address.
      </p>
    </div>
  </section>

  <div style="max-width: 600px; margin: 0 auto;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 48px;">
      <?php if (!empty($error)): ?>
      <div style="background: #fef2f2; border: 2px solid #fee2e2; padding: 20px; border-radius: 8px; color: #991b1b; font-weight: 800; margin-bottom: 32px;">
        <?php if ($error === 'missing_fields'): ?>
          Please provide both order number and email address.
        <?php elseif ($error === 'not_found'): ?>
          No order found with that combination. Please check your order number and email.
        <?php else: ?>
          <?= htmlspecialchars($error) ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <form action="/order/lookup" method="POST">
        <div style="margin-bottom: 32px;">
          <label style="display: block; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 16px;">Order Number</label>
          <input type="text" name="order_number" required placeholder="ST-20260428-XXXXXX" style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc; color: var(--primary);" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
        </div>

        <div style="margin-bottom: 40px;">
          <label style="display: block; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 16px;">Billing Email Address</label>
          <input type="email" name="email" required placeholder="representative@institution.com" style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc; color: var(--primary);" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
        </div>

        <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 72px; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">
          Locate Order Protocol
        </button>
      </form>

      <div style="margin-top: 40px; padding-top: 32px; border-top: 1px solid #f1f5f9; text-align: center;">
        <p style="color: var(--text-muted); font-size: 0.95rem; font-weight: 500;">
          Have an account? <a href="/login" style="color: var(--accent); font-weight: 900; text-decoration: none;">Portal Login</a>
        </p>
      </div>
    </div>
  </div>
</div>
