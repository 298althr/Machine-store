<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Unternehmensidentität' : 'Institutional Identity' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 550px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 100%), url('https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; max-width: 900px; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 2px;">
        <?= $lang === 'de' ? 'Unsere Wurzeln' : 'Institutional Heritage' ?>
      </div>
      <h1 style="font-size: 4.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 24px; font-weight: 900; letter-spacing: -2px;">
        <?= $lang === 'de' ? 'Technische Exzellenz,<br>Jahrzehntelange Präzision.' : 'Decades of Excellence,<br>Technical Precision.' ?>
      </h1>
      <p style="font-size: 1.5rem; opacity: 0.9; line-height: 1.6; max-width: 700px; font-weight: 500;">
        <?= $lang === 'de' 
            ? 'Seit 1909 setzen wir Maßstäbe im internationalen Anlagenbau und interdisziplinären Maschinenbau.' 
            : 'Setting global benchmarks in plant and interdisciplinary mechanical engineering since 1909.' ?>
      </p>
    </div>
  </section>

  <div style="display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 80px; align-items: start; margin-bottom: 120px;">
    <!-- Institutional Narrative -->
    <div style="line-height: 1.8; color: var(--text-muted); font-size: 1.15rem;">
      <h2 style="font-family: 'Outfit', sans-serif; color: var(--primary); font-size: 2.75rem; margin-bottom: 48px; font-weight: 900; letter-spacing: -1.5px;">
        <?= $lang === 'de' ? 'Unternehmensentwicklung' : 'Institutional Evolution' ?>
      </h2>
      
      <?php if ($lang === 'de'): ?>
        <p style="font-size: 1.6rem; color: var(--primary); font-weight: 800; font-family: 'Outfit', sans-serif; margin-bottom: 48px; line-height: 1.4; letter-spacing: -0.5px;">
          MAX STREICHER GmbH & Co. KG aA mit Hauptsitz in Deggendorf ist ein international tätiger Konzern mit über 4.500 Mitarbeitern.
        </p>
        <div style="background: white; border-left: 8px solid var(--accent); padding: 40px; border-radius: 8px; margin-bottom: 48px; box-shadow: var(--shadow-md);">
          <p style="margin-bottom: 0; font-weight: 500; font-style: italic;">"Was 1909 mit Straßenbau begann, hat sich zu einem interdisziplinären Konzern entwickelt. Wir meistern nationale und internationale Großprojekte durch technisches Know-how und eine solide Eigenkapitalstruktur."</p>
        </div>
        <p style="margin-bottom: 32px;">Unser Spektrum umfasst Pipelines, Anlagenbau, Maschinenbau sowie Hoch- und Tiefbau, unterstützt durch eigene Rohstoffquellen zur Qualitätssicherung.</p>
      <?php else: ?>
        <p style="font-size: 1.6rem; color: var(--primary); font-weight: 800; font-family: 'Outfit', sans-serif; margin-bottom: 48px; line-height: 1.4; letter-spacing: -0.5px;">
          MAX STREICHER GmbH & Co. KG aA, headquartered in Deggendorf, is an internationally active corporate group with more than 4,500 employees.
        </p>
        <div style="background: white; border-left: 8px solid var(--accent); padding: 40px; border-radius: 8px; margin-bottom: 48px; box-shadow: var(--shadow-md);">
          <p style="margin-bottom: 0; font-weight: 500; font-style: italic;">"Established in 1909 with a focus on infrastructure, STREICHER has evolved into a global interdisciplinary powerhouse. Organic growth and technical subject matter expertise are the pillars of our success in massive international projects."</p>
        </div>
        <p style="margin-bottom: 32px;">Our scope includes pipelines, plant engineering, mechanical engineering, and complex civil structures, backed by proprietary raw material plants for vertical quality control.</p>
      <?php endif; ?>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 80px;">
        <div style="background: #f8fafc; padding: 60px 48px; border-radius: var(--radius-lg); text-align: center; border: 1px solid #f1f5f9; box-shadow: var(--shadow-md); position: relative; overflow: hidden;">
          <div style="position: absolute; top: -10px; right: -10px; font-size: 8rem; opacity: 0.03; font-weight: 900; color: var(--primary);">HQ</div>
          <div style="font-size: 4.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; line-height: 1; letter-spacing: -2px;">1909</div>
          <div style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; color: var(--text-muted); margin-top: 24px;"><?= $lang === 'de' ? 'Gründung' : 'Established' ?></div>
        </div>
        <div style="background: #f8fafc; padding: 60px 48px; border-radius: var(--radius-lg); text-align: center; border: 1px solid #f1f5f9; box-shadow: var(--shadow-md); position: relative; overflow: hidden;">
          <div style="position: absolute; top: -10px; right: -10px; font-size: 8rem; opacity: 0.03; font-weight: 900; color: var(--primary);">GL</div>
          <div style="font-size: 4.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; line-height: 1; letter-spacing: -2px;">4,500+</div>
          <div style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; color: var(--text-muted); margin-top: 24px;"><?= $lang === 'de' ? 'Mitarbeiter' : 'Global Talent' ?></div>
        </div>
      </div>
    </div>

    <!-- Corporate Infrastructure Sidebar -->
    <aside style="position: sticky; top: 120px;">
      <div style="background: white; border-radius: var(--radius-lg); padding: 60px 48px; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 48px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; font-size: 8rem; opacity: 0.02; font-weight: 900;">OFFICE</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin-bottom: 40px; font-weight: 900; letter-spacing: -0.5px;">Executive Registry</h3>
        <address style="font-style: normal; color: var(--text-muted); line-height: 1.8; margin-bottom: 48px; font-size: 1.05rem; font-weight: 600;">
          MAX STREICHER GmbH & Co. KG aA<br>
          Schwaigerbreite 17<br>
          94469 Deggendorf<br>
          Germany
        </address>
        <div style="display: grid; gap: 24px; margin-bottom: 48px;">
          <a href="mailto:info@streicher.de" style="color: var(--accent); font-weight: 900; text-decoration: none; font-size: 1.25rem; font-family: 'Outfit', sans-serif;">info@streicher.de</a>
          <a href="https://www.streicher.de" target="_blank" style="color: var(--primary); font-weight: 900; text-decoration: none; border-bottom: 4px solid var(--accent); display: inline-block; width: fit-content; padding-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;">Global Group Website</a>
        </div>
        <a href="/contact" class="btn-modern btn-accent" style="width: 100%; height: 72px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Institutional Inquiry</a>
      </div>

      <div style="background: var(--primary); border-radius: var(--radius-lg); padding: 60px 48px; color: white; box-shadow: var(--shadow-xl); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; font-size: 8rem; opacity: 0.05; font-weight: 900;">SEC</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: white; margin-bottom: 40px; font-weight: 900; letter-spacing: -0.5px;">Interdisciplinary Sectors</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 20px;">
          <?php 
          $sectors = [
            ['Pipelines & Plants', 'Infrastructure'],
            ['Mechanical Engineering', 'Advanced Systems'],
            ['Electrical Engineering', 'Global Control'],
            ['Civil Engineering', 'Structural Design'],
            ['Raw Materials', 'Vertical Integration']
          ];
          foreach ($sectors as $s): ?>
          <li style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;">
            <a href="/business-sectors" style="color: white; text-decoration: none; display: block; transition: all 0.3s;" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <div style="font-size: 1.1rem; font-weight: 900; font-family: 'Outfit', sans-serif;"><?= $s[0] ?></div>
                  <div style="font-size: 0.75rem; opacity: 0.6; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px;"><?= $s[1] ?></div>
                </div>
                <span style="color: var(--accent); font-size: 1.5rem; font-weight: 900;">→</span>
              </div>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </div>
</div>
iv>
</div>
