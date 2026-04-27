<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Neuigkeiten' : 'Corporate Intelligence' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 400px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Intelligence Hub
      </div>
      <h1 style="font-size: 4.5rem; font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 16px; font-weight: 900; letter-spacing: -2px;">Corporate<br>Intelligence.</h1>
      <p style="font-size: 1.4rem; opacity: 0.9; max-width: 650px; font-weight: 500; line-height: 1.6;"><?= $lang === 'de' ? 'Aktuelle Entwicklungen und technische Innovationen der STREICHER Gruppe.' : 'Latest developments and technical innovations from the STREICHER Group.' ?></p>
    </div>
  </section>

  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(500px, 1fr)); gap: 48px; margin-bottom: 120px;">
    <?php foreach ($newsItems as $news): ?>
    <article style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: all 0.4s ease-out; position: relative;" onmouseover="this.style.transform='translateY(-12px)'" onmouseout="this.style.transform='translateY(0)'">
      <div style="position: relative; aspect-ratio: 16/10; overflow: hidden;">
        <img src="<?= $news['image'] ?>" alt="<?= $lang === 'de' ? $news['title_de'] : $news['title_en'] ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);">
        <?php if (!empty($news['hasVideo'])): ?>
        <div style="position: absolute; top: 32px; right: 32px; background: var(--accent); color: white; padding: 8px 20px; border-radius: 4px; font-size: 0.75rem; font-weight: 900; display: flex; align-items: center; gap: 10px; box-shadow: var(--shadow-md); z-index: 2; letter-spacing: 2px;">
          <span style="font-size: 0.9rem;">▶</span> VIDEO REPORT
        </div>
        <?php endif; ?>
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 40%; background: linear-gradient(to top, rgba(15, 23, 42, 0.6), transparent); z-index: 1;"></div>
      </div>
      <div style="padding: 48px; flex: 1; display: flex; flex-direction: column;">
        <time style="font-size: 0.85rem; color: var(--accent); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 20px;">
          <?= date($lang === 'de' ? 'd. M Y' : 'M d, Y', strtotime($news['date'])) ?>
        </time>
        <h3 style="font-size: 2rem; font-family: 'Outfit', sans-serif; margin: 0 0 24px 0; line-height: 1.1; color: var(--primary); font-weight: 900; letter-spacing: -1px;">
          <a href="/news/<?= $news['slug'] ?>" style="text-decoration: none; color: inherit; transition: all 0.3s;"><?= $lang === 'de' ? $news['title_de'] : $news['title_en'] ?></a>
        </h3>
        <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 40px; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
          <?= $lang === 'de' ? $news['excerpt_de'] : $news['excerpt_en'] ?>
        </p>
        <div style="margin-top: auto; padding-top: 32px; border-top: 2px solid #f8fafc;">
          <a href="/news/<?= $news['slug'] ?>" style="color: var(--primary); font-weight: 900; text-decoration: none; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s;" onmouseover="this.style.color='var(--accent)'; this.children[1].style.transform='translateX(8px)';" onmouseout="this.style.color='var(--primary)'; this.children[1].style.transform='translateX(0)';">
            <span><?= $lang === 'de' ? 'Vollständiger Bericht' : 'Access Full Report' ?></span>
            <span style="font-size: 1.5rem; transition: transform 0.3s;">→</span>
          </a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</div>
