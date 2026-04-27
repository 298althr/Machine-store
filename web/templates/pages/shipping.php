<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;"><?= $lang === 'de' ? 'Versandinformationen' : 'Global Logistics' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 400px; display: flex; align-items: center; padding: 80px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.5) 100%), url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 800px;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 2px;">
        Institutional Supply Chain
      </div>
      <h1 style="font-size: 4rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 24px;"><?= $lang === 'de' ? 'Logistik & Versand' : 'Global Logistics & Shipping' ?></h1>
      <p style="font-size: 1.3rem; opacity: 0.9; line-height: 1.6;">
        <?= $lang === 'de' 
            ? 'Weltweite Lieferung interdisziplinärer Anlagen mit höchster Präzision und Zuverlässigkeit.' 
            : 'Worldwide delivery of interdisciplinary plant and machinery systems with absolute precision and reliability.' ?>
      </p>
    </div>
  </section>

  <div style="max-width: 1200px; margin: 0 auto 120px;">
    <!-- Shipping Tiers -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; margin-bottom: 100px;">
      <?php 
      $options = [
        ['icon' => '🚢', 'title' => 'Industrial Freight', 'desc' => 'Global ground or sea freight optimized for heavy interdisciplinary equipment.', 'time' => '4-8 weeks'],
        ['icon' => '✈️', 'title' => 'Urgent Air Lift', 'desc' => 'Expedited institutional air shipping for critical system components.', 'time' => '1-2 weeks'],
        ['icon' => '📦', 'title' => 'Rapid Courier', 'desc' => 'Priority dispatch for technical spare parts and documentation.', 'time' => '3-7 days'],
      ];
      foreach ($options as $opt):
      ?>
      <div class="hover-lift" style="background: white; padding: 50px 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); text-align: center; border: 1px solid rgba(0,0,0,0.05); transition: var(--transition);">
        <div style="font-size: 4rem; margin-bottom: 32px;"><?= $opt['icon'] ?></div>
        <h4 style="font-size: 1.4rem; font-family: 'Outfit', sans-serif; margin-bottom: 16px; color: var(--primary);"><?= $opt['title'] ?></h4>
        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 24px; line-height: 1.6;"><?= $opt['desc'] ?></p>
        <div style="font-weight: 800; color: var(--accent); font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;"><?= $opt['time'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Global Transit Matrix -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 100px;">
      <div style="padding: 40px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin: 0; color: var(--primary);">Institutional Transit Matrix</h3>
        <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">SLA Estimates</span>
      </div>
      <table style="width: 100%; border-collapse: collapse;">
        <thead style="background: white;">
          <tr>
            <th style="text-align: left; padding: 24px 40px; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid #f1f5f9;">Global Region</th>
            <th style="text-align: left; padding: 24px 40px; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid #f1f5f9;">Freight</th>
            <th style="text-align: left; padding: 24px 40px; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid #f1f5f9;">Air</th>
            <th style="text-align: left; padding: 24px 40px; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid #f1f5f9;">Express</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $regions = [
            ['r' => 'European Union (EU)', 's' => '2-4 weeks', 'a' => '5-7 days', 'e' => '3-5 days'],
            ['r' => 'North America (USA/CAN)', 's' => '4-6 weeks', 'a' => '7-10 days', 'e' => '5-7 days'],
            ['r' => 'Middle East & Gulf Region', 's' => '4-6 weeks', 'a' => '5-7 days', 'e' => '5-7 days'],
            ['r' => 'Asia Pacific (APAC)', 's' => '6-8 weeks', 'a' => '7-14 days', 'e' => '7-10 days'],
          ];
          foreach ($regions as $row):
          ?>
          <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
            <td style="padding: 24px 40px; font-weight: 800; color: var(--primary); font-size: 1.1rem;"><?= $row['r'] ?></td>
            <td style="padding: 24px 40px; font-size: 1rem; color: var(--text-muted);"><?= $row['s'] ?></td>
            <td style="padding: 24px 40px; font-size: 1rem; color: var(--text-muted);"><?= $row['a'] ?></td>
            <td style="padding: 24px 40px; font-size: 1rem; color: var(--text-muted);"><?= $row['e'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Fulfillment Protocol -->
    <div style="background: #f8fafc; border-radius: var(--radius-lg); padding: 80px 60px; margin-bottom: 100px; border: 1px solid rgba(0,0,0,0.02);">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; margin-bottom: 60px; text-align: center; color: var(--primary); text-transform: uppercase; letter-spacing: 3px;">Fulfillment Protocol</h3>
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px;">
        <?php 
        $steps = ['Institutional Verification', 'Precision Preparation', 'Global Dispatch', 'Final Commissioning'];
        foreach ($steps as $i => $step):
        ?>
        <div style="text-align: center; position: relative;">
          <div style="width: 60px; height: 60px; background: var(--primary); color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-weight: 900; font-size: 1.5rem; box-shadow: var(--shadow-md); border: 4px solid white;"><?= $i+1 ?></div>
          <h5 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: var(--primary); font-weight: 800;"><?= $step ?></h5>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Assurance Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
      <div class="hover-lift" style="background: white; padding: 48px; border-radius: var(--radius-lg); border: 1px solid rgba(0,0,0,0.05); box-shadow: var(--shadow-sm); display: flex; gap: 32px; align-items: flex-start;">
        <div style="font-size: 3rem;">📋</div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--primary); margin-bottom: 12px; font-weight: 800;">Global Documentation</h4>
          <p style="color: var(--text-muted); font-size: 1rem; margin: 0; line-height: 1.6;">Full institutional and commercial documentation, including customs clearance support, provided with every interdisciplinary shipment.</p>
        </div>
      </div>
      <div class="hover-lift" style="background: white; padding: 48px; border-radius: var(--radius-lg); border: 1px solid rgba(0,0,0,0.05); box-shadow: var(--shadow-sm); display: flex; gap: 32px; align-items: flex-start;">
        <div style="font-size: 3rem;">🛡️</div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--primary); margin-bottom: 12px; font-weight: 800;">Asset Insurance</h4>
          <p style="color: var(--text-muted); font-size: 1rem; margin: 0; line-height: 1.6;">Comprehensive transit insurance is standard for all global industrial orders, ensuring absolute security for your technological assets.</p>
        </div>
      </div>
    </div>
  </div>
</div>
