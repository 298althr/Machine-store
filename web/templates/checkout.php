<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <a href="/cart" style="text-decoration: none; color: var(--accent);"><?= __('shopping_cart') ?></a>
    <span style="margin: 0 8px;">/</span> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 900;">Complete Your Order</span>
  </div>

  <section style="margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
    <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Final Step</div>
    <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -2px; line-height: 1;">Order Finalization</h1>
    <p style="color: var(--text-muted); font-size: 1.2rem; margin-top: 16px; font-weight: 500;">Review your details and confirm your industrial equipment order.</p>
  </section>

  <form action="/checkout" method="POST">
    <div style="display: grid; grid-template-columns: 1fr 450px; gap: 80px; align-items: start;">
      <!-- Registry Column -->
      <div style="display: flex; flex-direction: column; gap: 60px;">
        
        <!-- Corporate Registry -->
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
          <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 24px;">
            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">🏢</div>
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Shipping & Billing</h3>
              <p style="font-size: 0.8rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 0 0;">Company Information</p>
            </div>
          </div>
          <div style="padding: 60px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
              <div class="form-group-modern" style="grid-column: span 2;">
                <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Company Name</label>
                <input type="text" name="company" required placeholder="Global Industrial GmbH" style="width: 100%; height: 64px; padding: 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
              </div>
              <div class="form-group-modern">
                <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Contact Person</label>
                <input type="text" name="name" required style="width: 100%; height: 64px; padding: 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
              </div>
              <div class="form-group-modern">
                <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; height: 64px; padding: 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
              </div>
              <div class="form-group-modern" style="grid-column: span 2;">
                <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Shipping Address</label>
                <input type="text" name="address" required style="width: 100%; height: 64px; padding: 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
              </div>
              <div class="form-group-modern">
                <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">City</label>
                <input type="text" name="city" required style="width: 100%; height: 64px; padding: 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
              </div>
              <div class="form-group-modern">
                <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Postal Code</label>
                <input type="text" name="zip" required style="width: 100%; height: 64px; padding: 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
              </div>
            </div>
          </div>
        </div>

        <!-- Logistics Protocol Matrix -->
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
          <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 24px;">
            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">🚢</div>
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Shipping Method</h3>
              <p style="font-size: 0.8rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 0 0;">Select Transit Mode</p>
            </div>
          </div>
          <div style="padding: 60px;">
            <div style="display: grid; gap: 32px;">
              <label class="delivery-option" style="cursor: pointer;">
                <input type="radio" name="delivery_mode" value="regular" checked style="display: none;" onchange="updateDeliveryTotal()">
                <div class="delivery-card" style="padding: 40px; border: 3px solid #f1f5f9; border-radius: 12px; transition: all 0.3s; display: flex; align-items: center; gap: 40px; position: relative; overflow: hidden;">
                  <div style="font-size: 4rem;">🚢</div>
                  <div style="flex: 1;">
                    <div style="font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin-bottom: 8px; letter-spacing: -0.5px;">Standard Shipping</div>
                    <div style="font-size: 1rem; color: var(--text-muted); font-weight: 500;">Sea freight delivery (5-9 business days)</div>
                  </div>
                  <div style="font-weight: 900; color: var(--accent); font-size: 1.75rem; font-family: 'Outfit', sans-serif; letter-spacing: -1px;"><?= format_price(convert_price(5000, $displayCurrency), $displayCurrency) ?></div>
                </div>
              </label>

              <label class="delivery-option" style="cursor: pointer;">
                <input type="radio" name="delivery_mode" value="emergency" style="display: none;" onchange="updateDeliveryTotal()">
                <div class="delivery-card" style="padding: 40px; border: 3px solid #f1f5f9; border-radius: 12px; transition: all 0.3s; display: flex; align-items: center; gap: 40px; position: relative; overflow: hidden;">
                  <div style="font-size: 4rem;">⚡</div>
                  <div style="flex: 1;">
                    <div style="font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin-bottom: 8px; letter-spacing: -0.5px;">Priority Air Freight</div>
                    <div style="font-size: 1rem; color: var(--text-muted); font-weight: 500;">Expedited delivery (2-4 business days)</div>
                  </div>
                  <div style="font-weight: 900; color: var(--accent); font-size: 1.75rem; font-family: 'Outfit', sans-serif; letter-spacing: -1px;"><?= format_price(convert_price(15000, $displayCurrency), $displayCurrency) ?></div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Institutional Settlement Protocol -->
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
          <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 24px;">
            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">🏛️</div>
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Payment Method</h3>
              <p style="font-size: 0.8rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 0 0;">Payment Details</p>
            </div>
          </div>
          <div style="padding: 60px;">
            <div style="background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 12px; padding: 48px; margin-bottom: 40px; position: relative; overflow: hidden;">
              <div style="position: absolute; top: -20px; right: -20px; font-size: 10rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">BNK</div>
              <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px; position: relative; z-index: 1;">
                <span style="font-size: 3rem;">🏦</span>
                <span style="font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; font-size: 1.5rem; letter-spacing: -0.5px;">Bank Wire Transfer</span>
              </div>
              <p style="font-size: 1.15rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; font-weight: 500; position: relative; z-index: 1;">You will receive payment instructions and a pro-forma invoice immediately after placing your order.</p>
              <div style="display: grid; gap: 24px; font-size: 1rem; position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #e2e8f0; padding-bottom: 16px;">
                  <span style="font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem;">Beneficiary Registry</span>
                  <span style="font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($settings['account_holder'] ?? 'MAX STREICHER GmbH') ?></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                  <span style="font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem;">Banking Institution</span>
                  <span style="font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($settings['bank_name'] ?? 'Deutsche Bank AG') ?></span>
                </div>
              </div>
            </div>
            <div style="background: #fffbeb; border-radius: 12px; padding: 40px; border: 2px solid #fde68a; font-size: 1.1rem; color: #92400e; line-height: 1.8; font-weight: 600;">
              ⚠️ <strong style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem;">Customs & Duties Matrix:</strong> All prices are FOB (Free On Board). The institutional customer maintains full liability for import duties and local statutory taxes at the destination facility.
            </div>
          </div>
        </div>
      </div>

      <!-- Financial Summary Dashboard -->
      <aside style="position: sticky; top: 120px;">
        <div style="background: var(--primary); border-radius: var(--radius-lg); box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.4); padding: 60px; color: white; position: relative; overflow: hidden;">
          <div style="position: absolute; top: -50px; left: -50px; font-size: 20rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">FIN</div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; color: white; margin: 0 0 48px 0; font-weight: 900; letter-spacing: -1px; display: flex; align-items: center; gap: 16px; position: relative; z-index: 1;">
            Order Summary
          </h3>
          
          <div style="display: flex; flex-direction: column; gap: 24px; margin-bottom: 48px; max-height: 300px; overflow-y: auto; padding-right: 16px; scrollbar-width: thin; position: relative; z-index: 1;">
            <?php foreach ($cart as $item): ?>
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 16px;">
              <span style="color: rgba(255,255,255,0.7); font-weight: 600;"><?= htmlspecialchars($item['name']) ?> <span style="color: var(--accent); font-weight: 900; margin-left: 8px;">x<?= $item['qty'] ?></span></span>
              <span style="font-weight: 900; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;"><?= format_price($item['price'] * $item['qty']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <div style="display: grid; gap: 24px; padding-top: 40px; margin-bottom: 48px; position: relative; z-index: 1;">
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem;">
              <span style="opacity: 0.7; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem;">Items Subtotal</span>
              <span style="font-weight: 900; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;"><?= format_price(convert_price($total, $displayCurrency), $displayCurrency) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem;">
              <span style="opacity: 0.7; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem;">Shipping Cost</span>
              <span id="display-delivery" style="font-weight: 900; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;"></span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem;">
              <span style="opacity: 0.7; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem;">Statutory VAT (19%)</span>
              <span id="display-tax" style="font-weight: 900; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;"></span>
            </div>
            <div style="padding-top: 32px; border-top: 3px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: flex-end;">
              <div>
                <div style="color: var(--accent); font-weight: 900; text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; margin-bottom: 8px;">Final Settlement</div>
                <span style="font-family: 'Outfit', sans-serif; font-weight: 900; color: white; font-size: 3rem; line-height: 1; letter-spacing: -2px;" id="display-total"></span>
              </div>
            </div>
          </div>

          <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 84px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; box-shadow: 0 20px 40px rgba(220, 38, 38, 0.4); border-radius: 8px; position: relative; z-index: 1;">Place Order & Get Invoice</button>
          <p style="font-size: 0.9rem; color: rgba(255,255,255,0.5); text-align: center; line-height: 1.8; margin-top: 32px; font-weight: 600; position: relative; z-index: 1;">
            Protocol dispatch constitutes agreement to the <a href="/terms" style="color: white; font-weight: 900; text-decoration: underline;">Institutional Sales Matrix</a>.
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
  
  document.querySelectorAll('.delivery-card').forEach(c => {
    c.style.borderColor = '#f1f5f9';
    c.style.background = 'white';
    c.style.boxShadow = 'none';
  });
  const selectedCard = document.querySelector('input[name="delivery_mode"]:checked').nextElementSibling;
  selectedCard.style.borderColor = 'var(--accent)';
  selectedCard.style.background = 'rgba(220, 38, 38, 0.02)';
  selectedCard.style.boxShadow = 'var(--shadow-xl)';
}

updateDeliveryTotal();
</script>
