<?php 
$lang = $_SESSION['lang'] ?? 'de';
$displayCurrency = $_SESSION['display_currency'] ?? 'EUR';
$exchangeRate = get_exchange_rate();
?>
<div class="container-modern section-padding catalog-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <span class="current"><?= __('products') ?></span>
  </div>

  <div class="grid-sidebar">
    <!-- Sidebar -->
    <aside class="catalog-sidebar">
      <div class="sidebar-card search-card">
        <h3 class="sidebar-title"><?= __('search') ?></h3>
        <form action="/catalog" method="GET" class="search-form-modern">
          <?php if ($currentCategory): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory['slug']) ?>">
          <?php endif; ?>
          <div class="search-input-group">
            <input type="text" name="search" class="search-input" placeholder="<?= __('search_products') ?>" value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="search-submit"><i data-lucide="search"></i></button>
          </div>
        </form>
      </div>
      
      <!-- Categories Navigation -->
      <div class="sidebar-card category-card">
        <div class="sidebar-card-header">
          <h3 class="sidebar-title"><?= __('categories') ?></h3>
        </div>
        <div class="category-nav-list">
          <a href="/catalog" class="category-nav-item <?= !$currentCategory ? 'active' : '' ?>">
            <?= __('all_products') ?>
          </a>
          <?php foreach ($categories as $cat): ?>
          <?php $isActive = ($currentCategory && $currentCategory['slug'] === $cat['slug']); ?>
          <a href="/catalog?category=<?= htmlspecialchars($cat['slug']) ?>" 
             class="category-nav-item <?= $isActive ? 'active' : '' ?>">
            <?= htmlspecialchars($cat['name']) ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Technical Specifications Filters -->
      <?php if (!empty($filterOptions)): ?>
      <div class="sidebar-card filter-card">
        <div class="sidebar-card-header">
          <h3 class="sidebar-title"><?= $lang === 'de' ? 'Technische Filter' : 'Technical Filters' ?></h3>
        </div>
        <div class="filter-groups">
          <?php foreach ($filterOptions as $name => $opt): ?>
          <div class="filter-group">
            <h4 class="filter-group-title"><?= htmlspecialchars($name) ?> <?= $opt['unit'] ? '('.$opt['unit'].')' : '' ?></h4>
            <div class="filter-options">
              <?php foreach ($opt['values'] as $val): ?>
              <?php 
                $isChecked = (isset($specFilters[$name]) && (string)$specFilters[$name] === (string)$val); 
                $query = $_GET;
                if ($isChecked) {
                  unset($query[$name]);
                } else {
                  $query[$name] = $val;
                }
                $filterUrl = '/catalog?' . http_build_query($query);
              ?>
              <a href="<?= htmlspecialchars($filterUrl) ?>" class="filter-option <?= $isChecked ? 'active' : '' ?>">
                <span class="checkbox"><i data-lucide="<?= $isChecked ? 'check-square' : 'square' ?>"></i></span>
                <span class="label"><?= htmlspecialchars($val) ?> <?= htmlspecialchars($opt['unit']) ?></span>
              </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        
        <?php if (!empty($specFilters)): ?>
        <div class="filter-actions">
          <a href="/catalog?<?= $currentCategory ? 'category='.htmlspecialchars($currentCategory['slug']) : '' ?>" class="btn-clear-filters">
            <?= $lang === 'de' ? 'Filter zurücksetzen' : 'Clear All Filters' ?>
          </a>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </aside>
    
    <!-- Product Area -->
    <div class="product-area">
      <div class="product-grid-header">
        <div class="header-content">
          <h1 class="catalog-title"><?= $currentCategory ? htmlspecialchars($currentCategory['name']) : __('all_products') ?></h1>
          <p class="catalog-desc"><?= $currentCategory ? htmlspecialchars($currentCategory['description'] ?? '') : __('browse_catalog_full') ?></p>
        </div>
        <div class="currency-switcher">
          <a href="?<?= http_build_query(array_merge($_GET, ['currency' => 'EUR'])) ?>" class="currency-btn <?= $displayCurrency === 'EUR' ? 'active' : '' ?>">EUR €</a>
          <a href="?<?= http_build_query(array_merge($_GET, ['currency' => 'USD'])) ?>" class="currency-btn <?= $displayCurrency === 'USD' ? 'active' : '' ?>">USD $</a>
        </div>
      </div>
      
      <?php if ($search): ?>
      <div class="search-results-banner">
        <span><?= __('showing_results') ?> <strong>"<?= htmlspecialchars($search) ?>"</strong></span>
        <a href="/catalog<?= $currentCategory ? '?category=' . htmlspecialchars($currentCategory['slug']) : '' ?>" class="clear-search-link"><?= __('clear_search') ?></a>
      </div>
      <?php endif; ?>
      
      <?php if (empty($products)): ?>
      <div class="no-results-card card-modern text-center">
        <div class="no-results-icon"><i data-lucide="search-x"></i></div>
        <h3><?= __('no_products_found') ?></h3>
        <p><?= __('try_adjusting') ?></p>
        <?php render_component('button', [
          'href' => '/catalog',
          'variant' => 'accent',
          'label' => __('view_all')
        ]); ?>
      </div>
      <?php else: ?>
      <div class="product-grid-modern">
        <?php foreach ($products as $product): ?>
        <div class="product-card-modern">
          <div class="product-image-container">
            <img src="<?= get_product_image($product) ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
            <?php if (!$product['in_stock']): ?>
            <div class="stock-badge out-of-stock">Request Quote</div>
            <?php else: ?>
            <div class="stock-badge in-stock">Ready to Ship</div>
            <?php endif; ?>
          </div>
          <div class="product-info-modern">
            <div class="product-cat-modern"><?= htmlspecialchars($product['category_name'] ?? __('equipment')) ?></div>
            <h3 class="product-title-modern">
              <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>"><?= htmlspecialchars($product['name']) ?></a>
            </h3>
            <div class="product-card-footer">
              <div class="price-box">
                <div class="price-label"><?= __('starting_at') ?></div>
                <?php 
                  $basePrice = (float)$product['unit_price'];
                  $displayPrice = $displayCurrency === 'USD' ? $basePrice * $exchangeRate : $basePrice;
                ?>
                <div class="product-price-modern"><?= format_price($displayPrice, $displayCurrency) ?></div>
              </div>
              <div class="card-actions">
                <?php render_component('button', [
                  'href' => '/product?sku=' . htmlspecialchars($product['sku']),
                  'variant' => $product['in_stock'] ? 'outline' : 'accent',
                  'label' => $product['in_stock'] ? __('view') : 'Get Quote'
                ]); ?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
