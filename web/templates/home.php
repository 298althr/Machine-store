<!-- Hero Section - Updated <?= date('Y-m-d H:i:s') ?> -->
<section style="
    background: linear-gradient(rgba(15, 23, 42, 0.6), rgba(30, 41, 59, 0.7)), 
                url('/images/photos/drilling.jpg') center/cover fixed;
    color: white;
    padding: 120px 40px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    margin: 0;
">
  <div style="width: 100%; max-width: 1400px; margin: 0 auto;">
    <div style="max-width: 90%;">
      <div style="
        display: inline-block;
        background: rgba(220, 38, 38, 0.3);
        border: 1px solid rgba(220, 38, 38, 0.6);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 32px;
      ">
        <span>🇩🇪 <?= $lang === 'de' ? 'Deutsche Ingenieurskunst' : 'German Engineering Excellence' ?></span>
      </div>
      
      <h1 style="
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 800;
        margin: 0 0 32px 0;
        line-height: 1.1;
        letter-spacing: -1px;
        text-shadow: 0 4px 12px rgba(0,0,0,0.8);
        max-width: 100%;
      ">
        <?= $lang === 'de' ? 'Premium Industrieausrüstung für Öl & Gas' : 'Premium Industrial Equipment for Oil & Gas' ?>
      </h1>
      
      <p style="
        font-size: clamp(1.1rem, 2vw, 1.4rem);
        color: #e2e8f0;
        margin-bottom: 48px;
        line-height: 1.6;
        text-shadow: 0 2px 8px rgba(0,0,0,0.7);
        max-width: 700px;
      ">
        <?= $lang === 'de' 
          ? 'Streicher GmbH liefert erstklassige Hydrauliksysteme, Bohrausrüstung und Pipeline-Komponenten, denen Branchenführer weltweit seit 1972 vertrauen.'
          : 'Streicher GmbH delivers world-class hydraulic systems, drilling equipment, and pipeline components trusted by industry leaders worldwide since 1972.' ?>
      </p>
      
      <div style="display: flex; gap: 20px; margin-bottom: 60px; flex-wrap: wrap;">
        <a href="/catalog" style="
          background: #dc2626;
          color: white;
          padding: 16px 32px;
          border: none;
          border-radius: 8px;
          font-size: 1.1rem;
          font-weight: 600;
          cursor: pointer;
          text-decoration: none;
          display: inline-block;
          box-shadow: 0 6px 12px rgba(0,0,0,0.2);
          transition: all 0.15s ease;
        " onmouseover="this.style.background='#b91c1c'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#dc2626'; this.style.transform='translateY(0)';">
          <?= $lang === 'de' ? 'Katalog durchsuchen' : 'Browse Catalog' ?>
        </a>
        <a href="/quote" style="
          background: transparent;
          color: white;
          padding: 16px 32px;
          border: 2px solid white;
          border-radius: 8px;
          font-size: 1.1rem;
          font-weight: 600;
          cursor: pointer;
          text-decoration: none;
          display: inline-block;
          transition: all 0.15s ease;
        " onmouseover="this.style.background='white'; this.style.color='#1e293b';" onmouseout="this.style.background='transparent'; this.style.color='white';">
          <?= $lang === 'de' ? 'Angebot anfordern' : 'Request Quote' ?>
        </a>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; max-width: 800px;">
        <div style="text-align: center;">
          <div style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: #fca5a5; text-shadow: 0 2px 8px rgba(0,0,0,0.8);">50+</div>
          <div style="font-size: clamp(0.9rem, 1.5vw, 1rem); color: #e2e8f0; margin-top: 8px;"><?= $lang === 'de' ? 'Jahre Erfahrung' : 'Years Experience' ?></div>
        </div>
        <div style="text-align: center;">
          <div style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: #fca5a5; text-shadow: 0 2px 8px rgba(0,0,0,0.8);">100+</div>
          <div style="font-size: clamp(0.9rem, 1.5vw, 1rem); color: #e2e8f0; margin-top: 8px;"><?= $lang === 'de' ? 'Produkte' : 'Products' ?></div>
        </div>
        <div style="text-align: center;">
          <div style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: #fca5a5; text-shadow: 0 2px 8px rgba(0,0,0,0.8);">45+</div>
          <div style="font-size: clamp(0.9rem, 1.5vw, 1rem); color: #e2e8f0; margin-top: 8px;"><?= $lang === 'de' ? 'Länder' : 'Countries' ?></div>
        </div>
      </div>
    </div>
  </div>
  
  <div style="position: absolute; top: 20px; right: 20px;">
    <?php include __DIR__ . '/components/currency-toggle.php'; ?>
  </div>
