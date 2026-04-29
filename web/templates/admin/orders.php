<header class="admin-header">
  <div>
    <div class="admin-tag">Asset Registry</div>
    <h1 class="mb-0">Orders Management</h1>
    <p class="text-muted mt-12">
      <?php if ($currentStatus): ?>
        Showing Protocol: <span class="font-bold color-primary"><?= get_status_label($currentStatus) ?></span>
      <?php else: ?>
        Full Operational Registry
      <?php endif; ?>
    </p>
  </div>
</header>

<!-- Status Filter -->
<div class="card-modern mb-48">
  <div class="flex flex-wrap gap-12">
    <?php
    $statuses = [
      null => 'All',
      'awaiting_payment' => 'Awaiting Payment',
      'payment_pending_upload' => 'Payment Pending',
      'payment_uploaded' => 'Payment Uploaded',
      'payment_confirmed' => 'Ready to Ship',
      'shipped' => 'Shipped',
      'delivered' => 'Delivered'
    ];
    foreach ($statuses as $val => $label):
      $isActive = ($currentStatus === $val);
      render_component('button', [
        'href' => '/admin/orders' . ($val ? "?status={$val}" : ""),
        'variant' => $isActive ? 'accent' : 'outline',
        'size' => 'sm',
        'label' => $label
      ]);
    endforeach;
    ?>
  </div>
</div>

<!-- Orders Table -->
<div class="card-modern no-padding overflow-hidden">
  <?php if (empty($orders)): ?>
  <div class="text-center py-80">
    <div class="empty-icon mb-24 opacity-20">
      <i data-lucide="package-search" size="64"></i>
    </div>
    <h3>No Registry Entries</h3>
    <p class="text-muted">
      <?php if ($currentStatus): ?>
        No orders with status "<?= get_status_label($currentStatus) ?>".
      <?php else: ?>
        The operational registry is currently empty.
      <?php endif; ?>
    </p>
  </div>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Registry ID</th>
          <th>Timestamp</th>
          <th>Institutional Entity</th>
          <th>Settlement</th>
          <th>Protocol Status</th>
          <th class="text-right">Operations</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): 
          $billing = json_decode($order['billing_address'] ?? '{}', true) ?: [];
        ?>
        <tr>
          <td class="font-bold color-primary"><?= htmlspecialchars($order['order_number']) ?></td>
          <td class="text-muted">
            <div class="font-bold"><?= date('M j, Y', strtotime($order['created_at'])) ?></div>
            <div class="text-xs"><?= date('g:i A', strtotime($order['created_at'])) ?></div>
          </td>
          <td>
            <div class="font-bold color-primary"><?= htmlspecialchars($billing['company'] ?? $billing['name'] ?? 'N/A') ?></div>
            <div class="text-xs text-muted font-bold"><?= htmlspecialchars($billing['email'] ?? '') ?></div>
          </td>
          <td class="font-bold"><?= format_price((float)$order['total']) ?></td>
          <td>
            <?php 
              $variant = 'info';
              if ($order['status'] === 'delivered') $variant = 'success';
              if ($order['status'] === 'payment_uploaded') $variant = 'warning';
              if ($order['status'] === 'payment_declined') $variant = 'error';
              render_component('badge', [
                'label' => get_status_label($order['status']),
                'variant' => $variant
              ]); 
            ?>
          </td>
          <td class="text-right">
            <div class="flex flex-end gap-8">
              <?php render_component('button', [
                'href' => '/admin/orders/' . $order['id'],
                'variant' => 'outline',
                'size' => 'sm',
                'label' => 'Analyze'
              ]); ?>
              
              <?php if ($order['status'] === 'payment_uploaded'): ?>
              <form action="/admin/orders/<?= $order['id'] ?>/confirm-payment" method="POST" class="inline">
                <?php render_component('button', [
                  'type' => 'submit',
                  'variant' => 'accent',
                  'size' => 'sm',
                  'label' => 'Confirm'
                ]); ?>
              </form>
              <?php elseif ($order['status'] === 'payment_confirmed'): ?>
                <?php render_component('button', [
                  'href' => '/admin/orders/' . $order['id'] . '#ship',
                  'variant' => 'accent',
                  'size' => 'sm',
                  'label' => 'Dispatch',
                  'icon' => 'truck'
                ]); ?>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
