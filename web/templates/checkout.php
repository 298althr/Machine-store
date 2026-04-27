<div class="breadcrumb">
  <a href="/">Home</a> <span>/</span>
  <a href="/cart">Cart</a> <span>/</span>
  <span>Checkout</span>
</div>

<div class="page-header">
  <h1 class="page-title">Checkout</h1>
  <p class="page-subtitle">Complete your order</p>
</div>

<form action="/checkout" method="POST">
  <div class="checkout-grid">
    <!-- Checkout Form -->
    <div>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Billing & Shipping Information</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Company Name *</label>
            <input type="text" name="company" class="form-control" required placeholder="Your Company GmbH">
          </div>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label">Contact Name *</label>
              <input type="text" name="name" class="form-control" required placeholder="Full Name">
            </div>
            <div class="form-group">
              <label class="form-label">Email *</label>
              <input type="email" name="email" class="form-control" required placeholder="email@company.com">
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Phone Number *</label>
            <input type="tel" name="phone" class="form-control" required placeholder="+49 123 456 7890">
          </div>
          
          <div class="form-group">
            <label class="form-label">Street Address *</label>
            <input type="text" name="address" class="form-control" required placeholder="Street and number">
          </div>
          
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label">City *</label>
              <input type="text" name="city" class="form-control" required placeholder="City">
            </div>
            <div class="form-group">
              <label class="form-label">Postal Code *</label>
              <input type="text" name="zip" class="form-control" required placeholder="12345">
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Country *</label>
            <select name="country" class="form-control" required>
              <option value="Germany">Germany</option>
              <option value="Austria">Austria</option>
              <option value="Switzerland">Switzerland</option>
              <option value="Netherlands">Netherlands</option>
              <option value="Belgium">Belgium</option>
              <option value="France">France</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="United States">United States</option>
              <option value="Other">Other (specify in notes)</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="form-label">VAT Number (Optional)</label>
            <input type="text" name="vat_number" class="form-control" placeholder="e.g., DE123456789">
            <p class="form-hint">For EU companies - required for VAT exemption</p>
          </div>
          
          <div class="form-group">
            <label class="form-label">Delivery Facility / Offshore Platform (Optional)</label>
            <textarea name="delivery_facility" class="form-control" rows="3" placeholder="Final delivery location (e.g., Platform Name, GPS coordinates, facility address if different from billing)"></textarea>
            <p class="form-hint">Specify offshore platform, port facility, or final delivery location</p>
          </div>
          
          <div class="form-group">
            <label class="form-label">Order Notes (Optional)</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Special instructions, delivery requirements, etc."></textarea>
          </div>
        </div>
      </div>
      
      <!-- Delivery Mode Selection -->
      <div class="card mt-4">
        <div class="card-header">
          <h3 class="card-title">Delivery Mode</h3>
        </div>
        <div class="card-body">
          <div class="delivery-mode-options">
            <label class="delivery-mode-option">
              <input type="radio" name="delivery_mode" value="regular" checked onchange="updateDeliveryTotal()">
              <div class="delivery-mode-card">
                <div class="delivery-mode-header">
                  <span class="delivery-mode-icon">🚢</span>
                  <div>
                    <div class="delivery-mode-title">Regular Delivery</div>
                    <div class="delivery-mode-time">5-9 Working Days</div>
                  </div>
                  <div class="delivery-mode-price">€5,000.00</div>
                </div>
                <div class="delivery-mode-description">
                  Standard shipping via sea/air freight. Suitable for non-urgent orders.
                </div>
              </div>
            </label>
            
            <label class="delivery-mode-option">
              <input type="radio" name="delivery_mode" value="emergency" onchange="updateDeliveryTotal()">
              <div class="delivery-mode-card">
                <div class="delivery-mode-header">
                  <span class="delivery-mode-icon">⚡</span>
                  <div>
                    <div class="delivery-mode-title">Emergency Delivery</div>
                    <div class="delivery-mode-time">2-4 Working Days</div>
                  </div>
                  <div class="delivery-mode-price">€15,000.00</div>
                </div>
                <div class="delivery-mode-description">
                  Express air freight with priority handling. For urgent operational needs.
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>
      
      <!-- Payment Information -->
      <div class="card mt-4">
        <div class="card-header">
          <h3 class="card-title">Payment Method</h3>
        </div>
        <div class="card-body">
          <div class="alert alert-info">
            <div class="alert-title">🏦 Bank Transfer / Wire Payment</div>
            <p style="margin: 8px 0 0 0;">
              Please transfer the payment to our bank account below. Once you've made the payment, 
              upload your payment receipt for verification. Your order will be processed after payment confirmation.
            </p>
          </div>
          
          <!-- Bank Account Details -->
          <?php $s = $settings ?? []; ?>
          <div style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); padding: 24px; border-radius: 12px; margin-top: 16px; color: white;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
              <span style="font-size: 1.5rem;">🏦</span>
              <h4 style="margin: 0; color: white;">Bank Transfer Details</h4>
            </div>
            <div style="display: grid; gap: 16px; font-family: 'Courier New', monospace;">
              <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <span style="color: #94a3b8;">Bank Name</span>
                <span style="font-weight: 600;"><?= htmlspecialchars($s['bank_name'] ?? 'Deutsche Bank AG') ?></span>
              </div>
              <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <span style="color: #94a3b8;">Account Holder</span>
                <span style="font-weight: 600;"><?= htmlspecialchars($s['account_holder'] ?? 'Streicher GmbH') ?></span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <span style="color: #94a3b8;">IBAN</span>
                <span style="display: flex; align-items: center; gap: 8px;">
                  <span id="iban-value" style="font-weight: 600;"><?= htmlspecialchars($s['iban'] ?? 'DE89 3704 0044 0532 0130 00') ?></span>
                  <button type="button" onclick="copyToClipboard('DE89370400440532013000', this)" style="background: rgba(255,255,255,0.2); border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; color: white; font-size: 0.8rem;">📋 Copy</button>
                </span>
              </div>
              <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <span style="color: #94a3b8;">BIC/SWIFT</span>
                <span style="font-weight: 600;"><?= htmlspecialchars($s['bic'] ?? 'COBADEFFXXX') ?></span>
              </div>
            </div>
            <div style="margin-top: 16px; padding: 12px; background: rgba(234, 179, 8, 0.2); border: 1px solid rgba(234, 179, 8, 0.5); border-radius: 8px;">
              <strong style="color: #fbbf24;">⚠️ Important:</strong> 
              <span style="color: #fef3c7;">Use your Order Number as the payment reference</span>
            </div>
          </div>
          
          <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 16px;">
            <h4 style="margin: 0 0 12px 0;">📋 How it works:</h4>
            <ol style="margin: 0; padding-left: 20px; color: #475569;">
              <li style="margin-bottom: 8px;">Place your order and receive Order Invoice with order number</li>
              <li style="margin-bottom: 8px;">Transfer payment to our bank account above</li>
              <li style="margin-bottom: 8px;">Upload your payment receipt/confirmation</li>
              <li style="margin-bottom: 8px;">We verify payment (1-2 business days)</li>
              <li>Order is shipped with full tracking</li>
            </ol>
          </div>
          
          <!-- Import Duties Disclaimer -->
          <div style="background: #fff7ed; border: 2px solid #f59e0b; border-radius: 8px; padding: 20px; margin-top: 24px;">
            <h4 style="margin: 0 0 12px 0; color: #92400e; display: flex; align-items: center; gap: 8px;">
              <span>⚠️</span> Import Duties & Customs Responsibility
            </h4>
            <ul style="margin: 0; padding-left: 20px; color: #78350f; font-size: 0.9rem; line-height: 1.8;">
              <li style="margin-bottom: 8px;"><strong>Import Duties NOT Included:</strong> All prices are FOB (Free On Board). Import duties, customs fees, and local taxes are the customer's responsibility.</li>
              <li style="margin-bottom: 8px;"><strong>Customs Clearance:</strong> Customer is responsible for all customs clearance procedures and associated costs when equipment arrives at the destination port.</li>
              <li style="margin-bottom: 8px;"><strong>Documentation Provided:</strong> We will provide commercial invoice, packing list, certificate of origin, and equipment specifications for customs.</li>
              <li><strong>Risk Transfer:</strong> Once equipment is loaded at port of origin, risk transfers to customer. Customer handles inland transportation to final facility/offshore platform.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Order Summary -->
    <div>
      <div class="card" style="position: sticky; top: 120px;">
        <div class="card-header">
          <h3 class="card-title">Order Summary</h3>
        </div>
        <div class="card-body">
          <!-- Items -->
          <?php foreach ($cart as $item): ?>
          <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
            <div>
              <div style="font-weight: 500;"><?= htmlspecialchars($item['name']) ?></div>
              <div style="font-size: 0.85rem; color: #64748b;">Qty: <?= (int)$item['qty'] ?></div>
            </div>
            <div style="font-weight: 600;"><?= format_price($item['price'] * $item['qty']) ?></div>
          </div>
          <?php endforeach; ?>
          
          <!-- Totals -->
          <div class="cart-summary" style="margin-top: 16px;">
            <div class="cart-summary-row">
              <span>Subtotal</span>
              <span id="subtotal-amount"><?= format_price($total) ?></span>
            </div>
            <div class="cart-summary-row">
              <span>Delivery</span>
              <span id="delivery-amount">€5,000.00</span>
            </div>
            <div class="cart-summary-row">
              <span>Tax (VAT 19%)</span>
              <span id="tax-amount"><?= format_price(($total + 5000) * 0.19) ?></span>
            </div>
            <div class="cart-summary-row total">
              <span>Total</span>
              <span id="total-amount"><?= format_price(($total + 5000) * 1.19) ?></span>
            </div>
          </div>
          
          <script>
          const subtotal = <?= $total ?>;
          const deliveryCosts = {
            regular: 5000,
            emergency: 15000
          };
          
          function updateDeliveryTotal() {
            const selectedMode = document.querySelector('input[name="delivery_mode"]:checked').value;
            const deliveryCost = deliveryCosts[selectedMode];
            const taxRate = 0.19;
            
            const subtotalWithDelivery = subtotal + deliveryCost;
            const tax = subtotalWithDelivery * taxRate;
            const total = subtotalWithDelivery + tax;
            
            // Update display
            document.getElementById('delivery-amount').textContent = '€' + deliveryCost.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('tax-amount').textContent = '€' + tax.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('total-amount').textContent = '€' + total.toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
          }
          </script>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-lg btn-block">Place Order</button>
          <p style="margin: 12px 0 0 0; font-size: 0.85rem; color: #64748b; text-align: center;">
            By placing this order, you agree to our <a href="/terms">Terms & Conditions</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</form>

