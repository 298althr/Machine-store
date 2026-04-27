<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Retouren & Garantie' : 'Institutional Warranty' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 350px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Assurance
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 16px;"><?= $lang === 'de' ? 'Service & Garantie' : 'Service & Technical Warranty' ?></h1>
      <p style="font-size: 1.25rem; opacity: 0.9; max-width: 600px;">
        <?= $lang === 'de' 
            ? 'Unser Versprechen für interdisziplinäre Qualität und Zuverlässigkeit auch nach der Inbetriebnahme.' 
            : 'Our commitment to interdisciplinary quality and operational reliability continues far beyond commissioning.' ?>
      </p>
    </div>
  </section>

  <div style="max-width: 1000px; margin: 0 auto 120px;">
    <!-- Warranty Dashboard -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 60px;">
      <div style="background: var(--primary); padding: 40px; color: white; display: flex; align-items: center; justify-content: space-between;">
        <div>
          <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <span style="font-size: 2rem;">🛡️</span>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin: 0;"><?= $lang === 'de' ? '24 Monate Institutional Garantie' : '24-Month Institutional Warranty' ?></h3>
          </div>
          <p style="margin: 0; opacity: 0.8; font-size: 1.05rem;">
            <?= $lang === 'de' 
                ? 'Alle STREICHER Systeme sind durch unsere globale 24-monatige Werksgarantie abgedeckt.' 
                : 'All STREICHER systems are covered by our global 24-month factory warranty against manufacturing defects.' ?>
          </p>
        </div>
        <div style="text-align: right;">
          <div style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; opacity: 0.7;">Status</div>
          <div style="font-weight: 900; color: var(--accent); font-size: 1.2rem;">CERTIFIED ACTIVE</div>
        </div>
      </div>
      <div style="padding: 50px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">
          <div>
            <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: var(--primary); margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;"><?= $lang === 'de' ? 'Abgedeckte Leistungen:' : "Institutional Coverage:" ?></h4>
            <ul style="padding: 0; margin: 0; list-style: none; display: grid; gap: 16px;">
              <li style="display: flex; gap: 12px; font-size: 1rem; color: var(--text-muted);"><span style="color: #10b981; font-weight: 900;">✓</span> Material- & Verarbeitungsfehler</li>
              <li style="display: flex; gap: 12px; font-size: 1rem; color: var(--text-muted);"><span style="color: #10b981; font-weight: 900;">✓</span> Komponentenversagen unter Nennlast</li>
              <li style="display: flex; gap: 12px; font-size: 1rem; color: var(--text-muted);"><span style="color: #10b981; font-weight: 900;">✓</span> Software- & Steuerungssystemfehler</li>
            </ul>
          </div>
          <div>
            <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: var(--primary); margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;"><?= $lang === 'de' ? 'Ausschlüsse:' : "Exclusions:" ?></h4>
            <ul style="padding: 0; margin: 0; list-style: none; display: grid; gap: 16px;">
              <li style="display: flex; gap: 12px; font-size: 1rem; color: var(--text-muted);"><span style="color: #ef4444; font-weight: 900;">×</span> Betrieblich bedingter Verschleiß</li>
              <li style="display: flex; gap: 12px; font-size: 1rem; color: var(--text-muted);"><span style="color: #ef4444; font-weight: 900;">×</span> Fehler durch unsachgemäße Wartung</li>
              <li style="display: flex; gap: 12px; font-size: 1rem; color: var(--text-muted);"><span style="color: #ef4444; font-weight: 900;">×</span> Nicht autorisierte Systemmodifikationen</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Reverse Logistics -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); padding: 50px; margin-bottom: 60px;">
      <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 40px; border-bottom: 1px solid #f1f5f9; padding-bottom: 24px;">
        <span style="font-size: 2rem;">📦</span>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin: 0; color: var(--primary);"><?= $lang === 'de' ? 'Reverse Logistics & Retouren' : 'Reverse Logistics Protocols' ?></h3>
      </div>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: var(--primary); margin-bottom: 16px;">Procurement Eligibility</h4>
          <p style="font-size: 1rem; color: var(--text-muted); line-height: 1.7;">
            Unused system components and standard industrial parts can be returned within 30 days of acquisition. All returns must maintain original factory sealing and institutional documentation.
          </p>
        </div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: var(--primary); margin-bottom: 16px;">Restricted Assets</h4>
          <ul style="padding: 0; margin: 0; list-style: none; display: grid; gap: 12px;">
            <li style="font-size: 0.95rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px;"><span style="width: 6px; height: 6px; background: var(--accent); border-radius: 50%;"></span> Custom-Engineered Solutions</li>
            <li style="font-size: 0.95rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px;"><span style="width: 6px; height: 6px; background: var(--accent); border-radius: 50%;"></span> Installed Interdisciplinary Components</li>
            <li style="font-size: 0.95rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px;"><span style="width: 6px; height: 6px; background: var(--accent); border-radius: 50%;"></span> HazMat & Regulated Chemicals</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Claim Infrastructure -->
    <section style="background: #f8fafc; border-radius: var(--radius-lg); padding: 60px; border: 1px solid rgba(0,0,0,0.02);">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin-bottom: 48px; text-align: center; color: var(--primary); text-transform: uppercase; letter-spacing: 2px;">
        <?= $lang === 'de' ? 'Service-Infrastruktur' : 'Service Infrastructure' ?>
      </h3>
      
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; text-align: center;">
        <div class="hover-lift" style="background: white; padding: 40px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.05);">
          <div style="font-size: 3rem; margin-bottom: 24px;">🔄</div>
          <h5 style="font-size: 1.2rem; font-family: 'Outfit', sans-serif; margin-bottom: 12px; color: var(--primary);">Rapid Exchange</h5>
          <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0; line-height: 1.6;">Immediate replacement protocols for critical defective units.</p>
        </div>
        <div class="hover-lift" style="background: white; padding: 40px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.05);">
          <div style="font-size: 3rem; margin-bottom: 24px;">🛠️</div>
          <h5 style="font-size: 1.2rem; font-family: 'Outfit', sans-serif; margin-bottom: 12px; color: var(--primary);">Factory Overhaul</h5>
          <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0; line-height: 1.6;">Precision reconditioning by our senior engineering experts.</p>
        </div>
        <div class="hover-lift" style="background: white; padding: 40px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.05);">
          <div style="font-size: 3rem; margin-bottom: 24px;">👨‍🔧</div>
          <h5 style="font-size: 1.2rem; font-family: 'Outfit', sans-serif; margin-bottom: 12px; color: var(--primary);">Global On-Site</h5>
          <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0; line-height: 1.6;">Direct deployment for large-scale plant maintenance.</p>
        </div>
      </div>
    </section>
  </div>
</div>
