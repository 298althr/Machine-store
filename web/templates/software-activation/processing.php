<div class="page-header">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
      <h1>Processing Your Activation</h1>
      <p>Your payment is being verified by our team</p>
    </div>
    <div>
      <?php include __DIR__ . '/../components/currency-toggle.php'; ?>
    </div>
  </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <div class="card-header">
    <h2 class="card-title">Payment Verification in Progress</h2>
  </div>
  <div class="card-body">
    <div class="processing-modal" style="text-align: center; padding: 2rem 0;">
      <div class="spinner" style="
        width: 60px;
        height: 60px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
      "></div>
      
      <h3 style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 600; color: #111827;">
        Thank You for Your Payment!
      </h3>
      
      <p style="margin: 0 0 1.5rem 0; color: #6b7280; line-height: 1.6;">
        Your <?= htmlspecialchars($activation['payment_method'] === 'credit_card' ? 'credit card' : 'Google Play gift card') ?> payment has been received and is currently being verified by our team.
      </p>
    </div>
    
    <div class="order-details" style="background: #f9fafb; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600;">Activation Details</h4>
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
          <span style="color: #6b7280;">Status:</span>
          <span style="font-weight: 500; color: #d97706;">Processing</span>
        </div>
      </div>
    </div>
    
    <div class="next-steps" style="margin-bottom: 1.5rem;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600;">What Happens Next?</h4>
      <ol style="margin: 0; padding-left: 1.25rem; color: #6b7280; line-height: 1.6;">
        <li style="margin-bottom: 0.5rem;">Our team verifies your payment information</li>
        <li style="margin-bottom: 0.5rem;">You'll receive an email with the approval status</li>
        <li style="margin-bottom: 0.5rem;">If approved, your 16-digit license key will be sent via email</li>
        <li>Use the license key to activate your software</li>
      </ol>
    </div>
    
    <div class="timeframe-info" style="background: #fef3c7; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #fbbf24;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #92400e;">⏱️ Expected Verification Time</h4>
      <div style="color: #92400e; font-size: 0.875rem; line-height: 1.5;">
        <?php if ($activation['payment_method'] === 'credit_card'): ?>
          <strong>Credit Card Payments:</strong> Usually confirmed within <strong>up to 24 hours</strong>. Our team manually reviews each credit card transaction for security purposes.
        <?php else: ?>
          <strong>Google Play Gift Cards:</strong> Usually confirmed within <strong>about 1 hour</strong>. Gift card verification is typically faster than credit card processing.
        <?php endif; ?>
      </div>
      <div style="margin-top: 0.5rem; color: #92400e; font-size: 0.8rem;">
        You'll receive an email notification as soon as your payment is approved.
      </div>
    </div>
    
    <div class="status-check" style="background: #eff6ff; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #bfdbfe;">
      <h4 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #1e40af;">Check Your Status</h4>
      <p style="margin: 0 0 1rem 0; color: #1e40af; font-size: 0.875rem; line-height: 1.5;">
        You can check your activation status anytime using the link below. Save this token for future reference:
      </p>
      <div style="background: white; padding: 0.75rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.875rem; word-break: break-all; border: 1px solid #dbeafe;">
        <?= htmlspecialchars($activation['activation_token']) ?>
      </div>
    </div>
    
    <div class="actions">
      <a href="/software-activation/status/<?= htmlspecialchars($activation['activation_token']) ?>" class="btn btn-primary btn-block">
        Check Activation Status
      </a>
      <a href="/software-activation" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">
        Start New Activation
      </a>
    </div>
  </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

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
