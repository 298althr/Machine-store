<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Mediathek' : 'Media Library' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 400px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://www.streichergmbh.com/fileadmin/_processed_/8/6/csm_STREICHER-Deggendorf_Drohne_DJI_0003_6b7c9f7c8a.jpg') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Visual Archive
      </div>
      <h1 style="font-size: 4.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 16px; font-weight: 900; letter-spacing: -2px;">Visual<br>Engineering.</h1>
      <p style="font-size: 1.4rem; opacity: 0.9; max-width: 650px; font-weight: 500; line-height: 1.6;"><?= $lang === 'de' ? 'Unsere interdisziplinären Projekte und Anlagen in bewegten Bildern.' : 'Our interdisciplinary projects and plants in moving images.' ?></p>
    </div>
  </section>

  <!-- Projects Registry -->
  <div style="margin-bottom: 120px;">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
      <h2 style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Projekte und Anlagen' : 'Global Project Showcase' ?></h2>
      <span style="color: var(--accent); font-weight: 900; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px;">Institutional Vimeo Feed</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(550px, 1fr)); gap: 48px;">
      <?php foreach ($projectVideos as $video): ?>
      <div style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); transition: all 0.4s ease-out; position: relative;" onmouseover="this.style.transform='translateY(-12px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="position: relative; padding-bottom: 56.25%; height: 0; background: #000;">
          <iframe 
            src="https://player.vimeo.com/video/<?= $video['vimeo_id'] ?>?title=0&byline=0&portrait=0" 
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
            frameborder="0" allow="autoplay; fullscreen" allowfullscreen>
          </iframe>
        </div>
        <div style="padding: 40px; background: white; border-top: 6px solid var(--accent);">
          <h3 style="font-size: 1.4rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; line-height: 1.3; font-weight: 900; letter-spacing: -0.5px;"><?= $lang === 'de' ? $video['title_de'] : $video['title_en'] ?></h3>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Corporate Films Matrix -->
  <div style="margin-bottom: 120px; background: #f8fafc; padding: 100px 80px; border-radius: var(--radius-lg); border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">FILM</div>
    <div style="text-align: center; margin-bottom: 80px; position: relative; z-index: 1;">
      <h2 style="font-size: 3rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0 0 24px 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Unternehmensfilme' : 'Corporate Identity' ?></h2>
      <p style="color: var(--text-muted); max-width: 700px; margin: 0 auto; font-size: 1.2rem; font-weight: 500; line-height: 1.6;"><?= $lang === 'de' ? 'Einblick in die Welt der STREICHER Gruppe.' : 'A cinematic insight into the world of the STREICHER Group.' ?></p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(550px, 1fr)); gap: 48px; position: relative; z-index: 1;">
      <?php foreach ($imageVideos as $video): ?>
      <div style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); transition: all 0.4s ease-out;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <div style="position: relative; padding-bottom: 56.25%; height: 0; background: #000;">
          <?php if ($video['youtube_id']): ?>
          <iframe 
            src="https://www.youtube-nocookie.com/embed/<?= $video['youtube_id'] ?>?rel=0&modestbranding=1" 
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
            frameborder="0" allowfullscreen>
          </iframe>
          <?php else: ?>
          <iframe 
            src="https://player.vimeo.com/video/<?= $video['vimeo_id'] ?>?title=0&byline=0&portrait=0" 
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
            frameborder="0" allowfullscreen>
          </iframe>
          <?php endif; ?>
        </div>
        <div style="padding: 40px;">
          <h3 style="font-size: 1.4rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; line-height: 1.3; font-weight: 900; letter-spacing: -0.5px;"><?= $lang === 'de' ? $video['title_de'] : $video['title_en'] ?></h3>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Industrial Impression Registry -->
  <div style="margin-bottom: 120px;">
    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;">
      <h2 style="font-size: 2.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1.5px;"><?= $lang === 'de' ? 'Impressionen' : 'Industrial Impressions' ?></h2>
      <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Registry: HQ & Site Photography</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
      <?php foreach ($galleryImages as $index => $image): ?>
      <div style="position: relative; aspect-ratio: 1; border-radius: var(--radius-lg); overflow: hidden; cursor: pointer; border: 1px solid rgba(0,0,0,0.05); box-shadow: var(--shadow-lg);">
        <img src="<?= $image ?>" alt="Streicher Gallery Image" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
        <div style="position: absolute; inset: 0; background: linear-gradient(transparent, rgba(15, 23, 42, 0.8)); opacity: 0; transition: 0.4s; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
          <div style="color: white; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; font-size: 0.75rem; border: 2px solid white; padding: 12px 24px; border-radius: 4px;">Initialize Review</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
