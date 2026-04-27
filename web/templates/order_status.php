<?php
$billingAddress = json_decode($order['billing_address'] ?? '{}', true) ?: [];
$shippingAddress = json_decode($order['shipping_address'] ?? '{}', true) ?: [];
$orderType = $order['order_type'] ?? 'hardware';
$isSoftwareOrder = ($orderType === 'software');

// Determine progress step - software orders have different flow
$progressStep = 1;
if ($isSoftwareOrder) {
    switch ($order['status']) {
        case 'awaiting_payment': $progressStep = 1; break;
        case 'payment_uploaded': $progressStep = 2; break;
        case 'payment_confirmed': $progressStep = 3; break;
        case 'processing': $progressStep = 3; break;
        case 'license_sent': 
        case 'delivered': $progressStep = 4; break;
    }
} else {
    switch ($order['status']) {
        case 'awaiting_payment': $progressStep = 1; break;
        case 'payment_uploaded': $progressStep = 2; break;
        case 'payment_confirmed': $progressStep = 3; break;
        case 'processing': $progressStep = 3; break;
        case 'shipped': $progressStep = 4; break;
        case 'in_transit': $progressStep = 4; break;
        case 'out_for_delivery': $progressStep = 4; break;
        case 'delivered': $progressStep = 5; break;
    }
}
?>

