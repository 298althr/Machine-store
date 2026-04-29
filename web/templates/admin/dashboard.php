<header class="admin-header">
  <div>
    <div class="admin-tag">Admin Dashboard</div>
    <h1 class="mb-0">Command Center</h1>
    <p class="text-muted mt-12">Authorized Session: <span class="font-bold color-primary"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span></p>
  </div>
  <div>
    <?php render_component('button', [
      'href' => '/admin/orders?status=payment_uploaded',
      'variant' => 'accent',
      'label' => "Review Payments ({$stats['pending_payments']})",
      'icon' => 'credit-card'
    ]); ?>
  </div>
</header>

<!-- Analytics Stats -->
<div class="grid grid-4 mb-48">
  <div class="stat-card card-modern">
    <div class="stat-label">Total Orders</div>
    <div class="stat-value"><?= number_format((int)$stats['total_orders']) ?></div>
    <div class="stat-icon-bg">ORD</div>
  </div>
  <div class="stat-card card-modern <?= $stats['pending_payments'] > 0 ? 'border-accent' : '' ?>">
    <div class="stat-label">Pending Verification</div>
    <div class="stat-value <?= $stats['pending_payments'] > 0 ? 'text-accent' : '' ?>"><?= (int)$stats['pending_payments'] ?></div>
    <?php if ($stats['pending_payments'] > 0): ?>
    <div class="badge badge-error mt-12">Action Required</div>
    <?php endif; ?>
    <div class="stat-icon-bg">PAY</div>
  </div>
  <div class="stat-card card-modern">
    <div class="stat-label">Total Revenue</div>
    <div class="stat-value"><?= format_price((float)$stats['total_revenue']) ?></div>
    <div class="stat-icon-bg">REV</div>
  </div>
  <div class="stat-card card-modern">
    <div class="stat-label">Active Products</div>
    <div class="stat-value"><?= number_format((int)$stats['total_products']) ?></div>
    <div class="stat-icon-bg">PRD</div>
  </div>
</div>

<?php if (!empty($pendingPayments)): ?>
<div class="alert-banner bg-primary color-white p-40 border-radius-lg mb-48 flex-between">
  <div class="flex items-center gap-24">
    <i data-lucide="credit-card" size="48" class="text-accent"></i>
    <div>
      <h4 class="color-white mb-8">Payment Verifications Pending</h4>
      <p class="opacity-80 mb-0"><span class="text-accent font-bold"><?= count($pendingPayments) ?> orders</span> are awaiting manual verification.</p>
    </div>
  </div>
  <?php render_component('button', [
    'href' => '/admin/orders?status=payment_uploaded',
    'variant' => 'accent',
    'label' => 'Start Review'
  ]); ?>
</div>
<?php endif; ?>

<div class="grid grid-sidebar">
  <!-- Recent Orders Table -->
  <div class="card-modern no-padding overflow-hidden">
    <div class="px-32 py-24 bg-light border-bottom flex-between">
      <h3 class="mb-0">Recent Orders</h3>
      <a href="/admin/orders" class="text-accent text-sm font-bold uppercase tracking-wider">View All</a>
    </div>
    <div class="overflow-x-auto">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentOrders as $order): ?>
          <tr>
            <td class="font-bold color-primary"><?= htmlspecialchars($order['order_number']) ?></td>
            <td class="text-muted"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
            <td class="font-bold"><?= format_price((float)$order['total']) ?></td>
            <td>
              <?php 
                $variant = 'info';
                if ($order['status'] === 'delivered') $variant = 'success';
                if ($order['status'] === 'payment_uploaded') $variant = 'warning';
                render_component('badge', [
                  'label' => get_status_label($order['status']),
                  'variant' => $variant
                ]); 
              ?>
            </td>
            <td class="text-right">
              <?php render_component('button', [
                'href' => '/admin/orders/' . $order['id'],
                'variant' => 'outline',
                'size' => 'sm',
                'label' => 'Details'
              ]); ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Sidebar Utility -->
  <aside class="grid gap-24">
    <div class="card-modern">
      <h4 class="mb-24">Quick Actions</h4>
      <div class="grid gap-16">
        <?php render_component('button', [
          'href' => '/admin/orders?status=payment_confirmed',
          'variant' => 'outline',
          'label' => 'Dispatch Assets',
          'icon' => 'package',
          'class' => 'w-full'
        ]); ?>
        <?php render_component('button', [
          'href' => '/admin/products/new',
          'variant' => 'outline',
          'label' => 'Add New Product',
          'icon' => 'plus-circle',
          'class' => 'w-full'
        ]); ?>
      </div>
    </div>
    
    <div class="card-modern">
      <h4 class="mb-24">Order Status</h4>
      <?php
      $statusCounts = [
        'awaiting_payment' => 0,
        'payment_uploaded' => 0,
        'payment_confirmed' => 0,
        'shipped' => 0,
      ];
      foreach ($recentOrders as $o) {
        if (isset($statusCounts[$o['status']])) $statusCounts[$o['status']]++;
      }
      ?>
      <div class="grid gap-12">
        <?php foreach ($statusCounts as $status => $count): ?>
        <a href="/admin/orders?status=<?= $status ?>" class="flex-between p-12 bg-light border-radius-sm hover-bg-subtle">
          <span class="text-sm font-bold text-muted uppercase tracking-wider"><?= str_replace('_', ' ', $status) ?></span>
          <span class="badge badge-info"><?= $count ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </aside>
</div>
