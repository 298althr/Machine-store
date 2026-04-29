<div class="auth-page-wrapper">
  <!-- Left: Cinematic Branding -->
  <div class="auth-branding-side">
    <div class="auth-bg-overlay"></div>
    
    <div class="auth-branding-content">
      <div class="auth-tag">Institutional Engineering Heritage</div>
      <h2 class="auth-brand-title">STREICHER<span>.</span></h2>
      <p class="auth-brand-subtitle">
        High-fidelity access to interdisciplinary industrial assets and global procurement protocols.
      </p>
      
      <div class="auth-stats-grid grid grid-3">
        <div class="auth-stat-item">
          <div class="stat-num">4.5k+</div>
          <div class="stat-lab">Global Experts</div>
        </div>
        <div class="auth-stat-item border-x">
          <div class="stat-num">1909</div>
          <div class="stat-lab">Heritage Registry</div>
        </div>
        <div class="auth-stat-item">
          <div class="stat-num">GLB</div>
          <div class="stat-lab">Operational Reach</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Authentication Portal -->
  <div class="auth-form-side">
    <div class="auth-form-container">
      <div class="auth-form-header">
        <a href="/" class="auth-logo-link">
          <h2 class="auth-logo-text">STREICHER<span>.</span></h2>
        </a>
        <div class="auth-subtitle-tag">Authorization Required</div>
        <h1 class="auth-main-title">Portal Login</h1>
        <p class="auth-intro-text">Authorize your institutional credentials to access the global industrial registry.</p>
      </div>

      <?php if (!empty($error)): ?>
      <div class="alert alert-error mb-48">
        <i data-lucide="alert-triangle"></i>
        <div><?= htmlspecialchars($error) ?></div>
      </div>
      <?php endif; ?>

      <form action="/login" method="POST" class="grid gap-32">
        <?php render_component('form_field', [
          'label' => 'Institutional Email Address',
          'name' => 'email',
          'type' => 'email',
          'required' => true,
          'placeholder' => 'representative@institution.com'
        ]); ?>

        <div class="form-group-wrapper">
          <div class="flex-between mb-8">
            <label class="label-modern">Authorization Protocol Key</label>
            <a href="/forgot-password" class="text-accent text-xs font-bold uppercase tracking-wider">Key Recovery</a>
          </div>
          <?php render_component('form_field', [
            'name' => 'password',
            'type' => 'password',
            'required' => true,
            'placeholder' => '••••••••••••••••'
          ]); ?>
        </div>

        <div class="remember-me-wrapper">
          <label class="checkbox-label">
            <input type="checkbox" name="remember" class="checkbox-input">
            <span class="checkbox-text">Maintain protocol session for 30 business cycles</span>
          </label>
        </div>

        <?php render_component('button', [
          'type' => 'submit',
          'variant' => 'accent',
          'label' => 'Authorize Portal Access',
          'class' => 'w-full h-80 text-lg'
        ]); ?>
      </form>

      <div class="auth-footer-links">
        <p class="text-muted font-bold">
          New Representative? <a href="/register" class="text-accent underline ml-8">Initialize Registry</a>
        </p>
        
        <div class="admin-entry-link">
          <a href="/admin/login">Institutional Administrator Entry →</a>
        </div>
      </div>
    </div>
  </div>
</div>

