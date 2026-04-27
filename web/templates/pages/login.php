<div style="min-height: 100vh; display: flex; background: #0f172a;">
  <!-- Left: Cinematic Institutional Branding Matrix -->
  <div style="flex: 1; position: relative; display: flex; align-items: center; justify-content: center; padding: 100px; overflow: hidden;" class="branding-matrix">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(15, 23, 42, 0.8) 100%), url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop') center/cover;"></div>
    
    <!-- Abstract Tech Elements -->
    <div style="position: absolute; top: -10%; right: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(220, 38, 38, 0.05) 0%, transparent 70%); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -10%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(220, 38, 38, 0.03) 0%, transparent 70%); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 1; max-width: 700px; text-align: center;">
      <div style="font-size: 0.9rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 5px; margin-bottom: 32px;">Institutional Engineering Heritage</div>
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 5rem; color: white; font-weight: 900; line-height: 0.9; margin: 0 0 32px 0; letter-spacing: -3px;">STREICHER<span style="color: var(--accent);">.</span></h2>
      <p style="font-size: 1.75rem; color: rgba(255,255,255,0.7); font-weight: 500; line-height: 1.5; margin-bottom: 64px; letter-spacing: -0.5px;">
        High-fidelity access to interdisciplinary industrial assets and global procurement protocols.
      </p>
      
      <div style="display: flex; gap: 60px; justify-content: center; background: rgba(255,255,255,0.03); padding: 48px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
        <div style="text-align: center;">
          <div style="font-size: 3rem; font-weight: 900; color: var(--accent); font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 8px;">4.5k+</div>
          <div style="color: white; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; opacity: 0.5;">Global Experts</div>
        </div>
        <div style="text-align: center; border-left: 1px solid rgba(255,255,255,0.1); border-right: 1px solid rgba(255,255,255,0.1); padding: 0 60px;">
          <div style="font-size: 3rem; font-weight: 900; color: white; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 8px;">1909</div>
          <div style="color: white; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; opacity: 0.5;">Heritage Registry</div>
        </div>
        <div style="text-align: center;">
          <div style="font-size: 3rem; font-weight: 900; color: var(--accent); font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 8px;">GLB</div>
          <div style="color: white; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; opacity: 0.5;">Operational Reach</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Authentication & Authorization Portal -->
  <div style="width: 100%; max-width: 750px; background: white; display: flex; align-items: center; justify-content: center; padding: 100px 80px; position: relative;">
    <div style="width: 100%; max-width: 450px;">
      <div style="margin-bottom: 80px;">
        <a href="/" style="text-decoration: none; display: inline-block; margin-bottom: 48px;">
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; color: var(--primary); font-weight: 900; margin: 0; letter-spacing: -1.5px; line-height: 1;">STREICHER<span style="color: var(--accent);">.</span></h2>
        </a>
        <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Authorization Required</div>
        <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); font-weight: 900; line-height: 1; margin: 0 0 16px 0; letter-spacing: -2px;">Portal Login</h1>
        <p style="color: var(--text-muted); font-size: 1.2rem; font-weight: 500; line-height: 1.6;">Authorize your institutional credentials to access the global industrial registry.</p>
      </div>

      <?php if (!empty($error)): ?>
      <div style="background: #fff1f2; border: 2px solid #ffe4e6; padding: 24px; border-radius: 8px; color: #e11d48; font-weight: 900; font-size: 1rem; display: flex; gap: 20px; align-items: center; margin-bottom: 48px; animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1); letter-spacing: -0.2px;">
        <span style="font-size: 2rem;">⚠️</span>
        <div style="line-height: 1.4;"><?= htmlspecialchars($error) ?></div>
      </div>
      <?php endif; ?>

      <form action="/login" method="POST">
        <div class="form-group-modern" style="margin-bottom: 40px;">
          <label style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Institutional Email Address</label>
          <div style="position: relative;">
            <input type="email" name="email" required placeholder="representative@institution.com" style="width: 100%; height: 72px; padding: 0 32px; border: 3px solid #f8fafc; border-radius: 8px; font-size: 1.1rem; font-weight: 600; outline: none; transition: all 0.4s; background: #f8fafc; color: var(--primary);" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white'; this.style.boxShadow='0 10px 30px rgba(220, 38, 38, 0.05)';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
            <span style="position: absolute; right: 24px; top: 50%; transform: translateY(-50%); font-size: 1.5rem; opacity: 0.2;">📧</span>
          </div>
        </div>

        <div class="form-group-modern" style="margin-bottom: 40px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <label style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin: 0;">Authorization Protocol Key</label>
            <a href="/forgot-password" style="font-size: 0.85rem; color: var(--accent); font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Key Recovery</a>
          </div>
          <div style="position: relative;">
            <input type="password" name="password" required placeholder="••••••••••••••••" style="width: 100%; height: 72px; padding: 0 32px; border: 3px solid #f8fafc; border-radius: 8px; font-size: 1.1rem; font-weight: 600; outline: none; transition: all 0.4s; background: #f8fafc; color: var(--primary);" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white'; this.style.boxShadow='0 10px 30px rgba(220, 38, 38, 0.05)';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
            <span style="position: absolute; right: 24px; top: 50%; transform: translateY(-50%); font-size: 1.5rem; opacity: 0.2;">🔒</span>
          </div>
        </div>

        <div style="margin-bottom: 48px;">
          <label style="display: flex; align-items: center; gap: 16px; cursor: pointer; user-select: none;">
            <div style="position: relative; width: 28px; height: 28px;">
              <input type="checkbox" name="remember" style="width: 28px; height: 28px; cursor: pointer; accent-color: var(--accent); border-radius: 4px;">
            </div>
            <span style="font-size: 1.05rem; color: var(--text-muted); font-weight: 600;">Maintain protocol session for 30 business cycles</span>
          </label>
        </div>

        <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 84px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; border-radius: 8px; box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2);">
          Authorize Portal Access
        </button>
      </form>

      <div style="margin-top: 80px; text-align: center; border-top: 2px solid #f8fafc; padding-top: 60px;">
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">
          New Representative? <a href="/register" style="color: var(--accent); font-weight: 900; text-decoration: none; margin-left: 12px; border-bottom: 2px solid var(--accent); padding-bottom: 2px;">Initialize Registry</a>
        </p>
      </div>

      <div style="margin-top: 48px; text-align: center;">
        <a href="/admin/login" style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; font-weight: 900; text-decoration: none; opacity: 0.4; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.color='var(--accent)';" onmouseout="this.style.opacity='0.4'; this.style.color='var(--text-muted)';">
          Institutional Administrator Entry →
        </a>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes slideIn {
  from { transform: translateY(-30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
@media (max-width: 1200px) {
  .branding-matrix { display: none !important; }
  [style*="max-width: 750px"] { max-width: 100% !important; padding: 60px 40px !important; }
}
</style>

<style>
@keyframes slideIn {
  from { transform: translateY(-20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
@media (max-width: 1024px) {
  [style*="flex: 1"] { display: none !important; }
  [style*="max-width: 600px"] { max-width: 100% !important; }
}
</style>
