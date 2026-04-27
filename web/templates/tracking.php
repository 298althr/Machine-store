<?php
// Determine progress step
$progressStep = 1;
$isCustomsHold = false;
if ($shipment) {
    switch ($shipment['status']) {
        case 'shipped': $progressStep = 2; break;
        case 'in_transit': $progressStep = 3; break;
        case 'customs_hold': 
            $progressStep = 3; 
            $isCustomsHold = true;
            break;
        case 'out_for_delivery': $progressStep = 4; break;
        case 'delivered': $progressStep = 5; break;
        default: $progressStep = 2;
    }
}

$statusBannerClass = 'in-transit';
$statusBannerText = 'In Transit to Destination';
if ($shipment) {
    switch ($shipment['status']) {
        case 'shipped':
            $statusBannerClass = 'in-transit';
            $statusBannerText = 'Shipment Picked Up';
            break;
        case 'in_transit':
            $statusBannerClass = 'in-transit';
            $statusBannerText = 'In Transit to Destination';
            break;
        case 'customs_hold':
            $statusBannerClass = 'customs-hold';
            $statusBannerText = '⚠️ On Hold: Customs Clearance Required';
            break;
        case 'out_for_delivery':
            $statusBannerClass = 'out-for-delivery';
            $statusBannerText = 'Out for Delivery';
            break;
        case 'delivered':
            $statusBannerClass = 'delivered';
            $statusBannerText = 'Delivered';
            break;
    }
}

// Get shipping method display
$shippingMethods = [
    'air_freight' => '✈️ Air Freight',
    'sea_freight' => '🚢 Sea Freight',
    'local_van' => '🚐 Local Van Delivery',
    'motorcycle' => '🏍️ Motorcycle Courier',
];
$shippingMethodDisplay = $shippingMethods[$shipment['shipping_method'] ?? 'air_freight'] ?? '✈️ Air Freight';

// Get package type display
$packageTypes = [
    'crate' => '📦 Industrial Crate',
    'carton' => '📦 Carton Box',
    'pallet' => '🏗️ Pallet',
    'container' => '🚛 Container',
];
$packageTypeDisplay = $packageTypes[$shipment['package_type'] ?? 'crate'] ?? '📦 Industrial Crate';
?>

