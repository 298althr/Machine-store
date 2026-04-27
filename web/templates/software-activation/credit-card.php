<div class="page-header">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
      <h1>Credit Card Payment</h1>
      <p>Enter your credit card details for manual verification</p>
    </div>
    <div>
      <?php include __DIR__ . '/../components/currency-toggle.php'; ?>
    </div>
  </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <div class="card-header">
    <h2 class="card-title">Credit Card Information</h2>
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
    
    <div class="alert alert-info">
      <strong>Important:</strong> Your credit card information will be stored securely and processed manually by our team. You will receive an email with your activation status once payment is verified.
    </div>
    
    <form method="POST" 
          action="/software-activation/payment/credit-card/<?= htmlspecialchars($activation['activation_token']) ?>"
          autocomplete="off"
          style="display: flow-root;">
      <input type="hidden" name="payment_method" value="credit_card">
      <div style="display: none;">
        <input type="text" name="fakeusernameremembered">
        <input type="password" name="fakepasswordremembered">
      </div>
      <div class="form-group">
        <label for="cardholder_name" class="form-label">Cardholder Name *</label>
        <input type="text" id="cardholder_name" name="cardholder_name" class="form-control" 
               value="<?= htmlspecialchars($input['cardholder_name'] ?? '') ?>" 
               autocomplete="off" required>
      </div>
      
      <div class="form-group">
        <label for="card_number" class="form-label">Card Number *</label>
        <input type="text" id="card_number" name="card_number" class="form-control" 
               value="<?= htmlspecialchars($input['card_number'] ?? '') ?>" 
               placeholder="1234 5678 9012 3456" maxlength="19" 
               autocomplete="cc-number" required>
      </div>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div class="form-group">
          <label for="expiry_date" class="form-label">Expiry Date *</label>
          <input type="text" id="expiry_date" name="expiry_date" class="form-control" 
                   value="<?= htmlspecialchars($input['expiry_date'] ?? '') ?>" 
                   placeholder="MM/YY" maxlength="5" 
                   autocomplete="cc-exp" required>
        </div>
        
        <div class="form-group">
          <label for="cvv" class="form-label">CVV *</label>
          <input type="text" id="cvv" name="cvv" class="form-control" 
                   value="<?= htmlspecialchars($input['cvv'] ?? '') ?>" 
                   placeholder="123" maxlength="4" 
                   autocomplete="cc-csc" required>
        </div>
      </div>
      
      <div class="form-group">
        <label class="form-label">Billing Address</label>
        <input type="text" name="billing_address" class="form-control" 
               value="<?= htmlspecialchars($input['billing_address'] ?? '') ?>" 
               placeholder="Street Address" autocomplete="off">
      </div>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
        <div class="form-group">
          <input type="text" name="billing_city" class="form-control" 
                 value="<?= htmlspecialchars($input['billing_city'] ?? '') ?>" 
                 placeholder="City" autocomplete="off">
        </div>
        
        <div class="form-group">
          <input type="text" name="billing_country" class="form-control" 
                 value="<?= htmlspecialchars($input['billing_country'] ?? '') ?>" 
                 placeholder="Country" autocomplete="off">
        </div>
        
        <div class="form-group">
          <input type="text" name="billing_postal" class="form-control" 
                 value="<?= htmlspecialchars($input['billing_postal'] ?? '') ?>" 
                 placeholder="Postal Code" autocomplete="off">
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block">Submit Payment for Verification</button>
        <a href="/software-activation/payment/<?= htmlspecialchars($activation['activation_token']) ?>" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">Back to Payment Methods</a>
      </div>
    </form>
  </div>
</div>

<style>
.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #374151;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  box-sizing: border-box;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

.alert-info {
  background-color: #eff6ff;
  border: 1px solid #bfdbfe;
  color: #1e40af;
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

.order-summary {
  border: 1px solid #e5e7eb;
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

<script>
// Format card number as user types
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Format expiry date as MM/YY
document.getElementById('expiry_date').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});

// Only allow numbers for CVV
document.getElementById('cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});
</script>
