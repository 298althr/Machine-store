<?php
$lang = $_SESSION['lang'] ?? 'de';
$displayCurrency = $_SESSION['display_currency'] ?? 'EUR';
$exchangeRate = get_exchange_rate();
$features = json_decode($product['features'] ?? '[]', true) ?: [];
$specs = json_decode($product['specifications'] ?? '[]', true) ?: [];
$galleryImages = json_decode($product['gallery_images'] ?? '[]', true) ?: [];
if (empty($galleryImages)) {
    $galleryImages = [get_product_image($product)];
}
$basePrice = (float)$product['unit_price'];
$displayPrice = $displayCurrency === 'USD' ? $basePrice * $exchangeRate : $basePrice;
?>

<div class="container-modern section-padding product-detail-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <a href="/catalog"><?= __('products') ?></a>
    <?php if (!empty($product['category_slug'])): ?>
    <span class="separator">/</span> 
    <a href="/catalog?category=<?= htmlspecialchars($product['category_slug']) ?>"><?= htmlspecialchars($product['category_name']) ?></a>
    <?php endif; ?>
    <span class="separator">/</span> 
    <span class="current"><?= htmlspecialchars($product['name']) ?></span>
  </div>

  <div class="product-detail-layout grid grid-2">
    <!-- Left: Product Images -->
    <div class="product-gallery-modern">
      <div class="main-image-container card-modern no-padding">
        <?php if (!empty($galleryImages[0])): ?>
        <img src="<?= htmlspecialchars($galleryImages[0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="mainImage" class="main-image-img">
        <?php else: ?>
        <div class="no-image-placeholder">
          <i data-lucide="settings"></i>
        </div>
        <?php endif; ?>
      </div>
      
      <div class="gallery-thumbs-grid grid grid-4">
        <?php foreach ($galleryImages as $index => $imgUrl): ?>
        <div onclick="changeMainImage(this, '<?= htmlspecialchars($imgUrl) ?>')" 
             class="thumb-container card-modern no-padding <?= $index === 0 ? 'active' : '' ?>">
          <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Gallery Image <?= $index + 1 ?>" class="thumb-img">
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Right: Product Information -->
    <div class="product-info-modern">
      <div class="product-category-tag">
        <?= htmlspecialchars($product['category_name'] ?? 'Industrial Equipment') ?>
      </div>
      <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
      <div class="product-sku">SKU: <?= htmlspecialchars($product['sku']) ?></div>
      
      <div class="price-container-modern card-modern bg-light">
        <div class="price-flex">
          <span class="price-value"><?= format_price($displayPrice, $displayCurrency) ?></span>
          <span class="price-meta"><?= __('price_excludes') ?></span>
        </div>
      </div>
      
      <div class="product-description-text">
        <?= nl2br(htmlspecialchars($product['description'] ?? $product['short_desc'] ?? '')) ?>
      </div>
      
      <!-- Key Advantages -->
      <?php if (!empty($features)): ?>
      <div class="product-features-section">
        <h3 class="features-title"><?= __('key_features') ?></h3>
        <div class="features-grid grid">
          <?php foreach ($features as $feature): ?>
          <div class="feature-item card-modern py-12 px-20">
            <i data-lucide="check" class="text-accent"></i>
            <span><?= htmlspecialchars($feature) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Purchase Options -->
      <div class="purchase-card card-modern">
        <form id="addToCartForm" class="flex gap-20 flex-wrap">
          <input type="hidden" name="sku" value="<?= htmlspecialchars($product['sku']) ?>">
          <div class="qty-input-wrapper">
            <input type="number" name="qty" value="1" min="1" class="qty-input">
          </div>
          <?php render_component('button', [
            'type' => 'submit',
            'variant' => 'accent',
            'label' => __('add_to_cart'),
            'class' => 'flex-1 h-60',
            'icon' => 'shopping-cart'
          ]); ?>
        </form>
        <div id="cartMessage" class="alert alert-success mt-20" style="display: none;">
          <i data-lucide="check-circle"></i>
          <span><?= __('product_added') ?> <a href="/cart" class="text-accent font-bold underline ml-8"><?= __('view_cart') ?></a></span>
        </div>
      </div>
      
      <!-- Support & Trust -->
      <div class="trust-indicators grid grid-3">
        <div class="trust-item card-modern bg-light">
          <i data-lucide="truck"></i>
          <div class="trust-label"><?= __('worldwide_shipping') ?></div>
        </div>
        <div class="trust-item card-modern bg-light">
          <i data-lucide="shield-check"></i>
          <div class="trust-label"><?= (int)($product['warranty_months'] ?? 24) ?> <?= __('month_warranty') ?></div>
        </div>
        <div class="trust-item card-modern bg-light">
          <i data-lucide="headphones"></i>
          <div class="trust-label"><?= __('support_24_7') ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Technical Specifications -->
  <div class="specs-section-modern">
    <div class="section-header-line">
      <h2 class="section-title-modern-inline"><?= __('technical_specs') ?></h2>
    </div>
    
    <div class="grid grid-2">
      <?php if (!empty($specs)): ?>
      <div class="specs-table-card card-modern no-padding overflow-hidden">
        <?php foreach ($specs as $label => $value): ?>
        <div class="spec-row">
          <span class="spec-label"><?= htmlspecialchars($label) ?></span>
          <span class="spec-value"><?= htmlspecialchars($value) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
      <div class="quote-cta-card card-modern bg-light border-dashed">
        <h3 class="mb-16"><?= __('need_custom_quote') ?></h3>
        <p class="text-muted mb-32"><?= __('bulk_orders_text') ?></p>
        <?php render_component('button', [
          'href' => '/quote?product=' . htmlspecialchars($product['sku']),
          'variant' => 'accent',
          'label' => __('request_quote'),
          'class' => 'self-start'
        ]); ?>
      </div>
    </div>
  </div>

  <!-- Related Products -->
  <?php if (!empty($related)): ?>
  <section class="related-products-section">
    <div class="section-header-line">
      <h2 class="mb-0"><?= __('related_products') ?></h2>
    </div>
    <div class="grid grid-3">
      <?php foreach ($related as $rel): ?>
      <div class="product-card-modern card-modern no-padding overflow-hidden">
        <div class="product-image-container">
          <img src="<?= get_product_image($rel) ?>" alt="<?= htmlspecialchars($rel['name']) ?>">
        </div>
        <div class="product-info-modern p-24">
          <div class="product-cat-modern"><?= htmlspecialchars($rel['category_name'] ?? 'Equipment') ?></div>
          <h3 class="product-title-modern">
            <a href="/product?sku=<?= htmlspecialchars($rel['sku']) ?>"><?= htmlspecialchars($rel['name']) ?></a>
          </h3>
          <div class="flex-between flex-end mt-auto">
            <div>
              <div class="text-muted text-xs font-bold uppercase">From</div>
              <span class="price-sm"><?= format_price((float)$rel['unit_price']) ?></span>
            </div>
            <?php render_component('button', [
              'href' => '/product?sku=' . htmlspecialchars($rel['sku']),
              'variant' => 'accent',
              'size' => 'sm',
              'label' => 'View'
            ]); ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
