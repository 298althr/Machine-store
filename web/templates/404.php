<div class="container-modern section-padding" style="min-height: calc(100vh - 400px); display: flex; align-items: center; justify-content: center; text-align: center; position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at center, rgba(220, 38, 38, 0.05) 0%, transparent 70%); z-index: -1;"></div>
  
  <div style="max-width: 700px; position: relative; z-index: 1;">
    <div style="font-size: 12rem; line-height: 1; margin-bottom: 0; opacity: 0.05; font-weight: 900; font-family: 'Outfit', sans-serif; letter-spacing: -10px; user-select: none;">404</div>
    
    <div style="font-size: 5rem; margin-bottom: 32px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));">🛠️</div>
    
    <h1 style="font-size: 3.5rem; margin: 0 0 16px 0; font-family: 'Outfit', sans-serif; color: var(--primary); font-weight: 800; letter-spacing: -1px;">System Routing Failure</h1>
    
    <div style="display: inline-block; padding: 6px 16px; background: var(--primary); color: var(--accent); border-radius: 4px; font-size: 0.8rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 2px;">
      Error Code: ERR_MODULE_NOT_FOUND
    </div>
    
    <p style="color: var(--text-muted); font-size: 1.2rem; line-height: 1.7; margin-bottom: 60px; max-width: 550px; margin-left: auto; margin-right: auto;">
      The interdisciplinary resource or system path (<strong><?= htmlspecialchars($path ?? 'UNKNOWN_SECTOR') ?></strong>) you are attempting to initialize is currently offline or has been decommissioned.
    </p>
    
    <div style="display: flex; gap: 24px; justify-content: center; flex-wrap: wrap;">
      <a href="/" class="btn-modern btn-accent" style="padding: 20px 48px; font-weight: 900; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;">Return to Terminal</a>
      <a href="/catalog" class="btn-modern" style="padding: 20px 48px; border: 2px solid var(--primary); color: var(--primary); font-weight: 900; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;">Global Catalog</a>
    </div>
  </div>
</div>
