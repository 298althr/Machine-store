<div class="page-header">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
      <h1>Google Play Gift Card Payment</h1>
      <p>Upload photos of your gift card and receipt for verification</p>
    </div>
    <div>
      <?php include __DIR__ . '/../components/currency-toggle.php'; ?>
    </div>
  </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <div class="card-header">
    <h2 class="card-title">Gift Card Information</h2>
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
    
    <div class="alert alert-warning">
      <strong>Important Requirements:</strong><br>
      • Upload clear photos of both the front and back of your Google Play gift card<br>
      • Include a clear photo of the purchase receipt<br>
      • Ensure all numbers and codes are clearly visible<br>
      • Photos must be in JPG or PNG format, maximum 10MB each<br>
      • This helps us verify the card was not stolen and is legitimate
    </div>
    
    <!-- Simple Gift Card Purchase Links -->
    <div style="text-align: center; margin: 1.5rem 0; padding: 1rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
      <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.75rem;">
        Need a Google Play gift card? Select to buy from:
      </div>
      <div style="display: flex; justify-content: center; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        <!-- Amazon -->
        <a href="https://www.amazon.com/s?k=google+play+gift+card+digital" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#fef3c7'; this.style.borderColor='#fbbf24';" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent';">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" fill="#ff9900"/>
            <path d="M12 2v20c5.16-1.26 9-6.45 9-12V7l-9-4.5z" fill="#ff6200"/>
            <text x="12" y="16" text-anchor="middle" fill="white" font-size="8" font-weight="bold">amazon</text>
          </svg>
          <span style="font-weight: 500;">Amazon</span>
        </a>
        
        <!-- Target -->
        <a href="https://www.target.com/s?searchTerm=google+play+gift+card+email+delivery" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#fef3c7'; this.style.borderColor='#fbbf24';" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent';">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="12" r="10" fill="#cc0000"/>
            <circle cx="12" cy="12" r="6" fill="white"/>
            <circle cx="12" cy="12" r="3" fill="#cc0000"/>
          </svg>
          <span style="font-weight: 500;">Target</span>
        </a>
        
        <!-- PayPal -->
        <a href="https://www.paypal.com/us/gifts/brands/google-play" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#fef3c7'; this.style.borderColor='#fbbf24';" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent';">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <rect x="2" y="8" width="20" height="8" rx="1" fill="#003087"/>
            <text x="12" y="14" text-anchor="middle" fill="white" font-size="6" font-weight="bold">PayPal</text>
          </svg>
          <span style="font-weight: 500;">PayPal</span>
        </a>
        
        <!-- CardDelivery -->
        <a href="https://www.carddelivery.com/google-play" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: all 0.2s ease; border: 1px solid transparent;" onmouseover="this.style.background='#fef3c7'; this.style.borderColor='#fbbf24';" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent';">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="6" width="18" height="12" rx="2" fill="#10b981"/>
            <rect x="6" y="9" width="12" height="1" fill="white"/>
            <rect x="6" y="11" width="8" height="1" fill="white"/>
            <rect x="6" y="13" width="10" height="1" fill="white"/>
          </svg>
          <span style="font-weight: 500;">CardDelivery</span>
        </a>
      </div>
    </div>
    
    <form method="POST" action="/software-activation/payment/google-play/<?= htmlspecialchars($activation['activation_token']) ?>" enctype="multipart/form-data" id="google-play-form">
      <div class="form-group">
        <label class="form-label">Total Required Amount</label>
        <div style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">
          <?php 
          $current_currency = $_SESSION['display_currency'] ?? 'EUR';
          $display_amount = $activation['currency'] === $current_currency ? 
              (float)$activation['amount'] : 
              convert_price((float)$activation['amount'], $current_currency);
          echo format_price($display_amount, $current_currency);
          ?>
        </div>
        <div class="form-text">Upload one or more Google Play gift cards to cover this amount</div>
      </div>
      
      <div id="cards-container">
        <!-- Card 1 (always visible) -->
        <div class="card-entry" data-card-index="0">
          <div class="card-header" style="background: #f8fafc; padding: 1rem; border-radius: 0.375rem 0.375rem 0 0; border: 1px solid #e5e7eb; border-bottom: none;">
            <h4 style="margin: 0; font-size: 1rem; color: #374151;">Gift Card #1</h4>
          </div>
          <div class="card-body" style="background: white; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0 0 0.375rem 0.375rem; margin-bottom: 1rem;">
            <div class="form-group">
              <label class="form-label">Gift Card Value (<?= htmlspecialchars($_SESSION['display_currency'] ?? 'EUR') ?>) *</label>
              <input type="number" name="card_values[]" class="form-control card-value" 
                     step="0.01" min="0" required onchange="updateTotal()">
            </div>
            
            <div class="form-group">
              <label class="form-label">Gift Card Photo *</label>
              <input type="file" name="card_images[]" class="form-control" 
                     accept="image/jpeg,image/jpg,image/png" required>
            </div>
            
            <div class="form-group">
              <label class="form-label">Purchase Receipt Photo *</label>
              <input type="file" name="receipt_images[]" class="form-control" 
                     accept="image/jpeg,image/jpg,image/png" required>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Total Amount Display -->
      <div class="form-group">
        <div style="background: #f0f9ff; padding: 1rem; border-radius: 0.375rem; border: 1px solid #0ea5e9;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 600; color: #0369a1;">Total Gift Card Value:</span>
            <span id="total-value" style="font-size: 1.25rem; font-weight: 700; color: #0369a1;">$0.00</span>
          </div>
          <div id="amount-status" style="margin-top: 0.5rem; font-size: 0.875rem;"></div>
        </div>
      </div>
      
      <!-- Add More Button -->
      <div class="form-group">
        <button type="button" id="add-card-btn" class="btn btn-outline" style="width: 100%;">
          + Add Another Gift Card
        </button>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block" id="submit-btn" disabled>
          Submit for Verification
        </button>
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