</section>

<!-- Trusted By Section -->
<section class="clients-section">
  <div class="section-container">
    <p class="clients-label"><?= $lang === 'de' ? 'Vertraut von führenden Unternehmen weltweit' : 'Trusted by leading companies worldwide' ?></p>
    <div class="clients-grid">
      <div class="client-logo" title="Shell">
        <img src="https://logo.clearbit.com/shell.com" alt="Shell" onerror="this.parentElement.innerHTML='<span>Shell</span>'">
      </div>
      <div class="client-logo" title="BP">
        <img src="https://logo.clearbit.com/bp.com" alt="BP" onerror="this.parentElement.innerHTML='<span>BP</span>'">
      </div>
      <div class="client-logo" title="ExxonMobil">
        <img src="https://logo.clearbit.com/exxonmobil.com" alt="ExxonMobil" onerror="this.parentElement.innerHTML='<span>ExxonMobil</span>'">
      </div>
      <div class="client-logo" title="Chevron">
        <img src="https://logo.clearbit.com/chevron.com" alt="Chevron" onerror="this.parentElement.innerHTML='<span>Chevron</span>'">
      </div>
      <div class="client-logo" title="TotalEnergies">
        <img src="https://logo.clearbit.com/totalenergies.com" alt="TotalEnergies" onerror="this.parentElement.innerHTML='<span>TotalEnergies</span>'">
      </div>
      <div class="client-logo" title="ConocoPhillips">
        <img src="https://logo.clearbit.com/conocophillips.com" alt="ConocoPhillips" onerror="this.parentElement.innerHTML='<span>ConocoPhillips</span>'">
      </div>
      <div class="client-logo" title="Equinor">
        <img src="https://logo.clearbit.com/equinor.com" alt="Equinor" onerror="this.parentElement.innerHTML='<span>Equinor</span>'">
      </div>
      <div class="client-logo" title="Eni">
        <img src="https://logo.clearbit.com/eni.com" alt="Eni" onerror="this.parentElement.innerHTML='<span>Eni</span>'">
      </div>
      <div class="client-logo" title="Petrobras">
        <img src="https://logo.clearbit.com/petrobras.com.br" alt="Petrobras" onerror="this.parentElement.innerHTML='<span>Petrobras</span>'">
      </div>
      <div class="client-logo" title="Saudi Aramco">
        <img src="https://logo.clearbit.com/aramco.com" alt="Saudi Aramco" onerror="this.parentElement.innerHTML='<span>Aramco</span>'">
      </div>
    </div>
  </div>
</section>

<!-- Categories -->
<section class="categories-section">
  <div class="section-container">
    <div class="section-header">
      <h2 class="section-title"><?= $lang === 'de' ? 'Produktkategorien' : 'Product Categories' ?></h2>
      <p class="section-subtitle"><?= $lang === 'de' ? 'Entdecken Sie unser umfassendes Sortiment an Industrieausrüstung' : 'Explore our comprehensive range of industrial equipment' ?></p>
    </div>
    
    <div class="categories-grid">
      <?php 
      $categoryIcons = [
        'pipelines-plants' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h16M4 6v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6M4 18h16M4 18v-4c0-1.1.9-2 2-2h12c1.1 0 2 .9 2 2v4"/></svg>',
        'mechanical-engineering' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>',
        'electrical-engineering' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
        'civil-engineering' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 20h20M4 20V10l8-6 8 6v10M9 20v-6h6v6"/><rect x="9" y="10" width="6" height="4"/></svg>',
        'raw-materials' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>',
        'drilling-technology' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L12 22M8 6h8M6 10h12M4 14h16M8 18h8"/><circle cx="12" cy="4" r="2"/></svg>',
        'hydraulic-systems' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>',
        'instrumentation' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="6"/></svg>',
        'engineering-software' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M7 8l3 3-3 3M12 14h5"/></svg>',
        'aviation-engineering' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 00-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>',
      ];
      foreach ($categories as $cat): 
        $icon = $categoryIcons[$cat['slug']] ?? '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>';
      ?>
      <a href="/catalog?category=<?= htmlspecialchars($cat['slug']) ?>" class="category-card">
        <div class="category-icon"><?= $icon ?></div>
        <h3 class="category-name"><?= htmlspecialchars($cat['name']) ?></h3>
        <p class="category-desc"><?= htmlspecialchars($cat['description'] ?? '') ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="products-section">
  <div class="section-container">
    <div class="section-header-flex">
      <div>
        <h2 class="section-title"><?= $lang === 'de' ? 'Ausgewählte Produkte' : 'Featured Products' ?></h2>
        <p class="section-subtitle"><?= $lang === 'de' ? 'Hochleistungsausrüstung für anspruchsvolle Anwendungen' : 'High-performance equipment for demanding applications' ?></p>
      </div>
      <a href="/catalog" class="btn btn-outline"><?= $lang === 'de' ? 'Alle Produkte →' : 'View All Products →' ?></a>
    </div>
    
    <div class="product-grid">
      <?php foreach ($products as $product): ?>
      <div class="product-card">
        <div class="product-card-image" style="<?= !empty($product['image_url']) ? 'background-image: url(' . htmlspecialchars($product['image_url']) . '); background-size: cover; background-position: center;' : '' ?>">
          <?php if (!empty($product['is_featured'])): ?>
          <span class="product-badge"><?= $lang === 'de' ? 'Empfohlen' : 'Featured' ?></span>
          <?php endif; ?>
          <?php if (empty($product['image_url'])): ?>
          <div class="placeholder-icon">⚙️</div>
          <?php endif; ?>
        </div>
        <div class="product-card-body">
          <div class="product-category"><?= htmlspecialchars($product['category_name'] ?? 'Equipment') ?></div>
          <h3 class="product-card-title">
            <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>"><?= htmlspecialchars($product['name']) ?></a>
          </h3>
          <p class="product-card-desc"><?= htmlspecialchars($product['short_desc'] ?? '') ?></p>
          <div class="product-card-footer">
            <div>
              <span class="product-price-label"><?= $lang === 'de' ? 'Ab' : 'Starting at' ?></span>
              <span class="product-price"><?= format_price((float)$product['unit_price']) ?></span>
            </div>
            <a href="/product?sku=<?= htmlspecialchars($product['sku']) ?>" class="btn btn-sm btn-primary"><?= $lang === 'de' ? 'Details' : 'View' ?></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Software Activation Section -->
