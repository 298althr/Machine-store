<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Technischer Support' : 'Institutional Support' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 350px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Engineering Support
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 16px;"><?= $lang === 'de' ? 'Technischer Support' : 'Interdisciplinary Support' ?></h1>
      <p style="font-size: 1.25rem; opacity: 0.9; max-width: 600px;">
        <?= $lang === 'de' 
            ? 'Weltweite Experten-Unterstützung für Ihre interdisziplinären Anlagen – rund um die Uhr.' 
            : 'Subject matter expertise for your interdisciplinary systems – accessible globally, 24/7.' ?>
      </p>
    </div>
  </section>

  <!-- Support Protocols -->
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; margin-bottom: 100px;">
    <div class="hover-lift" style="background: white; padding: 60px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); text-align: center; border: 1px solid rgba(0,0,0,0.05);">
      <div style="font-size: 4rem; margin-bottom: 32px;">💬</div>
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin-bottom: 16px; font-weight: 800;">Engineering Chat</h3>
      <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 32px;">Direct synchronous access to our senior engineering desk for immediate technical resolution.</p>
      <a href="/contact" class="btn-modern btn-accent" style="width: 100%; justify-content: center; font-weight: 800;">Initialize Tech Chat</a>
    </div>

    <div class="hover-lift" style="background: white; padding: 60px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); text-align: center; border: 1px solid rgba(0,0,0,0.05);">
      <div style="font-size: 4rem; margin-bottom: 32px;">✉️</div>
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin-bottom: 16px; font-weight: 800;">Technical Inquiry</h3>
      <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 32px;">Formal submission for complex interdisciplinary specifications and documentation requests.</p>
      <div style="font-weight: 900; color: var(--accent); font-size: 1.1rem; letter-spacing: 0.5px;">tech.support@streicher-group.com</div>
    </div>

    <div class="hover-lift" style="background: var(--primary); padding: 60px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); text-align: center; color: white; border: 1px solid var(--accent);">
      <div style="font-size: 4rem; margin-bottom: 32px;">🚨</div>
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: white; margin-bottom: 16px; font-weight: 800;">Emergency Hotline</h3>
      <p style="color: rgba(255,255,255,0.7); font-size: 1rem; line-height: 1.7; margin-bottom: 32px;">24/7 Rapid response protocol for critical industrial equipment failures and site safety.</p>
      <div style="font-weight: 900; color: var(--accent); font-size: 1.4rem;">+49 941 123 456-99</div>
    </div>
  </div>

  <!-- Technical Ticket Infrastructure -->
  <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 120px;">
    <div style="padding: 48px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; color: var(--primary); margin: 0; font-weight: 800;">System Support Ticket</h2>
      <span style="background: var(--accent); color: white; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 900; letter-spacing: 1px;">ENCRYPTION ACTIVE</span>
    </div>
    <div style="padding: 80px;">
      <form action="/support" method="POST">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; margin-bottom: 40px;">
          <div class="form-group-modern">
            <label>Authorized Representative *</label>
            <input type="text" name="name" required placeholder="John Doe">
          </div>
          <div class="form-group-modern">
            <label>Legal Entity / Company *</label>
            <input type="text" name="company" required placeholder="Global Industrial AG">
          </div>
          <div class="form-group-modern">
            <label>Corporate Email *</label>
            <input type="email" name="email" required placeholder="representative@company.com">
          </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 40px;">
          <div class="form-group-modern">
            <label>Equipment Asset ID / SKU</label>
            <input type="text" name="equipment" placeholder="e.g. STR-SYS-5000-X">
          </div>
          <div class="form-group-modern">
            <label>Operational Priority Level</label>
            <select name="priority" style="font-weight: 600;">
              <option value="low">Institutional Inquiry (Non-Critical)</option>
              <option value="medium">Standard Issue (Ops Affected)</option>
              <option value="high">Critical Failure (Equipment Down)</option>
              <option value="critical">Severe Risk (Safety Protocol Active)</option>
            </select>
          </div>
        </div>
        
        <div class="form-group-modern" style="margin-bottom: 60px;">
          <label>Detailed Technical Description *</label>
          <textarea name="issue" rows="6" required placeholder="Outline the technical specifications, interdisciplinary symptoms, and site conditions..."></textarea>
        </div>
        
        <button type="submit" class="btn-modern btn-accent" style="padding: 24px 60px; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Transmit Support Request</button>
      </form>
    </div>
  </div>

  <!-- Interdisciplinary Resource Library -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; margin-bottom: 100px;">
    <?php 
    $resources = [
      ['icon' => '📚', 'title' => 'Technical Manuals'],
      ['icon' => '⚙️', 'title' => 'System Components'],
      ['icon' => '🎥', 'title' => 'Integration Guides'],
      ['icon' => '❓', 'title' => 'Knowledge Base'],
    ];
    foreach ($resources as $res):
    ?>
    <div class="hover-lift" style="background: white; padding: 40px; border-radius: var(--radius-lg); border: 1px solid rgba(0,0,0,0.05); text-align: center; box-shadow: var(--shadow-sm);">
      <div style="font-size: 3rem; margin-bottom: 24px;"><?= $res['icon'] ?></div>
      <div style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--primary); font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;"><?= $res['title'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
