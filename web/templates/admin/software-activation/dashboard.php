<div class="admin-header">
  <div>
    <h1 style="margin: 0;">Software Activation</h1>
    <p style="margin: 4px 0 0 0; color: #64748b;">Manage software license activations and payments</p>
  </div>
  <div>
    <a href="/admin/software-activation/requests" class="btn btn-primary">
      Review Pending Payments (<?= (int)$stats['pending_payments'] ?>)
    </a>
  </div>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-value"><?= number_format((int)$stats['total_activations']) ?></div>
    <div class="stat-label">Total Activations</div>
  </div>
  <div class="stat-card">
    <div class="stat-value"><?= (int)$stats['pending_payments'] ?></div>
    <div class="stat-label">Pending Payments</div>
    <?php if ($stats['pending_payments'] > 0): ?>
    <div class="stat-change" style="color: #ca8a04;">Requires attention</div>
    <?php endif; ?>
  </div>
  <div class="stat-card">
    <div class="stat-value"><?= (int)$stats['processing_activations'] ?></div>
    <div class="stat-label">Processing</div>
  </div>
  <div class="stat-card">
    <div class="stat-value">003XXXXXX</div>
    <div class="stat-label">License Format</div>
  </div>
</div>

<!-- Pending Payments Alert -->
<?php if ($stats['pending_payments'] > 0): ?>
<div class="alert alert-warning mb-4">
  <div class="alert-title">⚠️ Payments Awaiting Verification</div>
  <p style="margin: 4px 0 0 0;">
    <?= $stats['pending_payments'] ?> activation(s) have payments that need verification.
    <a href="/admin/software-activation/requests" style="margin-left: 8px;">Review Now →</a>
  </p>
</div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
  <!-- Recent Activations -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Recent Activations</h3>
      <a href="/admin/software-activation/requests" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div style="overflow-x: auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Token</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Payment</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recent_activations as $activation): ?>
          <tr>
            <td>
              <div style="font-family: monospace; font-size: 0.8rem; color: #64748b;">
                <?= substr($activation['activation_token'], 0, 8) ?>...
              </div>
            </td>
            <td>
              <div style="font-weight: 500;"><?= htmlspecialchars($activation['customer_email']) ?></div>
              <?php if ($activation['customer_name']): ?>
              <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($activation['customer_name']) ?></div>
              <?php endif; ?>
            </td>
            <td>
              <div style="font-weight: 500;"><?= htmlspecialchars($activation['product_name']) ?></div>
            </td>
            <td>
              <div style="font-weight: 600;"><?= format_price((float)$activation['amount'], $activation['currency']) ?></div>
              <div style="font-size: 0.8rem; color: #64748b;"><?= ucfirst($activation['payment_method']) ?></div>
            </td>
            <td>
              <span class="order-status-badge status-<?= str_replace('_', '-', $activation['status']) ?>">
                <?= ucfirst($activation['status']) ?>
              </span>
            </td>
            <td>
              <a href="/admin/software-activation/activation/<?= $activation['id'] ?>" class="btn btn-sm btn-outline">View</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Quick Actions -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
      </div>
      <div class="card-body">
        <a href="/admin/software-activation/requests?payment_status=pending" class="btn btn-block btn-primary mb-2">
          💳 Review Payments
        </a>
        <a href="/admin/software-activation/requests?status=processing" class="btn btn-block btn-success mb-2">
          🔑 Process Activations
        </a>
        <a href="/admin/software-activation/licenses" class="btn btn-block btn-outline mb-2">
          🔢 Manage Licenses
        </a>
        <a href="/admin/software-activation/products" class="btn btn-block btn-outline">
          📦 Software Products
        </a>
      </div>
    </div>
    
    <!-- Status Summary -->
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">Status Summary</h3>
      </div>
      <div class="card-body">
        <?php
        $statusCounts = [
          'pending' => 0,
          'processing' => 0,
          'approved' => 0,
          'rejected' => 0,
          'completed' => 0,
        ];
        foreach ($recent_activations as $a) {
          if (isset($statusCounts[$a['status']])) {
            $statusCounts[$a['status']]++;
          }
        }
        ?>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <a href="/admin/software-activation/requests?status=pending" style="display: flex; justify-content: space-between; text-decoration: none; color: inherit;">
            <span>Pending</span>
            <span class="order-status-badge status-pending"><?= $statusCounts['pending'] ?></span>
          </a>
          <a href="/admin/software-activation/requests?status=processing" style="display: flex; justify-content: space-between; text-decoration: none; color: inherit;">
            <span>Processing</span>
            <span class="order-status-badge status-processing"><?= $statusCounts['processing'] ?></span>
          </a>
          <a href="/admin/software-activation/requests?status=approved" style="display: flex; justify-content: space-between; text-decoration: none; color: inherit;">
            <span>Approved</span>
            <span class="order-status-badge status-approved"><?= $statusCounts['approved'] ?></span>
          </a>
          <a href="/admin/software-activation/requests?status=completed" style="display: flex; justify-content: space-between; text-decoration: none; color: inherit;">
            <span>Completed</span>
            <span class="order-status-badge status-completed"><?= $statusCounts['completed'] ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
