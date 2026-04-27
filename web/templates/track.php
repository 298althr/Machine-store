<?php
$tracking = $_GET['tracking'] ?? '';
$lang = $_SESSION['lang'] ?? 'de';
?>
<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 32px; font-size: 0.95rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent); font-weight: 700;"><?= __('home') ?></a> 
    <span style="margin: 0 12px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary); font-weight: 900; letter-spacing: -0.5px;"><?= $lang === 'de' ? 'Sendungsverfolgung' : 'Shipment Tracking Protocol' ?></span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 400px; display: flex; align-items: center; padding: 100px; background: #0f172a; box-shadow: var(--shadow-2xl);">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.7) 100%), url('https://images.unsplash.com/photo-1566633806327-68e152aaf26d?q=80&w=2070&auto=format&fit=crop') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 800px;">
      <div style="display: inline-block; padding: 8px 24px; background: var(--accent); border-radius: 8px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 4px; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);">
        Logistics Intelligence Matrix
      </div>
      <h1 style="font-size: 5rem; font-family: 'Outfit', sans-serif; line-height: 0.95; margin: 0 0 32px 0; font-weight: 900; letter-spacing: -3px;"><?= $lang === 'de' ? 'Globale Logistik-<br>Überwachung.' : 'Global Logistics<br>Monitoring.' ?></h1>
      <p style="font-size: 1.5rem; color: rgba(255,255,255,0.7); max-width: 650px; line-height: 1.6; font-weight: 500; letter-spacing: -0.5px;">
        <?= $lang === 'de' ? 'Echtzeit-Tracking Ihrer interdisziplinären Anlagen und Komponenten.' : 'Real-time monitoring of your interdisciplinary plant and machinery systems deployment.' ?>
      </p>
    </div>
  </section>

  <div style="max-width: 1200px; margin: 0 auto 150px;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 80px;">
      <div style="padding: 60px 80px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; left: -20px; font-size: 10rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif; pointer-events: none;">TRACK</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1px; position: relative; z-index: 1;">Initialize Tracking Registry</h3>
        <p style="color: var(--accent); font-size: 0.85rem; margin: 12px 0 0 0; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; position: relative; z-index: 1;">Secure Interdisciplinary Protocol</p>
      </div>
      <div style="padding: 80px;">
        <form id="track-form">
          <div class="form-group-modern" style="margin-bottom: 48px;">
            <label style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;"><?= __('tracking_number') ?> *</label>
            <input type="text" id="tracking" name="tracking" required placeholder="e.g. STR-TRA-12345678" value="<?= htmlspecialchars($tracking) ?>" style="width: 100%; height: 84px; padding: 0 32px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 900; font-size: 1.5rem; outline: none; transition: all 0.4s; background: #f8fafc; font-family: 'Outfit', sans-serif; color: var(--primary); text-transform: uppercase;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 96px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 4px; border-radius: 8px; box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2);">Initialize Tracking Protocol Matrix</button>
        </form>
      </div>
    </div>

    <div id="result" style="margin-top: 80px;"></div>
  </div>
</div>

<script>
const form = document.getElementById('track-form');
const resultDiv = document.getElementById('result');
const lang = '<?= $lang ?>';

const translations = {
  loading: { en: 'Synchronizing with global logistics intelligence API...', de: 'Synchronisierung mit der globalen Logistik-Intelligenz-Schnittstelle...' },
  error: { en: 'Technical Protocol Error: Unable to retrieve registry data.', de: 'Technischer Protokollfehler: Registerdaten konnten nicht abgerufen werden.' },
  not_found: { en: 'Asset Identifier not located in global registry.', de: 'Anlagen-Kennung im globalen Register nicht gefunden.' },
  shipment: { en: 'Asset Registry ID', de: 'Anlagen-Register-ID' },
  carrier: { en: 'Authorized Logistics Partner', de: 'Autorisierter Logistikpartner' },
  status: { en: 'Deployment Status', de: 'Einsatzstatus' },
  origin: { en: 'Interdisciplinary Origin', de: 'Interdisziplinärer Ursprung' },
  destination: { en: 'Institutional Destination', de: 'Institutioneller Bestimmungsort' },
  shipped_date: { en: 'Registry Dispatch Date', de: 'Register-Versanddatum' },
  tracking_history: { en: 'Real-Time Deployment History Matrix', de: 'Echtzeit-Einsatzverlauf-Matrix' },
  time: { en: 'Registry Timestamp', de: 'Register-Zeitstempel' },
  location: { en: 'Geospatial Coordinates', de: 'Geospatial-Koordinaten' },
  description: { en: 'Event Protocol Specification', de: 'Ereignis-Protokoll-Spezifikation' }
};

