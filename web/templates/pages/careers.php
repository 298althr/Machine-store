<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Karriere-Portal' : 'Institutional Careers' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 450px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 900px;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 2px;">
        Empowering Talent Matrix
      </div>
      <h1 style="font-size: 4.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 24px; font-weight: 900; letter-spacing: -2px;">Architect the<br>Industrial Future.</h1>
      <p style="font-size: 1.4rem; opacity: 0.9; line-height: 1.6; font-weight: 500; max-width: 700px;">
        <?= $lang === 'de' 
            ? 'Werden Sie Teil eines globalen Teams, das die Industrie von morgen durch interdisziplinäre Exzellenz gestaltet.' 
            : 'Join a global team defining the future of industry through interdisciplinary engineering excellence.' ?>
      </p>
    </div>
  </section>

  <!-- Core Values Matrix -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; margin-bottom: 120px;">
    <?php 
    $values = [
      ['icon' => '🌍', 'de' => 'Globaler Impact', 'en' => 'Global Impact', 'text_de' => 'Interdisziplinäre Projekte weltweit.', 'text_en' => 'Leading massive industrial projects across 50+ countries.'],
      ['icon' => '📈', 'de' => 'Wachstum', 'en' => 'Organic Growth', 'text_de' => 'Kontinuierliche Entwicklungschancen.', 'text_en' => 'Structured career paths and leadership development.'],
      ['icon' => '💡', 'de' => 'Innovation', 'en' => 'Technical Edge', 'text_de' => 'Arbeiten mit Spitzentechnologie.', 'text_en' => 'Deploying the most advanced automation and engineering systems.'],
      ['icon' => '🤝', 'de' => 'Kultur', 'en' => 'Strong Cohesion', 'text_de' => 'Zusammenhalt im STREICHER Team.', 'text_en' => 'A supportive, collaborative environment across all disciplines.'],
    ];
    foreach ($values as $v):
    ?>
    <div style="background: white; padding: 60px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); text-align: center; border: 1px solid rgba(0,0,0,0.05); transition: all 0.4s ease-out; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='var(--accent)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(0,0,0,0.05)';">
      <div style="position: absolute; top: -10px; right: -10px; font-size: 6rem; opacity: 0.03; font-weight: 900;"><?= $v['icon'] ?></div>
      <div style="font-size: 3.5rem; margin-bottom: 32px; position: relative; z-index: 1;"><?= $v['icon'] ?></div>
      <h3 style="font-size: 1.4rem; font-family: 'Outfit', sans-serif; margin: 0 0 20px 0; color: var(--primary); font-weight: 900; letter-spacing: -0.5px; position: relative; z-index: 1;"><?= $lang === 'de' ? $v['de'] : $v['en'] ?></h3>
      <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.7; font-weight: 500; position: relative; z-index: 1;"><?= $lang === 'de' ? $v['text_de'] : $v['text_en'] ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Institutional Openings Registry -->
  <section style="margin-bottom: 120px;">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
      <div>
        <h2 style="font-size: 3rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0 0 12px 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Offene Vakanzen' : 'Institutional Openings' ?></h2>
        <p style="color: var(--text-muted); font-size: 1.2rem; font-weight: 500;"><?= $lang === 'de' ? 'Finden Sie Ihre nächste Herausforderung.' : 'Discover your role in global infrastructure.' ?></p>
      </div>
      <a href="/contact" style="color: var(--accent); font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem; padding: 12px 0; border-bottom: 3px solid var(--accent); transition: all 0.3s;" onmouseover="this.style.paddingRight='10px'" onmouseout="this.style.paddingRight='0'">Spontaneous Application Protocol →</a>
    </div>
    
    <div style="display: grid; gap: 32px;">
      <?php 
      $jobs = [
        ['title' => 'Lead Project Manager - Strategic Pipeline Infrastructure', 'loc' => 'Deggendorf, Germany', 'type' => 'Full-time / Institutional', 'ref' => 'REF-STR-001'],
        ['title' => 'Senior Automation Systems Engineer', 'loc' => 'Regensburg, Germany', 'type' => 'Full-time / Technical', 'ref' => 'REF-STR-002'],
        ['title' => 'Global Sales Director - Interdisciplinary Oil & Gas', 'loc' => 'Houston, TX, USA', 'type' => 'Full-time / Commercial', 'ref' => 'REF-STR-003'],
        ['title' => 'Institutional HSE Protocol Specialist', 'loc' => 'Global Assignments', 'type' => 'Contract / Safety', 'ref' => 'REF-STR-004'],
      ];
      foreach ($jobs as $job):
      ?>
      <div style="background: white; padding: 48px; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s;" onmouseover="this.style.boxShadow='0 25px 50px -12px rgba(15, 23, 42, 0.1)';">
        <div>
          <div style="font-size: 0.75rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;"><?= $job['ref'] ?></div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; color: var(--primary); margin: 0 0 16px 0; font-weight: 900; letter-spacing: -0.5px;"><?= $job['title'] ?></h4>
          <div style="font-size: 1.05rem; color: var(--text-muted); display: flex; gap: 32px; align-items: center; font-weight: 600;">
            <span style="display: flex; align-items: center; gap: 10px;"><span style="font-size: 1.25rem;">📍</span> <?= $job['loc'] ?></span>
            <span style="display: flex; align-items: center; gap: 10px;"><span style="font-size: 1.25rem;">📁</span> <?= $job['type'] ?></span>
          </div>
        </div>
        <a href="/contact" class="btn-modern btn-accent" style="padding: 24px 48px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Apply Securely</a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Benefits Matrix -->
  <section style="background: #f8fafc; border-radius: var(--radius-lg); padding: 120px 100px; margin-bottom: 120px; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; left: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">EVO</div>
    <div style="text-align: center; margin-bottom: 100px; position: relative; z-index: 1;">
      <h2 style="font-size: 3rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0 0 24px 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Ihre Vorteile bei uns' : 'Institutional Growth Matrix' ?></h2>
      <p style="color: var(--text-muted); max-width: 700px; margin: 0 auto; font-size: 1.25rem; font-weight: 500; line-height: 1.6;"><?= $lang === 'de' ? 'Wir investieren langfristig in unsere Mitarbeiter.' : 'We provide the foundational infrastructure for your personal and interdisciplinary evolution.' ?></p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 80px 60px; position: relative; z-index: 1;">
      <?php 
      $benefits = [
        ['icon' => '🛡️', 'title' => 'Asset Security', 'desc' => 'Market-leading institutional pension and insurance matrices.'],
        ['icon' => '🎓', 'title' => 'Academy Protocol', 'desc' => 'Continuous technical and leadership certification cycles.'],
        ['icon' => '🔋', 'title' => 'Health Registry', 'desc' => 'Premium health management and interdisciplinary fitness programs.'],
        ['icon' => '📅', 'title' => 'Balance Control', 'desc' => 'Flexible and hybrid models for interdisciplinary institutional roles.'],
        ['icon' => '🚀', 'title' => 'Global Mobility', 'desc' => 'Strategic opportunities for international project deployment.'],
        ['icon' => '💎', 'title' => 'Exclusive Perks', 'desc' => 'Corporate discounts and premium institutional team amenities.'],
      ];
      foreach ($benefits as $b):
      ?>
      <div style="display: flex; gap: 32px; align-items: flex-start;">
        <div style="width: 64px; height: 64px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; flex-shrink: 0; box-shadow: var(--shadow-md); border: 1px solid #f1f5f9;"><?= $b['icon'] ?></div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; color: var(--primary); margin: 0 0 12px 0; font-weight: 900; letter-spacing: -0.5px;"><?= $b['title'] ?></h4>
          <p style="font-size: 1.05rem; color: var(--text-muted); margin: 0; line-height: 1.7; font-weight: 500;"><?= $b['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
