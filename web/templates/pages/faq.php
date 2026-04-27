<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Häufige Fragen' : 'Knowledge Base' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 350px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1454165833767-027ffea9e77b?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Support
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 16px;"><?= $lang === 'de' ? 'Häufig gestellte Fragen' : 'Institutional FAQ' ?></h1>
      <p style="font-size: 1.2rem; opacity: 0.9; max-width: 600px;">
        <?= $lang === 'de' 
            ? 'Alles Wissenswerte über unsere interdisziplinären Prozesse, Bestellabläufe und weltweiten Versand.' 
            : 'Comprehensive guidance on our interdisciplinary processes, corporate procurement, and global logistics.' ?>
      </p>
    </div>
  </section>

  <div style="max-width: 1000px; margin: 0 auto;">
    <?php 
    $sections = [
      ['icon' => '🛒', 'title' => 'Corporate Procurement', 'qs' => [
        ['q' => 'How do I initiate a corporate procurement process?', 'a' => 'You can initiate procurement by selecting components through our catalog or requesting a custom institutional quote. Our system handles B2B authentication and specialized pricing tiers.'],
        ['q' => 'What institutional payment protocols are supported?', 'a' => 'We primarily utilize SEPA and international bank transfers (SWIFT) for industrial transactions. Detailed banking coordinates are provided with the proforma invoice.'],
      ]],
      ['icon' => '🚚', 'title' => 'Global Logistics & Delivery', 'qs' => [
        ['q' => 'What is the scope of your international shipping?', 'a' => 'STREICHER Group provides worldwide logistics to over 50 countries, including specialized heavy-duty transport and customs clearance support for large-scale equipment.'],
        ['q' => 'What are the standard industrial lead times?', 'a' => 'Inventory items typically ship within 14 business days. Custom-engineered machinery and large-scale plant components require structured manufacturing timelines, typically 8-16 weeks.'],
      ]],
      ['icon' => '🏭', 'title' => 'Technical Compliance & Standards', 'qs' => [
        ['q' => 'Are all products certified to international standards?', 'a' => 'Yes, our engineering output complies with rigorous global standards, including ISO 9001:2015, API Spec Q1, and European pressure equipment directives.'],
        ['q' => 'Do you provide on-site technical integration?', 'a' => 'Absolutely. We offer interdisciplinary field service technicians for global site integration, commissioning, and long-term maintenance contracts.'],
      ]],
    ];
    foreach ($sections as $sec):
    ?>
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 50px;">
      <div style="padding: 32px 48px; background: #f8fafc; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 16px;">
          <span style="font-size: 2rem;"><?= $sec['icon'] ?></span> <?= $sec['title'] ?>
        </h3>
        <span style="color: var(--accent); font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Section Insight</span>
      </div>
      <div style="padding: 48px;">
        <?php foreach ($sec['qs'] as $q): ?>
        <div style="margin-bottom: 40px; border-bottom: 1px solid rgba(0,0,0,0.03); padding-bottom: 40px;">
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--primary); margin-bottom: 16px; line-height: 1.4;"><?= $q['q'] ?></h4>
          <p style="color: var(--text-muted); line-height: 1.8; font-size: 1rem; margin: 0;"><?= $q['a'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- CTA -->
    <div style="background: var(--primary); border-radius: var(--radius-lg); padding: 100px 60px; text-align: center; color: white; box-shadow: var(--shadow-lg); margin-top: 80px; position: relative; overflow: hidden;">
      <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(45deg, rgba(255,255,255,0.05) 0%, transparent 100%);"></div>
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; color: white; margin-bottom: 24px;">Still Require Technical Clarity?</h3>
      <p style="color: rgba(255,255,255,0.8); font-size: 1.2rem; margin-bottom: 50px; max-width: 700px; margin: 0 auto 50px; line-height: 1.6;">Our interdisciplinary sales engineers are available for direct technical consultations regarding your specific project requirements.</p>
      <div style="display: flex; gap: 32px; justify-content: center; position: relative; z-index: 1;">
        <a href="/contact" class="btn-modern btn-accent" style="padding: 20px 50px; font-weight: 800;">Contact Global Sales</a>
        <a href="/support" class="btn-modern" style="padding: 20px 50px; border: 2px solid rgba(255,255,255,0.3); color: white; background: transparent; font-weight: 800;">Technical Support</a>
      </div>
    </div>
  </div>
</div>
