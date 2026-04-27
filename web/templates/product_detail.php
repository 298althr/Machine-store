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

<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 32px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <a href="/catalog" style="text-decoration: none; color: var(--accent);"><?= __('products') ?></a>
    <?php if (!empty($product['category_slug'])): ?>
    <span style="margin: 0 8px;">/</span> 
    <a href="/catalog?category=<?= htmlspecialchars($product['category_slug']) ?>" style="text-decoration: none; color: var(--accent);"><?= htmlspecialchars($product['category_name']) ?></a>
    <?php endif; ?>
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 900;"><?= htmlspecialchars($product['name']) ?></span>
  </div>

  <div class="product-detail-layout grid-2">
    <!-- Left: Product Images -->
    <div class="product-gallery-modern">
      <div style="background: white; border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 32px; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
        <?php if (!empty($galleryImages[0])): ?>
        <img src="<?= htmlspecialchars($galleryImages[0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="mainImage" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);">
        <?php else: ?>
        <div style="font-size: 8rem; color: #f1f5f9;">⚙️</div>
        <?php endif; ?>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
        <?php foreach ($galleryImages as $index => $imgUrl): ?>
        <div onclick="changeMainImage(this, '<?= htmlspecialchars($imgUrl) ?>')" 
             style="aspect-ratio: 1; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid <?= $index === 0 ? 'var(--accent)' : '#f1f5f9' ?>; transition: all 0.3s; background: white; padding: 8px;"
             class="thumb-container">
          <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Gallery Image <?= $index + 1 ?>" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Right: Product Information -->
    <div class="product-info-modern">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        <?= htmlspecialchars($product['category_name'] ?? 'Industrial Equipment') ?>
      </div>
      <h1 style="margin: 0 0 16px 0; color: var(--primary); line-height: 1.1;"><?= htmlspecialchars($product['name']) ?></h1>
      <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; margin-bottom: 32px; letter-spacing: 2px; text-transform: uppercase; background: #f8fafc; display: inline-block; padding: 8px 16px; border-radius: 4px; border: 1px solid #f1f5f9;">SKU: <?= htmlspecialchars($product['sku']) ?></div>
      
      <div style="margin-bottom: 40px; padding: 30px; background: #f8fafc; border-radius: var(--radius-lg); border: 2px solid #f1f5f9;">
        <div style="display: flex; align-items: baseline; gap: 16px;">
          <span style="font-size: 3rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= format_price($displayPrice, $displayCurrency) ?></span>
          <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;"><?= __('price_excludes') ?></span>
        </div>
      </div>
      
      <div style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 48px;">
        <?= nl2br(htmlspecialchars($product['description'] ?? $product['short_desc'] ?? '')) ?>
      </div>
      
      <!-- Key Advantages -->
      <?php if (!empty($features)): ?>
      <div style="margin-bottom: 48px;">
        <h3 style="font-size: 1.1rem; margin: 0 0 20px 0; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;"><?= __('key_features') ?></h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
          <?php foreach ($features as $feature): ?>
          <div style="display: flex; align-items: center; gap: 12px; font-size: 0.95rem; color: var(--text-main); font-weight: 600; background: white; padding: 12px 20px; border-radius: 8px; border: 1px solid #f1f5f9;">
            <span style="color: var(--accent);">✓</span> <?= htmlspecialchars($feature) ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Purchase Options -->
      <div style="background: white; padding: 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 40px;">
        <form id="addToCartForm" style="display: flex; gap: 20px; flex-wrap: wrap;">
          <input type="hidden" name="sku" value="<?= htmlspecialchars($product['sku']) ?>">
          <div style="width: 120px;">
            <input type="number" name="qty" value="1" min="1" style="width: 100%; height: 60px; border: 2px solid #f1f5f9; border-radius: 8px; text-align: center; font-weight: 700; font-size: 1.25rem; outline: none; background: #f8fafc;">
          </div>
          <button type="submit" class="btn-modern btn-accent" style="flex: 1; height: 60px;">
            <?= __('add_to_cart') ?>
          </button>
        </form>
        <div id="cartMessage" style="display: none; margin-top: 20px; padding: 15px; background: #f0fdf4; color: #166534; border-radius: 8px; font-weight: 700; text-align: center; border: 1px solid #bbf7d0;">
          ✓ <?= __('product_added') ?> <a href="/cart" style="color: var(--accent); text-decoration: underline; margin-left: 10px;"><?= __('view_cart') ?></a>
        </div>
      </div>
      
      <!-- Support & Trust -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; text-align: center;">
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #f1f5f9;">
          <div style="font-size: 1.5rem; margin-bottom: 4px;">🚚</div>
          <div style="font-size: 0.65rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><?= __('worldwide_shipping') ?></div>
        </div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #f1f5f9;">
          <div style="font-size: 1.5rem; margin-bottom: 4px;">🛡️</div>
          <div style="font-size: 0.65rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><?= (int)($product['warranty_months'] ?? 24) ?> <?= __('month_warranty') ?></div>
        </div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #f1f5f9;">
          <div style="font-size: 1.5rem; margin-bottom: 4px;">📞</div>
          <div style="font-size: 0.65rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><?= __('support_24_7') ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Technical Specifications -->
  <div style="margin-bottom: 100px;">
    <div style="border-bottom: 2px solid #f1f5f9; margin-bottom: 48px;">
      <h2 style="padding-bottom: 16px; border-bottom: 4px solid var(--accent); display: inline-block; margin-bottom: -3px;"><?= __('technical_specs') ?></h2>
    </div>
    
    <div class="grid-2">
      <?php if (!empty($specs)): ?>
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
        <?php foreach ($specs as $label => $value): ?>
        <div style="display: flex; justify-content: space-between; padding: 20px 32px; border-bottom: 1px solid #f1f5f9;">
          <span style="font-weight: 700; color: var(--primary); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;"><?= htmlspecialchars($label) ?></span>
          <span style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($value) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
      <div style="background: #f8fafc; border-radius: var(--radius-lg); padding: 48px; border: 2px dashed rgba(15, 23, 42, 0.1); display: flex; flex-direction: column; justify-content: center;">
        <h3 style="margin: 0 0 16px 0;"><?= __('need_custom_quote') ?></h3>
        <p style="color: var(--text-muted); margin-bottom: 32px; line-height: 1.6;"><?= __('bulk_orders_text') ?></p>
        <a href="/quote?product=<?= htmlspecialchars($product['sku']) ?>" class="btn-modern btn-accent" style="align-self: flex-start;"><?= __('request_quote') ?></a>
      </div>
    </div>
  </div>

  <!-- Related Products -->
  <?php if (!empty($related)): ?>
  <section>
    <div style="margin-bottom: 48px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
      <h2 style="margin: 0;"><?= __('related_products') ?></h2>
    </div>
    <div class="grid-3">
      <?php foreach ($related as $rel): ?>
      <div class="product-card-modern">
        <div class="product-image-container">
          <img src="<?= get_product_image($rel) ?>" alt="<?= htmlspecialchars($rel['name']) ?>">
        </div>
        <div class="product-info-modern">
          <div class="product-cat-modern"><?= htmlspecialchars($rel['category_name'] ?? 'Equipment') ?></div>
          <h3 class="product-title-modern">
            <a href="/product?sku=<?= htmlspecialchars($rel['sku']) ?>" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($rel['name']) ?></a>
          </h3>
          <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto;">
            <div>
              <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">From</div>
              <span class="product-price-modern" style="font-size: 1.25rem;"><?= format_price((float)$rel['unit_price']) ?></span>
            </div>
            <a href="/product?sku=<?= htmlspecialchars($rel['sku']) ?>" class="btn-modern btn-accent" style="padding: 8px 16px; font-size: 0.75rem;">View</a>
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
