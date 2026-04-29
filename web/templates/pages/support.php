<div class="container-modern section-padding support-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <span class="current"><?= $lang === 'de' ? 'Technischer Support' : 'Institutional Support' ?></span>
  </div>

  <section class="page-hero-modern bg-primary color-white mb-80">
    <div class="hero-bg-overlay support"></div>
    <div class="hero-content-modern relative z-1 p-60">
      <div class="badge badge-accent mb-24 tracking-widest">
        Engineering Support
      </div>
      <h1 class="text-4xl font-black mb-16"><?= $lang === 'de' ? 'Technischer Support' : 'Interdisciplinary Support' ?></h1>
      <p class="text-xl opacity-90 font-medium max-w-600">
        <?= $lang === 'de' 
            ? 'Weltweite Experten-Unterstützung für Ihre interdisziplinären Anlagen – rund um die Uhr.' 
            : 'Subject matter expertise for your interdisciplinary systems – accessible globally, 24/7.' ?>
      </p>
    </div>
  </section>

  <!-- Support Protocols -->
  <div class="grid grid-3 gap-40 mb-100">
    <div class="card-modern p-60 px-40 text-center hover-lift">
      <div class="p-24 bg-light border-radius-full inline-block mb-32">
        <i data-lucide="messages-square" size="48" class="text-accent"></i>
      </div>
      <h3 class="text-xl font-black color-primary mb-16">Engineering Chat</h3>
      <p class="text-muted font-medium leading-relaxed mb-32">Direct synchronous access to our senior engineering desk for immediate technical resolution.</p>
      <?php render_component('button', [
        'href' => '/contact',
        'variant' => 'accent',
        'label' => 'Initialize Tech Chat',
        'class' => 'w-full font-black uppercase tracking-widest'
      ]); ?>
    </div>

    <div class="card-modern p-60 px-40 text-center hover-lift">
      <div class="p-24 bg-light border-radius-full inline-block mb-32">
        <i data-lucide="mail" size="48" class="text-accent"></i>
      </div>
      <h3 class="text-xl font-black color-primary mb-16">Technical Inquiry</h3>
      <p class="text-muted font-medium leading-relaxed mb-32">Formal submission for complex interdisciplinary specifications and documentation requests.</p>
      <div class="font-black text-accent text-lg tracking-wide">tech.support@streicher-group.com</div>
    </div>

    <div class="card-modern bg-primary color-white p-60 px-40 text-center hover-lift-accent border border-accent shadow-lg">
      <div class="p-24 bg-white/10 border-radius-full inline-block mb-32">
        <i data-lucide="phone-call" size="48" class="text-accent"></i>
      </div>
      <h3 class="text-xl font-black color-white mb-16">Emergency Hotline</h3>
      <p class="text-white/70 font-medium leading-relaxed mb-32">24/7 Rapid response protocol for critical industrial equipment failures and site safety.</p>
      <div class="font-black text-accent text-2xl tracking-wide">+49 941 123 456-99</div>
    </div>
  </div>

  <!-- Technical Ticket Infrastructure -->
  <div class="card-modern no-padding overflow-hidden shadow-lg mb-120">
    <div class="px-48 py-32 bg-light border-bottom flex-between items-center">
      <h2 class="text-2xl font-black color-primary mb-0">System Support Ticket</h2>
      <?php render_component('badge', [
        'label' => 'ENCRYPTION ACTIVE',
        'variant' => 'accent'
      ]); ?>
    </div>
    <div class="p-80">
      <form action="/support" method="POST" class="grid gap-32">
        <div class="grid grid-3 gap-32">
          <?php render_component('form_field', [
            'label' => 'Authorized Representative',
            'name' => 'name',
            'required' => true,
            'placeholder' => 'John Doe'
          ]); ?>
          <?php render_component('form_field', [
            'label' => 'Legal Entity / Company',
            'name' => 'company',
            'required' => true,
            'placeholder' => 'Global Industrial AG'
          ]); ?>
          <?php render_component('form_field', [
            'label' => 'Corporate Email',
            'name' => 'email',
            'type' => 'email',
            'required' => true,
            'placeholder' => 'representative@company.com'
          ]); ?>
        </div>
        
        <div class="grid grid-2 gap-32">
          <?php render_component('form_field', [
            'label' => 'Equipment Asset ID / SKU',
            'name' => 'equipment',
            'placeholder' => 'e.g. STR-SYS-5000-X'
          ]); ?>
          <div class="form-group-modern">
            <label class="label-modern mb-16">Operational Priority Level</label>
            <div class="relative">
              <select name="priority" class="input-modern w-full appearance-none font-bold">
                <option value="low">Institutional Inquiry (Non-Critical)</option>
                <option value="medium">Standard Issue (Ops Affected)</option>
                <option value="high">Critical Failure (Equipment Down)</option>
                <option value="critical">Severe Risk (Safety Protocol Active)</option>
              </select>
              <i data-lucide="chevron-down" class="absolute right-16 top-50 translate-y-n50 opacity-40" size="18"></i>
            </div>
          </div>
        </div>
        
        <div class="form-group-modern">
          <label class="label-modern mb-16">Detailed Technical Description</label>
          <textarea name="issue" rows="6" required class="input-modern w-full h-auto py-24 resize-none" placeholder="Outline the technical specifications, interdisciplinary symptoms, and site conditions..."></textarea>
        </div>
        
        <div class="mt-24">
          <?php render_component('button', [
            'type' => 'submit',
            'variant' => 'accent',
            'label' => 'Transmit Support Request',
            'class' => 'px-60 py-24 text-lg font-black uppercase tracking-widest'
          ]); ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Interdisciplinary Resource Library -->
  <div class="grid grid-4 gap-32 mb-100">
    <?php 
    $resources = [
      ['icon' => 'book-open', 'title' => 'Technical Manuals'],
      ['icon' => 'settings', 'title' => 'System Components'],
      ['icon' => 'video', 'title' => 'Integration Guides'],
      ['icon' => 'help-circle', 'title' => 'Knowledge Base'],
    ];
    foreach ($resources as $res):
    ?>
    <div class="card-modern p-40 text-center hover-lift shadow-sm">
      <div class="p-20 bg-light border-radius-full inline-block mb-24">
        <i data-lucide="<?= $res['icon'] ?>" size="40" class="text-accent"></i>
      </div>
      <div class="font-black color-primary text-base uppercase tracking-wider"><?= $res['title'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
