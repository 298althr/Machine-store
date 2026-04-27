<div class="admin-header">
  <div>
    <h1 style="margin: 0;">Activation Requests</h1>
    <p style="margin: 4px 0 0 0; color: #64748b;">View and manage software activation requests</p>
  </div>
  <div>
    <a href="/admin/software-activation" class="btn btn-outline">← Back to Dashboard</a>
  </div>
</div>

<!-- Filters -->
<div class="card mb-4">
  <div class="card-header">
    <h3 class="card-title">Filters</h3>
  </div>
  <div class="card-body">
    <form method="GET" action="/admin/software-activation/requests">
      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="">All Statuses</option>
            <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="processing" <?= ($_GET['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Processing</option>
            <option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
          </select>
        </div>
        <div>
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-control">
            <option value="">All Payment Statuses</option>
            <option value="pending" <?= ($_GET['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= ($_GET['payment_status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="rejected" <?= ($_GET['payment_status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
          </select>
        </div>
        <div>
          <label class="form-label">Payment Method</label>
          <select name="payment_method" class="form-control">
            <option value="">All Methods</option>
            <option value="credit_card" <?= ($_GET['payment_method'] ?? '') === 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
            <option value="google_play" <?= ($_GET['payment_method'] ?? '') === 'google_play' ? 'selected' : '' ?>>Google Play</option>
          </select>
        </div>
        <div>
          <button type="submit" class="btn btn-primary">Apply Filters</button>
          <a href="/admin/software-activation/requests" class="btn btn-outline">Clear</a>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Activations List -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Activation Requests</h3>
    <div>
      <span class="badge" style="background: #3b82f6; color: white;"><?= count($activations) ?> requests</span>
    </div>
  </div>
  <div style="overflow-x: auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>Token</th>
          <th>Customer</th>
          <th>Product</th>
          <th>Amount</th>
          <th>Payment</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($activations as $activation): ?>
        <tr>
          <td>
            <div style="font-family: monospace; font-size: 0.8rem; color: #64748b;"><?= substr($activation['activation_token'], 0, 12) ?>...</div>
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
          </td>
          <td>
            <div style="font-weight: 500;"><?= ucfirst($activation['payment_method']) ?></div>
            <div style="font-size: 0.8rem; color: #64748b;"><?= ucfirst($activation['payment_status']) ?></div>
          </td>
          <td>
            <span class="order-status-badge status-<?= str_replace('_', '-', $activation['status']) ?>"><?= ucfirst($activation['status']) ?></span>
          </td>
          <td>
            <div style="font-size: 0.8rem;"><?= date('M j, Y', strtotime($activation['created_at'])) ?></div>
            <div style="font-size: 0.75rem; color: #64748b;"><?= date('H:i', strtotime($activation['created_at'])) ?></div>
          </td>
          <td>
            <a href="/admin/software-activation/activation/<?= $activation['id'] ?>" class="btn btn-sm btn-primary">View</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($activations)): ?>
    <div style="text-align: center; padding: 2rem; color: #64748b;">No activation requests found matching your filters.</div>
    <?php endif; ?>
  </div>
</div>
