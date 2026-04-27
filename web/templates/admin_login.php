<?php
$error = $error ?? null;
?>
<div style="min-height: calc(100vh - 400px); display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: var(--bg-body);">
  <div style="width: 100%; max-width: 450px;">
    <!-- Logo/Brand area -->
    <div style="text-align: center; margin-bottom: 40px;">
      <div style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 900; color: var(--primary); letter-spacing: -1px; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <span style="color: var(--accent);">S</span>TREICHER <span style="font-weight: 300; font-size: 0.8em; opacity: 0.5;">ADMIN</span>
      </div>
      <div style="height: 4px; width: 40px; background: var(--accent); margin: 16px auto 0; border-radius: 2px;"></div>
    </div>

    <!-- Login Card -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-premium); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
      <div style="padding: 40px;">
        <h1 style="font-size: 1.5rem; margin-bottom: 8px; font-family: 'Outfit', sans-serif; text-align: center;">Secure Terminal Login</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; margin-bottom: 32px;">Authorized administrative access only.</p>

        <?php if ($error): ?>
          <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 16px; border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 700; margin-bottom: 24px; text-align: center; display: flex; align-items: center; gap: 12px;">
            <span>⚠️</span> <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="post" action="/admin/login" style="display: flex; flex-direction: column; gap: 20px;">
          <div>
            <label for="email" style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--primary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Administrator ID (Email)</label>
            <div style="position: relative;">
              <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); opacity: 0.5;">👤</span>
              <input type="email" id="email" name="email" required autocomplete="email" placeholder="admin@streicher.de" style="width: 100%; height: 52px; border: 1.5px solid var(--bg-body); border-radius: var(--radius-md); padding: 0 16px 0 44px; outline: none; transition: var(--transition); font-size: 0.95rem;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 4px rgba(220, 38, 38, 0.05)'" onblur="this.style.borderColor='var(--bg-body)'; this.style.boxShadow='none'">
            </div>
          </div>

          <div>
            <label for="password" style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--primary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Security Key</label>
            <div style="position: relative;">
              <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); opacity: 0.5;">🔒</span>
              <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" style="width: 100%; height: 52px; border: 1.5px solid var(--bg-body); border-radius: var(--radius-md); padding: 0 16px 0 44px; outline: none; transition: var(--transition); font-size: 0.95rem;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 4px rgba(220, 38, 38, 0.05)'" onblur="this.style.borderColor='var(--bg-body)'; this.style.boxShadow='none'">
            </div>
          </div>

          <div style="margin-top: 10px;">
            <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 56px; font-size: 1rem; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 12px;">
              Verify & Enter Terminal
            </button>
          </div>
        </form>
      </div>
      
      <div style="background: var(--bg-body); padding: 20px; border-top: 1px solid rgba(0,0,0,0.05); text-align: center;">
        <a href="/" style="font-size: 0.85rem; color: var(--text-muted); text-decoration: none; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
          <span>←</span> Back to Corporate Store
        </a>
      </div>
    </div>
    
    <div style="text-align: center; margin-top: 32px; font-size: 0.75rem; color: var(--text-muted); opacity: 0.5;">
      &copy; <?= date('Y') ?> Streicher GmbH. System activities are logged.
    </div>
  </div>
</div>
