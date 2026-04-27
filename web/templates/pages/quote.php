<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Angebot anfordern' : 'Institutional Quote' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 60px; min-height: 300px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        B2B Procurement
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 16px;"><?= $lang === 'de' ? 'Angebot anfordern' : 'Request Institutional Quote' ?></h1>
      <p style="font-size: 1.2rem; opacity: 0.9; max-width: 600px;">
        <?= $lang === 'de' 
            ? 'Erhalten Sie maßgeschneiderte Konditionen und fachspezifische Beratung für Ihre Projekte.' 
            : 'Access tailored procurement terms and interdisciplinary technical consultation for your global projects.' ?>
      </p>
    </div>
  </section>

  <?php if (!empty($success)): ?>
  <div style="background: #ecfdf5; border: 1px solid #10b981; border-radius: var(--radius-lg); padding: 32px; margin-bottom: 60px; display: flex; gap: 24px; align-items: center; box-shadow: var(--shadow-md);">
    <div style="font-size: 2.5rem;">🏢</div>
    <div>
      <div style="font-size: 1.2rem; font-weight: 900; color: #065f46; margin-bottom: 4px;"><?= $lang === 'de' ? 'Anfrage erfolgreich im System erfasst!' : 'Quote Request Securely Transmitted' ?></div>
      <div style="color: #065f46; opacity: 0.8; font-size: 1rem;"><?= $lang === 'de' ? 'Unsere Fachabteilung wird Ihre Spezifikationen prüfen und sich zeitnah melden.' : 'Our interdisciplinary team will review your specifications and contact your procurement department within 24-48 hours.' ?></div>
    </div>
  </div>
  <?php endif; ?>

  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 60px; align-items: start;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); padding: 60px;">
      <form action="/quote" method="POST">
        <!-- Step 1: Corporate Identity -->
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 40px;">
          <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem;">1</div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin: 0; color: var(--primary);"><?= $lang === 'de' ? 'Unternehmensdaten' : 'Corporate Identity' ?></h3>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
          <div class="form-group-modern">
            <label><?= $lang === 'de' ? 'Vollständiger Name' : 'Authorized Representative' ?> *</label>
            <input type="text" name="name" required placeholder="Dipl.-Ing. John Doe">
          </div>
          <div class="form-group-modern">
            <label><?= $lang === 'de' ? 'Position / Titel' : 'Corporate Title' ?></label>
            <input type="text" name="title" placeholder="Head of Procurement">
          </div>
        </div>

        <div class="form-group-modern" style="margin-bottom: 32px;">
          <label><?= $lang === 'de' ? 'Unternehmen' : 'Legal Entity / Company' ?> *</label>
          <input type="text" name="company" required placeholder="Industrial Engineering Group AG">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 60px;">
          <div class="form-group-modern">
            <label>Corporate Email *</label>
            <input type="email" name="email" required placeholder="representative@company.com">
          </div>
          <div class="form-group-modern">
            <label><?= $lang === 'de' ? 'Telefon / Durchwahl' : 'Direct Line' ?> *</label>
            <input type="tel" name="phone" required placeholder="+49 ...">
          </div>
        </div>

        <!-- Step 2: Technical Specifications -->
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 40px;">
          <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem;">2</div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin: 0; color: var(--primary);"><?= $lang === 'de' ? 'Technische Spezifikationen' : 'Technical Specifications' ?></h3>
        </div>

        <?php if ($product): ?>
        <div style="background: #f8fafc; padding: 24px 32px; border-radius: var(--radius-lg); margin-bottom: 32px; border: 1px solid rgba(0,0,0,0.05); border-left: 6px solid var(--accent);">
          <div style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; color: var(--accent); margin-bottom: 8px; letter-spacing: 1px;"><?= $lang === 'de' ? 'Angeforderte Systemkomponente' : 'Requested System Component' ?></div>
          <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary);"><?= htmlspecialchars($product['name']) ?></div>
          <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 4px;">SKU: <?= htmlspecialchars($product['sku']) ?></div>
          <input type="hidden" name="product_sku" value="<?= htmlspecialchars($product['sku']) ?>">
        </div>
        <?php endif; ?>

        <div class="form-group-modern" style="margin-bottom: 32px;">
          <label><?= $lang === 'de' ? 'Anforderungsdetails / Lastenheft' : 'Detailed Requirements / Specifications' ?> *</label>
          <textarea name="description" rows="6" required placeholder="<?= $lang === 'de' ? 'Beschreiben Sie Ihre technischen Anforderungen, Projektumfang...' : 'Outline your technical requirements, interdisciplinary scope, and specific industrial standards...' ?>"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 60px;">
          <div class="form-group-modern">
            <label><?= $lang === 'de' ? 'Erwartete Menge' : 'Projected Quantity' ?></label>
            <input type="number" name="quantity" min="1" value="1">
          </div>
          <div class="form-group-modern">
            <label><?= $lang === 'de' ? 'Gewünschte Inbetriebnahme' : 'Target Commissioning' ?></label>
            <input type="date" name="delivery_date">
          </div>
        </div>

        <button type="submit" class="btn-modern btn-accent" style="width: 100%; padding: 24px; font-size: 1.1rem; justify-content: center; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">
          <?= $lang === 'de' ? 'Offizielle Angebotsanfrage senden' : 'Transmit Institutional Quote Request' ?>
        </button>
      </form>
    </div>

    <aside>
      <div style="background: var(--primary); color: white; border-radius: var(--radius-lg); padding: 40px; margin-bottom: 32px; box-shadow: var(--shadow-lg);">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin-bottom: 32px;"><?= $lang === 'de' ? 'Exklusive B2B Vorteile' : 'Institutional Benefits' ?></h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 24px;">
          <li style="display: flex; gap: 16px; align-items: flex-start;">
            <span style="color: var(--accent); font-size: 1.2rem; font-weight: 900;">✓</span>
            <div>
              <div style="font-weight: 800; font-size: 1rem; margin-bottom: 4px;"><?= $lang === 'de' ? 'Individuelle Kalkulation' : 'Dynamic Scale Pricing' ?></div>
              <div style="font-size: 0.9rem; opacity: 0.7; line-height: 1.5;"><?= $lang === 'de' ? 'Individuelle Preisstaffelung für Großprojekte.' : 'Tiered pricing structures optimized for large-scale procurement.' ?></div>
            </div>
          </li>
          <li style="display: flex; gap: 16px; align-items: flex-start;">
            <span style="color: var(--accent); font-size: 1.2rem; font-weight: 900;">✓</span>
            <div>
              <div style="font-weight: 800; font-size: 1rem; margin-bottom: 4px;"><?= $lang === 'de' ? 'Fachspezifische Beratung' : 'Interdisciplinary Advisory' ?></div>
              <div style="font-size: 0.9rem; opacity: 0.7; line-height: 1.5;"><?= $lang === 'de' ? 'Direkter Zugang zu unseren Fachexperten.' : 'Direct access to senior subject matter experts and engineers.' ?></div>
            </div>
          </li>
          <li style="display: flex; gap: 16px; align-items: flex-start;">
            <span style="color: var(--accent); font-size: 1.2rem; font-weight: 900;">✓</span>
            <div>
              <div style="font-weight: 800; font-size: 1rem; margin-bottom: 4px;"><?= $lang === 'de' ? 'Priorisierte Abwicklung' : 'SLA-Driven Processing' ?></div>
              <div style="font-size: 0.9rem; opacity: 0.7; line-height: 1.5;"><?= $lang === 'de' ? 'Bevorzugte Bearbeitung Ihrer Anfragen.' : 'Priority project management and rapid response protocols.' ?></div>
            </div>
          </li>
        </ul>
      </div>

      <div style="background: white; border-radius: var(--radius-lg); padding: 40px; border: 1px solid rgba(0,0,0,0.05); text-align: center; box-shadow: var(--shadow-md);">
        <div style="font-size: 3.5rem; margin-bottom: 24px;">⏱️</div>
        <div style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: var(--primary); margin-bottom: 8px; font-weight: 900;">24-48h</div>
        <div style="color: var(--text-muted); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;"><?= $lang === 'de' ? 'Durchschnittliche SLA' : 'Institutional Response SLA' ?></div>
      </div>
    </aside>
  </div>
</div>
