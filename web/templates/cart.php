<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= __('shopping_cart') ?></span>
  </div>

  <div style="margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
    <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Procurement Protocol</div>
    <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -2px; line-height: 1;"><?= __('shopping_cart') ?></h1>
    <p style="color: var(--text-muted); font-size: 1.2rem; margin-top: 16px; font-weight: 500;"><?= count($cart) ?> <?= $lang === 'de' ? 'Positionen in Ihrer Bestellung' : 'items in your professional order' ?>.</p>
  </div>

  <?php if (empty($cart)): ?>
  <div style="background: white; border-radius: var(--radius-lg); padding: 120px 60px; text-align: center; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; left: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">NULL</div>
    <div style="font-size: 5rem; margin-bottom: 32px; position: relative; z-index: 1;">📋</div>
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 2rem; margin: 0 0 16px 0; font-weight: 900; color: var(--primary); position: relative; z-index: 1;"><?= __('cart_empty') ?></h3>
    <p style="color: var(--text-muted); margin-bottom: 48px; max-width: 500px; margin-left: auto; margin-right: auto; font-size: 1.2rem; font-weight: 500; position: relative; z-index: 1;"><?= __('start_shopping') ?></p>
    <a href="/catalog" class="btn-modern btn-accent" style="padding: 24px 60px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; position: relative; z-index: 1;"><?= __('browse_catalog') ?></a>
  </div>
  <?php else: ?>
  <div style="display: grid; grid-template-columns: 1fr 450px; gap: 60px; align-items: start;">
    <!-- Items Column -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
            <th style="padding: 32px; text-align: left; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 900;">Asset Specification</th>
            <th style="padding: 32px; text-align: left; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 900;">Valuation</th>
            <th style="padding: 32px; text-align: center; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 900;">Units</th>
            <th style="padding: 32px; text-align: right; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 900;">Registry Total</th>
            <th style="padding: 32px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item): ?>
          <tr data-sku="<?= htmlspecialchars($item['sku']) ?>" style="border-bottom: 1px solid #f1f5f9; transition: all 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            <td style="padding: 40px 32px;">
              <div style="display: flex; align-items: center; gap: 32px;">
                <div style="width: 120px; height: 120px; background: white; border-radius: 8px; border: 1px solid #f1f5f9; padding: 12px; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                  <img src="<?= get_product_image($item) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div>
                  <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; margin-bottom: 8px; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;"><?= htmlspecialchars($item['name']) ?></div>
                  <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 900; letter-spacing: 2px; text-transform: uppercase; background: #f1f5f9; display: inline-block; padding: 4px 12px; border-radius: 4px;">SKU: <?= htmlspecialchars($item['sku']) ?></div>
                </div>
              </div>
            </td>
            <td style="padding: 40px 32px; font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><?= format_price($item['price']) ?></td>
            <td style="padding: 40px 32px; text-align: center;">
              <div style="display: inline-block; position: relative;">
                <input type="number" value="<?= (int)$item['qty'] ?>" min="1" style="width: 100px; height: 54px; text-align: center; padding: 0; border: 2px solid #f1f5f9; border-radius: 6px; font-weight: 900; font-size: 1.25rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';" onchange="updateQuantity('<?= htmlspecialchars($item['sku']) ?>', this.value)">
              </div>
            </td>
            <td style="padding: 40px 32px; text-align: right; font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;" class="item-total"><?= format_price($item['price'] * $item['qty']) ?></td>
            <td style="padding: 40px 32px; text-align: right;">
              <button onclick="removeItem('<?= htmlspecialchars($item['sku']) ?>')" style="background: #fff1f2; border: none; color: #e11d48; cursor: pointer; padding: 12px; border-radius: 8px; transition: all 0.3s; border: 1px solid #ffe4e6;" onmouseover="this.style.background='#ffe4e6'; this.style.transform='scale(1.1)';" onmouseout="this.style.background='#fff1f2'; this.style.transform='scale(1)';">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Valuation Sidebar -->
    <aside style="position: sticky; top: 120px;">
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 48px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; font-size: 10rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">SUM</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0 0 40px 0; font-weight: 900; letter-spacing: -0.5px; position: relative; z-index: 1;">Order Valuation Matrix</h3>
        
        <div style="display: flex; flex-direction: column; gap: 24px; margin-bottom: 48px; position: relative; z-index: 1;">
          <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 600;">
            <span style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Net Subtotal</span>
            <span id="subtotal" style="font-weight: 900; color: var(--primary);"><?= format_price($total) ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 1rem; color: var(--text-muted); font-weight: 500; font-style: italic; background: #f8fafc; padding: 16px; border-radius: 6px; border: 1px solid #f1f5f9;">
            <span>Logistics & VAT Protocol</span>
            <span>Registry calculated at checkout</span>
          </div>
          <div style="padding-top: 32px; border-top: 3px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); font-size: 1.25rem; text-transform: uppercase; letter-spacing: 1px;">Estimated Total</span>
            <span id="total" style="font-size: 2.25rem; font-weight: 900; color: var(--accent); font-family: 'Outfit', sans-serif; letter-spacing: -1px;"><?= format_price($total) ?></span>
          </div>
        </div>

        <div style="position: relative; z-index: 1;">
          <a href="/checkout" class="btn-modern btn-accent" style="width: 100%; height: 72px; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; margin-bottom: 24px;">Initialize Procurement</a>
          <a href="/catalog" style="display: flex; align-items: center; justify-content: center; height: 60px; text-align: center; color: var(--text-muted); font-weight: 900; text-decoration: none; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s; border: 2px solid #f1f5f9; border-radius: 8px;" onmouseover="this.style.background='#f1f5f9'; this.style.color='var(--primary)'" onmouseout="this.style.background='transparent'; this.style.color='var(--text-muted)'">← Return to Asset Catalog</a>
        </div>

        <div style="margin-top: 60px; padding: 32px; background: #f8fafc; border-radius: 8px; border: 1px solid #f1f5f9; position: relative; z-index: 1;">
          <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); margin-bottom: 16px; font-weight: 900;">Secure Institutional Portal</h4>
          <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.8; margin: 0; font-weight: 500;">We utilize secure wire transfers and high-trust interdisciplinary payment verification for all industrial orders globally.</p>
        </div>
      </div>
    </aside>
  </div>
  <?php endif; ?>