<style>
.delivery-mode-options {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.delivery-mode-option {
  cursor: pointer;
  display: block;
}

.delivery-mode-option input[type="radio"] {
  display: none;
}

.delivery-mode-card {
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.3s ease;
  background: white;
}

.delivery-mode-option input[type="radio"]:checked + .delivery-mode-card {
  border-color: #0066cc;
  background: #f0f7ff;
  box-shadow: 0 4px 12px rgba(0, 102, 204, 0.15);
}

.delivery-mode-card:hover {
  border-color: #94a3b8;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.delivery-mode-header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 12px;
}

.delivery-mode-icon {
  font-size: 2rem;
  flex-shrink: 0;
}

.delivery-mode-title {
  font-weight: 600;
  font-size: 1.1rem;
  color: #1e293b;
}

.delivery-mode-time {
  font-size: 0.9rem;
  color: #64748b;
  margin-top: 2px;
}

.delivery-mode-price {
  margin-left: auto;
  font-weight: 700;
  font-size: 1.25rem;
  color: #0066cc;
}

.delivery-mode-description {
  color: #475569;
  font-size: 0.9rem;
  line-height: 1.5;
  padding-left: 56px;
}

@media (max-width: 768px) {
  .delivery-mode-header {
    flex-wrap: wrap;
  }
  
  .delivery-mode-price {
    width: 100%;
    margin-left: 56px;
    margin-top: 8px;
  }
  
  .delivery-mode-description {
    padding-left: 0;
    margin-top: 8px;
  }
}
</style>