function t(key) {
  return translations[key] ? (translations[key][lang] || translations[key]['en']) : key;
}

async function fetchTracking(trackingNumber) {
  resultDiv.innerHTML = '<div style="text-align: center; padding: 120px;"><div style="width: 100px; height: 100px; border: 8px solid #f1f5f9; border-top-color: var(--accent); border-radius: 50%; animation: spin 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite; margin: 0 auto 48px;"></div><p style="font-size: 1.25rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 2px; font-family: \'Outfit\', sans-serif;">' + t('loading') + '</p></div>';
  
  try {
    const res = await fetch('/api/track', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ tracking_number: trackingNumber })
    });
    
    if (!res.ok) {
      resultDiv.innerHTML = '<div style="background: white; border: 3px solid var(--accent); color: var(--primary); padding: 60px; border-radius: var(--radius-lg); font-weight: 900; text-align: center; font-size: 1.5rem; font-family: \'Outfit\', sans-serif; box-shadow: var(--shadow-2xl);">' + t('error') + '</div>';
      return;
    }
    
    const data = await res.json();
    if (!data || !data.tracking_number) {
      resultDiv.innerHTML = '<div style="background: white; border-radius: var(--radius-lg); padding: 100px; text-align: center; box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; position: relative;"><div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: \'Outfit\', sans-serif;">?</div><div style="font-size: 6rem; margin-bottom: 40px; filter: grayscale(1); opacity: 0.3;">🔍</div><h3 style="font-family: \'Outfit\', sans-serif; font-size: 2.25rem; color: var(--primary); margin-bottom: 16px; font-weight: 900; letter-spacing: -1px;">' + t('not_found') + '</h3><p style="color: var(--text-muted); font-size: 1.25rem; font-weight: 500; max-width: 600px; margin: 0 auto;">Please verify the tracking protocol identifier provided by your institutional procurement representative.</p></div>';
      return;
    }
    
    let html = '<div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 60px; animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);">';
    html += '<div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;"><h3 style="font-family: \'Outfit\', sans-serif; font-size: 1.75rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">' + t('shipment') + ': <span style="color: var(--accent);">' + data.tracking_number + '</span></h3><button onclick="window.print()" style="padding: 12px 24px; background: white; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 900; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor=\'var(--accent)\'; this.style.color=\'var(--accent)\'" onmouseout="this.style.borderColor=\'#f1f5f9\'; this.style.color=\'var(--text-muted)\'">🖨️ Export PDF</button></div>';
    html += '<div style="padding: 60px;">';
    html += '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 48px;">';
    html += '<div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;"><div style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; letter-spacing: 2px;">' + t('carrier') + '</div><div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: \'Outfit\', sans-serif;">' + (data.carrier || 'Streicher Logistics') + '</div></div>';
    html += '<div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;"><div style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; letter-spacing: 2px;">' + t('status') + '</div><div><span style="display: inline-block; padding: 12px 24px; background: var(--accent); color: white; border-radius: 8px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2);">' + (data.status || 'Active') + '</span></div></div>';
    if (data.origin_city) html += '<div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;"><div style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; letter-spacing: 2px;">' + t('origin') + '</div><div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: \'Outfit\', sans-serif;">' + data.origin_city + (data.origin_country ? ', <span style="color: var(--accent);">' + data.origin_country + '</span>' : '') + '</div></div>';
    if (data.destination_city) html += '<div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;"><div style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; letter-spacing: 2px;">' + t('destination') + '</div><div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: \'Outfit\', sans-serif;">' + data.destination_city + (data.destination_country ? ', <span style="color: var(--accent);">' + data.destination_country + '</span>' : '') + '</div></div>';
    if (data.shipped_at) html += '<div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;"><div style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; letter-spacing: 2px;">' + t('shipped_date') + '</div><div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: \'Outfit\', sans-serif;">' + new Date(data.shipped_at).toLocaleDateString(lang === 'de' ? 'de-DE' : 'en-US', { month: 'long', day: 'numeric', year: 'numeric' }) + '</div></div>';
    html += '</div></div></div>';
    
    if (Array.isArray(data.events) && data.events.length) {
      html += '<div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; animation: slideIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);">';
      html += '<div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 24px;"><div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">🕒</div><div><h3 style="font-family: \'Outfit\', sans-serif; font-size: 1.75rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">' + t('tracking_history') + '</h3><p style="font-size: 0.8rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 4px;">Real-Time Protocol Matrix</p></div></div>';
      html += '<div style="padding: 0; overflow-x: auto;"><table style="width: 100%; border-collapse: collapse;">';
      html += '<thead><tr style="background: white; border-bottom: 3px solid #f1f5f9;"><th style="padding: 32px 48px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">' + t('time') + '</th><th style="padding: 32px 48px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">' + t('location') + '</th><th style="padding: 32px 48px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">' + t('status') + '</th><th style="padding: 32px 48px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); font-weight: 900; letter-spacing: 2px;">' + t('description') + '</th></tr></thead><tbody>';
      data.events.forEach((ev, idx) => {
        const timestamp = ev.timestamp ? new Date(ev.timestamp) : (ev.ts ? new Date(ev.ts) : null);
        const formattedDate = timestamp ? timestamp.toLocaleString(lang === 'de' ? 'de-DE' : 'en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
        const isLatest = idx === 0;
        
        html += '<tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.3s; background: ' + (isLatest ? '#f8fafc' : 'white') + ';" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'' + (isLatest ? '#f8fafc' : 'white') + '\'">';
        html += '<td style="padding: 32px 48px; font-weight: 900; color: var(--primary); font-size: 1rem; font-family: \'Outfit\', sans-serif;">' + formattedDate + '</td>';
        html += '<td style="padding: 32px 48px; font-weight: 700; color: var(--primary); font-size: 1rem;">' + (ev.location || '-') + '</td>';
        html += '<td style="padding: 32px 48px;"><span style="padding: 8px 16px; background: ' + (isLatest ? 'var(--accent)' : '#e2e8f0') + '; color: ' + (isLatest ? 'white' : 'var(--primary)') + '; border-radius: 6px; font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; box-shadow: ' + (isLatest ? '0 5px 10px rgba(220, 38, 38, 0.2)' : 'none') + ';">' + (ev.status || ev.status_label || ev.status || 'Update') + '</span></td>';
        html += '<td style="padding: 32px 48px; color: var(--text-muted); font-size: 1rem; font-weight: 500; line-height: 1.5;">' + (ev.description || '-') + '</td></tr>';
      });
      html += '</tbody></table></div></div>';
    }
    
    resultDiv.innerHTML = html;
  } catch (err) {
    console.error('Tracking fetch error:', err);
    resultDiv.innerHTML = '<div style="background: white; border: 3px solid var(--accent); color: var(--primary); padding: 60px; border-radius: var(--radius-lg); font-weight: 900; text-align: center; font-size: 1.5rem; font-family: \'Outfit\', sans-serif; box-shadow: var(--shadow-2xl);">' + t('error') + '</div>';
  }
}

form.addEventListener('submit', function (e) {
  e.preventDefault();
  const tracking = document.getElementById('tracking').value.trim();
  if (tracking) {
    fetchTracking(tracking);
  }
});

<?php if ($tracking): ?>
fetchTracking('<?= addslashes($tracking) ?>');
<?php endif; ?>
</script>

<style>
@keyframes spin {
  to { transform: rotate(360deg); }
}
@keyframes slideIn {
  from { transform: translateY(50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
