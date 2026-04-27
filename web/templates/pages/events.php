<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Veranstaltungen' : 'Institutional Presence' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 400px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Presence Matrix
      </div>
      <h1 style="font-size: 4.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 16px; font-weight: 900; letter-spacing: -2px;">Global Event<br>Network.</h1>
      <p style="font-size: 1.4rem; opacity: 0.9; max-width: 650px; font-weight: 500; line-height: 1.6;"><?= $lang === 'de' ? 'Treffen Sie die STREICHER Gruppe auf den führenden Industriemessen und Fachtagungen weltweit.' : 'Connect with the STREICHER Group at leading industrial trade fairs and conferences worldwide.' ?></p>
    </div>
  </section>

  <!-- Upcoming Events Matrix -->
  <div style="margin-bottom: 120px;">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
      <h2 style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Kommende Termine' : 'Scheduled Events' ?></h2>
      <span style="color: var(--accent); font-weight: 900; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px;">2024-2025 Calendar Registry</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(500px, 1fr)); gap: 48px;">
      <?php foreach ($events as $event): ?>
      <article style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: all 0.4s ease-out;" onmouseover="this.style.transform='translateY(-12px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="position: relative; aspect-ratio: 16/10; overflow: hidden;">
          <img src="<?= $event['image'] ?>" alt="<?= $event['title_en'] ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);">
          <div style="position: absolute; top: 32px; right: 32px; background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: var(--shadow-lg); min-width: 100px; border: 1px solid #f1f5f9; z-index: 2;">
            <div style="font-size: 2rem; font-weight: 900; color: var(--accent); line-height: 1; font-family: 'Outfit', sans-serif;"><?= date('d', strtotime($event['date'])) ?></div>
            <div style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; color: var(--primary); margin-top: 6px; letter-spacing: 2px;"><?= date('M', strtotime($event['date'])) ?></div>
          </div>
          <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 40%; background: linear-gradient(to top, rgba(15, 23, 42, 0.6), transparent); z-index: 1;"></div>
        </div>
        <div style="padding: 48px; flex: 1;">
          <h3 style="font-size: 1.75rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0 0 24px 0; line-height: 1.2; font-weight: 900; letter-spacing: -0.5px;">
            <?= $lang === 'de' ? $event['title_de'] : $event['title_en'] ?>
          </h3>
          <div style="font-size: 1.05rem; color: var(--text-muted); margin-bottom: 32px; display: grid; gap: 12px; font-weight: 600;">
            <span style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 1.25rem;">📍</span> <?= $event['location'] ?></span>
            <span style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 1.25rem;">📅</span> <?= date('d.m.Y', strtotime($event['date'])) ?> - <?= date('d.m.Y', strtotime($event['end_date'])) ?></span>
          </div>
          <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin: 0; font-weight: 500;">
            <?= $lang === 'de' ? $event['desc_de'] : $event['desc_en'] ?>
          </p>
          <div style="margin-top: 40px; padding-top: 32px; border-top: 2px solid #f8fafc;">
            <a href="/contact" class="btn-modern btn-accent" style="width: 100%; height: 64px; font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Institutional Registration</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Retrospective Matrix -->
  <section style="background: #f8fafc; border-radius: var(--radius-lg); padding: 120px 100px; margin-bottom: 120px; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">ARC</div>
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px; position: relative; z-index: 1;">
      <h2 style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Rückblick' : 'Institutional Retrospective' ?></h2>
      <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Archive Participation Protocol</span>
    </div>
    
    <div style="display: grid; gap: 32px; position: relative; z-index: 1;">
      <?php foreach ($pastEvents as $event): ?>
      <div style="background: white; padding: 48px; border-radius: var(--radius-lg); display: flex; align-items: center; gap: 80px; box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s;" onmouseover="this.style.boxShadow='0 25px 50px -12px rgba(15, 23, 42, 0.1)';">
        <div style="min-width: 140px; text-align: center; border-right: 3px solid #f1f5f9; padding-right: 48px; flex-shrink: 0;">
          <div style="font-size: 2.5rem; font-weight: 900; color: var(--primary); opacity: 0.1; line-height: 1; font-family: 'Outfit', sans-serif;"><?= date('d', strtotime($event['date'])) ?></div>
          <div style="font-size: 0.85rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; margin-top: 8px; letter-spacing: 2px;"><?= date('M Y', strtotime($event['date'])) ?></div>
        </div>
        <div style="flex: 1;">
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0 0 12px 0; font-weight: 900; letter-spacing: -0.5px;"><?= $lang === 'de' ? $event['title_de'] : $event['title_en'] ?></h4>
          <span style="font-size: 1rem; color: var(--accent); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: flex; align-items: center; gap: 10px;"><span style="font-size: 1.25rem;">📍</span> <?= $event['location'] ?></span>
        </div>
        <div style="max-width: 550px; font-size: 1.05rem; color: var(--text-muted); line-height: 1.8; font-weight: 500;">
          <?= $lang === 'de' ? $event['desc_de'] : $event['desc_en'] ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
