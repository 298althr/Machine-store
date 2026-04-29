<div class="auth-page-wrapper">
  <!-- Left: Cinematic Heritage Matrix -->
  <div class="auth-branding-side">
    <div class="auth-bg-overlay heritage"></div>
    
    <div class="auth-branding-content">
      <div class="auth-tag">Interdisciplinary Partnership</div>
      <h2 class="auth-brand-title">Initialize Your <span class="text-accent">Registry.</span></h2>
      <p class="auth-brand-subtitle">
        Join an elite global network of industrial partners and access the comprehensive STREICHER interdisciplinary asset ecosystem.
      </p>
      
      <div class="grid gap-32">
        <div class="auth-feature-card card-modern">
          <div class="feature-icon-wrapper"><i data-lucide="shield-check"></i></div>
          <div class="feature-text">
            <h4 class="feature-title">Verified Procurement Protocol</h4>
            <p class="feature-desc">Authenticated B2B access for global industrial entities.</p>
          </div>
        </div>
        <div class="auth-feature-card card-modern">
          <div class="feature-icon-wrapper"><i data-lucide="bar-chart-3"></i></div>
          <div class="feature-text">
            <h4 class="feature-title">Custom Valuation Matrix</h4>
            <p class="feature-desc">Individual interdisciplinary asset valuation and registry access.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Registry Portal -->
  <div class="auth-form-side overflow-y-auto">
    <div class="auth-form-container wide">
      <div class="auth-form-header">
        <a href="/" class="auth-logo-link">
          <h2 class="auth-logo-text">STREICHER<span>.</span></h2>
        </a>
        <div class="auth-subtitle-tag">Enrollment Protocol</div>
        <h1 class="auth-main-title">Account Registry</h1>
        <p class="auth-intro-text">Initialize your official interdisciplinary partnership today.</p>
      </div>

      <?php if (!empty($errors)): ?>
      <div class="alert alert-error mb-48">
        <div class="alert-title">Enrollment Validation Failure</div>
        <ul class="alert-list">
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <form action="/register" method="POST" class="grid gap-32">
        <div class="grid grid-2">
          <?php render_component('form_field', [
            'label' => 'Given Name',
            'name' => 'first_name',
            'required' => true
          ]); ?>
          <?php render_component('form_field', [
            'label' => 'Surname',
            'name' => 'last_name',
            'required' => true
          ]); ?>
        </div>

        <?php render_component('form_field', [
          'label' => 'Institutional Entity',
          'name' => 'company',
          'required' => true,
          'placeholder' => 'GmbH, AG, Inc, Ltd...'
        ]); ?>

        <?php render_component('form_field', [
          'label' => 'Representative Email Address',
          'name' => 'email',
          'type' => 'email',
          'required' => true,
          'placeholder' => 'representative@institution.com'
        ]); ?>

        <div class="grid grid-2">
          <?php render_component('form_field', [
            'label' => 'Authorization Key',
            'name' => 'password',
            'type' => 'password',
            'required' => true
          ]); ?>
          <?php render_component('form_field', [
            'label' => 'Key Verification',
            'name' => 'password_confirm',
            'type' => 'password',
            'required' => true
          ]); ?>
        </div>

        <div class="terms-wrapper">
          <label class="checkbox-label">
            <input type="checkbox" name="terms" required class="checkbox-input">
            <span class="checkbox-text">
              I authorize full compliance with the <a href="/terms" class="text-accent underline font-bold">Institutional Terms</a> and the global interdisciplinary <a href="/privacy" class="text-accent underline font-bold">Privacy Protocol</a>.
            </span>
          </label>
        </div>

        <?php render_component('button', [
          'type' => 'submit',
          'variant' => 'accent',
          'label' => 'Initialize Registry Enrollment',
          'class' => 'w-full h-80 text-lg'
        ]); ?>
      </form>

      <div class="auth-footer-links">
        <p class="text-muted font-bold">
          Already Authenticated? <a href="/login" class="text-accent underline ml-8">Portal Entry</a>
        </p>
      </div>
    </div>
  </div>
</div>

