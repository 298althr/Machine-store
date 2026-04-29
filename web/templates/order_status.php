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

<div class="container-modern section-padding order-status-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <a href="/account">Institutional Portal</a> 
    <span class="separator">/</span> 
    <span class="current">Order Registry #<?= htmlspecialchars($order['order_number']) ?></span>
  </div>

  <div class="max-w-1200 mx-auto mb-120">
    <!-- Order Lifecycle Header -->
    <header class="admin-header flex-between items-end mb-80 border-bottom pb-40">
      <div>
        <div class="tracking-tag">Procurement Registry</div>
        <h1 class="text-5xl font-black color-primary mb-0">Order #<?= htmlspecialchars($order['order_number']) ?></h1>
        <p class="text-muted text-xl mt-16 font-medium">
          Initialized on <?= date('F d, Y', strtotime($order['created_at'])) ?>
        </p>
      </div>
      <div class="text-right">
        <div class="text-xs font-black text-muted uppercase tracking-widest mb-8">Registry Status</div>
        <?php render_component('badge', [
          'label' => get_status_label($order['status']),
          'variant' => $order['status'] === 'delivered' ? 'success' : 'accent',
          'class' => 'text-lg px-32 py-12 shadow-lg'
        ]); ?>
      </div>
    </header>
    
    <!-- Status Intelligence Banner -->
    <div class="card-modern no-padding overflow-hidden mb-60 shadow-xl">
      <div class="p-60 text-center bg-light border-bottom relative overflow-hidden">
        <div class="absolute top--20 left--20 text-9xl opacity-02 font-black font-display pointer-events-none">LIVE</div>
        <p class="text-2xl font-semibold color-primary max-w-900 mx-auto leading-relaxed relative z-1">
          <?php
          if ($isSoftwareOrder) {
              switch ($order['status']) {
                  case 'awaiting_payment': echo 'INTERDISCIPLINARY SOFTWARE: Institutional payment protocol required to initialize license activation.'; break;
                  case 'payment_uploaded': echo 'VERIFICATION: Your interdisciplinary payment receipt is currently undergoing technical review.'; break;
                  case 'payment_confirmed': case 'processing': echo 'AUTHENTICATION: Payment validated. Our engineering team is preparing your dedicated license credentials.'; break;
                  case 'license_sent': case 'delivered': echo 'DEPLOYED: Software license has been dispatched. Refer to technical documentation sent to your registered email.'; break;
                  default: echo 'Asset is currently navigating the procurement lifecycle.';
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
      <div class="p-80 px-60">
        <div class="stepper-modern max-w-1000 mx-auto">
          <?php 
          $steps = $isSoftwareOrder 
            ? [['icon' => 'file-text', 'label' => 'Placed'], ['icon' => 'credit-card', 'label' => 'Verified'], ['icon' => 'check-circle', 'label' => 'Confirmed'], ['icon' => 'cpu', 'label' => 'Deployed']]
            : [['icon' => 'file-text', 'label' => 'Placed'], ['icon' => 'credit-card', 'label' => 'Verified'], ['icon' => 'check-circle', 'label' => 'Confirmed'], ['icon' => 'truck', 'label' => 'Logistics'], ['icon' => 'package', 'label' => 'Finalized']];
          $totalSteps = count($steps);
          ?>
          <?php foreach ($steps as $i => $step): $idx = $i + 1; 
            $isActive = $progressStep >= $idx;
          ?>
          <div class="step-item <?= $isActive ? 'active' : '' ?>">
            <div class="step-icon shadow-md">
              <i data-lucide="<?= $step['icon'] ?>"></i>
            </div>
            <div class="step-label">
              <?= $step['label'] ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    
    <!-- Urgent Intervention Protocol -->
    <?php if ($order['status'] === 'awaiting_payment'): ?>
    <div class="alert alert-error mb-60 p-60 border-radius-lg flex-between items-center shadow-xl relative overflow-hidden">
      <div class="absolute top--50 right--50 text-9xl opacity-05 font-black font-display">!</div>
      <div class="flex items-center gap-40 relative z-1">
        <div class="p-24 bg-white border-radius-full shadow-lg pulse-red">
          <i data-lucide="alert-triangle" size="64" class="text-accent"></i>
        </div>
        <div>
          <h4 class="text-2xl font-black color-primary mb-8">Institutional Action Required</h4>
          <p class="text-muted text-xl mb-0 font-medium">Payment verification protocol must be completed to authorize interdisciplinary procurement dispatch.</p>
        </div>
      </div>
      <?php render_component('button', [
        'href' => "/order/{$order['id']}/payment",
        'variant' => 'accent',
        'label' => 'Upload Verification Receipt',
        'class' => 'px-48 py-24 text-lg uppercase tracking-wider font-black relative z-1 shadow-lg'
      ]); ?>
    </div>
    <?php endif; ?>
    
    <!-- Logistics Intelligence Matrix -->
    <?php if (!empty($shipments)): ?>
    <div class="card-modern no-padding overflow-hidden mb-60 shadow-xl">
      <div class="px-40 py-24 bg-light border-bottom flex items-center gap-24">
        <div class="p-12 bg-primary color-white border-radius-md shadow-sm">
          <i data-lucide="truck" size="24"></i>
        </div>
        <div>
          <h3 class="text-xl font-black color-primary mb-0">Logistics Intelligence Registry</h3>
          <p class="text-xs font-black text-muted uppercase tracking-widest mt-4">Global Asset Tracking Matrix</p>
        </div>
      </div>
      <div class="p-60">
        <div class="grid gap-32">
          <?php foreach ($shipments as $shipment): ?>
          <div class="card-modern p-40 border-subtle hover-lift flex-between items-center bg-white shadow-sm">
            <div class="flex items-center gap-40">
              <div class="p-24 bg-light border-radius-lg">
                <i data-lucide="package" size="48" class="text-accent"></i>
              </div>
              <div>
                <div class="text-xs font-black text-muted uppercase tracking-widest mb-8">Logistics Deployment Partner</div>
                <div class="text-2xl font-black color-primary font-display"><?= htmlspecialchars($shipment['carrier']) ?></div>
                <div class="text-lg font-bold text-accent mt-8 font-mono tracking-wider">ID: <?= htmlspecialchars($shipment['tracking_number']) ?></div>
              </div>
            </div>
            <?php render_component('button', [
              'href' => "/track?tracking=" . urlencode($shipment['tracking_number']),
              'variant' => 'accent',
              'label' => 'Access Live Matrix',
              'class' => 'px-40 py-20 uppercase tracking-widest font-black',
              'icon' => 'activity'
            ]); ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <div class="grid grid-sidebar items-start mb-60">
      <!-- Asset Inventory Matrix -->
      <div class="card-modern no-padding overflow-hidden shadow-xl">
        <div class="px-40 py-24 bg-light border-bottom">
          <h3 class="text-lg font-black color-primary mb-0 uppercase tracking-widest">Institutional Asset Inventory</h3>
        </div>
        <div class="p-0">
          <table class="admin-table">
            <thead>
              <tr class="bg-light">
                <th class="px-60">Specification</th>
                <th class="px-60 text-right">Valuation</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
              <tr class="hover-bg-subtle transition">
                <td class="px-60 py-32">
                  <div class="text-lg font-black color-primary font-display mb-4"><?= htmlspecialchars($item['sku']) ?></div>
                  <div class="text-sm font-bold text-muted uppercase tracking-wider">Operational Units: <?= (int)$item['qty'] ?> Registry Items</div>
                </td>
                <td class="px-60 py-32 text-right text-xl font-black color-primary font-display"><?= format_price((float)$item['total']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr class="bg-light font-black border-top-thick">
                <td class="px-60 py-40 text-xl color-primary uppercase tracking-widest">Institutional Investment Total</td>
                <td class="px-60 py-40 text-right text-3xl color-accent font-display"><?= format_price((float)$order['total']) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      
      <!-- Logistics Destination Registry -->
      <div class="card-modern no-padding overflow-hidden shadow-xl sticky-top-120">
        <div class="px-40 py-24 bg-light border-bottom flex items-center gap-20">
          <div class="p-12 bg-primary color-white border-radius-md shadow-sm">
            <i data-lucide="map-pin" size="24"></i>
          </div>
          <h3 class="text-lg font-black color-primary mb-0">Dispatch Destination</h3>
        </div>
        <div class="p-60">
          <div class="text-xs font-black text-accent uppercase tracking-widest mb-24 border-bottom-thick inline-block pb-8">Official Registry Entity</div>
          <div class="color-primary font-bold text-lg leading-relaxed">
            <div class="text-xl font-black mb-8"><?= htmlspecialchars($shippingAddress['company'] ?? $billingAddress['company'] ?? '') ?></div>
            <?= htmlspecialchars($shippingAddress['name'] ?? $billingAddress['name'] ?? '') ?><br>
            <?= htmlspecialchars($shippingAddress['address'] ?? $billingAddress['address'] ?? '') ?><br>
            <span class="bg-light px-12 py-4 border-radius-sm inline-block mt-4"><?= htmlspecialchars(($shippingAddress['zip'] ?? $billingAddress['zip'] ?? '') . ' ' . ($shippingAddress['city'] ?? $billingAddress['city'] ?? '')) ?></span><br>
            <div class="text-xs font-black text-accent uppercase tracking-widest mt-24 pt-24 border-top border-subtle flex items-center gap-8">
              <i data-lucide="globe" size="14"></i>
              <?= htmlspecialchars($shippingAddress['country'] ?? $billingAddress['country'] ?? '') ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Support Infrastructure Portal -->
    <div class="help-section-modern bg-primary color-white p-80 flex-between items-center shadow-2xl border-radius-lg relative overflow-hidden">
      <div class="absolute top--50 left--50 text-9xl opacity-03 font-black font-display">HLP</div>
      <div class="relative z-1">
        <h4 class="text-4xl font-black color-white mb-16">Technical Support Required?</h4>
        <p class="opacity-70 text-xl font-medium max-w-600 mb-0">Our interdisciplinary engineering team is available for procurement inquiries.</p>
      </div>
      <div class="flex gap-32 relative z-1">
        <?php render_component('button', [
          'href' => "mailto:support@streicher.de",
          'variant' => 'outline',
          'label' => 'Institutional Email',
          'class' => 'px-48 py-24 font-black border-white/20 bg-white/10 hover-bg-white/20 text-white',
          'icon' => 'mail'
        ]); ?>
        <?php render_component('button', [
          'href' => '/contact',
          'variant' => 'accent',
          'label' => 'Global Contact Matrix',
          'class' => 'px-48 py-24 font-black shadow-lg',
          'icon' => 'globe'
        ]); ?>
      </div>
    </div>
  </div>
</div>

