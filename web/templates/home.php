<!-- Hero Section -->
<section class="hero-modern">
  <img src="/images/photos/drilling.jpg" alt="Industrial Drilling" class="hero-bg">
  <div class="hero-overlay"></div>
  
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
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
      <a href="/catalog" class="btn-modern btn-accent">
        <?= $lang === 'de' ? 'Katalog durchsuchen' : 'Browse Catalog' ?>
      </a>
      <a href="/quote" class="btn-modern btn-white">
        <?= $lang === 'de' ? 'Angebot anfordern' : 'Request Quote' ?>
      </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; margin-top: 80px; max-width: 600px;">
      <div>
        <div style="font-size: 2.5rem; font-weight: 800; color: var(--accent);">50+</div>
        <div style="font-size: 0.9rem; color: rgba(255,255,255,0.7);"><?= $lang === 'de' ? 'Jahre Erfahrung' : 'Years Experience' ?></div>
      </div>
      <div>
        <div style="font-size: 2.5rem; font-weight: 800; color: var(--accent);">100+</div>
        <div style="font-size: 0.9rem; color: rgba(255,255,255,0.7);"><?= $lang === 'de' ? 'Produkte' : 'Products' ?></div>
      </div>
      <div>
        <div style="font-size: 2.5rem; font-weight: 800; color: var(--accent);">45+</div>
        <div style="font-size: 0.9rem; color: rgba(255,255,255,0.7);"><?= $lang === 'de' ? 'Länder' : 'Countries' ?></div>
      </div>
    </div>
  </div>
</section>

<!-- Trusted By Section -->
<section style="background: white; padding: 40px 0; border-bottom: 1px solid var(--gray-200);">
  <div class="container-modern">
    <p style="text-align: center; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 32px;">
      <?= $lang === 'de' ? 'Vertraut von führenden Unternehmen weltweit' : 'Trusted by leading companies worldwide' ?>
    </p>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 48px; opacity: 0.6; filter: grayscale(100%);">
      <?php 
      $clients = ['Shell', 'BP', 'ExxonMobil', 'Chevron', 'TotalEnergies', 'Aramco'];
      foreach ($clients as $client): 
      ?>
      <div style="font-weight: 800; font-size: 1.25rem; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= $client ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Product Categories -->
<section class="section-padding">
  <div class="container-modern">
    <div class="text-center" style="margin-bottom: 60px;">
      <h2 style="font-size: 2.5rem; margin-bottom: 16px;"><?= $lang === 'de' ? 'Produktkategorien' : 'Product Categories' ?></h2>
      <p style="color: var(--text-muted); font-size: 1.1rem;"><?= $lang === 'de' ? 'Entdecken Sie unser umfassendes Sortiment an Industrieausrüstung' : 'Explore our comprehensive range of industrial equipment' ?></p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
      <?php foreach ($categories as $cat): ?>
      <a href="/catalog?category=<?= htmlspecialchars($cat['slug']) ?>" style="text-decoration: none; color: inherit; background: white; padding: 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); transition: var(--transition); border: 1px solid rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-lg)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-md)';">
        <div style="width: 56px; height: 56px; background: rgba(15, 23, 42, 0.05); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: var(--accent);">
          <span style="font-size: 1.5rem;">⚙️</span>
        </div>
        <h3 style="margin-bottom: 12px; font-size: 1.25rem;"><?= htmlspecialchars($cat['name']) ?></h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;"><?= htmlspecialchars($cat['description'] ?? '') ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="section-padding" style="background: var(--bg-body);">
  <div class="container-modern">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 60px;">
      <div>
        <h2 style="font-size: 2.5rem; margin-bottom: 16px;"><?= $lang === 'de' ? 'Ausgewählte Produkte' : 'Featured Products' ?></h2>
        <p style="color: var(--text-muted); font-size: 1.1rem;"><?= $lang === 'de' ? 'Hochleistungsausrüstung für anspruchsvolle Anwendungen' : 'High-performance equipment for demanding applications' ?></p>
      </div>
      <a href="/catalog" class="btn-modern btn-accent"><?= $lang === 'de' ? 'Alle Produkte anzeigen' : 'View All Products' ?></a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 32px;">
      <?php foreach ($products as $product): ?>
      <div class="product-card-modern">
        <div class="product-image-container">
          <img src="<?= get_product_image($product) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          <?php if (!empty($product['is_featured'])): ?>
          <span style="position: absolute; top: 12px; right: 12px; background: var(--accent); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
            <?= $lang === 'de' ? 'Empfohlen' : 'Featured' ?>
          </span>
          <?php endif; ?>
        </div>
        <div class="product-info-modern">
          <div class="product-cat-modern"><?= htmlspecialchars($product['category_name'] ?? 'Equipment') ?></div>
          <h3 class="product-title-modern">
            <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($product['name']) ?></a>
          </h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 20px;"><?= htmlspecialchars($product['short_desc'] ?? '') ?></p>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <div>
              <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;"><?= $lang === 'de' ? 'Ab' : 'From' ?></div>
              <div class="product-price-modern"><?= format_price((float)$product['unit_price']) ?></div>
            </div>
            <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" class="btn-modern btn-accent" style="padding: 10px 20px; font-size: 0.85rem;"><?= $lang === 'de' ? 'Details' : 'Details' ?></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Software Activation -->
