<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= __('shopping_cart') ?></span>
  </div>

  <div style="margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
    <h1 style="margin: 0; font-weight: 900;"><?= __('shopping_cart') ?></h1>
    <p style="color: var(--text-muted); font-size: 1.1rem; margin-top: 12px; font-weight: 500;"><?= count($cart) ?> <?= $lang === 'de' ? 'Positionen in Ihrem Warenkorb' : 'items in your cart' ?>.</p>
  </div>

  <?php if (empty($cart)): ?>
  <div style="background: white; border-radius: var(--radius-lg); padding: 80px 40px; text-align: center; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05);">
    <div style="font-size: 4rem; margin-bottom: 24px;">🛒</div>
    <h3 style="margin-bottom: 16px;"><?= __('cart_empty') ?></h3>
    <p style="color: var(--text-muted); margin-bottom: 32px;"><?= __('start_shopping') ?></p>
    <a href="/catalog" class="btn-modern btn-accent"><?= __('browse_catalog') ?></a>
  </div>
  <?php else: ?>
  <div class="grid-cart">
    <!-- Items Column -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow-x: auto;">
      <table style="width: 100%; min-width: 600px; border-collapse: collapse;">
        <thead>
          <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
            <th style="padding: 24px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--primary); font-weight: 800;">Product</th>
            <th style="padding: 24px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--primary); font-weight: 800;">Price</th>
            <th style="padding: 24px; text-align: center; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--primary); font-weight: 800;">Qty</th>
            <th style="padding: 24px; text-align: right; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--primary); font-weight: 800;">Total</th>
            <th style="padding: 24px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item): ?>
          <tr data-sku="<?= htmlspecialchars($item['sku']) ?>" style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 24px;">
              <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 80px; height: 80px; background: white; border-radius: 8px; border: 1px solid #f1f5f9; padding: 8px; flex-shrink: 0;">
                  <img src="<?= get_product_image($item) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div>
                  <div style="font-weight: 800; color: var(--primary); margin-bottom: 4px;"><?= htmlspecialchars($item['name']) ?></div>
                  <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">SKU: <?= htmlspecialchars($item['sku']) ?></div>
                </div>
              </div>
            </td>
            <td style="padding: 24px; font-weight: 600;"><?= format_price($item['price']) ?></td>
            <td style="padding: 24px; text-align: center;">
              <input type="number" value="<?= (int)$item['qty'] ?>" min="1" style="width: 70px; height: 44px; text-align: center; border: 2px solid #f1f5f9; border-radius: 6px; font-weight: 700; outline: none;" onchange="updateQuantity('<?= htmlspecialchars($item['sku']) ?>', this.value)">
            </td>
            <td style="padding: 24px; text-align: right; font-weight: 800; color: var(--primary);" class="item-total"><?= format_price($item['price'] * $item['qty']) ?></td>
            <td style="padding: 24px; text-align: right;">
              <button onclick="removeItem('<?= htmlspecialchars($item['sku']) ?>')" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Summary Sidebar -->
    <aside style="position: sticky; top: 120px;">
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 40px;">
        <h3 style="margin-bottom: 32px;"><?= __('order_summary') ?></h3>
        
        <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 32px;">
          <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted); font-weight: 600;">Subtotal</span>
            <span id="subtotal" style="font-weight: 800; color: var(--primary);"><?= format_price($total) ?></span>
          </div>
          <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #f1f5f9; font-size: 0.9rem; color: var(--text-muted);">
            <?= $lang === 'de' ? 'Versand und Steuern werden beim Checkout berechnet.' : 'Shipping and taxes calculated at checkout.' ?>
          </div>
          <div style="padding-top: 24px; border-top: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 800; color: var(--primary);"><?= __('total') ?></span>
            <span id="total" style="font-size: 2rem; font-weight: 900; color: var(--accent);"><?= format_price($total) ?></span>
          </div>
        </div>

        <a href="/checkout" class="btn-modern btn-accent" style="width: 100%; height: 64px;"><?= __('checkout') ?></a>
        <a href="/catalog" style="display: block; text-align: center; margin-top: 24px; color: var(--text-muted); font-weight: 700; text-decoration: none; font-size: 0.9rem;">← <?= __('continue_shopping') ?></a>
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
    location.reload(); // Simplest way to ensure everything updates correctly
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
