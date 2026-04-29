<div class="container-modern section-padding faq-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <span class="current"><?= $lang === 'de' ? 'Häufige Fragen' : 'Knowledge Base' ?></span>
  </div>

  <section class="page-hero-modern bg-primary color-white mb-80">
    <div class="hero-bg-overlay faq"></div>
    <div class="hero-content-modern relative z-1 p-60">
      <div class="badge badge-accent mb-24 tracking-widest">
        Institutional Support
      </div>
      <h1 class="text-4xl font-black mb-16"><?= $lang === 'de' ? 'Häufig gestellte Fragen' : 'Institutional FAQ' ?></h1>
      <p class="text-xl opacity-90 font-medium max-w-600">
        <?= $lang === 'de' 
            ? 'Alles Wissenswerte über unsere interdisziplinären Prozesse, Bestellabläufe und weltweiten Versand.' 
            : 'Comprehensive guidance on our interdisciplinary processes, corporate procurement, and global logistics.' ?>
      </p>
    </div>
  </section>

  <div class="max-w-1000 mx-auto">
    <?php 
    $sections = [
      ['icon' => 'shopping-cart', 'title' => 'Corporate Procurement', 'qs' => [
        ['q' => 'How do I initiate a corporate procurement process?', 'a' => 'You can initiate procurement by selecting components through our catalog or requesting a custom institutional quote. Our system handles B2B authentication and specialized pricing tiers.'],
        ['q' => 'What institutional payment protocols are supported?', 'a' => 'We primarily utilize SEPA and international bank transfers (SWIFT) for industrial transactions. Detailed banking coordinates are provided with the proforma invoice.'],
      ]],
      ['icon' => 'truck', 'title' => 'Global Logistics & Delivery', 'qs' => [
        ['q' => 'What is the scope of your international shipping?', 'a' => 'STREICHER Group provides worldwide logistics to over 50 countries, including specialized heavy-duty transport and customs clearance support for large-scale equipment.'],
        ['q' => 'What are the standard industrial lead times?', 'a' => 'Inventory items typically ship within 14 business days. Custom-engineered machinery and large-scale plant components require structured manufacturing timelines, typically 8-16 weeks.'],
      ]],
      ['icon' => 'factory', 'title' => 'Technical Compliance & Standards', 'qs' => [
        ['q' => 'Are all products certified to international standards?', 'a' => 'Yes, our engineering output complies with rigorous global standards, including ISO 9001:2015, API Spec Q1, and European pressure equipment directives.'],
        ['q' => 'Do you provide on-site technical integration?', 'a' => 'Absolutely. We offer interdisciplinary field service technicians for global site integration, commissioning, and long-term maintenance contracts.'],
      ]],
    ];
    foreach ($sections as $sec):
    ?>
    <div class="card-modern no-padding overflow-hidden shadow-md mb-50">
      <div class="px-48 py-32 bg-light border-bottom flex-between items-center">
        <h3 class="text-xl font-black color-primary mb-0 flex items-center gap-16">
          <i data-lucide="<?= $sec['icon'] ?>" size="28" class="text-accent"></i>
          <?= $sec['title'] ?>
        </h3>
        <?php render_component('badge', [
          'label' => 'Section Insight',
          'variant' => 'info',
          'size' => 'sm'
        ]); ?>
      </div>
      <div class="p-48">
        <?php foreach ($sec['qs'] as $q): ?>
        <div class="mb-40 border-bottom border-subtle pb-40">
          <h4 class="text-lg font-black color-primary mb-16 leading-relaxed"><?= $q['q'] ?></h4>
          <p class="text-muted font-medium leading-relaxed mb-0"><?= $q['a'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- CTA -->
    <div class="bg-primary border-radius-lg p-100 px-60 text-center color-white shadow-lg mt-80 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent"></div>
      <h3 class="text-4xl font-black color-white mb-24 relative z-1">Still Require Technical Clarity?</h3>
      <p class="text-lg color-white opacity-80 mb-50 max-w-700 mx-auto font-medium leading-relaxed relative z-1">Our interdisciplinary sales engineers are available for direct technical consultations regarding your specific project requirements.</p>
      <div class="flex-center gap-32 relative z-1">
        <?php render_component('button', [
          'href' => '/contact',
          'variant' => 'accent',
          'label' => 'Contact Global Sales',
          'class' => 'px-50 py-20 font-black uppercase tracking-widest'
        ]); ?>
        <?php render_component('button', [
          'href' => '/support',
          'variant' => 'outline',
          'label' => 'Technical Support',
          'class' => 'px-50 py-20 font-black uppercase tracking-widest border-white/30 text-white hover-bg-white/10'
        ]); ?>
      </div>
    </div>
  </div>
</div>
