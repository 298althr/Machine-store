<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Geschäftsbereiche' : 'Interdisciplinary Sectors' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 120px; min-height: 450px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('/images/photos/pipeline2.jpg') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 900px;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 2px;">
        Institutional Diversity
      </div>
      <h1 style="font-size: 4.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 24px; font-weight: 900; letter-spacing: -2px;">Interdisciplinary<br>Engineering.</h1>
      <p style="font-size: 1.4rem; opacity: 0.9; line-height: 1.6; font-weight: 500;">
        <?= $lang === 'de' 
            ? 'Die STREICHER Gruppe bündelt interdisziplinäres Know-how in fünf Kernsektoren für globale Großprojekte.' 
            : 'The STREICHER Group consolidates interdisciplinary expertise across five core sectors for global large-scale projects.' ?>
      </p>
    </div>
  </section>

  <div style="display: flex; flex-direction: column; gap: 160px; margin-bottom: 160px;">
    <?php foreach ($sectors as $index => $sector): ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 100px; align-items: center; <?= $index % 2 === 1 ? 'direction: rtl;' : '' ?>">
      <div style="direction: ltr;" class="hover-lift">
        <div style="position: relative; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05);">
          <img src="<?= $sector['image'] ?>" alt="<?= $lang === 'de' ? $sector['title_de'] : $sector['title_en'] ?>" style="width: 100%; height: 600px; object-fit: cover; transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
          <div style="position: absolute; bottom: 40px; left: 40px; background: white; width: 100px; height: 100px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 3rem; box-shadow: var(--shadow-xl); border: 1px solid #f1f5f9; z-index: 2;">
            <?= $sector['icon'] ?>
          </div>
          <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 40%; background: linear-gradient(to top, rgba(15, 23, 42, 0.4), transparent); z-index: 1;"></div>
        </div>
      </div>
      <div style="direction: ltr;">
        <div style="font-size: 0.9rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 32px; display: flex; align-items: center; gap: 16px;">
          <span style="width: 60px; height: 3px; background: var(--accent);"></span>
          DOMAIN PROTOCOL S-<?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
        </div>
        <h2 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; margin: 0 0 32px 0; color: var(--primary); line-height: 1; font-weight: 900; letter-spacing: -1.5px;">
          <?= $lang === 'de' ? $sector['title_de'] : $sector['title_en'] ?>
        </h2>
        <p style="font-size: 1.25rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 48px; font-weight: 500;">
          <?= $lang === 'de' ? $sector['desc_de'] : $sector['desc_en'] ?>
        </p>
        <a href="/business-sectors/<?= $sector['slug'] ?>" class="btn-modern btn-accent" style="padding: 24px 56px; display: inline-flex; align-items: center; gap: 20px; font-weight: 900; font-size: 1rem; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">
          <?= $lang === 'de' ? 'Sektor-Spezifikationen' : 'Sector Protocol' ?> <span style="font-size: 1.5rem;">→</span>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <section style="background: var(--primary); border-radius: var(--radius-lg); padding: 120px 100px; text-align: center; color: white; box-shadow: var(--shadow-xl); position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">HUB</div>
    <h2 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; margin-bottom: 32px; font-weight: 900; letter-spacing: -1.5px; position: relative; z-index: 1;"><?= $lang === 'de' ? 'Interdisziplinäre Beratung' : 'Interdisciplinary Consulting' ?></h2>
    <p style="font-size: 1.4rem; opacity: 0.8; max-width: 850px; margin: 0 auto 60px; line-height: 1.6; font-weight: 500; position: relative; z-index: 1;">
      <?= $lang === 'de' 
          ? 'Unsere Fachexperten unterstützen Sie bei der Realisierung komplexer technologischer Anforderungen weltweit.' 
          : 'Our subject matter experts support the realization of your complex technological requirements on a global scale.' ?>
    </p>
    <div style="display: flex; gap: 32px; justify-content: center; position: relative; z-index: 1;">
      <a href="/contact" class="btn-modern btn-accent" style="padding: 28px 60px; font-size: 1.1rem; font-weight: 900; border-radius: 8px; text-transform: uppercase; letter-spacing: 2px;">Institutional Inquiry</a>
      <a href="/reference-projects" class="btn-modern" style="padding: 28px 60px; font-size: 1.1rem; border: 2px solid rgba(255,255,255,0.2); background: transparent; color: white; font-weight: 900; border-radius: 8px; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='white';" onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.2)';">
        <?= $lang === 'de' ? 'Referenzen ansehen' : 'View Global Registry' ?>
      </a>
    </div>
  </section>
</div>
