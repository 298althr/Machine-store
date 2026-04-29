<?php
$billingAddress = json_decode($order['billing_address'] ?? '{}', true) ?: [];
?>

<div class="container-modern section-padding order-confirmation-page">
  <div class="max-w-900 mx-auto text-center">
    <div class="mb-32 flex justify-center">
      <div class="p-32 bg-light border-radius-full shadow-lg">
        <i data-lucide="shield-check" size="80" class="text-success"></i>
      </div>
    </div>
    <h1 class="text-5xl font-black color-primary mb-16">Procurement Protocol Initialized</h1>
    <p class="text-xl text-muted mb-48 max-w-700 mx-auto font-medium leading-relaxed">
      Thank you for your institutional order. We have received your interdisciplinary payment verification and will begin formal authentication shortly.
    </p>
    
    <!-- Institutional Order Summary -->
    <div class="card-modern no-padding overflow-hidden mb-60 shadow-xl">
      <div class="p-48">
        <div class="grid grid-3 gap-40 text-center">
          <div class="border-right">
            <div class="text-xs font-black text-muted uppercase tracking-widest mb-12">Order ID</div>
            <div class="text-2xl font-black color-primary font-display">#<?= htmlspecialchars($order['order_number']) ?></div>
          </div>
          <div class="border-right">
            <div class="text-xs font-black text-muted uppercase tracking-widest mb-12">Investment</div>
            <div class="text-2xl font-black color-primary font-display"><?= format_price((float)$order['total']) ?></div>
          </div>
          <div>
            <div class="text-xs font-black text-muted uppercase tracking-widest mb-12">Status</div>
            <div>
              <?php render_component('badge', [
                'label' => 'PAYMENT VERIFYING',
                'variant' => 'success',
                'class' => 'px-16 py-8'
              ]); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Roadmap Protocol -->
    <div class="card-modern bg-light border-subtle p-60 mb-60">
      <h3 class="text-lg font-black color-primary mb-48 uppercase tracking-widest">Verification Roadmap</h3>
      <div class="grid grid-4 gap-32 text-center relative">
        <div class="roadmap-step">
          <div class="roadmap-icon completed">
            <i data-lucide="check"></i>
          </div>
          <div class="font-black color-primary text-sm mb-4">Receipt Received</div>
          <div class="text-xs text-success font-black uppercase tracking-widest">Completed</div>
        </div>
        <div class="roadmap-step">
          <div class="roadmap-icon active pulse">
            <i data-lucide="search"></i>
          </div>
          <div class="font-black color-primary text-sm mb-4">Institutional Review</div>
          <div class="text-xs text-muted font-bold uppercase tracking-widest">1-2 Business Days</div>
        </div>
        <div class="roadmap-step">
          <div class="roadmap-icon">
            <i data-lucide="package"></i>
          </div>
          <div class="font-black color-primary text-sm mb-4">Asset Preparation</div>
          <div class="text-xs text-muted font-bold uppercase tracking-widest">Pending</div>
        </div>
        <div class="roadmap-step">
          <div class="roadmap-icon">
            <i data-lucide="truck"></i>
          </div>
          <div class="font-black color-primary text-sm mb-4">Global Dispatch</div>
          <div class="text-xs text-muted font-bold uppercase tracking-widest">Pending</div>
        </div>
      </div>
    </div>
    
    <div class="card-modern border-accent border-2 p-40 text-left flex items-start gap-32 bg-white shadow-md">
      <div class="p-12 bg-light border-radius-md text-accent">
        <i data-lucide="mail" size="32"></i>
      </div>
      <div>
        <h4 class="text-lg font-black color-primary mb-8">Confirmation Protocol Sent</h4>
        <p class="text-muted text-lg mb-0 leading-relaxed font-medium">
          An institutional confirmation has been dispatched to <strong class="color-primary"><?= htmlspecialchars($billingAddress['email'] ?? 'the representative email') ?></strong>. You will receive a second technical notification once the payment verification process is finalized.
        </p>
      </div>
    </div>
    
    <div class="flex justify-center gap-24 mt-60">
      <?php render_component('button', [
        'href' => '/order/' . $order['id'],
        'variant' => 'accent',
        'label' => 'View Order Status',
        'class' => 'px-48 py-20 text-lg uppercase tracking-wider font-black',
        'icon' => 'eye'
      ]); ?>
      <?php render_component('button', [
        'href' => '/catalog',
        'variant' => 'outline',
        'label' => 'Procurement Catalog',
        'class' => 'px-48 py-20 text-lg uppercase tracking-wider font-black border-2',
        'icon' => 'package'
      ]); ?>
    </div>
  </div>
</div>