<div class="container-modern section-padding" style="padding-top: 40px;">
  <!-- Search Form -->
  <?php if (!$shipment): ?>
  <div style="max-width: 800px; margin: 0 auto;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; padding: 80px;">
      <div style="text-align: center; margin-bottom: 64px;">
        <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 24px;">Logistics Matrix</div>
        <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); font-weight: 900; line-height: 1; margin: 0 0 16px 0; letter-spacing: -2px;">Track Your Asset</h1>
        <p style="color: var(--text-muted); font-size: 1.25rem; font-weight: 500; line-height: 1.6;">Initialize real-time monitoring of your interdisciplinary procurement logistics.</p>
      </div>

      <form action="/track" method="GET">
        <div class="form-group-modern" style="margin-bottom: 40px;">
          <label style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 16px;">Tracking Registry Number</label>
          <input type="text" name="tracking" required placeholder="STR20241205ABCD..." value="<?= htmlspecialchars($trackingNumber ?? '') ?>" style="width: 100%; height: 72px; padding: 0 32px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 600; font-size: 1.25rem; outline: none; transition: all 0.4s; background: #f8fafc; font-family: 'Outfit', sans-serif;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
        </div>
        <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 84px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; border-radius: 8px;">Initialize Matrix Search</button>
      </form>
      
      <?php if ($trackingNumber && !$shipment): ?>
      <div style="margin-top: 40px; padding: 32px; background: rgba(220, 38, 38, 0.05); border: 2px solid var(--accent); border-radius: 12px; display: flex; gap: 24px; align-items: center;">
        <div style="font-size: 2.5rem;">⚠️</div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--primary); margin: 0 0 4px 0; font-weight: 900;">Shipment Not Found</h4>
          <p style="color: var(--text-muted); font-size: 1.05rem; margin: 0; font-weight: 500;">Registry #<?= htmlspecialchars($trackingNumber) ?> could not be located in our logistics intelligence matrix. Please verify the credentials.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
  
  <?php if ($shipment): ?>
  <!-- Tracking Header -->
  <div style="max-width: 1200px; margin: 0 auto 120px;">
    <header style="margin-bottom: 80px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end;">
      <div>
        <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 16px;">Logistics Matrix Intelligence</div>
        <div style="display: flex; align-items: center; gap: 24px;">
          <h1 style="font-size: 4rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -2px; line-height: 1;">ID: <?= htmlspecialchars($shipment['tracking_number']) ?></h1>
          <button type="button" onclick="copyToClipboard('<?= htmlspecialchars($shipment['tracking_number']) ?>', this)" style="background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 900; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s;" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--primary)'">📋 Copy</button>
        </div>
        <p style="color: var(--text-muted); font-size: 1.25rem; margin-top: 24px; font-weight: 500;">
          Deployment Carrier: <span style="font-weight: 900; color: var(--primary);"><?= htmlspecialchars($shipment['carrier'] ?? 'Streicher Logistics') ?></span>
        </p>
      </div>
      <div style="text-align: right;">
        <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Deployment Status</div>
        <span style="display: inline-block; padding: 16px 40px; background: var(--accent); color: white; border-radius: 8px; font-size: 1.25rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2);">
          <?= $statusBannerText ?>
        </span>
      </div>
    </header>
    
    <!-- Progress Intelligence Matrix -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 60px;">
      <div style="padding: 80px 60px; border-bottom: 2px solid #f1f5f9;">
        <div class="tracking-progress-modern" style="display: flex; justify-content: space-between; position: relative; max-width: 1000px; margin: 0 auto;">
          <!-- Progress Line -->
          <div style="position: absolute; top: 32px; left: 0; width: 100%; height: 6px; background: #f1f5f9; z-index: 0; border-radius: 3px;"></div>
          <?php 
          $totalSteps = 5;
          $fillWidth = (($progressStep - 1) / ($totalSteps - 1)) * 100;
          ?>
          <div style="position: absolute; top: 32px; left: 0; width: <?= $fillWidth ?>%; height: 6px; background: var(--accent); z-index: 1; transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 3px;"></div>
          
          <?php 
          $steps = [['📦', 'Order Placed'], ['🏭', 'Shipped'], ['🚚', 'In Transit'], ['📍', 'Out for Delivery'], ['✅', 'Delivered']];
          foreach ($steps as $i => $step): $idx = $i + 1; 
          ?>
          <div style="position: relative; z-index: 2; text-align: center; width: 140px;">
            <div style="width: 70px; height: 70px; background: <?= $progressStep >= $idx ? 'var(--accent)' : 'white' ?>; color: <?= $progressStep >= $idx ? 'white' : 'var(--text-muted)' ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 24px; border: 4px solid <?= $progressStep >= $idx ? 'var(--accent)' : '#f1f5f9' ?>; box-shadow: <?= $progressStep >= $idx ? '0 10px 20px rgba(220, 38, 38, 0.2)' : 'var(--shadow-sm)' ?>; transition: all 0.4s; transform: <?= $progressStep == $idx ? 'scale(1.15)' : 'scale(1)' ?>;">
              <?= $step[0] ?>
            </div>
            <div style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: <?= $progressStep >= $idx ? 'var(--primary)' : 'var(--text-muted)' ?>;">
              <?= $step[1] ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- Shipment Intelligence Details -->
      <div style="padding: 60px;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;">
          <div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Origin Registry</div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.5rem; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
              <?= htmlspecialchars($shipment['origin_city'] ?? 'Regensburg') ?>, <span style="color: var(--accent);"><?= htmlspecialchars($shipment['origin_country'] ?? 'DE') ?></span>
            </div>
          </div>
          <div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Logistics Destination</div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.5rem; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
              <?php 
                if (!empty($shipment['destination'])) {
                  echo htmlspecialchars($shipment['destination']);
                } else {
                  $destCity = $shipment['destination_city'] ?? '';
                  $destCountry = $shipment['destination_country'] ?? '';
                  echo htmlspecialchars($destCity) . ($destCity && $destCountry ? ', ' : '') . '<span style="color: var(--accent);">' . htmlspecialchars($destCountry) . '</span>';
                }
              ?>
            </div>
          </div>
          <div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Estimated Arrival Matrix</div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.5rem; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
              <?php if (!empty($shipment['estimated_delivery'])): ?>
                <?= date('F j, Y', strtotime($shipment['estimated_delivery'])) ?>
              <?php else: ?>
                Calculating Matrix...
              <?php endif; ?>
            </div>
          </div>
          <div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Deployment Method</div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;"><?= $shippingMethodDisplay ?></div>
          </div>
          <div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Asset Container Specification</div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;"><?= $packageTypeDisplay ?></div>
          </div>
          <div style="padding: 32px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Authorized Carrier</div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif;">🚛 Streicher Logistics</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Customs Hold Protocol -->
    <?php if ($isCustomsHold): ?>
    <div style="background: white; border: 3px solid var(--accent); border-radius: var(--radius-lg); padding: 60px; margin-bottom: 60px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-xl); position: relative; overflow: hidden;">
      <div style="position: absolute; top: -50px; right: -50px; font-size: 20rem; opacity: 0.02; font-weight: 900; font-family: 'Outfit', sans-serif;">!</div>
      <div style="display: flex; gap: 40px; align-items: center; position: relative; z-index: 1;">
        <div style="font-size: 4rem; animation: pulse-red 2s infinite;">⚠️</div>
        <div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; color: var(--primary); margin: 0 0 8px 0; font-weight: 900; letter-spacing: -0.5px;">Customs Clearance Required</h4>
          <p style="color: var(--text-muted); font-size: 1.25rem; margin: 0; font-weight: 500;">Institutional documentation matrix must be verified to proceed with interdisciplinary logistics.</p>
        </div>
      </div>
      <button type="button" class="btn-modern btn-accent" style="padding: 24px 48px; font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px; position: relative; z-index: 1;" onclick="openCommunicationModal()">View Protocol Registry</button>
    </div>
    <?php endif; ?>
    
    <!-- Tracking Events Matrix -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
      <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 24px;">
        <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">🕒</div>
        <div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -0.5px;">Deployment History Matrix</h3>
          <p style="font-size: 0.8rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 0 0;">Real-Time Logistics Event Registry</p>
        </div>
      </div>
      
      <div style="padding: 60px;">
        <?php if (empty($events)): ?>
        <div style="text-align: center; padding: 100px; color: var(--text-muted);">
          <div style="font-size: 5rem; margin-bottom: 32px; opacity: 0.2;">📭</div>
          <p style="font-size: 1.5rem; font-weight: 900; font-family: 'Outfit', sans-serif;">Registry Empty</p>
          <p style="font-size: 1.1rem; font-weight: 500;">No tracking events have been initialized for this asset.</p>
        </div>
        <?php else: ?>
        <div style="position: relative;">
          <!-- Vertical Line -->
          <div style="position: absolute; left: 180px; top: 0; bottom: 0; width: 4px; background: #f1f5f9; z-index: 0; border-radius: 2px;"></div>
          
          <?php foreach ($events as $index => $event): 
            $timestamp = strtotime($event['timestamp'] ?? $event['ts'] ?? 'now');
            $isLatest = $index === 0;
          ?>
          <div style="display: flex; gap: 60px; margin-bottom: 60px; position: relative; z-index: 1;">
            <div style="width: 120px; text-align: right;">
              <div style="font-size: 1rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= date('M j, Y', $timestamp) ?></div>
              <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-top: 4px;"><?= date('g:i A', $timestamp) ?></div>
            </div>
            
            <div style="width: 24px; height: 24px; background: <?= $isLatest ? 'var(--accent)' : 'white' ?>; border: 4px solid <?= $isLatest ? 'white' : '#f1f5f9' ?>; border-radius: 50%; box-shadow: <?= $isLatest ? '0 0 20px rgba(220, 38, 38, 0.4)' : 'none' ?>; margin-top: 4px;"></div>
            
            <div style="flex: 1; background: <?= $isLatest ? '#f8fafc' : 'white' ?>; padding: 32px; border-radius: 12px; border: 2px solid <?= $isLatest ? 'var(--accent)' : '#f1f5f9' ?>; transition: all 0.3s;" class="hover-lift">
              <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; font-family: 'Outfit', sans-serif; margin-bottom: 8px; letter-spacing: -0.5px;">
                <?= htmlspecialchars($event['description'] ?? $event['status_label'] ?? $event['status'] ?? 'Update') ?>
              </div>
              <div style="font-size: 1rem; font-weight: 600; color: var(--text-muted); display: flex; align-items: center; gap: 12px;">
                <span style="color: var(--accent);">📍</span> <?= htmlspecialchars($event['location'] ?? $event['location_city'] ?? '') ?>
                <?php if (!empty($event['facility']) || !empty($event['location_facility'])): ?>
                  <span style="opacity: 0.3;">|</span> <?= htmlspecialchars($event['facility'] ?? $event['location_facility']) ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Support Matrix -->
    <div style="margin-top: 60px; background: var(--primary); border-radius: var(--radius-lg); padding: 80px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.4); position: relative; overflow: hidden;">
      <div style="position: absolute; top: -50px; left: -50px; font-size: 20rem; opacity: 0.03; font-weight: 900; font-family: 'Outfit', sans-serif;">HLP</div>
      <div style="position: relative; z-index: 1;">
        <h4 style="font-family: 'Outfit', sans-serif; font-size: 2.25rem; margin: 0 0 16px 0; font-weight: 900; color: white; letter-spacing: -1px;">Technical Support Required?</h4>
        <p style="margin: 0; opacity: 0.7; font-size: 1.35rem; font-weight: 500; letter-spacing: -0.2px;">Our interdisciplinary logistics engineering team is available 24/7.</p>
      </div>
      <div style="display: flex; gap: 32px; position: relative; z-index: 1;">
        <button type="button" class="btn-modern" style="padding: 24px 48px; background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.2); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;" onclick="openCommunicationModal()">Access Message Registry</button>
        <a href="/contact" class="btn-modern btn-accent" style="padding: 24px 48px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Global Support Matrix</a>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Communication Portal Matrix -->
