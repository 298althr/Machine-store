<header style="margin-bottom: 60px; display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #e2e8f0; padding-bottom: 32px;">
  <div>
    <div style="font-size: 0.8rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 12px;">Institutional Terminal</div>
    <h1 style="font-size: 3rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1.5px; line-height: 1;">Command Center</h1>
    <p style="margin: 16px 0 0 0; color: var(--text-muted); font-size: 1.1rem; font-weight: 500;">Authorized Session Active: <span style="color: var(--primary); font-weight: 800;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span></p>
  </div>
  <div>
    <a href="/admin/orders?status=payment_uploaded" class="btn-modern btn-accent" style="padding: 16px 32px; border-radius: 8px; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; font-size: 0.85rem;">
      Review Pending Payments (<?= (int)$stats['pending_payments'] ?>)
    </a>
  </div>
</header>

<!-- Analytics Matrix -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; margin-bottom: 60px;">
  <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
    <div style="font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Total Deployment Registry</div>
    <div style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -1px;"><?= number_format((int)$stats['total_orders']) ?></div>
    <div style="position: absolute; bottom: -20px; right: -20px; font-size: 8rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">ORD</div>
  </div>
  <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid <?= $stats['pending_payments'] > 0 ? 'var(--accent)' : 'rgba(0,0,0,0.05)' ?>; position: relative; overflow: hidden;">
    <div style="font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Verification Queue</div>
    <div style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: <?= $stats['pending_payments'] > 0 ? 'var(--accent)' : 'var(--primary)' ?>; letter-spacing: -1px;"><?= (int)$stats['pending_payments'] ?></div>
    <?php if ($stats['pending_payments'] > 0): ?>
    <div style="margin-top: 12px; font-size: 0.75rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 1px;">✓ Action Required</div>
    <?php endif; ?>
    <div style="position: absolute; bottom: -20px; right: -20px; font-size: 8rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">PAY</div>
  </div>
  <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
    <div style="font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Institutional Revenue</div>
    <div style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -1px;"><?= format_price((float)$stats['total_revenue']) ?></div>
    <div style="position: absolute; bottom: -20px; right: -20px; font-size: 8rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">REV</div>
  </div>
  <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
    <div style="font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Active Asset Portfolio</div>
    <div style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -1px;"><?= number_format((int)$stats['total_products']) ?></div>
    <div style="position: absolute; bottom: -20px; right: -20px; font-size: 8rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">PRD</div>
  </div>
</div>

<!-- Registry Warning Matrix -->
<?php if (!empty($pendingPayments)): ?>
<div style="background: #0f172a; border-radius: var(--radius-lg); padding: 40px 60px; margin-bottom: 60px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-xl); border: 1px solid var(--accent);">
  <div style="display: flex; gap: 32px; align-items: center;">
    <div style="font-size: 3rem;">💳</div>
    <div>
      <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: white; margin: 0 0 8px 0; font-weight: 900; letter-spacing: -0.5px;">Institutional Payment Verification</h4>
      <p style="color: rgba(255,255,255,0.6); font-size: 1.1rem; margin: 0; font-weight: 500;">
        <span style="color: var(--accent); font-weight: 900;"><?= count($pendingPayments) ?> Registry Entries</span> are currently awaiting authoritative validation matrix.
      </p>
    </div>
  </div>
  <a href="/admin/orders?status=payment_uploaded" class="btn-modern btn-accent" style="padding: 20px 40px; border-radius: 8px; text-transform: uppercase; letter-spacing: 2px; font-weight: 900;">Initialize Review →</a>