</div>

<script>
async function updateQuantity(sku, qty) {
  qty = parseInt(qty);
  if (qty < 1) qty = 1;
  
  const res = await fetch('/api/cart', {
    method: 'PUT',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({sku, qty})
  });
  
  if (res.ok) {
    const data = await res.json();
    const formattedTotal = data.total.toLocaleString('de-DE', {style: 'currency', currency: 'EUR'});
    document.getElementById('subtotal').textContent = formattedTotal;
    document.getElementById('total').textContent = formattedTotal;
    
    const row = document.querySelector(`tr[data-sku="${sku}"]`);
    if (row) {
      const priceText = row.querySelector('td:nth-child(2)').textContent;
      const price = parseFloat(priceText.replace(/[^0-9,]/g, '').replace(',', '.'));
      const itemTotal = price * qty;
      row.querySelector('.item-total').textContent = itemTotal.toLocaleString('de-DE', {style: 'currency', currency: 'EUR'});
    }
    
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) cartCount.textContent = data.cart_count;
  }
}

async function removeItem(sku) {
  if (!confirm('<?= $lang === 'de' ? "Position aus dem Protokoll entfernen?" : "Remove this asset from procurement registry?" ?>')) return;
  const res = await fetch('/api/cart', {
    method: 'DELETE',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({sku})
  });
  if (res.ok) location.reload();
}
</script>

<script>
async function updateQuantity(sku, qty) {
  qty = parseInt(qty);
  if (qty < 1) qty = 1;
  
  const res = await fetch('/api/cart', {
    method: 'PUT',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({sku, qty})
  });
  
  if (res.ok) {
    const data = await res.json();
    const formattedTotal = data.total.toLocaleString('de-DE', {style: 'currency', currency: 'EUR'});
    document.getElementById('subtotal').textContent = formattedTotal;
    document.getElementById('total').textContent = formattedTotal;
    
    const row = document.querySelector(`tr[data-sku="${sku}"]`);
    if (row) {
      const priceText = row.querySelector('td:nth-child(2)').textContent;
      const price = parseFloat(priceText.replace(/[^0-9,]/g, '').replace(',', '.'));
      const itemTotal = price * qty;
      row.querySelector('.item-total').textContent = itemTotal.toLocaleString('de-DE', {style: 'currency', currency: 'EUR'});
    }
    
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) cartCount.textContent = data.cart_count;
  }
}

async function removeItem(sku) {
  if (!confirm('<?= $lang === 'de' ? "Position entfernen?" : "Remove this item?" ?>')) return;
  const res = await fetch('/api/cart', {
    method: 'DELETE',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({sku})
  });
  if (res.ok) location.reload();
}
</script>
