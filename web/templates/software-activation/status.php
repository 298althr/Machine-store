<div class="page-header">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
      <h1>Activation Status</h1>
      <p>Check the status of your software activation</p>
    </div>
    <div>
      <?php include __DIR__ . '/../components/currency-toggle.php'; ?>
    </div>
  </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <div class="card-header">
    <h2 class="card-title">Activation Details</h2>
  </div>
  <div class="card-body">
    <div class="status-badge" style="text-align: center; margin-bottom: 1.5rem;">
      <?php
      $statusColors = [
          'pending' => '#6b7280',
          'processing' => '#d97706',
          'approved' => '#059669',
          'rejected' => '#dc2626',
          'completed' => '#059669'
      ];
      $statusIcons = [
          'pending' => '⏳',
          'processing' => '⚙️',
          'approved' => '✅',
          'rejected' => '❌',
          'completed' => '🎉'
      ];
      $statusColor = $statusColors[$activation['status']] ?? '#6b7280';
      $statusIcon = $statusIcons[$activation['status']] ?? '⏳';
      ?>
      <div style="
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: <?= $statusColor ?>20;
        color: <?= $statusColor ?>;
        border: 2px solid <?= $statusColor ?>;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 1.125rem;
      ">
        <?= $statusIcon ?> <?= ucfirst(htmlspecialchars($activation['status'])) ?>
      </div>
    </div>
    
    <div class="order-details" style="background: #f9fafb; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600;">Activation Information</h4>
      <div style="display: grid; gap: 0.5rem; font-size: 0.875rem;">
        <div style="display: flex; justify-content: space-between;">
          <span style="color: #6b7280;">Product:</span>
          <span style="font-weight: 500;"><?= htmlspecialchars($activation['product_name']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span style="color: #6b7280;">Email:</span>
          <span style="font-weight: 500;"><?= htmlspecialchars($activation['customer_email']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span style="color: #6b7280;">Amount:</span>
          <span style="font-weight: 500;">
            <?php 
            $current_currency = $_SESSION['display_currency'] ?? 'EUR';
            $display_amount = $activation['currency'] === $current_currency ? 
                (float)$activation['amount'] : 
                convert_price((float)$activation['amount'], $current_currency);
            echo format_price($display_amount, $current_currency);
            ?>
          </span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span style="color: #6b7280;">Payment Method:</span>
          <span style="font-weight: 500;"><?= ucfirst(htmlspecialchars($activation['payment_method'])) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span style="color: #6b7280;">Submitted:</span>
          <span style="font-weight: 500;"><?= date('M j, Y H:i', strtotime($activation['created_at'])) ?></span>
        </div>
      </div>
    </div>
    
    <?php if ($activation['status'] === 'approved' || $activation['status'] === 'completed'): ?>
    <div class="license-key" style="background: #ecfdf5; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #a7f3d0;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #059669;">🎉 Your License Key</h4>
      <p style="margin: 0 0 1rem 0; color: #059669; font-size: 0.875rem; line-height: 1.5;">
        Congratulations! Your activation has been approved. Use the license key below to activate your software:
      </p>
      <div style="display: flex; gap: 0.5rem; align-items: stretch;">
        <div style="flex: 1; background: white; padding: 1rem; border-radius: 0.25rem; font-family: monospace; font-size: 1.125rem; font-weight: 600; text-align: center; border: 1px solid #a7f3d0; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: center;">
          <?= htmlspecialchars($activation['license_key'] ?: '003' . str_pad((string)random_int(0, 999999999999), 12, '0', STR_PAD_LEFT)) ?>
        </div>
        <button onclick="copyToClipboard('<?= htmlspecialchars($activation['license_key'] ?: '003' . str_pad((string)random_int(0, 999999999999), 12, '0', STR_PAD_LEFT)) ?>', this)" style="padding: 0.75rem 1rem; background: #059669; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
          📋 Copy
        </button>
      </div>
      <p style="margin: 1rem 0 0 0; color: #059669; font-size: 0.75rem; text-align: center;">
        This license key has also been sent to your email address
      </p>
    </div>
    <?php endif; ?>
    
    <?php if ($activation['status'] === 'rejected'): ?>
    <div class="rejection-info" style="background: #fef2f2; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #dc2626;">❌ Activation Rejected</h4>
      <p style="margin: 0 0 0.75rem 0; color: #dc2626; font-size: 0.875rem; line-height: 1.5;">
        We were unable to approve your activation. Please review the reason below and contact support if needed.
      </p>
      <?php if ($activation['admin_notes']): ?>
      <div style="background: white; padding: 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; border: 1px solid #fecaca;">
        <strong>Reason:</strong> <?= htmlspecialchars($activation['admin_notes']) ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($activation['status'] === 'pending' || $activation['status'] === 'processing'): ?>
    <div class="processing-info" style="background: #fffbeb; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #fed7aa;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #d97706;">⏳ Still Processing</h4>
      <p style="margin: 0; color: #d97706; font-size: 0.875rem; line-height: 1.5;">
        Your activation is still being processed. Our team is carefully reviewing your payment information. You will receive an email notification once the process is complete.
      </p>
    </div>
    <?php endif; ?>
    
    <div class="status-reference" style="background: #eff6ff; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #bfdbfe;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #1e40af;">Status Reference</h4>
      <p style="margin: 0 0 0.75rem 0; color: #1e40af; font-size: 0.875rem; line-height: 1.5;">
        Save this reference token to check your status later:
      </p>
      <div style="background: white; padding: 0.75rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.75rem; word-break: break-all; border: 1px solid #dbeafe;">
        <?= htmlspecialchars($activation['activation_token']) ?>
      </div>
    </div>
    
    <div class="actions">
      <button onclick="window.location.reload()" class="btn btn-primary btn-block">
        🔄 Refresh Status
      </button>
      <a href="/software-activation" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">
        Start New Activation
      </a>
      <?php if ($activation['status'] === 'rejected'): ?>
      <a href="mailto:store@streichergmbh.com?subject=Activation Rejected - <?= htmlspecialchars($activation['activation_token']) ?>" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">
        📧 Contact Support
      </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  border: 1px solid transparent;
  border-radius: 0.375rem;
  font-size: 1rem;
  font-weight: 500;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.15s ease-in-out;
}

.btn-primary {
  background-color: #3b82f6;
  border-color: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background-color: #2563eb;
  border-color: #2563eb;
}

.btn-outline {
  background-color: transparent;
  border-color: #d1d5db;
  color: #374151;
}

.btn-outline:hover {
  background-color: #f9fafb;
  border-color: #9ca3af;
}

.btn-block {
  width: 100%;
}

.card {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  overflow: hidden;
}

.card-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  background-color: #f9fafb;
}

.card-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.card-body {
  padding: 1.5rem;
}

.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-header h1 {
  margin: 0 0 0.5rem 0;
  font-size: 2rem;
  font-weight: 700;
  color: #111827;
}

.page-header p {
  margin: 0;
  color: #6b7280;
  font-size: 1.125rem;
}

.actions {
  margin-top: 1.5rem;
}
</style>

<script>
// Auto-refresh every 30 seconds for pending/processing status
<?php if ($activation['status'] === 'pending' || $activation['status'] === 'processing'): ?>
setTimeout(() => {
    window.location.reload();
}, 30000);
<?php endif; ?>
</script>