</div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 400px; gap: 60px;">
  <!-- Recent Deployment Registry -->
  <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
    <div style="padding: 32px 48px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Recent Deployment Registry</h3>
      <a href="/admin/orders" style="font-size: 0.8rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; text-decoration: none;">View Full Matrix →</a>
    </div>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: white; border-bottom: 2px solid #f1f5f9;">
            <th style="padding: 24px 48px; text-align: left; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">Registry ID</th>
            <th style="padding: 24px 48px; text-align: left; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">Timestamp</th>
            <th style="padding: 24px 48px; text-align: left; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">Value</th>
            <th style="padding: 24px 48px; text-align: left; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">Status Protocol</th>
            <th style="padding: 24px 48px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentOrders as $order): ?>
          <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
            <td style="padding: 24px 48px; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= htmlspecialchars($order['order_number']) ?></td>
            <td style="padding: 24px 48px; font-weight: 700; color: var(--text-muted); font-size: 0.95rem;"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
            <td style="padding: 24px 48px; font-weight: 900; color: var(--primary); font-size: 1.1rem;"><?= format_price((float)$order['total']) ?></td>
            <td style="padding: 24px 48px;">
              <span style="display: inline-block; padding: 8px 16px; background: #e2e8f0; color: var(--primary); border-radius: 6px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">
                <?= get_status_label($order['status']) ?>
              </span>
            </td>
            <td style="padding: 24px 48px; text-align: right;">
              <a href="/admin/orders/<?= $order['id'] ?>" style="display: inline-block; padding: 10px 20px; background: var(--primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 900; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--primary)'">Analyze</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Utility Command Matrix -->
  <div style="display: flex; flex-direction: column; gap: 32px;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
      <div style="padding: 32px 40px; background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Utility Commands</h3>
      </div>
      <div style="padding: 40px; display: flex; flex-direction: column; gap: 16px;">
        <a href="/admin/orders?status=payment_uploaded" class="btn-modern btn-accent" style="width: 100%; height: 60px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; justify-content: center;">
          💳 Verify Payments
        </a>
        <a href="/admin/orders?status=payment_confirmed" class="btn-modern" style="width: 100%; height: 60px; background: #16a34a; color: white; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; justify-content: center; border: none;">
          📦 Dispatch Assets
        </a>
        <a href="/admin/products/new" class="btn-modern" style="width: 100%; height: 60px; background: var(--primary); color: white; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; justify-content: center; border: none;">
          🏭 Registry New Asset
        </a>
      </div>
    </div>
    
    <!-- Deployment Matrix Summary -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
      <div style="padding: 32px 40px; background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Protocol Summary</h3>
      </div>
      <div style="padding: 40px;">
        <?php
        $statusCounts = [
          'awaiting_payment' => 0,
          'payment_uploaded' => 0,
          'payment_confirmed' => 0,
          'shipped' => 0,
          'delivered' => 0,
        ];
        foreach ($recentOrders as $o) {
          if (isset($statusCounts[$o['status']])) {
            $statusCounts[$o['status']]++;
          }
        }
        ?>
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <a href="/admin/orders?status=awaiting_payment" style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 16px; background: #f8fafc; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.background='#f1f5f9'">
            <span style="font-size: 0.85rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">Awaiting Sync</span>
            <span style="padding: 6px 12px; background: #94a3b8; color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 900;"><?= $statusCounts['awaiting_payment'] ?></span>
          </a>
          <a href="/admin/orders?status=payment_uploaded" style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 16px; background: #f8fafc; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.background='#f1f5f9'">
            <span style="font-size: 0.85rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">Verification Queue</span>
            <span style="padding: 6px 12px; background: var(--accent); color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 900;"><?= $statusCounts['payment_uploaded'] ?></span>
          </a>
          <a href="/admin/orders?status=payment_confirmed" style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 16px; background: #f8fafc; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.background='#f1f5f9'">
            <span style="font-size: 0.85rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">Ready for Dispatch</span>
            <span style="padding: 6px 12px; background: #16a34a; color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 900;"><?= $statusCounts['payment_confirmed'] ?></span>
          </a>
          <a href="/admin/orders?status=shipped" style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 16px; background: #f8fafc; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.background='#f1f5f9'">
            <span style="font-size: 0.85rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">In Transit Matrix</span>
            <span style="padding: 6px 12px; background: var(--primary); color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 900;"><?= $statusCounts['shipped'] ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