<section class="software-activation-section" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 4rem 0;">
  <div class="section-container">
    <div class="section-header">
      <div style="display: inline-flex; align-items: center; gap: 0.75rem; background: #059669; color: white; padding: 0.5rem 1.5rem; border-radius: 2rem; margin-bottom: 1rem;">
        <div style="font-size: 1.25rem;">🔑</div>
        <span style="font-weight: 600;">Software Activation</span>
      </div>
      <h2 class="section-title" style="color: #064e3b;"><?= $lang === 'de' ? 'Software-Lizenz aktivieren' : 'Activate Your Software License' ?></h2>
      <p class="section-subtitle" style="color: #047857;">
        <?= $lang === 'de' 
          ? 'Kaufen und aktivieren Sie Ihre Software-Lizenzen sicher und einfach online.'
          : 'Purchase and activate your software licenses securely and easily online.' ?>
      </p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
      <!-- Activation Card -->
      <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #dcfce7;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
          <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            🔑
          </div>
          <div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #064e3b; margin-bottom: 0.25rem;">
              <?= $lang === 'de' ? 'Neue Lizenz aktivieren' : 'Activate New License' ?>
            </h3>
            <p style="color: #047857; font-size: 0.875rem;">
              <?= $lang === 'de' ? 'Kaufen und aktivieren Sie Ihre Software' : 'Purchase and activate your software' ?>
            </p>
          </div>
        </div>
        
        <div style="color: #374151; font-size: 0.875rem; line-height: 1.6; margin-bottom: 1.5rem;">
          <?= $lang === 'de' 
            ? 'Wählen Sie Ihre Software, bezahlen Sie sicher per Kreditkarte oder Google Play Gift Card und erhalten Sie sofort Ihre Lizenz.'
            : 'Choose your software, pay securely via credit card or Google Play gift card, and receive your license instantly.' ?>
        </div>
        
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
          <span style="background: #f0fdf4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 500;">
            ✓ <?= $lang === 'de' ? 'Sichere Zahlung' : 'Secure Payment' ?>
          </span>
          <span style="background: #f0fdf4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 500;">
            ✓ <?= $lang === 'de' ? 'Sofortige Aktivierung' : 'Instant Activation' ?>
          </span>
          <span style="background: #f0fdf4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 500;">
            ✓ <?= $lang === 'de' ? '24/7 Support' : '24/7 Support' ?>
          </span>
        </div>
        
        <a href="/software-activation" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none; padding: 0.875rem 2rem; font-weight: 600; border-radius: 0.5rem; text-decoration: none; display: inline-block; text-align: center; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(5, 150, 105, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
          <?= $lang === 'de' ? 'Software aktivieren' : 'Activate Software' ?> →
        </a>
      </div>
      
      <!-- Status Check Card -->
      <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #dcfce7;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
          <div style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            🔍
          </div>
          <div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #064e3b; margin-bottom: 0.25rem;">
              <?= $lang === 'de' ? 'Lizenzstatus prüfen' : 'Check License Status' ?>
            </h3>
            <p style="color: #047857; font-size: 0.875rem;">
              <?= $lang === 'de' ? 'Verfolgen Sie Ihre Aktivierung' : 'Track your activation' ?>
            </p>
          </div>
        </div>
        
        <div style="color: #374151; font-size: 0.875rem; line-height: 1.6; margin-bottom: 1.5rem;">
          <?= $lang === 'de' 
            ? 'Überprüfen Sie den Status Ihrer Software-Aktivierung mit Ihrem Token. Verfolgen Sie den Fortschritt von der Zahlung bis zur Lizenzierung.'
            : 'Check the status of your software activation with your token. Track progress from payment to licensing.' ?>
        </div>
        
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
          <span style="background: #f0fdf4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 500;">
            ✓ <?= $lang === 'de' ? 'Echtzeit-Status' : 'Real-time Status' ?>
          </span>
          <span style="background: #f0fdf4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 500;">
            ✓ <?= $lang === 'de' ? 'Token-Tracking' : 'Token Tracking' ?>
          </span>
          <span style="background: #f0fdf4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 500;">
            ✓ <?= $lang === 'de' ? 'Lizenz-Download' : 'License Download' ?>
          </span>
        </div>
        
        <a href="/software-activation" class="btn btn-outline" style="background: white; color: #059669; border: 2px solid #059669; padding: 0.875rem 2rem; font-weight: 600; border-radius: 0.5rem; text-decoration: none; display: inline-block; text-align: center; transition: all 0.2s ease;" onmouseover="this.style.background='#059669'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#059669';">
          <?= $lang === 'de' ? 'Status prüfen' : 'Check Status' ?> →
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Trust Badges -->
<section class="trust-section">
  <div class="section-container">
    <h2 class="trust-title"><?= $lang === 'de' ? 'Warum Streicher wählen?' : 'Why Choose Streicher?' ?></h2>
    <div class="trust-grid">
      <div class="trust-item">
        <div class="trust-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="6"/><path d="M12 14v8M8 22h8"/></svg>
        </div>
        <div class="trust-content">
          <div class="trust-name">ISO 9001:2015</div>
          <div class="trust-desc"><?= $lang === 'de' ? 'Qualitätszertifiziert' : 'Quality Certified' ?></div>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div class="trust-content">
          <div class="trust-name">API <?= $lang === 'de' ? 'Zertifiziert' : 'Certified' ?></div>
          <div class="trust-desc"><?= $lang === 'de' ? 'Öl & Gas Standards' : 'Oil & Gas Standards' ?></div>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
        </div>
        <div class="trust-content">
          <div class="trust-name"><?= $lang === 'de' ? 'Weltweiter Versand' : 'Global Shipping' ?></div>
          <div class="trust-desc"><?= $lang === 'de' ? '45+ Länder' : '45+ Countries' ?></div>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L3 7v6c0 5.5 3.8 10.3 9 12 5.2-1.7 9-6.5 9-12V7l-9-5z"/></svg>
        </div>
        <div class="trust-content">
          <div class="trust-name"><?= $lang === 'de' ? '24 Monate Garantie' : '24-Month Warranty' ?></div>
          <div class="trust-desc"><?= $lang === 'de' ? 'Volle Abdeckung' : 'Full Coverage' ?></div>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
        </div>
        <div class="trust-content">
          <div class="trust-name"><?= $lang === 'de' ? '24/7 Support' : '24/7 Support' ?></div>
          <div class="trust-desc"><?= $lang === 'de' ? 'Expertenunterstützung' : 'Expert Assistance' ?></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="section-container">
    <h2 class="cta-title"><?= $lang === 'de' ? 'Benötigen Sie maßgeschneiderte Ausrüstung oder Mengenrabatte?' : 'Need Custom Equipment or Bulk Pricing?' ?></h2>
    <p class="cta-subtitle">
      <?= $lang === 'de' 
        ? 'Unser Ingenieurteam hilft Ihnen, die perfekte Lösung für Ihre Projektanforderungen zu finden.'
        : 'Our engineering team can help you find the perfect solution for your project requirements.' ?>
    </p>
    <div class="cta-buttons">
      <a href="/quote" class="btn btn-primary btn-lg"><?= $lang === 'de' ? 'Angebot anfordern' : 'Request a Quote' ?></a>
      <a href="/contact" class="btn btn-secondary btn-lg"><?= $lang === 'de' ? 'Vertrieb kontaktieren' : 'Contact Sales' ?></a>
    </div>
  </div>
</section>
