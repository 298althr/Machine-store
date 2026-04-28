<?php
$bankName = $settings['bank_name'] ?? 'Deutsche Bank AG';
$accountHolder = $settings['account_holder'] ?? 'MAX STREICHER GmbH';
$iban = $settings['iban'] ?? 'DE89 3704 0044 0532 0130 00';
$swift = $settings['bic'] ?? 'COBADEFFXXX';
$displayCurrency = $_SESSION['display_currency'] ?? 'EUR';
$currencySymbol = $displayCurrency === 'USD' ? '$' : '€';
?>

<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a>
    <span style="margin: 0 8px;">/</span>
    <a href="/order/<?= $order['id'] ?>/invoice" style="text-decoration: none; color: var(--accent);">Asset #<?= htmlspecialchars($order['order_number']) ?></a>
    <span style="margin: 0 8px;">/</span>
    <span style="color: var(--text-main); font-weight: 600;">Payment Confirmation</span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 60px; min-height: 250px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Settlement
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 12px;">Confirm Payment</h1>
      <p style="font-size: 1.25rem; opacity: 0.9;">
        Secure financial portal for Asset #<?= htmlspecialchars($order['order_number']) ?>
      </p>
    </div>
  </section>

  <div style="display: grid; grid-template-columns: 1fr 400px; gap: 60px; align-items: start; margin-bottom: 120px;">
    <main>
      <div style="background: white; border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 40px; display: flex; align-items: center; gap: 32px;">
        <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0; border: 1px solid #f1f5f9;">🛡️</div>
        <div>
          <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-weight: 900; margin-bottom: 6px;">Lifecycle Status</div>
          <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;">Awaiting Payment</div>
          <p style="color: var(--text-muted); font-size: 1rem; margin-top: 12px; line-height: 1.6; font-weight: 500;">
            Please review the institutional banking registry below. Once the wire transfer is complete, click the confirmation button to proceed to receipt upload.
          </p>
        </div>
      </div>

      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 40px;">
        <div style="background: #f8fafc; padding: 24px 40px; border-bottom: 1px solid #f1f5f9;">
          <h3 style="font-size: 1.1rem; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; color: var(--primary); margin: 0;">Institutional Banking Registry</h3>
        </div>
        <div style="padding: 48px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 48px;">
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">Banking Institution</div>
              <div style="font-weight: 800; color: var(--primary); font-size: 1.2rem;"><?= htmlspecialchars($bankName) ?></div>
            </div>
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">Account Beneficiary</div>
              <div style="font-weight: 800; color: var(--primary); font-size: 1.2rem;"><?= htmlspecialchars($accountHolder) ?></div>
            </div>
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">IBAN Identifier</div>
              <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; letter-spacing: 1px; font-family: monospace;"><?= htmlspecialchars($iban) ?></div>
            </div>
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">BIC / SWIFT Protocol</div>
              <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; letter-spacing: 1px; font-family: monospace;"><?= htmlspecialchars($swift) ?></div>
            </div>
          </div>

          <div style="background: #fff7ed; border: 2px dashed #f59e0b; border-radius: var(--radius-lg); padding: 32px; text-align: center; box-shadow: var(--shadow-sm);">
            <div style="font-size: 0.85rem; color: #92400e; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; margin-bottom: 12px;">MANDATORY INSTITUTIONAL REFERENCE</div>
            <div style="font-size: 2.5rem; font-weight: 900; color: #7c2d12; font-family: 'Outfit', sans-serif; line-height: 1;"><?= htmlspecialchars($order['invoice_number'] ?? $order['order_number']) ?></div>
            <div style="font-size: 1rem; color: #92400e; margin-top: 16px; font-weight: 700; max-width: 400px; margin-left: auto; margin-right: auto;">Include this identifier in the transaction narrative for automated interdisciplinary verification.</div>
          </div>
        </div>
      </div>

      <form action="/order/<?= $order['id'] ?>/payment-confirm" method="POST">
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 48px; text-align: center;">
          <div style="font-size: 3rem; margin-bottom: 24px;">🏦</div>
          <h3 style="font-size: 1.5rem; font-family: 'Outfit', sans-serif; margin-bottom: 16px; font-weight: 800; color: var(--primary);">Have You Completed the Transfer?</h3>
          <p style="color: var(--text-muted); font-size: 1.05rem; margin-bottom: 40px; line-height: 1.6; max-width: 500px; margin-left: auto; margin-right: auto;">
            Once the institutional wire transfer has been initiated from your banking facility, confirm below to unlock the receipt upload portal.
          </p>
          <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 72px; font-size: 1.2rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">
            I Have Made Payment →
          </button>
          <p style="margin-top: 20px; color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">
            <a href="/order/<?= $order['id'] ?>/invoice" style="color: var(--accent); font-weight: 900; text-decoration: none;">← Back to Invoice</a>
          </p>
        </div>
      </form>
    </main>

    <aside style="position: sticky; top: 120px;">
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); padding: 40px;">
        <h3 style="font-size: 1.25rem; margin-bottom: 32px; font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--primary);">Asset Summary</h3>

        <div style="margin-bottom: 40px;">
          <?php foreach ($items as $item): ?>
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 1px solid #f8fafc; padding-bottom: 16px;">
            <div>
              <div style="font-weight: 800; font-size: 1rem; color: var(--primary); line-height: 1.3;"><?= htmlspecialchars($item['product_name'] ?? $item['sku']) ?></div>
              <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; margin-top: 4px;">QTY: <?= (int)$item['quantity'] ?> Units</div>
            </div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1rem;"><?= $currencySymbol ?><?= number_format((float)($item['total_price'] ?? $item['unit_price'] * $item['quantity']), 2) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="padding-top: 32px; border-top: 3px solid #f1f5f9;">
          <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px;">
            <span style="font-weight: 800; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Settlement Total</span>
            <span style="font-size: 2.2rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; line-height: 1;"><?= $currencySymbol ?><?= number_format((float)$order['total_amount'], 2) ?></span>
          </div>
          <div style="text-align: right; font-size: 0.8rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Includes Statutory VAT</div>
        </div>
      </div>
    </aside>
  </div>
</div>
