<?php 
$lang = $_SESSION['lang'] ?? 'de';
$displayCurrency = $_SESSION['display_currency'] ?? 'EUR';
$exchangeRate = get_exchange_rate();
?>

<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= __('products') ?></span>
  </div>

  <div class="grid-sidebar">
    <!-- Sidebar -->
    <aside class="catalog-sidebar">
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 32px; margin-bottom: 32px;">
        <h3 style="font-size: 1.1rem; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;"><?= __('search') ?></h3>
        <form action="/catalog" method="GET" style="position: relative;">
          <?php if ($currentCategory): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory['slug']) ?>">
          <?php endif; ?>
          <input type="text" name="search" style="width: 100%; height: 50px; padding: 0 45px 0 16px; border: 2px solid #f1f5f9; border-radius: 8px; outline: none; background: #f8fafc;" placeholder="<?= __('search_products') ?>" value="<?= htmlspecialchars($search ?? '') ?>">
          <button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">🔍</button>
        </form>
      </div>
      
      <!-- Categories Navigation -->
      <div style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05);">
        <div style="padding: 24px 32px; border-bottom: 2px solid #f1f5f9; background: #f8fafc;">
          <h3 style="font-size: 1.1rem; margin: 0; text-transform: uppercase; letter-spacing: 1px;"><?= __('categories') ?></h3>
        </div>
        <div style="display: flex; flex-direction: column;">
          <a href="/catalog" style="padding: 16px 32px; text-decoration: none; color: <?= !$currentCategory ? 'var(--accent)' : 'var(--text-main)' ?>; font-weight: 700; border-left: 4px solid <?= !$currentCategory ? 'var(--accent)' : 'transparent' ?>;">
            <?= __('all_products') ?>
          </a>
          <?php foreach ($categories as $cat): ?>
          <?php $isActive = ($currentCategory && $currentCategory['slug'] === $cat['slug']); ?>
          <a href="/catalog?category=<?= htmlspecialchars($cat['slug']) ?>" 
             style="padding: 16px 32px; text-decoration: none; color: <?= $isActive ? 'var(--accent)' : 'var(--text-main)' ?>; font-weight: 700; border-left: 4px solid <?= $isActive ? 'var(--accent)' : 'transparent' ?>; border-top: 1px solid #f1f5f9;">
            <?= htmlspecialchars($cat['name']) ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>
    
    <!-- Product Area -->
    <div>
      <div style="margin-bottom: 40px; border-bottom: 2px solid #f1f5f9; padding-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
        <div>
          <h1 style="margin: 0 0 8px 0; font-weight: 900;"><?= $currentCategory ? htmlspecialchars($currentCategory['name']) : __('all_products') ?></h1>
          <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px;"><?= $currentCategory ? htmlspecialchars($currentCategory['description'] ?? '') : __('browse_catalog_full') ?></p>
        </div>
        <div style="display: flex; gap: 12px; background: #f1f5f9; padding: 4px; border-radius: 8px;">
          <a href="?<?= http_build_query(array_merge($_GET, ['currency' => 'EUR'])) ?>" class="btn-modern" style="padding: 8px 16px; font-size: 0.8rem; <?= $displayCurrency === 'EUR' ? 'background: white; color: var(--accent);' : 'background: transparent; color: var(--text-muted);' ?>">EUR €</a>
          <a href="?<?= http_build_query(array_merge($_GET, ['currency' => 'USD'])) ?>" class="btn-modern" style="padding: 8px 16px; font-size: 0.8rem; <?= $displayCurrency === 'USD' ? 'background: white; color: var(--accent);' : 'background: transparent; color: var(--text-muted);' ?>">USD $</a>
        </div>
      </div>
      
      <?php if ($search): ?>
      <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #f1f5f9;">
        <span><?= __('showing_results') ?> <strong>"<?= htmlspecialchars($search) ?>"</strong></span>
        <a href="/catalog<?= $currentCategory ? '?category=' . htmlspecialchars($currentCategory['slug']) : '' ?>" style="color: var(--accent); font-weight: 700; text-decoration: none;"><?= __('clear_search') ?></a>
      </div>
      <?php endif; ?>
      
      <?php if (empty($products)): ?>
      <div style="background: white; border-radius: var(--radius-lg); padding: 80px 40px; text-align: center; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05);">
        <div style="font-size: 4rem; margin-bottom: 24px;">🔍</div>
        <h3 style="margin-bottom: 16px;"><?= __('no_products_found') ?></h3>
        <p style="color: var(--text-muted); margin-bottom: 32px;"><?= __('try_adjusting') ?></p>
        <a href="/catalog" class="btn-modern btn-accent"><?= __('view_all') ?></a>
      </div>
      <?php else: ?>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 32px;">
        <?php foreach ($products as $product): ?>
        <div class="product-card-modern">
          <div class="product-image-container">
            <img src="<?= get_product_image($product) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          </div>
          <div class="product-info-modern">
            <div class="product-cat-modern"><?= htmlspecialchars($product['category_name'] ?? __('equipment')) ?></div>
            <h3 class="product-title-modern">
              <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($product['name']) ?></a>
            </h3>
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto;">
              <div>
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;"><?= __('starting_at') ?></div>
                <?php 
                  $basePrice = (float)$product['unit_price'];
                  $displayPrice = $displayCurrency === 'USD' ? $basePrice * $exchangeRate : $basePrice;
                ?>
                <div class="product-price-modern" style="font-size: 1.25rem;"><?= format_price($displayPrice, $displayCurrency) ?></div>
              </div>
              <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" class="btn-modern btn-accent" style="padding: 8px 16px; font-size: 0.75rem;"><?= __('view') ?></a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