.form-control[type="file"] {
  padding: 0.5rem;
  cursor: pointer;
}

.form-text {
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 0.25rem;
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

.alert-warning {
  background-color: #fffbeb;
  border: 1px solid #fed7aa;
  color: #d97706;
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
let cardCount = 1;
const requiredAmount = <?= (float)$activation['amount'] ?>;
const originalCurrency = '<?= htmlspecialchars($activation['currency']) ?>';
const currentCurrency = '<?= htmlspecialchars($_SESSION['display_currency'] ?? 'EUR') ?>';
const convertedRequiredAmount = originalCurrency === currentCurrency ? requiredAmount : <?= convert_price((float)$activation['amount'], $_SESSION['display_currency'] ?? 'EUR') ?>;

function updateTotal() {
  const cardValues = document.querySelectorAll('.card-value');
  let total = 0;
  
  cardValues.forEach(input => {
    const value = parseFloat(input.value) || 0;
    total += value;
  });
  
  const totalElement = document.getElementById('total-value');
  const statusElement = document.getElementById('amount-status');
  const submitBtn = document.getElementById('submit-btn');
  
  totalElement.textContent = formatCurrency(total);
  
  if (total >= convertedRequiredAmount) {
    statusElement.innerHTML = `<span style="color: #059669;">✅ Amount covered. You have ${formatCurrency(total - convertedRequiredAmount)} extra.</span>`;
    submitBtn.disabled = false;
  } else {
    statusElement.innerHTML = `<span style="color: #dc2626;">❌ Need ${formatCurrency(convertedRequiredAmount - total)} more.</span>`;
    submitBtn.disabled = true;
  }
}

function formatCurrency(amount) {
  return currentCurrency === 'USD' ? '$' + amount.toFixed(2) : '€' + amount.toFixed(2);
}

function addCard() {
  cardCount++;
  const container = document.getElementById('cards-container');
  
  const cardEntry = document.createElement('div');
  cardEntry.className = 'card-entry';
  cardEntry.dataset.cardIndex = cardCount - 1;
  
  cardEntry.innerHTML = `
    <div class="card-header" style="background: #f8fafc; padding: 1rem; border-radius: 0.375rem 0.375rem 0 0; border: 1px solid #e5e7eb; border-bottom: none; position: relative;">
      <h4 style="margin: 0; font-size: 1rem; color: #374151;">Gift Card #${cardCount}</h4>
      <button type="button" onclick="removeCard(this)" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: #ef4444; color: white; border: none; border-radius: 0.25rem; padding: 0.25rem 0.5rem; cursor: pointer; font-size: 0.75rem;">Remove</button>
    </div>
    <div class="card-body" style="background: white; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0 0 0.375rem 0.375rem; margin-bottom: 1rem;">
      <div class="form-group">
        <label class="form-label">Gift Card Value (${currentCurrency}) *</label>
        <input type="number" name="card_values[]" class="form-control card-value" 
               step="0.01" min="0" required onchange="updateTotal()">
      </div>
      
      <div class="form-group">
        <label class="form-label">Gift Card Photo *</label>
        <input type="file" name="card_images[]" class="form-control" 
               accept="image/jpeg,image/jpg,image/png" required>
      </div>
      
      <div class="form-group">
        <label class="form-label">Purchase Receipt Photo *</label>
        <input type="file" name="receipt_images[]" class="form-control" 
               accept="image/jpeg,image/jpg,image/png" required>
      </div>
    </div>
  `;
  
  container.appendChild(cardEntry);
  updateTotal();
}

function removeCard(button) {
  const cardEntry = button.closest('.card-entry');
  cardEntry.remove();
  updateTotal();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('add-card-btn').addEventListener('click', addCard);
  updateTotal();
});
</script>

<script>
// File size validation
document.getElementById('card_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.size > 10 * 1024 * 1024) { // 10MB limit
        alert('File size must be less than 10MB');
        e.target.value = '';
    }
});

document.getElementById('receipt_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.size > 10 * 1024 * 1024) { // 10MB limit
        alert('File size must be less than 10MB');
        e.target.value = '';
    }
});
</script>
