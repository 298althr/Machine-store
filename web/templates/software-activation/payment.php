<div class="page-header">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
      <h1>Choose Payment Method</h1>
      <p>Select how you would like to pay for your software activation</p>
    </div>
    <div>
      <?php include __DIR__ . '/../components/currency-toggle.php'; ?>
    </div>
  </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <div class="card-header">
    <h2 class="card-title">Payment Details</h2>
  </div>
  <div class="card-body">
    <div class="order-summary" style="background: #f9fafb; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
      <h3 style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600;">Order Summary</h3>
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <div style="font-weight: 500;"><?= htmlspecialchars($activation['product_name']) ?></div>
          <div style="font-size: 0.875rem; color: #6b7280;">Software License Activation</div>
        </div>
        <div style="font-size: 1.25rem; font-weight: 700; color: #111827;">
          <?php 
          $current_currency = $_SESSION['display_currency'] ?? 'EUR';
          $display_amount = $activation['currency'] === $current_currency ? 
              (float)$activation['amount'] : 
              convert_price((float)$activation['amount'], $current_currency);
          echo format_price($display_amount, $current_currency);
          ?>
        </div>
      </div>
    </div>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="/software-activation/payment/<?= htmlspecialchars($activation['activation_token']) ?>">
      <div class="payment-methods">
        <div class="payment-option">
          <input type="radio" id="credit_card" name="payment_method" value="credit_card" checked>
          <label for="credit_card" class="payment-label">
            <div class="payment-icon">💳</div>
            <div class="payment-info">
              <div class="payment-title">Credit Card</div>
              <div class="payment-description">Pay with credit/debit card. Manual verification by our team.</div>
            </div>
          </label>
        </div>
        
        <div class="payment-option">
          <input type="radio" id="google_play" name="payment_method" value="google_play">
          <label for="google_play" class="payment-label">
            <div class="payment-icon">
              <img src="/images/googleplay.webp" alt="Google Play" style="width: 48px; height: 48px; border-radius: 8px;">
            </div>
            <div class="payment-info">
              <div class="payment-title">Google Play Gift Card</div>
              <div class="payment-description">Upload photos of your gift card and receipt for verification.</div>
            </div>
          </label>
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block">Continue with Selected Payment</button>
        <a href="/software-activation" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">Back</a>
      </div>
    </form>
  </div>
</div>

<style>
.payment-methods {
  margin-bottom: 1.5rem;
}

.payment-option {
  margin-bottom: 1rem;
}

.payment-option input[type="radio"] {
  display: none;
}

.payment-label {
  display: flex;
  align-items: center;
  padding: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.15s ease-in-out;
}

.payment-option input[type="radio"]:checked + .payment-label {
  border-color: #3b82f6;
  background-color: #eff6ff;
}

.payment-label:hover {
  border-color: #9ca3af;
}

.payment-icon {
  font-size: 2rem;
  margin-right: 1rem;
}

.payment-info {
  flex: 1;
}

.payment-title {
  font-weight: 600;
  font-size: 1.125rem;
  color: #111827;
  margin-bottom: 0.25rem;
}

.payment-description {
  font-size: 0.875rem;
  color: #6b7280;
  line-height: 1.4;
}

.order-summary {
  border: 1px solid #e5e7eb;
}

.form-actions {
  margin-top: 2rem;
}

.alert {
  padding: 1rem;
  border-radius: 0.375rem;
  margin-bottom: 1rem;
}

.alert-danger {
  background-color: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
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
</style>
