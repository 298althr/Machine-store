<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'HSE-Q Compliance' : 'Institutional HSE-Q' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 450px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 800px;">
      <div style="display: inline-block; padding: 6px 16px; background: #10b981; border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 2px;">
        Zero Incident Mandate
      </div>
      <h1 style="font-size: 4rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 24px;">Institutional HSE-Q Protocol</h1>
      <p style="font-size: 1.3rem; opacity: 0.9; line-height: 1.6;">
        <?= $lang === 'de' 
            ? 'Unsere interdisziplinäre Verantwortung für Gesundheit, Sicherheit, Umwelt und Qualität steht im Zentrum unseres globalen Handelns.' 
            : 'Our interdisciplinary responsibility for Health, Safety, Environment, and Quality remains the absolute cornerstone of our global operations.' ?>
      </p>
    </div>
  </section>

  <div style="text-align: center; max-width: 900px; margin: 0 auto 100px;">
    <div style="width: 80px; height: 4px; background: var(--accent); margin: 0 auto 40px; border-radius: 2px;"></div>
    <p style="font-size: 1.6rem; color: var(--primary); font-weight: 700; font-family: 'Outfit', sans-serif; line-height: 1.6;">
      <?= $lang === 'de' 
          ? 'Bei STREICHER hat die Sicherheit und Gesundheit aller Beteiligten höchste Priorität. Unsere zertifizierten Prozesse garantieren operative Exzellenz.' 
          : 'At STREICHER, the safety and health of all stakeholders is our ultimate priority. Our certified processes ensure interdisciplinary operational excellence.' ?>
    </p>
  </div>

  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; margin-bottom: 120px;">
    <?php 
    $pillars = [
      ['icon' => '🏥', 'de' => 'Gesundheit', 'en' => 'Health', 'text_de' => 'Präventive Maßnahmen und interdisziplinäres Gesundheitsmanagement.', 'text_en' => 'Proactive prevention and comprehensive interdisciplinary health management.'],
      ['icon' => '🛡️', 'de' => 'Sicherheit', 'en' => 'Safety', 'text_de' => 'Null Unfälle durch kontinuierliche technologische Auditierung.', 'text_en' => 'Targeting zero incidents through continuous technological auditing and training.'],
      ['icon' => '🌿', 'de' => 'Umwelt', 'en' => 'Environment', 'text_de' => 'Minimierung des ökologischen Fußabdrucks bei jedem Großprojekt.', 'text_en' => 'Minimizing the ecological footprint across all global interdisciplinary projects.'],
      ['icon' => '✅', 'de' => 'Qualität', 'en' => 'Quality', 'text_de' => 'Zertifizierte Prozesse für deutsche Ingenieurtechnische Exzellenz.', 'text_en' => 'Certified processes ensuring absolute German engineering excellence.'],
    ];
    foreach ($pillars as $p):
    ?>
    <div class="hover-lift" style="background: white; padding: 60px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: var(--transition);">
      <div style="font-size: 4rem; margin-bottom: 32px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));"><?= $p['icon'] ?></div>
      <h3 style="font-size: 1.6rem; font-family: 'Outfit', sans-serif; margin-bottom: 20px; color: var(--primary); font-weight: 800;"><?= $lang === 'de' ? $p['de'] : $p['en'] ?></h3>
      <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.7;"><?= $lang === 'de' ? $p['text_de'] : $p['text_en'] ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <section style="background: #f8fafc; border-radius: var(--radius-lg); padding: 100px 80px; margin-bottom: 120px; border: 1px solid rgba(0,0,0,0.02); box-shadow: var(--shadow-sm);">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 32px;">
      <h2 style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); font-weight: 800;"><?= $lang === 'de' ? 'Globale Akkreditierungen' : 'Global Accreditations' ?></h2>
      <span style="color: var(--accent); font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px;">Compliance Matrix</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;">
      <?php 
      $certs = [
        'ISO 9001:2015' => 'Quality Management Systems',
        'ISO 14001:2015' => 'Environmental Management Systems',
        'ISO 45001:2018' => 'Occupational Health & Safety',
        'SCCP Version 2011' => 'Safety Certificate Contractors (Petrochemical)',
        'API Spec Q1' => 'Petroleum & Natural Gas Industry Quality',
        'EN 1090-2' => 'Steel & Aluminum Structure Execution'
      ];
      foreach ($certs as $c => $desc):
      ?>
      <div class="hover-lift" style="background: white; padding: 48px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 20px; text-align: left;">
        <div style="background: #f1f5f9; padding: 12px 24px; border-radius: 8px; font-weight: 900; color: var(--accent); font-size: 1.1rem; display: inline-block; width: fit-content; letter-spacing: 1px;">
          <?= $c ?>
        </div>
        <div style="font-size: 1rem; color: var(--primary); font-weight: 700; line-height: 1.4; opacity: 0.8;"><?= $desc ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
>
  </section>
</div>