<section class="section-padding" style="background: linear-gradient(135deg, hsl(142, 71%, 95%) 0%, hsl(142, 71%, 90%) 100%);">
  <div class="container-modern text-center">
    <div style="display: inline-flex; align-items: center; gap: 12px; background: var(--success); color: white; padding: 8px 24px; border-radius: var(--radius-full); margin-bottom: 24px;">
      <span style="font-size: 1.25rem;">🔑</span>
      <span style="font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">Software Activation</span>
    </div>
    <h2 style="font-size: 2.5rem; margin-bottom: 16px; color: var(--primary);"><?= $lang === 'de' ? 'Software-Lizenz aktivieren' : 'Activate Your Software License' ?></h2>
    <p style="color: hsl(142, 71%, 20%); max-width: 600px; margin: 0 auto 48px;">
      <?= $lang === 'de' 
        ? 'Kaufen und aktivieren Sie Ihre Software-Lizenzen sicher und einfach online.'
        : 'Purchase and activate your software licenses securely and easily online.' ?>
    </p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; text-align: left;">
      <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        <h3 style="margin-bottom: 16px; color: var(--primary);"><?= $lang === 'de' ? 'Neue Lizenz' : 'New License' ?></h3>
        <p style="color: var(--text-muted); margin-bottom: 32px; font-size: 0.95rem;"><?= $lang === 'de' ? 'Kaufen und aktivieren Sie Ihre Software sofort.' : 'Purchase and activate your software instantly.' ?></p>
        <a href="/software-activation" class="btn-modern btn-accent" style="width: 100%;"><?= $lang === 'de' ? 'Software aktivieren' : 'Activate Software' ?></a>
      </div>
      <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        <h3 style="margin-bottom: 16px; color: var(--primary);"><?= $lang === 'de' ? 'Status prüfen' : 'Check Status' ?></h3>
        <p style="color: var(--text-muted); margin-bottom: 32px; font-size: 0.95rem;"><?= $lang === 'de' ? 'Verfolgen Sie Ihre Aktivierung mit Ihrem Token.' : 'Track your activation with your token.' ?></p>
        <a href="/software-activation" class="btn-modern btn-white" style="width: 100%; border: 2px solid var(--primary);"><?= $lang === 'de' ? 'Status prüfen' : 'Check Status' ?></a>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: var(--primary); color: white;">
  <div class="container-modern text-center">
    <h2 style="font-size: 3rem; margin-bottom: 24px;"><?= $lang === 'de' ? 'Benötigen Sie ein individuelles Angebot?' : 'Need a Custom Quote?' ?></h2>
    <p style="color: rgba(255,255,255,0.7); font-size: 1.25rem; max-width: 700px; margin: 0 auto 48px;">
      <?= $lang === 'de' 
        ? 'Unser Ingenieurteam hilft Ihnen, die perfekte Lösung für Ihre Projektanforderungen zu finden.'
        : 'Our engineering team can help you find the perfect solution for your project requirements.' ?>
    </p>
    <div style="display: flex; justify-content: center; gap: 24px; flex-wrap: wrap;">
      <a href="/quote" class="btn-modern btn-accent" style="padding: 18px 48px; font-size: 1.1rem;"><?= $lang === 'de' ? 'Angebot anfordern' : 'Request a Quote' ?></a>
      <a href="/contact" class="btn-modern btn-white" style="padding: 18px 48px; font-size: 1.1rem;"><?= $lang === 'de' ? 'Vertrieb kontaktieren' : 'Contact Sales' ?></a>
    </div>
  </div>
</section>ieren' : 'Contact Sales' ?></a>
    </div>
  </div>
</section>
