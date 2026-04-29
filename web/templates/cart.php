<?php 
// Currency variables are injected by render_template
?>
<div class="container-modern section-padding cart-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <span class="current"><?= __('shopping_cart') ?></span>
  </div>

  <div class="page-header-modern">
    <h1 class="mb-0"><?= __('shopping_cart') ?></h1>
    <p class="text-muted mt-12"><?= count($cart) ?> <?= $lang === 'de' ? 'Positionen in Ihrem Warenkorb' : 'items in your cart' ?>.</p>
  </div>

  <?php if (empty($cart)): ?>
  <div class="cart-empty-card card-modern text-center py-80">
    <div class="empty-icon mb-24">
      <i data-lucide="shopping-cart" size="64"></i>
    </div>
    <h3 class="mb-16"><?= __('cart_empty') ?></h3>
    <p class="text-muted mb-32"><?= __('start_shopping') ?></p>
    <?php render_component('button', [
      'href' => '/catalog',
      'variant' => 'accent',
      'label' => __('browse_catalog')
    ]); ?>
  </div>
  <?php else: ?>
  <div class="cart-layout grid-cart">
    <!-- Items Column -->
    <div class="cart-items-container card-modern no-padding overflow-hidden">
      <div class="overflow-x-auto">
        <table class="cart-table admin-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th class="text-center">Qty</th>
              <th class="text-right">Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $item): ?>
            <tr data-sku="<?= htmlspecialchars($item['sku']) ?>">
              <td>
                <div class="flex items-center gap-20">
                  <div class="cart-item-image card-modern no-padding p-8 bg-white border-subtle">
                    <img src="<?= get_product_image($item) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="thumb-img">
                  </div>
                  <div>
                    <div class="font-bold color-primary mb-4"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="text-xs text-muted font-bold tracking-wider uppercase">SKU: <?= htmlspecialchars($item['sku']) ?></div>
                  </div>
                </div>
              </td>
              <td class="font-semibold"><?= format_price(convert_price($item['price'], $displayCurrency), $displayCurrency) ?></td>
              <td class="text-center">
                <input type="number" value="<?= (int)$item['qty'] ?>" min="1" class="qty-input-sm" onchange="updateQuantity('<?= htmlspecialchars($item['sku']) ?>', this.value)">
              </td>
              <td class="text-right font-bold color-primary"><?= format_price(convert_price($item['price'] * $item['qty'], $displayCurrency), $displayCurrency) ?></td>
              <td class="text-right">
                <button onclick="removeItem('<?= htmlspecialchars($item['sku']) ?>')" class="btn-remove" title="Remove Item">
                  <i data-lucide="trash-2"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Summary Sidebar -->
    <aside class="cart-sidebar">
      <div class="card-modern sticky-top-120">
        <h3 class="mb-32"><?= __('order_summary') ?></h3>
        
        <div class="summary-details grid gap-20 mb-32">
          <div class="flex-between">
            <span class="text-muted font-semibold">Subtotal</span>
            <span id="subtotal" class="font-bold color-primary"><?= format_price(convert_price($total, $displayCurrency), $displayCurrency) ?></span>
          </div>
          <div class="alert alert-info py-16 px-20 border-radius-md text-sm">
            <i data-lucide="info" size="16"></i>
            <span><?= $lang === 'de' ? 'Versand und Steuern werden beim Checkout berechnet.' : 'Shipping and taxes calculated at checkout.' ?></span>
          </div>
          <div class="pt-24 border-top flex-between items-center">
            <span class="font-bold color-primary"><?= __('total') ?></span>
            <span id="total" class="text-2xl font-black text-accent"><?= format_price(convert_price($total, $displayCurrency), $displayCurrency) ?></span>
          </div>
        </div>

        <?php render_component('button', [
          'href' => '/checkout',
          'variant' => 'accent',
          'label' => __('checkout'),
          'class' => 'w-full h-64 text-lg'
        ]); ?>
        
        <a href="/catalog" class="btn-continue-shopping mt-24">
          <i data-lucide="arrow-left"></i>
          <span><?= __('continue_shopping') ?></span>
        </a>
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