</div>
</div>

<script>
function changeMainImage(thumbnail, imageUrl) {
  const mainImage = document.getElementById('mainImage');
  if (mainImage) {
    mainImage.style.opacity = '0';
    mainImage.style.transform = 'scale(0.95)';
    setTimeout(() => {
      mainImage.src = imageUrl;
      mainImage.style.opacity = '1';
      mainImage.style.transform = 'scale(1)';
    }, 300);
  }
  
  document.querySelectorAll('.thumb-container').forEach(t => t.style.borderColor = '#f1f5f9');
  thumbnail.style.borderColor = 'var(--accent)';
}

document.getElementById('addToCartForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  const data = {
    sku: formData.get('sku'),
    qty: parseInt(formData.get('qty'))
  };
  
  const submitBtn = this.querySelector('button[type="submit"]');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Processing...';
  
  try {
    const res = await fetch('/api/cart', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    });
    
    if (res.ok) {
      const result = await res.json();
      const msg = document.getElementById('cartMessage');
      msg.style.display = 'block';
      
      const cartCount = document.querySelector('.cart-count');
      if (cartCount) {
        cartCount.textContent = result.cart_count;
        cartCount.style.display = 'inline';
      }
      
      setTimeout(() => { msg.style.display = 'none'; }, 5000);
    }
  } catch (err) {
    console.error('Add to cart failed:', err);
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = '<?= __('add_to_cart') ?>';
  }
});
</script>