<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <a href="/account" style="text-decoration: none; color: var(--accent);">Institutional Portal</a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 900;">Order Registry #<?= htmlspecialchars($order['order_number']) ?></span>
  </div>

  <div style="max-width: 1200px; margin: 0 auto 120px;">
    <!-- Order Lifecycle Header -->
    <header style="margin-bottom: 80px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end;">
      <div>
        <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Procurement Registry</div>
        <h1 style="font-size: 4rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -2px; line-height: 1;">Asset #<?= htmlspecialchars($order['order_number']) ?></h1>
        <p style="color: var(--text-muted); font-size: 1.25rem; margin-top: 16px; font-weight: 500;">
          Initialized on <?= date('F d, Y', strtotime($order['created_at'])) ?>
        </p>
      </div>
      <div style="text-align: right;">
        <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Registry Status</div>
        <span style="display: inline-block; padding: 12px 32px; background: var(--accent); color: white; border-radius: 8px; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2);">
          <?= get_status_label($order['status']) ?>
        </span>
      </div>
    </header>
    
    <!-- Status Intelligence Banner -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 60px;">
      <div style="padding: 60px; text-align: center; background: #f8fafc; border-bottom: 1px solid #f1f5f9; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; left: -20px; font-size: 15rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif; pointer-events: none;">LIVE</div>
        <p style="font-size: 1.5rem; font-weight: 600; color: var(--primary); max-width: 900px; margin: 0 auto; line-height: 1.6; position: relative; z-index: 1;">
          <?php
          if ($isSoftwareOrder) {
              switch ($order['status']) {
                  case 'awaiting_payment': echo '💻 INTERDISCIPLINARY SOFTWARE: Institutional payment protocol required to initialize license activation.'; break;
                  case 'payment_uploaded': echo '💻 VERIFICATION: Your interdisciplinary payment receipt is currently undergoing technical review.'; break;
                  case 'payment_confirmed': case 'processing': echo '💻 AUTHENTICATION: Payment validated. Our engineering team is preparing your dedicated license credentials.'; break;
                  case 'license_sent': case 'delivered': echo '💻 DEPLOYED: Software license has been dispatched. Refer to technical documentation sent to your registered email.'; break;
                  default: echo '💻 Asset is currently navigating the procurement lifecycle.';
              }
          } else {
              switch ($order['status']) {
                  case 'awaiting_payment': echo 'INDUSTRIAL HARDWARE: Institutional payment protocol required to authorize logistics dispatch.'; break;
                  case 'payment_uploaded': echo 'VERIFICATION: Payment receipt received. Institutional audit in progress (Estimated: 1-2 business days).'; break;
                  case 'payment_confirmed': echo 'AUTHENTICATION: Payment validated. Assets are being staged for interdisciplinary logistics.'; break;
                  case 'shipped': case 'in_transit': echo 'LOGISTICS: Assets have exited the production facility and are currently in transit.'; break;
                  case 'out_for_delivery': echo 'LOGISTICS: Assets are scheduled for arrival at the designated facility today.'; break;
                  case 'delivered': echo 'PROCUREMENT FINALIZED: Assets successfully delivered. Thank you for your continued partnership.'; break;
                  default: echo 'Asset is currently navigating the procurement lifecycle.';
              }
          }
          ?>
        </p>
      </div>
      
      <!-- Lifecycle Progress Protocol -->
      <div style="padding: 80px 60px;">
        <div class="tracking-progress-modern" style="display: flex; justify-content: space-between; position: relative; max-width: 1000px; margin: 0 auto;">
          <!-- Progress Line -->
          <div style="position: absolute; top: 32px; left: 0; width: 100%; height: 6px; background: #f1f5f9; z-index: 0; border-radius: 3px;"></div>
          <?php 
          $steps = $isSoftwareOrder 
            ? [['📝', 'Placed'], ['💳', 'Verified'], ['✅', 'Confirmed'], ['💻', 'Deployed']]
            : [['📝', 'Placed'], ['💳', 'Verified'], ['✅', 'Confirmed'], ['🚚', 'Logistics'], ['📦', 'Finalized']];
          $totalSteps = count($steps);
          $fillWidth = (($progressStep - 1) / ($totalSteps - 1)) * 100;
          ?>
          <div style="position: absolute; top: 32px; left: 0; width: <?= $fillWidth ?>%; height: 6px; background: var(--accent); z-index: 1; transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 3px;"></div>
          
          <?php foreach ($steps as $i => $step): $idx = $i + 1; ?>
          <div style="position: relative; z-index: 2; text-align: center; width: 120px;">
            <div style="width: 70px; height: 70px; background: <?= $progressStep >= $idx ? 'var(--accent)' : 'white' ?>; color: <?= $progressStep >= $idx ? 'white' : 'var(--text-muted)' ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 24px; border: 4px solid <?= $progressStep >= $idx ? 'var(--accent)' : '#f1f5f9' ?>; box-shadow: <?= $progressStep >= $idx ? '0 10px 20px rgba(220, 38, 38, 0.2)' : 'var(--shadow-sm)' ?>; transition: all 0.4s; transform: <?= $progressStep == $idx ? 'scale(1.15)' : 'scale(1)' ?>;">
              <?= $step[0] ?>
            </div>
            <div style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: <?= $progressStep >= $idx ? 'var(--primary)' : 'var(--text-muted)' ?>;">
              <?= $step[1] ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    
    <!-- Urgent Intervention Protocol -->
    <?php if ($order['status'] === 'awaiting_payment'): ?>
    <div style="background: white; border: 3px solid var(--accent); border-radius: var(--radius-lg); padding: 60px; margin-bottom: 60px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-xl); position: relative; overflow: hidden;">
      <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">!</div>
      <div style="display: flex; gap: 40px; align-items: center; position: relative; z-index: 1;">
        <div style="font-size: 4rem; animation: pulse-red 2s infinite;">⚠️</div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; color: var(--primary); margin: 0 0 8px 0; font-weight: 900; letter-spacing: -0.5px;">Institutional Action Required</h4>
          <p style="color: var(--text-muted); font-size: 1.25rem; margin: 0; font-weight: 500;">Payment verification protocol must be completed to authorize interdisciplinary procurement dispatch.</p>
        </div>
      </div>
      <a href="/order/<?= $order['id'] ?>/payment" class="btn-modern btn-accent" style="padding: 24px 48px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; position: relative; z-index: 1;">Upload Verification Receipt</a>
    </div>
    <?php endif; ?>
    
    <!-- Logistics Intelligence Matrix -->
    <?php if (!empty($shipments)): ?>
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 60px;">
      <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 24px;">
        <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">🚢</div>
        <div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Logistics Intelligence Registry</h3>
          <p style="font-size: 0.8rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 0 0;">Global Asset Tracking Matrix</p>
        </div>
      </div>
      <div style="padding: 60px;">
        <div style="display: grid; gap: 32px;">
          <?php foreach ($shipments as $shipment): ?>
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 40px; background: white; border: 2px solid #f1f5f9; border-radius: 12px; transition: all 0.3s;" class="hover-lift">
            <div style="display: flex; gap: 40px; align-items: center;">
              <div style="font-size: 3rem;">📦</div>
              <div>
                <div style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 8px;">Logistics Deployment Partner</div>
                <div style="font-weight: 900; color: var(--primary); font-size: 1.75rem; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;"><?= htmlspecialchars($shipment['carrier']) ?></div>
                <div style="font-family: 'Outfit', sans-serif; color: var(--accent); font-weight: 900; margin-top: 8px; font-size: 1.1rem; letter-spacing: 1px;">ID: <?= htmlspecialchars($shipment['tracking_number']) ?></div>
              </div>
            </div>
            <a href="/track?tracking=<?= urlencode($shipment['tracking_number']) ?>" class="btn-modern btn-accent" style="padding: 20px 40px; font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Access Live Matrix</a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 450px; gap: 60px; margin-bottom: 60px; align-items: start;">
      <!-- Asset Inventory Matrix -->
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Institutional Asset Inventory</h3>
        </div>
        <div style="padding: 0;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                <th style="padding: 24px 60px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 900;">Specification</th>
                <th style="padding: 24px 60px; text-align: right; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 900;">Valuation</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
              <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                <td style="padding: 32px 60px;">
                  <div style="font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; font-size: 1.15rem; margin-bottom: 4px;"><?= htmlspecialchars($item['sku']) ?></div>
                  <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 700;">Operational Units: <?= (int)$item['qty'] ?> Registry Items</div>
                </td>
                <td style="padding: 32px 60px; text-align: right; font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;"><?= format_price((float)$item['total']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr style="background: #f8fafc; font-weight: 900;">
                <td style="padding: 40px 60px; font-size: 1.25rem; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">Institutional Investment Total</td>
                <td style="padding: 40px 60px; text-align: right; font-size: 2rem; color: var(--accent); font-family: 'Outfit', sans-serif; letter-spacing: -1px;"><?= format_price((float)$order['total']) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      
      <!-- Logistics Destination Registry -->
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; position: sticky; top: 120px;">
        <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 20px;">
          <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: white;">📍</div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Dispatch Destination</h3>
        </div>
        <div style="padding: 60px; line-height: 1.8;">
          <div style="font-weight: 900; color: var(--accent); font-size: 0.85rem; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 3px; border-bottom: 3px solid var(--accent); display: inline-block; padding-bottom: 8px;">Official Registry Entity</div>
          <div style="color: var(--text-main); font-weight: 600; font-size: 1.2rem; font-family: 'Outfit', sans-serif;">
            <div style="font-weight: 900; color: var(--primary); margin-bottom: 8px; font-size: 1.4rem;"><?= htmlspecialchars($shippingAddress['company'] ?? $billingAddress['company'] ?? '') ?></div>
            <?= htmlspecialchars($shippingAddress['name'] ?? $billingAddress['name'] ?? '') ?><br>
            <?= htmlspecialchars($shippingAddress['address'] ?? $billingAddress['address'] ?? '') ?><br>
            <?= htmlspecialchars(($shippingAddress['zip'] ?? $billingAddress['zip'] ?? '') . ' ' . ($shippingAddress['city'] ?? $billingAddress['city'] ?? '')) ?><br>
            <div style="text-transform: uppercase; letter-spacing: 2px; font-weight: 900; color: var(--accent); margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 16px;"><?= htmlspecialchars($shippingAddress['country'] ?? $billingAddress['country'] ?? '') ?></div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Support Infrastructure Portal -->
    <div style="background: var(--primary); border-radius: var(--radius-lg); padding: 80px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.4); position: relative; overflow: hidden;">
      <div style="position: absolute; top: -50px; left: -50px; font-size: 20rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">HLP</div>
      <div style="position: relative; z-index: 1;">
        <h4 style="font-family: 'Outfit', sans-serif; font-size: 2.25rem; margin: 0 0 16px 0; font-weight: 900; color: white; letter-spacing: -1px;">Technical Support Required?</h4>
        <p style="margin: 0; opacity: 0.7; font-size: 1.35rem; font-weight: 500; letter-spacing: -0.2px;">Our interdisciplinary engineering team is available for procurement inquiries.</p>
      </div>
      <div style="display: flex; gap: 32px; position: relative; z-index: 1;">
        <a href="mailto:support@streicher.de" class="btn-modern" style="padding: 24px 48px; background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.2); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Institutional Email</a>
        <a href="/contact" class="btn-modern btn-accent" style="padding: 24px 48px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Global Contact Matrix</a>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes pulse-red {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}
</style>
