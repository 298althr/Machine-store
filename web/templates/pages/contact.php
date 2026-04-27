<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 32px; font-size: 0.95rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent); font-weight: 700;"><?= __('home') ?></a> 
    <span style="margin: 0 12px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary); font-weight: 900; letter-spacing: -0.5px;"><?= $lang === 'de' ? 'Interdisziplinärer Kontakt' : 'Institutional Contact Registry' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 100px; min-height: 450px; display: flex; align-items: center; padding: 100px; background: #0f172a; box-shadow: var(--shadow-2xl);">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.7) 100%), url('https://images.unsplash.com/photo-1516387933999-ed331c9c5a6d?q=80&w=2070&auto=format&fit=crop') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 800px;">
      <div style="display: inline-block; padding: 8px 24px; background: var(--accent); border-radius: 8px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 4px; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);">
        Global Communication Matrix
      </div>
      <h1 style="font-size: 5rem; font-family: 'Outfit', sans-serif; line-height: 0.95; margin: 0 0 32px 0; font-weight: 900; letter-spacing: -3px;">Connect With<br><span style="color: var(--accent);">Technical Experts.</span></h1>
      <p style="font-size: 1.5rem; color: rgba(255,255,255,0.7); max-width: 650px; line-height: 1.6; font-weight: 500; letter-spacing: -0.5px;">
        Our interdisciplinary engineering and strategic sales teams provide technical guidance for complex global industrial requirements.
      </p>
    </div>
  </section>

  <?php if (!empty($success)): ?>
  <div style="max-width: 1300px; margin: 0 auto 80px; background: white; border: 3px solid #16a34a; padding: 60px; border-radius: var(--radius-lg); display: flex; align-items: center; gap: 48px; box-shadow: var(--shadow-2xl); animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.03; color: #16a34a; font-weight: 900; font-family: 'Outfit', sans-serif;">✓</div>
    <div style="font-size: 5rem; filter: drop-shadow(0 20px 40px rgba(22, 163, 74, 0.3)); position: relative; z-index: 1;">✅</div>
    <div style="position: relative; z-index: 1;">
      <h3 style="font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--primary); font-size: 2.25rem; margin: 0 0 12px 0; letter-spacing: -1.5px;">Protocol Dispatch Successful</h3>
      <p style="color: var(--text-muted); font-size: 1.25rem; margin: 0; font-weight: 500; line-height: 1.6;">Your technical inquiry has been entered into our global routing matrix. An authorized representative will respond within 24 business cycles.</p>
    </div>
  </div>
  <?php endif; ?>

  <div style="display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 80px; max-width: 1400px; margin: 0 auto 150px;">
    <!-- Global Inquiry Portal -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column;">
      <div style="padding: 60px 80px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; left: -20px; font-size: 12rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif; pointer-events: none;">INQ</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1px; position: relative; z-index: 1;">Initialize Technical Inquiry</h3>
        <p style="color: var(--accent); font-size: 0.85rem; margin: 12px 0 0 0; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; position: relative; z-index: 1;">Interdisciplinary Communication Protocol Matrix</p>
      </div>
      <div style="padding: 80px; flex: 1;">
        <form action="/contact" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Representative Full Name</label>
            <input type="text" name="name" required style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc; font-family: 'Outfit', sans-serif;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Institutional Entity</label>
            <input type="text" name="company" style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc; font-family: 'Outfit', sans-serif;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Technical Email Identifier</label>
            <input type="email" name="email" required placeholder="representative@institution.com" style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc; font-family: 'Outfit', sans-serif;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <div class="form-group-modern">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Inquiry Subject Protocol</label>
            <select name="subject" required style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 900; font-size: 1rem; outline: none; transition: all 0.4s; background: #f8fafc; cursor: pointer; font-family: 'Outfit', sans-serif; appearance: none; color: var(--primary);" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
              <option value="sales">Interdisciplinary Sales & Asset Inquiry</option>
              <option value="support">Engineering & Technical Support</option>
              <option value="quote">Institutional Bulk Procurement Quote</option>
              <option value="partnership">Strategic Global Partnership</option>
            </select>
          </div>
          <div class="form-group-modern" style="grid-column: span 2;">
            <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Technical Requirement Details</label>
            <textarea name="message" rows="8" required placeholder="Outline your interdisciplinary industrial specifications, technical requirements or project parameters..." style="width: 100%; padding: 32px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.1rem; outline: none; transition: all 0.4s; font-family: inherit; resize: none; background: #f8fafc;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';"></textarea>
          </div>
          <div style="grid-column: span 2; margin-top: 24px;">
            <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 84px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; border-radius: 8px; box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2);">Dispatch Technical Inquiry Matrix</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Institutional Info Grid -->
    <div>
      <div style="background: white; border-radius: var(--radius-lg); padding: 60px; box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 60px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; font-size: 15rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">HQ</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: var(--primary); margin-bottom: 60px; font-weight: 900; letter-spacing: -1px;">Global Headquarters</h3>
        
        <div style="display: flex; gap: 32px; margin-bottom: 48px; align-items: start;">
          <div style="width: 64px; height: 64px; background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">🏢</div>
          <div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; margin-bottom: 8px; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">MAX STREICHER GmbH</div>
            <p style="color: var(--text-muted); font-size: 1.15rem; margin: 0; line-height: 1.6; font-weight: 600;">Industriestraße 45<br>93049 Regensburg, Germany</p>
          </div>
        </div>

        <div style="display: flex; gap: 32px; margin-bottom: 48px; align-items: start;">
          <div style="width: 64px; height: 64px; background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">📞</div>
          <div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; margin-bottom: 8px; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">Global Support Registry</div>
            <div style="font-weight: 900; color: var(--accent); font-size: 1.5rem; font-family: 'Outfit', sans-serif;">+49 941 123 456-0</div>
          </div>
        </div>

        <div style="display: flex; gap: 32px; align-items: start;">
          <div style="width: 64px; height: 64px; background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">✉️</div>
          <div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; margin-bottom: 8px; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">Official Registry Email</div>
            <div style="font-weight: 900; color: var(--accent); font-size: 1.25rem; font-family: 'Outfit', sans-serif; border-bottom: 3px solid var(--accent); padding-bottom: 4px;">procurement@streicher.de</div>
          </div>
        </div>
      </div>

      <div style="background: var(--primary); border-radius: var(--radius-lg); padding: 60px; color: white; box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.4); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; left: -50px; font-size: 15rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">PRO</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; color: white; margin-bottom: 48px; font-weight: 900; letter-spacing: -1px; position: relative; z-index: 1;">Operational Protocol Matrix</h3>
        <div style="display: flex; flex-direction: column; gap: 32px; font-size: 1.1rem; position: relative; z-index: 1;">
          <div style="display: flex; justify-content: space-between; border-bottom: 2px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
            <span style="opacity: 0.6; font-weight: 600;">Registry Cycles</span>
            <span style="font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Monday - Friday</span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 2px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
            <span style="opacity: 0.6; font-weight: 600;">Availability (CET)</span>
            <span style="font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">08:00 - 18:00</span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="opacity: 0.6; font-weight: 600;">Global Support Matrix</span>
            <span style="font-weight: 900; text-transform: uppercase; color: var(--accent); letter-spacing: 2px;">24/7 Priority Axis</span>
          </div>
        </div>
        <div style="margin-top: 60px; padding: 32px; background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.1); border-radius: 12px; font-size: 1rem; line-height: 1.6; font-weight: 600; position: relative; z-index: 1;">
          🚨 <strong style="color: var(--accent); text-transform: uppercase; letter-spacing: 1px;">Emergency Interdisciplinary Protocol:</strong> Active for institutional partners with verified Technical SLA credentials. Access via dedicated Portal hotline Axis-A.
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes slideIn {
  from { transform: translateY(50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>

<style>
@keyframes slideIn {
  from { transform: translateY(30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
