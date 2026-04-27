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

  <div class="product-detail-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; margin-bottom: 120px;">
    <!-- Left: Gallery Matrix -->
    <div class="product-gallery-modern">
      <div style="background: white; border-radius: var(--radius-lg); padding: 60px; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 32px; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; cursor: crosshair;">
        <?php if (!empty($galleryImages[0])): ?>
        <img src="<?= htmlspecialchars($galleryImages[0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="mainImage" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);">
        <?php else: ?>
        <div style="font-size: 10rem; color: #f1f5f9;">⚙️</div>
        <?php endif; ?>
        <div style="position: absolute; bottom: 32px; left: 32px; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); background: rgba(255,255,255,0.8); padding: 8px 16px; border-radius: 4px; border: 1px solid #f1f5f9;">Asset Verification: Online</div>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <?php foreach ($galleryImages as $index => $imgUrl): ?>
        <div onclick="changeMainImage(this, '<?= htmlspecialchars($imgUrl) ?>')" 
             style="aspect-ratio: 1; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid <?= $index === 0 ? 'var(--accent)' : '#f1f5f9' ?>; transition: all 0.3s; background: white; padding: 12px; box-shadow: var(--shadow-sm);"
             class="thumb-container">
          <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Gallery Image <?= $index + 1 ?>" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Right: Asset Intelligence -->
    <div class="product-info-modern">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); color: white; border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        <?= htmlspecialchars($product['category_name'] ?? 'Industrial Asset') ?>
      </div>
      <h1 style="font-size: 4rem; margin: 0 0 16px 0; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -2px; line-height: 1;"><?= htmlspecialchars($product['name']) ?></h1>
      <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 900; margin-bottom: 48px; letter-spacing: 3px; text-transform: uppercase; background: #f8fafc; display: inline-block; padding: 8px 16px; border-radius: 4px; border: 1px solid #f1f5f9;">Protocol SKU: <?= htmlspecialchars($product['sku']) ?></div>
      
      <div style="margin-bottom: 48px; padding: 40px; background: #f8fafc; border-radius: var(--radius-lg); border: 2px solid #f1f5f9; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; font-size: 8rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">PRC</div>
        <div style="display: flex; align-items: baseline; gap: 16px; position: relative; z-index: 1;">
          <span style="font-size: 3.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; letter-spacing: -2px;"><?= format_price($displayPrice, $displayCurrency) ?></span>
          <span style="font-size: 1rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;"><?= __('price_excludes') ?></span>
        </div>
      </div>
      
      <div style="font-size: 1.25rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 60px; font-weight: 500;">
        <?= nl2br(htmlspecialchars($product['description'] ?? $product['short_desc'] ?? '')) ?>
      </div>
      
      <!-- Technical Advantages Matrix -->
      <?php if (!empty($features)): ?>
      <div style="margin-bottom: 60px;">
        <h3 style="font-size: 1.25rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); margin: 0 0 24px 0; text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px solid #f1f5f9; padding-bottom: 16px;">Asset Advantages Matrix</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <?php foreach ($features as $feature): ?>
          <div style="display: flex; align-items: center; gap: 16px; font-size: 1rem; color: var(--text-main); font-weight: 600; background: white; padding: 16px 24px; border-radius: 8px; border: 1px solid #f1f5f9; box-shadow: var(--shadow-sm);">
            <span style="color: var(--accent); font-size: 1.25rem;">⚡</span> <?= htmlspecialchars($feature) ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Transaction Portal -->
      <div style="background: white; padding: 48px; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 48px; position: relative;">
        <div style="position: absolute; top: 12px; right: 24px; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--accent);">Secure Transaction Matrix</div>
        <form id="addToCartForm" style="display: flex; gap: 24px;">
          <input type="hidden" name="sku" value="<?= htmlspecialchars($product['sku']) ?>">
          <div style="position: relative; width: 140px;">
            <div style="position: absolute; top: -12px; left: 16px; background: white; padding: 0 8px; font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Quantity</div>
            <input type="number" name="qty" value="1" min="1" style="width: 100%; height: 72px; border: 2px solid #f1f5f9; border-radius: 8px; text-align: center; font-weight: 900; font-size: 1.5rem; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
          </div>
          <button type="submit" class="btn-modern btn-accent" style="flex: 1; height: 72px; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">
            <?= __('add_to_cart') ?>
          </button>
        </form>
        <div id="cartMessage" style="display: none; margin-top: 24px; padding: 20px; background: #f0fdf4; color: #166534; border-radius: 8px; font-size: 1rem; font-weight: 900; text-align: center; border: 2px solid #bbf7d0; text-transform: uppercase; letter-spacing: 1px;">
          ✓ <?= __('product_added') ?> <a href="/cart" style="color: var(--accent); text-decoration: underline; margin-left: 12px;"><?= __('view_cart') ?></a>
        </div>
      </div>
      
      <!-- Institutional Trust Registry -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; text-align: center;">
        <div style="background: #f8fafc; padding: 24px; border-radius: 8px; border: 1px solid #f1f5f9;">
          <div style="font-size: 2rem; margin-bottom: 8px;">🚚</div>
          <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 1px;"><?= __('worldwide_shipping') ?></div>
        </div>
        <div style="background: #f8fafc; padding: 24px; border-radius: 8px; border: 1px solid #f1f5f9;">
          <div style="font-size: 2rem; margin-bottom: 8px;">🛡️</div>
          <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 1px;"><?= (int)($product['warranty_months'] ?? 24) ?> <?= __('month_warranty') ?></div>
        </div>
        <div style="background: #f8fafc; padding: 24px; border-radius: 8px; border: 1px solid #f1f5f9;">
          <div style="font-size: 2rem; margin-bottom: 8px;">📞</div>
          <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 1px;"><?= __('support_24_7') ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Technical Intelligence Matrix -->
  <div style="margin-bottom: 120px;">
    <div style="border-bottom: 2px solid #f1f5f9; display: flex; gap: 60px; margin-bottom: 60px; position: relative;">
      <h2 style="font-size: 1.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; padding-bottom: 24px; border-bottom: 4px solid var(--accent); margin-bottom: -3px; color: var(--primary); text-transform: uppercase; letter-spacing: 2px;">Technical Specifications</h2>
      <?php if (!empty($product['long_description'])): ?>
      <h2 style="font-size: 1.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; padding-bottom: 24px; color: var(--text-muted); cursor: pointer; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s;" onmouseover="this.style.color='var(--accent)'">Detailed Overview</h2>
      <?php endif; ?>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 60px;">
      <?php if (!empty($specs)): ?>
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 32px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--primary);">Specification Registry</div>
        <?php foreach ($specs as $label => $value): ?>
        <div style="display: flex; justify-content: space-between; padding: 24px 32px; border-bottom: 1px solid #f1f5f9; transition: all 0.3s;" onmouseover="this.style.background='#f8fafc';">
          <span style="font-weight: 900; color: var(--primary); font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($label) ?></span>
          <span style="color: var(--text-main); font-weight: 600; font-size: 1rem;"><?= htmlspecialchars($value) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
      <div style="background: #f8fafc; border-radius: var(--radius-lg); padding: 60px; border: 2px dashed rgba(15, 23, 42, 0.1); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center;">
        <div style="position: absolute; top: -50px; right: -50px; font-size: 15rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">QTE</div>
        <h4 style="font-size: 2rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); margin: 0 0 24px 0; letter-spacing: -1px;"><?= __('need_custom_quote') ?></h4>
        <p style="color: var(--text-muted); margin-bottom: 40px; line-height: 1.8; font-size: 1.15rem; font-weight: 500; position: relative; z-index: 1;"><?= __('bulk_orders_text') ?></p>
        <a href="/quote?product=<?= htmlspecialchars($product['sku']) ?>" class="btn-modern btn-accent" style="height: 72px; padding: 0 48px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; align-self: flex-start; position: relative; z-index: 1;">Request Institutional Quote</a>
      </div>
    </div>
  </div>

  <?php if (!empty($product['long_description'])): ?>
  <div style="background: white; border-radius: var(--radius-lg); padding: 80px; box-shadow: var(--shadow-xl); margin-bottom: 120px; border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">ENG</div>
    <h3 style="font-size: 2rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); margin: 0 0 32px 0; letter-spacing: -1px; position: relative; z-index: 1;">Complete Engineering Overview</h3>
    <div style="white-space: pre-wrap; line-height: 2; color: var(--text-muted); font-size: 1.15rem; font-weight: 500; position: relative; z-index: 1;"><?= htmlspecialchars($product['long_description']) ?></div>
  </div>
  <?php endif; ?>

  <!-- Related Assets Matrix -->
  <?php if (!empty($relatedProducts)): ?>
  <section>
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
      <h2 style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); margin: 0; letter-spacing: -1.5px;"><?= __('related_products') ?></h2>
      <span style="color: var(--accent); font-weight: 900; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px;">Asset Registry Recommendations</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 40px;">
      <?php foreach ($relatedProducts as $related): ?>
      <div class="product-card-modern" style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); transition: all 0.4s ease-out;" onmouseover="this.style.transform='translateY(-12px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="product-image-container" style="height: 280px; background: #f8fafc; overflow: hidden;">
          <img src="<?= get_product_image($related) ?>" alt="<?= htmlspecialchars($related['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease;">
        </div>
        <div class="product-info-modern" style="padding: 40px;">
          <div class="product-cat-modern" style="font-size: 0.75rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;"><?= htmlspecialchars($related['category_name'] ?? 'Equipment') ?></div>
          <h3 class="product-title-modern" style="font-size: 1.4rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); margin: 0 0 24px 0; line-height: 1.2; letter-spacing: -0.5px;">
            <a href="/product?sku=<?= htmlspecialchars($related['sku']) ?>" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($related['name']) ?></a>
          </h3>
          <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; padding-top: 24px; border-top: 1px solid #f1f5f9;">
            <div>
              <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 900; letter-spacing: 1px; margin-bottom: 4px;">Valuation</div>
              <span class="product-price-modern" style="font-size: 1.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; letter-spacing: -1px;"><?= format_price((float)$related['unit_price']) ?></span>
            </div>
            <a href="/product?sku=<?= htmlspecialchars($related['sku']) ?>" class="btn-modern btn-accent" style="height: 48px; padding: 0 20px; font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 4px; display: flex; align-items: center;">View</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
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
