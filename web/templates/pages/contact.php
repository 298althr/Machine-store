<div class="container-modern section-padding contact-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <span class="current"><?= $lang === 'de' ? 'Interdisziplinärer Kontakt' : 'Institutional Contact Registry' ?></span>
  </div>

  <section class="page-hero-modern bg-primary color-white mb-100">
    <div class="hero-bg-overlay contact"></div>
    <div class="hero-content-modern relative z-1 p-100 max-w-800">
      <div class="badge badge-accent mb-32 tracking-widest">
        Global Communication Matrix
      </div>
      <h1 class="text-5xl mb-32">Connect With<br><span class="text-accent">Technical Experts.</span></h1>
      <p class="text-xl opacity-70 font-medium">
        Our interdisciplinary engineering and strategic sales teams provide technical guidance for complex global industrial requirements.
      </p>
    </div>
  </section>

  <?php if (!empty($success)): ?>
  <div class="alert alert-success p-60 mb-80 border-radius-lg flex items-center gap-48 shadow-xl relative overflow-hidden">
    <div class="absolute top--50 right--50 text-9xl opacity-03 text-success font-black font-display">✓</div>
    <div class="status-icon-wrapper bg-white shadow-lg relative z-1 text-5xl">
      <i data-lucide="check-circle" size="64" class="text-success"></i>
    </div>
    <div class="relative z-1">
      <h3 class="text-2xl font-black color-primary mb-12">Protocol Dispatch Successful</h3>
      <p class="text-muted text-lg mb-0 font-medium leading-relaxed">Your technical inquiry has been entered into our global routing matrix. An authorized representative will respond within 24 business cycles.</p>
    </div>
  </div>
  <?php endif; ?>

  <div class="grid grid-contact max-w-1400 mx-auto mb-150">
    <!-- Global Inquiry Portal -->
    <div class="card-modern no-padding overflow-hidden flex flex-col shadow-2xl">
      <div class="p-60 px-80 bg-light border-bottom relative overflow-hidden">
        <div class="absolute top--20 left--20 text-9xl opacity-02 font-black font-display pointer-events-none">INQ</div>
        <h3 class="text-2xl font-black color-primary mb-0 relative z-1">Initialize Technical Inquiry</h3>
        <p class="text-accent text-xs font-black uppercase tracking-widest mt-12 relative z-1">Interdisciplinary Communication Protocol Matrix</p>
      </div>
      <div class="p-80 flex-1">
        <form action="/contact" method="POST" class="grid grid-2 gap-40">
          <?php render_component('form_field', [
            'label' => 'Representative Full Name',
            'name' => 'name',
            'required' => true
          ]); ?>
          <?php render_component('form_field', [
            'label' => 'Institutional Entity',
            'name' => 'company'
          ]); ?>
          <?php render_component('form_field', [
            'label' => 'Technical Email Identifier',
            'name' => 'email',
            'type' => 'email',
            'required' => true,
            'placeholder' => 'representative@institution.com'
          ]); ?>
          <div class="form-group-modern">
            <label class="label-modern mb-16">Inquiry Subject Protocol</label>
            <div class="relative">
              <select name="subject" required class="input-modern w-full appearance-none">
                <option value="sales">Interdisciplinary Sales & Asset Inquiry</option>
                <option value="support">Engineering & Technical Support</option>
                <option value="quote">Institutional Bulk Procurement Quote</option>
                <option value="partnership">Strategic Global Partnership</option>
              </select>
              <i data-lucide="chevron-down" class="absolute right-16 top-50 translate-y-n50 opacity-40" size="18"></i>
            </div>
          </div>
          <div class="form-group-modern span-2">
            <label class="label-modern mb-16">Technical Requirement Details</label>
            <textarea name="message" rows="8" required class="input-modern w-full h-auto py-24 resize-none" placeholder="Outline your interdisciplinary industrial specifications, technical requirements or project parameters..."></textarea>
          </div>
          <div class="span-2 mt-24">
            <?php render_component('button', [
              'type' => 'submit',
              'variant' => 'accent',
              'label' => 'Dispatch Technical Inquiry Matrix',
              'class' => 'w-full h-80 text-lg uppercase tracking-widest font-black shadow-lg'
            ]); ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Institutional Info Grid -->
    <div class="grid gap-40">
      <div class="card-modern p-60 shadow-2xl relative overflow-hidden">
        <div class="absolute top--50 right--50 text-9xl opacity-02 font-black font-display">HQ</div>
        <h3 class="text-2xl font-black color-primary mb-60">Global Headquarters</h3>
        
        <div class="flex gap-32 mb-48 items-start">
          <div class="p-16 bg-light border-radius-lg shadow-sm">
            <i data-lucide="building-2" size="32" class="text-primary"></i>
          </div>
          <div>
            <div class="text-lg font-black color-primary mb-8">MAX STREICHER GmbH</div>
            <p class="text-muted text-lg mb-0 leading-relaxed font-semibold">Industriestraße 45<br>93049 Regensburg, Germany</p>
          </div>
        </div>

        <div class="flex gap-32 mb-48 items-start">
          <div class="p-16 bg-light border-radius-lg shadow-sm">
            <i data-lucide="phone" size="32" class="text-primary"></i>
          </div>
          <div>
            <div class="text-lg font-black color-primary mb-8">Global Support Registry</div>
            <div class="text-2xl font-black text-accent">+49 941 123 456-0</div>
          </div>
        </div>

        <div class="flex gap-32 items-start">
          <div class="p-16 bg-light border-radius-lg shadow-sm">
            <i data-lucide="mail" size="32" class="text-primary"></i>
          </div>
          <div>
            <div class="text-lg font-black color-primary mb-8">Official Registry Email</div>
            <div class="text-lg font-black text-accent border-bottom-thick inline-block pb-4">procurement@streicher.de</div>
          </div>
        </div>
      </div>

      <div class="card-modern bg-primary color-white p-60 shadow-2xl relative overflow-hidden">
        <div class="absolute top--50 left--50 text-9xl opacity-03 font-black font-display">PRO</div>
        <h3 class="text-xl font-black color-white mb-48 uppercase tracking-widest relative z-1">Operational Protocol Matrix</h3>
        <div class="flex flex-col gap-32 text-lg relative z-1">
          <div class="flex-between border-bottom border-white/10 pb-20">
            <span class="opacity-60 font-semibold">Registry Cycles</span>
            <span class="font-black uppercase tracking-wider">Monday - Friday</span>
          </div>
          <div class="flex-between border-bottom border-white/10 pb-20">
            <span class="opacity-60 font-semibold">Availability (CET)</span>
            <span class="font-black uppercase tracking-wider">08:00 - 18:00</span>
          </div>
          <div class="flex-between">
            <span class="opacity-60 font-semibold">Global Support Matrix</span>
            <span class="font-black uppercase text-accent tracking-wider">24/7 Priority Axis</span>
          </div>
        </div>
        <div class="mt-60 p-32 bg-white/5 border border-white/10 border-radius-lg text-base leading-relaxed font-semibold relative z-1">
          <i data-lucide="alert-circle" class="text-accent inline-block mr-8" size="20"></i>
          <strong class="text-accent uppercase tracking-widest">Emergency Interdisciplinary Protocol:</strong> Active for institutional partners with verified Technical SLA credentials. Access via dedicated Portal hotline Axis-A.
        </div>
      </div>
    </div>
  </div>
</div>