<?php if ($shipment): ?>
<div id="communicationModal" class="modal-overlay-modern" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.95); z-index: 10000; backdrop-filter: blur(20px); align-items: center; justify-content: center; padding: 40px;">
  <div style="background: white; width: 100%; max-width: 1000px; height: 90vh; border-radius: var(--radius-lg); display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-2xl);">
    <div style="padding: 40px 60px; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
      <div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1px;">Shipment Protocol Registry</h3>
        <p style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin: 8px 0 0 0;">Secure Interdisciplinary Communication</p>
      </div>
      <button type="button" style="background: none; border: none; font-size: 2.5rem; color: var(--text-muted); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'" onclick="closeCommunicationModal()">&times;</button>
    </div>
    
    <div style="flex: 1; display: grid; grid-template-columns: 350px 1fr; overflow: hidden;">
      <!-- Sidebar: Registry Intel -->
      <div style="background: #f8fafc; border-right: 2px solid #f1f5f9; padding: 40px;">
        <div style="margin-bottom: 40px;">
          <div style="font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Asset Registry Number</div>
          <div style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 900; color: var(--primary); background: white; padding: 16px; border-radius: 8px; border: 1px solid #f1f5f9;"><?= htmlspecialchars($shipment['tracking_number']) ?></div>
        </div>
        
        <div style="padding: 24px; background: white; border-radius: 12px; border: 1px solid #f1f5f9; margin-bottom: 24px;">
          <div style="font-size: 0.7rem; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">Security Protocol</div>
          <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; gap: 12px; align-items: center; font-size: 0.9rem; font-weight: 600; color: var(--text-main);">
              <span style="color: #16a34a;">🔒</span> End-to-End Encrypted
            </div>
            <div style="display: flex; gap: 12px; align-items: center; font-size: 0.9rem; font-weight: 600; color: var(--text-main);">
              <span style="color: #16a34a;">🛡️</span> Authorized Personnel Only
            </div>
            <div style="display: flex; gap: 12px; align-items: center; font-size: 0.9rem; font-weight: 600; color: var(--text-main);">
              <span style="color: #16a34a;">📁</span> Verified Document Matrix
            </div>
          </div>
        </div>
      </div>
      
      <!-- Main: Message Registry -->
      <div style="display: flex; flex-direction: column; height: 100%;">
        <div style="flex: 1; padding: 60px; overflow-y: auto; background: white;" id="messagesContainer">
          <?php if (empty($communications)): ?>
          <div style="text-align: center; padding: 100px 40px; color: var(--text-muted);">
            <div style="font-size: 4rem; margin-bottom: 24px; opacity: 0.1;">💬</div>
            <p style="font-size: 1.25rem; font-weight: 900; font-family: 'Outfit', sans-serif;">Protocol Registry Initialized</p>
            <p style="font-size: 1rem; font-weight: 500;">Start a secure conversation with our logistics engineering team.</p>
          </div>
          <?php else: ?>
          <?php foreach ($communications as $comm): ?>
          <div style="margin-bottom: 40px; display: flex; flex-direction: column; align-items: <?= $comm['sender_type'] === 'customer' ? 'flex-end' : 'flex-start' ?>;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px; font-size: 0.8rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
              <span>
                <?php if ($comm['sender_type'] === 'admin'): ?>
                  🏢 STREICHER LOGISTICS
                <?php elseif ($comm['sender_type'] === 'system'): ?>
                  🤖 SYSTEM PROTOCOL
                <?php else: ?>
                  👤 AUTHORIZED REPRESENTATIVE
                <?php endif; ?>
              </span>
              <span style="opacity: 0.3;">•</span>
              <span><?= date('M j, Y g:i A', strtotime($comm['created_at'])) ?></span>
            </div>
            
            <div style="max-width: 80%; padding: 24px; border-radius: 12px; background: <?= $comm['sender_type'] === 'customer' ? 'var(--accent)' : '#f8fafc' ?>; color: <?= $comm['sender_type'] === 'customer' ? 'white' : 'var(--primary)' ?>; border: 2px solid <?= $comm['sender_type'] === 'customer' ? 'var(--accent)' : '#f1f5f9' ?>; box-shadow: var(--shadow-sm); font-weight: 500; line-height: 1.6;">
              <?php if (!empty($comm['document_path'])): ?>
              <div style="margin-bottom: 16px; background: rgba(255,255,255,0.1); padding: 16px; border-radius: 8px; display: flex; align-items: center; gap: 16px; border: 1px solid rgba(255,255,255,0.2);">
                <span style="font-size: 1.5rem;">📄</span>
                <div style="flex: 1;">
                  <div style="font-size: 0.9rem; font-weight: 900;"><?= htmlspecialchars($comm['document_name']) ?></div>
                  <a href="<?= htmlspecialchars($comm['document_path']) ?>" target="_blank" style="font-size: 0.8rem; font-weight: 900; color: inherit; text-decoration: underline; text-transform: uppercase; letter-spacing: 1px;">Initialize Download</a>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($comm['message'])): ?>
                <?= nl2br(htmlspecialchars($comm['message'])) ?>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        <!-- Input Protocol Matrix -->
        <div style="padding: 40px 60px; background: #f8fafc; border-top: 2px solid #f1f5f9;">
          <form action="/api/tracking/<?= htmlspecialchars($shipment['tracking_number']) ?>/message" method="POST" enctype="multipart/form-data" id="messageForm">
            <div style="background: white; border: 2px solid #f1f5f9; border-radius: 12px; padding: 8px; display: flex; flex-direction: column; gap: 16px; transition: all 0.3s;" id="inputMatrix">
              <textarea name="message" style="width: 100%; height: 120px; padding: 24px; border: none; font-size: 1.1rem; font-weight: 500; outline: none; resize: none; font-family: inherit;" placeholder="Initialize technical communication matrix..." onfocus="document.getElementById('inputMatrix').style.borderColor='var(--accent)'" onblur="document.getElementById('inputMatrix').style.borderColor='#f1f5f9'"></textarea>
              
              <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; background: #f8fafc; border-radius: 8px;">
                <div style="display: flex; gap: 24px; align-items: center;">
                  <label style="cursor: pointer; display: flex; align-items: center; gap: 12px; font-weight: 900; font-size: 0.85rem; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">
                    <span style="font-size: 1.25rem;">📎</span> ATTACH DOCUMENT MATRIX
                    <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" onchange="showFileName(this)">
                  </label>
                  <span id="fileName" style="font-size: 0.85rem; font-weight: 700; color: var(--accent); letter-spacing: 0.5px;"></span>
                </div>
                <button type="submit" class="btn-modern btn-accent" style="padding: 16px 32px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Initialize Dispatch</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes pulse-red {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}

