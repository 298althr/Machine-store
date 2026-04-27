<!-- Hero Section -->
<section class="hero-modern">
  <div class="hero-bg-overlay"></div>
  
  <div class="hero-content container-modern">
    <div class="hero-tag">
      🇩🇪 <?= $lang === 'de' ? 'Deutsche Ingenieurskunst' : 'German Engineering Excellence' ?>
    </div>
    
    <h1 class="hero-title">
      <?= $lang === 'de' ? 'Premium Industrieausrüstung für Öl & Gas' : 'Premium Industrial Equipment for Oil & Gas' ?>
    </h1>
    
    <p class="hero-subtitle">
      <?= $lang === 'de' 
        ? 'Streicher GmbH liefert erstklassige Hydrauliksysteme, Bohrausrüstung und Pipeline-Komponenten, denen Branchenführer weltweit seit 1972 vertrauen.'
        : 'Streicher GmbH delivers world-class hydraulic systems, drilling equipment, and pipeline components trusted by industry leaders worldwide since 1972.' ?>
    </p>
    
    <div class="hero-actions">
      <a href="/catalog" class="btn-modern btn-accent">
        <?= $lang === 'de' ? 'Katalog durchsuchen' : 'Browse Catalog' ?>
      </a>
      <a href="/contact" class="btn-modern btn-white">
        <?= $lang === 'de' ? 'Angebot anfordern' : 'Request Quote' ?>
      </a>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-number">50+</div>
        <div class="stat-label"><?= $lang === 'de' ? 'Jahre Erfahrung' : 'Years Experience' ?></div>
      </div>
      <div class="stat-item">
        <div class="stat-number">100+</div>
        <div class="stat-label"><?= $lang === 'de' ? 'Produkte' : 'Products' ?></div>
      </div>
      <div class="stat-item">
        <div class="stat-number">45+</div>
        <div class="stat-label"><?= $lang === 'de' ? 'Länder' : 'Countries' ?></div>
      </div>
    </div>
  </div>

  <div class="hero-image-side desktop-only">
     <div class="hero-image-wrapper">
        <img src="/assets/images/drilling.jpg" alt="Industrial Drilling">
        <div class="image-overlay-accent"></div>
     </div>
  </div>
</section>

<!-- Trusted By Section -->
<section class="trusted-section">
  <div class="container-modern">
    <p class="trusted-title">
      <?= $lang === 'de' ? 'Vertraut von führenden Unternehmen weltweit' : 'Trusted by leading companies worldwide' ?>
    </p>
    <div class="client-logo-grid">
      <?php 
      $clients = ['Shell', 'BP', 'ExxonMobil', 'Chevron', 'TotalEnergies', 'Aramco'];
      foreach ($clients as $client): 
      ?>
      <div class="client-logo"><?= $client ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Product Categories -->
