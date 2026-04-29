<div class="container-modern section-padding checkout-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <a href="/cart"><?= __('shopping_cart') ?></a>
    <span class="separator">/</span> 
    <span class="current">Complete Your Order</span>
  </div>

  <section class="checkout-header">
    <div class="checkout-tag">Final Step</div>
    <h1 class="checkout-title">Order Finalization</h1>
    <p class="checkout-subtitle">Review your details and confirm your industrial equipment order.</p>
  </section>

  <form action="/checkout" method="POST">
    <div class="checkout-grid grid-sidebar">
      <!-- Registry Column -->
      <div class="checkout-main grid">
        
        <!-- Corporate Registry -->
        <div class="checkout-section card-modern">
          <div class="section-header-modern">
            <div class="header-icon"><i data-lucide="building-2"></i></div>
            <div class="header-text">
              <h3 class="section-title-modern">Shipping & Billing</h3>
              <p class="section-tag-modern">Company Information</p>
            </div>
          </div>
          <div class="section-body">
            <div class="form-grid grid-2">
              <?php render_component('form_field', [
                'label' => 'Company Name',
                'name' => 'company',
                'required' => true,
                'placeholder' => 'Global Industrial GmbH',
                'class' => 'span-2'
              ]); ?>
              <?php render_component('form_field', [
                'label' => 'Contact Person',
                'name' => 'name',
                'required' => true
              ]); ?>
              <?php render_component('form_field', [
                'label' => 'Email Address',
                'name' => 'email',
                'type' => 'email',
                'required' => true
              ]); ?>
              <?php render_component('form_field', [
                'label' => 'Shipping Address',
                'name' => 'address',
                'required' => true,
                'class' => 'span-2'
              ]); ?>
              <?php render_component('form_field', [
                'label' => 'City',
                'name' => 'city',
                'required' => true
              ]); ?>
              <?php render_component('form_field', [
                'label' => 'Postal Code',
                'name' => 'zip',
                'required' => true
              ]); ?>
            </div>
          </div>
        </div>

        <!-- Logistics Protocol Matrix -->
        <div class="checkout-section card-modern">
          <div class="section-header-modern">
            <div class="header-icon"><i data-lucide="ship"></i></div>
            <div class="header-text">
              <h3 class="section-title-modern">Shipping Method</h3>
              <p class="section-tag-modern">Select Transit Mode</p>
            </div>
          </div>
          <div class="section-body">
            <div class="delivery-options grid">
              <label class="delivery-option-modern">
                <input type="radio" name="delivery_mode" value="regular" checked onchange="updateDeliveryTotal()">
                <div class="delivery-card-modern">
                  <div class="delivery-icon"><i data-lucide="ship"></i></div>
                  <div class="delivery-info">
                    <div class="delivery-name">Standard Shipping</div>
                    <div class="delivery-desc">Sea freight delivery (5-9 business days)</div>
                  </div>
                  <div class="delivery-price"><?= format_price(convert_price(5000, $displayCurrency), $displayCurrency) ?></div>
                </div>
              </label>

              <label class="delivery-option-modern">
                <input type="radio" name="delivery_mode" value="emergency" onchange="updateDeliveryTotal()">
                <div class="delivery-card-modern">
                  <div class="delivery-icon"><i data-lucide="zap"></i></div>
                  <div class="delivery-info">
                    <div class="delivery-name">Priority Air Freight</div>
                    <div class="delivery-desc">Expedited delivery (2-4 business days)</div>
                  </div>
                  <div class="delivery-price"><?= format_price(convert_price(15000, $displayCurrency), $displayCurrency) ?></div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Institutional Settlement Protocol -->
        <div class="checkout-section card-modern">
          <div class="section-header-modern">
            <div class="header-icon"><i data-lucide="landmark"></i></div>
            <div class="header-text">
              <h3 class="section-title-modern">Payment Method</h3>
              <p class="section-tag-modern">Payment Details</p>
            </div>
          </div>
          <div class="section-body">
            <div class="payment-info-box bg-light">
              <div class="payment-icon-group">
                <i data-lucide="banknote" class="main-icon"></i>
                <span class="payment-name">Bank Wire Transfer</span>
              </div>
              <p class="payment-help">You will receive payment instructions and a pro-forma invoice immediately after placing your order.</p>
              <div class="bank-details-grid grid">
                <div class="bank-detail-item">
                  <span class="detail-label">Beneficiary Registry</span>
                  <span class="detail-value"><?= htmlspecialchars($settings['account_holder'] ?? 'MAX STREICHER GmbH') ?></span>
                </div>
                <div class="bank-detail-item">
                  <span class="detail-label">Banking Institution</span>
                  <span class="detail-value"><?= htmlspecialchars($settings['bank_name'] ?? 'Deutsche Bank AG') ?></span>
                </div>
              </div>
            </div>
            <div class="customs-warning">
              <i data-lucide="alert-triangle"></i>
              <span><strong>Customs & Duties Matrix:</strong> All prices are FOB (Free On Board). The institutional customer maintains full liability for import duties and local statutory taxes at the destination facility.</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Financial Summary Dashboard -->
      <aside class="checkout-sidebar">
        <div class="summary-card bg-primary">
          <h3 class="summary-title">Order Summary</h3>
          
          <div class="summary-items">
            <?php foreach ($cart as $item): ?>
            <div class="summary-item">
              <span class="item-name"><?= htmlspecialchars($item['name']) ?> <span class="item-qty">x<?= $item['qty'] ?></span></span>
              <span class="item-price"><?= format_price($item['price'] * $item['qty']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="summary-totals grid">
            <div class="total-row">
              <span class="total-label">Items Subtotal</span>
              <span class="total-value"><?= format_price(convert_price($total, $displayCurrency), $displayCurrency) ?></span>
            </div>
            <div class="total-row">
              <span class="total-label">Shipping Cost</span>
              <span id="display-delivery" class="total-value"></span>
            </div>
            <div class="total-row">
              <span class="total-label">Statutory VAT (19%)</span>
              <span id="display-tax" class="total-value"></span>
            </div>
            <div class="final-total-box">
              <div class="final-label">Final Settlement</div>
              <div class="final-value" id="display-total"></div>
            </div>
          </div>

          <?php render_component('button', [
            'type' => 'submit',
            'variant' => 'accent',
            'class' => 'checkout-submit',
            'label' => 'Place Order & Get Invoice'
          ]); ?>
          <p class="terms-text">
            Protocol dispatch constitutes agreement to the <a href="/terms">Institutional Sales Matrix</a>.
          </p>
        </div>
      </aside>
    </div>
  </form>
</div>

<script>
const baseSubtotal = <?= convert_price($total, $displayCurrency) ?>;
const rates = { regular: <?= convert_price(5000, $displayCurrency) ?>, emergency: <?= convert_price(15000, $displayCurrency) ?> };
const symbol = '<?= $currencySymbol ?>';
const locale = '<?= $displayCurrency === 'USD' ? 'en-US' : 'de-DE' ?>';

function updateDeliveryTotal() {
  const mode = document.querySelector('input[name="delivery_mode"]:checked').value;
  const delivery = rates[mode];
  const tax = (baseSubtotal + delivery) * 0.19;
  const total = baseSubtotal + delivery + tax;
  
  const formatter = new Intl.NumberFormat(locale, {
    style: 'currency',
    currency: '<?= $displayCurrency ?>',
    minimumFractionDigits: 2
  });
  
  document.getElementById('display-delivery').textContent = formatter.format(delivery);
  document.getElementById('display-tax').textContent = formatter.format(tax);
  document.getElementById('display-total').textContent = formatter.format(total);
}

updateDeliveryTotal();
</script>