.modal-overlay-modern {
  display: none;
}
.modal-overlay-modern.active {
  display: flex !important;
}
</style>

<script>
function openCommunicationModal() {
  document.getElementById('communicationModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeCommunicationModal() {
  document.getElementById('communicationModal').classList.remove('active');
  document.body.style.overflow = '';
}

function showFileName(input) {
  const fileName = input.files[0]?.name || '';
  document.getElementById('fileName').textContent = fileName ? '✓ ' + fileName : '';
}

// Handle message form submission
document.getElementById('messageForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const submitBtn = this.querySelector('button[type="submit"]');
  submitBtn.disabled = true;
  submitBtn.textContent = 'DISPATCHING...';
  
  const formData = new FormData(this);
  try {
    const res = await fetch(this.action, {
      method: 'POST',
      body: formData
    });
    if (res.ok) {
      window.location.reload();
    } else {
      alert('Technical Protocol Dispatch Failure. Please verify matrix connection.');
      submitBtn.disabled = false;
      submitBtn.textContent = 'INITIALIZE DISPATCH';
    }
  } catch (err) {
    console.error('Error:', err);
    alert('Technical Protocol Dispatch Failure. Please verify matrix connection.');
    submitBtn.disabled = false;
    submitBtn.textContent = 'INITIALIZE DISPATCH';
  }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeCommunicationModal();
  }
});

function copyToClipboard(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    const originalText = btn.textContent;
    btn.textContent = '✓ COPIED';
    btn.style.background = '#16a34a';
    setTimeout(() => {
      btn.textContent = originalText;
      btn.style.background = 'var(--primary)';
    }, 2000);
  });
}
</script>
<?php endif; ?>