<section class="section-padding bg-light">
  <div class="container-modern">
    <div class="section-header text-center">
      <h2 class="section-title"><?= $lang === 'de' ? 'Produktkategorien' : 'Product Categories' ?></h2>
      <p class="section-subtitle"><?= $lang === 'de' ? 'Entdecken Sie unser umfassendes Sortiment an Industrieausrüstung' : 'Explore our comprehensive range of industrial equipment' ?></p>
    </div>

    <div class="category-grid-modern">
      <?php foreach ($categories as $cat): ?>
      <a href="/catalog?category=<?= htmlspecialchars($cat['slug']) ?>" class="category-card-modern">
        <div class="category-icon">⚙️</div>
        <h3 class="category-title"><?= htmlspecialchars($cat['name']) ?></h3>
        <p class="category-desc"><?= htmlspecialchars($cat['description'] ?? '') ?></p>
        <div class="category-link"><?= __('view') ?> →</div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="section-padding">
  <div class="container-modern">
    <div class="section-header-flex">
      <div class="header-text">
        <h2 class="section-title"><?= $lang === 'de' ? 'Ausgewählte Produkte' : 'Featured Products' ?></h2>
        <p class="section-subtitle"><?= $lang === 'de' ? 'Hochleistungsausrüstung für anspruchsvolle Anwendungen' : 'High-performance equipment for demanding applications' ?></p>
      </div>
      <a href="/catalog" class="btn-modern btn-accent"><?= $lang === 'de' ? 'Alle Produkte anzeigen' : 'View All Products' ?></a>
    </div>

    <div class="product-grid-modern">
      <?php foreach ($products as $product): ?>
      <div class="product-card-modern">
        <div class="product-image-container">
          <img src="<?= get_product_image($product) ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
          <?php if (!empty($product['is_featured'])): ?>
          <span class="featured-badge"><?= $lang === 'de' ? 'Empfohlen' : 'Featured' ?></span>
          <?php endif; ?>
        </div>
        <div class="product-info-modern">
          <div class="product-cat-modern"><?= htmlspecialchars($product['category_name'] ?? 'Equipment') ?></div>
          <h3 class="product-title-modern">
            <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>"><?= htmlspecialchars($product['name']) ?></a>
          </h3>
          <p class="product-short-desc"><?= htmlspecialchars($product['short_desc'] ?? '') ?></p>
          <div class="product-card-footer">
            <div class="price-box">
              <div class="price-label"><?= $lang === 'de' ? 'Ab' : 'From' ?></div>
              <div class="product-price-modern"><?= format_price((float)$product['unit_price']) ?></div>
            </div>
            <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" class="btn-modern btn-view"><?= $lang === 'de' ? 'Details' : 'Details' ?></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Software Activation -->
<section class="section-padding software-activation-section">
  <div class="container-modern text-center">
    <div class="software-badge">
      <span class="icon">🔑</span>
      <span class="label">Software Activation</span>
    </div>
    <h2 class="section-title"><?= $lang === 'de' ? 'Software-Lizenz aktivieren' : 'Activate Your Software License' ?></h2>
    <p class="section-subtitle max-w-600">
      <?= $lang === 'de' 
        ? 'Kaufen und aktivieren Sie Ihre Software-Lizenzen sicher und einfach online.'
        : 'Purchase and activate your software licenses securely and easily online.' ?>
    </p>
    
    <div class="software-actions-grid">
      <div class="software-card">
        <h3><?= $lang === 'de' ? 'Neue Lizenz' : 'New License' ?></h3>
        <p><?= $lang === 'de' ? 'Kaufen und aktivieren Sie Ihre Software sofort.' : 'Purchase and activate your software instantly.' ?></p>
        <a href="/software-activation" class="btn-modern btn-accent w-full"><?= $lang === 'de' ? 'Software aktivieren' : 'Activate Software' ?></a>
      </div>
      <div class="software-card">
        <h3><?= $lang === 'de' ? 'Status prüfen' : 'Check Status' ?></h3>
        <p><?= $lang === 'de' ? 'Verfolgen Sie Ihre Aktivierung mit Ihrem Token.' : 'Track your activation with your token.' ?></p>
        <a href="/software-activation" class="btn-modern btn-outline w-full"><?= $lang === 'de' ? 'Status prüfen' : 'Check Status' ?></a>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="section-padding cta-section">
  <div class="container-modern text-center">
    <h2 class="cta-title"><?= $lang === 'de' ? 'Benötigen Sie ein individuelles Angebot?' : 'Need a Custom Quote?' ?></h2>
    <p class="cta-subtitle">
      <?= $lang === 'de' 
        ? 'Unser Ingenieurteam hilft Ihnen, die perfekte Lösung für Ihre Projektanforderungen zu finden.'
        : 'Our engineering team can help you find the perfect solution for your project requirements.' ?>
    </p>
    <div class="cta-actions">
      <a href="/quote" class="btn-modern btn-accent lg"><?= $lang === 'de' ? 'Angebot anfordern' : 'Request a Quote' ?></a>
      <a href="/contact" class="btn-modern btn-white lg"><?= $lang === 'de' ? 'Vertrieb kontaktieren' : 'Contact Sales' ?></a>
    </div>
  </div>
</section>
 </div>
  </div>
</section>ieren' : 'Contact Sales' ?></a>
    </div>
  </div>
</section>
