<div style="min-height: 100vh; display: flex; background: #0f172a;">
  <!-- Left: Cinematic Heritage Matrix -->
  <div style="flex: 1; position: relative; display: flex; align-items: center; justify-content: center; padding: 100px; overflow: hidden;" class="heritage-matrix">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(15, 23, 42, 0.8) 100%), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=2070&auto=format&fit=crop') center/cover; filter: grayscale(20%);"></div>
    
    <div style="position: relative; z-index: 1; max-width: 700px;">
      <div style="font-size: 0.9rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 5px; margin-bottom: 32px;">Interdisciplinary Partnership</div>
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 4.5rem; color: white; font-weight: 900; line-height: 0.95; margin: 0 0 32px 0; letter-spacing: -3px;">Initialize Your <span style="color: var(--accent);">Registry.</span></h2>
      <p style="font-size: 1.5rem; color: rgba(255,255,255,0.7); font-weight: 500; line-height: 1.6; margin-bottom: 64px; letter-spacing: -0.5px;">
        Join an elite global network of industrial partners and access the comprehensive STREICHER interdisciplinary asset ecosystem.
      </p>
      
      <div style="display: grid; gap: 40px;">
        <div style="display: flex; gap: 32px; align-items: center; background: rgba(255,255,255,0.03); padding: 32px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
          <div style="width: 64px; height: 64px; background: rgba(220, 38, 38, 0.1); border: 1px solid var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">🛡️</div>
          <div>
            <h4 style="color: white; font-family: 'Outfit', sans-serif; font-size: 1.25rem; margin: 0; font-weight: 900; letter-spacing: -0.5px;">Verified Procurement Protocol</h4>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; margin: 4px 0 0 0; font-weight: 500;">Authenticated B2B access for global industrial entities.</p>
          </div>
        </div>
        <div style="display: flex; gap: 32px; align-items: center; background: rgba(255,255,255,0.03); padding: 32px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
          <div style="width: 64px; height: 64px; background: rgba(220, 38, 38, 0.1); border: 1px solid var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">📊</div>
          <div>
            <h4 style="color: white; font-family: 'Outfit', sans-serif; font-size: 1.25rem; margin: 0; font-weight: 900; letter-spacing: -0.5px;">Custom Valuation Matrix</h4>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; margin: 4px 0 0 0; font-weight: 500;">Individual interdisciplinary asset valuation and registry access.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Registry Portal -->
  <div style="width: 100%; max-width: 800px; background: white; display: flex; align-items: center; justify-content: center; padding: 100px 80px; overflow-y: auto;">
    <div style="width: 100%; max-width: 550px;">
      <div style="margin-bottom: 64px;">
        <a href="/" style="text-decoration: none; display: inline-block; margin-bottom: 48px;">
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; color: var(--primary); font-weight: 900; margin: 0; letter-spacing: -1.5px; line-height: 1;">STREICHER<span style="color: var(--accent);">.</span></h2>
        </a>
        <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Enrollment Protocol</div>
        <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); font-weight: 900; line-height: 1; margin: 0 0 16px 0; letter-spacing: -2px;">Account Registry</h1>
        <p style="color: var(--text-muted); font-size: 1.2rem; font-weight: 500; line-height: 1.6;">Initialize your official interdisciplinary partnership today.</p>
      </div>

      <?php if (!empty($errors)): ?>
      <div style="background: #fef2f2; border: 2px solid #fee2e2; padding: 24px; border-radius: 8px; color: #991b1b; font-weight: 800; margin-bottom: 32px;">
        <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; font-weight: 900;">Enrollment Validation Failure</div>
        <ul style="margin: 0; padding-left: 20px; line-height: 1.8;">
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <form action="/register" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Given Name</label>
            <input type="text" name="first_name" required style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Surname</label>
            <input type="text" name="last_name" required style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
        </div>

        <div class="form-group-modern" style="margin-bottom: 32px;">
          <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Institutional Entity</label>
          <input type="text" name="company" required placeholder="GmbH, AG, Inc, Ltd..." style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
        </div>

        <div class="form-group-modern" style="margin-bottom: 32px;">
          <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Representative Email Address</label>
          <input type="email" name="email" required placeholder="representative@institution.com" style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 48px;">
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Authorization Key</label>
            <input type="password" name="password" required minlength="8" style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Key Verification</label>
            <input type="password" name="password_confirm" required style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
        </div>

        <div style="margin-bottom: 64px;">
          <label style="display: flex; gap: 20px; cursor: pointer; user-select: none;">
            <input type="checkbox" name="terms" required style="width: 28px; height: 28px; cursor: pointer; accent-color: var(--accent); flex-shrink: 0;">
            <span style="font-size: 1rem; color: var(--text-muted); line-height: 1.6; font-weight: 500;">
              I authorize full compliance with the <a href="/terms" style="color: var(--accent); font-weight: 900; text-decoration: none; border-bottom: 2px solid var(--accent); padding-bottom: 2px;">Institutional Terms</a> and the global interdisciplinary <a href="/privacy" style="color: var(--accent); font-weight: 900; text-decoration: none; border-bottom: 2px solid var(--accent); padding-bottom: 2px;">Privacy Protocol</a>.
            </span>
          </label>
        </div>

        <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 84px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; border-radius: 8px; box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2);">
          Initialize Registry Enrollment
        </button>
      </form>

      <div style="margin-top: 80px; text-align: center; border-top: 2px solid #f8fafc; padding-top: 60px;">
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">
          Already Authenticated? <a href="/login" style="color: var(--accent); font-weight: 900; text-decoration: none; margin-left: 12px; border-bottom: 2px solid var(--accent); padding-bottom: 2px;">Portal Entry</a>
        </p>
      </div>
    </div>
  </div>
</div>

<style>
@media (max-width: 1200px) {
  .heritage-matrix { display: none !important; }
  [style*="max-width: 800px"] { max-width: 100% !important; padding: 60px 40px !important; }
}
</style>

<style>
@media (max-width: 1024px) {
  [style*="flex: 1"] { display: none !important; }
  [style*="max-width: 700px"] { max-width: 100% !important; }
}
</style>
