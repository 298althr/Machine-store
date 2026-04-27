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

  <div class="catalog-layout" style="display: grid; grid-template-columns: 350px 1fr; gap: 60px;">
    <!-- Sidebar Registry -->
    <aside class="catalog-sidebar">
      <!-- Search Registry -->
      <div style="background: white; border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-xl); margin-bottom: 40px; border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; font-size: 6rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">SRC</div>
        <h3 style="font-size: 1.25rem; margin-bottom: 24px; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -0.5px; position: relative; z-index: 1;"><?= __('search') ?></h3>
        <form action="/catalog" method="GET" style="position: relative; z-index: 1;">
          <?php if ($currentCategory): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory['slug']) ?>">
          <?php endif; ?>
          <input type="text" name="search" style="width: 100%; height: 60px; padding: 0 60px 0 24px; border: 2px solid #f1f5f9; border-radius: 8px; font-size: 1rem; outline: none; transition: all 0.3s; background: #f8fafc; font-weight: 500;" placeholder="<?= __('search_products') ?>" value="<?= htmlspecialchars($search ?? '') ?>" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';">
          <button type="submit" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--accent); font-size: 1.25rem;">🔍</button>
        </form>
      </div>
      
      <!-- Categories Navigation Matrix -->
      <div style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); position: relative;">
        <div style="padding: 40px; border-bottom: 2px solid #f1f5f9; background: #f8fafc;">
          <h3 style="font-size: 1.25rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -0.5px; margin: 0;"><?= __('categories') ?></h3>
        </div>
        <div style="display: flex; flex-direction: column;">
          <a href="/catalog" style="padding: 24px 40px; text-decoration: none; color: <?= !$currentCategory ? 'var(--accent)' : 'var(--text-main)' ?>; font-weight: <?= !$currentCategory ? '900' : '600' ?>; background: <?= !$currentCategory ? '#fff' : 'transparent' ?>; border-left: 4px solid <?= !$currentCategory ? 'var(--accent)' : 'transparent' ?>; transition: all 0.3s; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;">
            <?= __('all_products') ?>
          </a>
          <?php foreach ($categories as $cat): ?>
          <?php $isActive = ($currentCategory && $currentCategory['slug'] === $cat['slug']); ?>
          <a href="/catalog?category=<?= htmlspecialchars($cat['slug']) ?>" 
             style="padding: 24px 40px; text-decoration: none; color: <?= $isActive ? 'var(--accent)' : 'var(--text-main)' ?>; font-weight: <?= $isActive ? '900' : '600' ?>; background: <?= $isActive ? '#fff' : 'transparent' ?>; border-left: 4px solid <?= $isActive ? 'var(--accent)' : 'transparent' ?>; transition: all 0.3s; font-size: 1rem; border-top: 1px solid #f1f5f9;">
            <?= htmlspecialchars($cat['name']) ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>
    
    <!-- Asset Intelligence Area -->
    <div>
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
        <div>
          <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Institutional Registry</div>
          <h1 style="font-size: 3.5rem; margin: 0 0 16px 0; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); letter-spacing: -2px; line-height: 1;">
            <?= $currentCategory ? htmlspecialchars($currentCategory['name']) : __('all_products') ?>
          </h1>
          <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 700px; font-weight: 500; line-height: 1.6;">
            <?= $currentCategory ? htmlspecialchars($currentCategory['description'] ?? '') : __('browse_catalog_full') ?>
          </p>
        </div>
        
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 20px;">
          <div style="display: flex; background: #f1f5f9; padding: 6px; border-radius: 8px;">
            <a href="?<?= http_build_query(array_merge($_GET, ['currency' => 'EUR'])) ?>" 
               style="padding: 10px 24px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 900; transition: all 0.3s; <?= $displayCurrency === 'EUR' ? 'background: white; color: var(--accent); box-shadow: var(--shadow-md);' : 'color: var(--text-muted);' ?>">
              EUR €
            </a>
            <a href="?<?= http_build_query(array_merge($_GET, ['currency' => 'USD'])) ?>" 
               style="padding: 10px 24px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 900; transition: all 0.3s; <?= $displayCurrency === 'USD' ? 'background: white; color: var(--accent); box-shadow: var(--shadow-md);' : 'color: var(--text-muted);' ?>">
              USD $
            </a>
          </div>
          <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; background: #f8fafc; padding: 8px 16px; border-radius: 4px; border: 1px solid #f1f5f9;">
            <?= count($products) ?> <?= count($products) !== 1 ? __('products_found') : __('product_found') ?>
          </span>
        </div>
      </div>
      
      <?php if ($search): ?>
      <div style="background: #f8fafc; padding: 24px 32px; border-radius: 8px; margin-bottom: 48px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #f1f5f9;">
        <span style="font-size: 1.1rem; font-weight: 500;">
          <?= __('showing_results') ?> <strong style="color: var(--primary);">"<?= htmlspecialchars($search) ?>"</strong>
        </span>
        <a href="/catalog<?= $currentCategory ? '?category=' . htmlspecialchars($currentCategory['slug']) : '' ?>" style="font-size: 0.9rem; color: var(--accent); font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px solid var(--accent);"><?= __('clear_search') ?></a>
      </div>
      <?php endif; ?>
      
      <?php if (empty($products)): ?>
      <div style="background: white; border-radius: var(--radius-lg); padding: 120px 60px; text-align: center; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; left: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">NULL</div>
        <div style="font-size: 5rem; margin-bottom: 32px; position: relative; z-index: 1;">🔍</div>
        <h3 style="font-size: 2rem; margin: 0 0 16px 0; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); position: relative; z-index: 1;"><?= __('no_products_found') ?></h3>
        <p style="color: var(--text-muted); margin-bottom: 48px; font-size: 1.2rem; font-weight: 500; position: relative; z-index: 1;"><?= __('try_adjusting') ?></p>
        <a href="/catalog" class="btn-modern btn-accent" style="padding: 24px 48px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; position: relative; z-index: 1;"><?= __('view_all') ?></a>
      </div>
      <?php else: ?>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 40px;">
        <?php foreach ($products as $product): ?>
        <div class="product-card-modern" style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: all 0.4s ease-out; position: relative;" onmouseover="this.style.transform='translateY(-12px)'" onmouseout="this.style.transform='translateY(0)'">
          <div class="product-image-container" style="position: relative; aspect-ratio: 1; background: #f8fafc; overflow: hidden;">
            <img src="<?= get_product_image($product) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease;">
            <?php if (!empty($product['is_featured'])): ?>
            <span style="position: absolute; top: 20px; right: 20px; background: var(--accent); color: white; padding: 8px 16px; border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; z-index: 2; box-shadow: var(--shadow-md);">
              <?= __('featured') ?>
            </span>
            <?php elseif (($product['product_type'] ?? 'hardware') === 'software'): ?>
            <span style="position: absolute; top: 20px; right: 20px; background: linear-gradient(135deg, #0f172a 0%, #334155 100%); color: white; padding: 8px 16px; border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; z-index: 2; box-shadow: var(--shadow-md);">
              💻 Software
            </span>
            <?php endif; ?>
          </div>
          <div class="product-info-modern" style="padding: 40px; flex: 1; display: flex; flex-direction: column;">
            <div class="product-cat-modern" style="font-size: 0.8rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;"><?= htmlspecialchars($product['category_name'] ?? __('equipment')) ?></div>
            <h3 class="product-title-modern" style="font-size: 1.5rem; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); margin: 0 0 16px 0; line-height: 1.2; letter-spacing: -0.5px;">
              <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" style="text-decoration: none; color: inherit; transition: all 0.3s;"><?= htmlspecialchars($product['name']) ?></a>
            </h3>
            <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 32px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 500; line-height: 1.6;"><?= htmlspecialchars($product['short_desc'] ?? '') ?></p>
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; padding-top: 32px; border-top: 2px solid #f8fafc;">
              <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 900; letter-spacing: 1.5px; margin-bottom: 8px;"><?= __('starting_at') ?></div>
                <?php 
                  $basePrice = (float)$product['unit_price'];
                  $displayPrice = $displayCurrency === 'USD' ? $basePrice * $exchangeRate : $basePrice;
                ?>
                <div class="product-price-modern" style="font-size: 1.75rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; letter-spacing: -1px; line-height: 1;"><?= format_price($displayPrice, $displayCurrency) ?></div>
              </div>
              <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" class="btn-modern btn-accent" style="height: 54px; padding: 0 24px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 6px; display: flex; align-items: center; justify-content: center;"><?= __('view') ?></a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
