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
  <div style="max-width: 600px; margin: 0 auto;">
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 40px;">
      <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="margin: 0 0 16px 0;"><?= __('track_order') ?></h1>
        <p style="color: var(--text-muted);"><?= __('track_order_text') ?></p>
      </div>

      <form action="/track" method="GET">
        <div class="form-group-modern" style="margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 12px; font-weight: 700;"><?= __('tracking_number') ?></label>
          <input type="text" name="tracking" required placeholder="STR..." value="<?= htmlspecialchars($trackingNumber ?? '') ?>" style="width: 100%; height: 60px; padding: 0 20px; border: 2px solid #f1f5f9; border-radius: 8px; font-weight: 600; outline: none; background: #f8fafc;">
        </div>
        <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 60px;"><?= __('track') ?></button>
      </form>
      
      <?php if ($trackingNumber && !$shipment): ?>
      <div style="margin-top: 24px; padding: 20px; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px; color: #991b1b;">
        <?= __('tracking_not_found') ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
  
  <?php if ($shipment): ?>
  <!-- Tracking Header -->
  <div style="max-width: 1200px; margin: 0 auto;">
    <header style="margin-bottom: 60px; border-bottom: 2px solid #f1f5f9; padding-bottom: 40px;" class="grid-2">
      <div>
        <div style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Order Shipment</div>
        <h1 style="margin: 0;"><?= htmlspecialchars($shipment['tracking_number']) ?></h1>
        <p style="color: var(--text-muted); margin-top: 12px;">Carrier: <strong><?= htmlspecialchars($shipment['carrier'] ?? 'Streicher Logistics') ?></strong></p>
      </div>
      <div style="text-align: right;">
        <span style="display: inline-block; padding: 12px 32px; background: var(--accent); color: white; border-radius: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
          <?= $statusBannerText ?>
        </span>
      </div>
    </header>
    
    <!-- Progress Stepper -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 40px; margin-bottom: 40px;">
      <div class="tracking-progress-modern" style="display: flex; justify-content: space-between; position: relative; flex-wrap: wrap; gap: 20px;">
        <?php 
        $steps = [['📦', 'Ordered'], ['🏭', 'Shipped'], ['🚚', 'Transit'], ['📍', 'Delivery'], ['✅', 'Delivered']];
        foreach ($steps as $i => $step): $idx = $i + 1; 
        ?>
        <div style="text-align: center; flex: 1; min-width: 80px;">
          <div style="width: 50px; height: 50px; background: <?= $progressStep >= $idx ? 'var(--accent)' : '#f1f5f9' ?>; color: <?= $progressStep >= $idx ? 'white' : 'var(--text-muted)' ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 12px;">
            <?= $step[0] ?>
          </div>
          <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: <?= $progressStep >= $idx ? 'var(--primary)' : 'var(--text-muted)' ?>;">
            <?= $step[1] ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Shipment Details -->
    <div class="grid-3" style="margin-bottom: 40px;">
      <div style="padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
        <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Origin</div>
        <div style="font-weight: 800; color: var(--primary); font-size: 1.25rem;">
          <?= htmlspecialchars($shipment['origin_city'] ?? 'Regensburg') ?>, <?= htmlspecialchars($shipment['origin_country'] ?? 'DE') ?>
        </div>
      </div>
      <div style="padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
        <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Destination</div>
        <div style="font-weight: 800; color: var(--primary); font-size: 1.25rem;">
          <?= htmlspecialchars($shipment['destination'] ?? 'Order Address') ?>
        </div>
      </div>
      <div style="padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
        <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Estimated Delivery</div>
        <div style="font-weight: 800; color: var(--primary); font-size: 1.25rem;">
          <?= !empty($shipment['estimated_delivery']) ? date('M j, Y', strtotime($shipment['estimated_delivery'])) : 'Pending' ?>
        </div>
      </div>
    </div>
    
    <!-- Event History -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
      <div style="padding: 24px 40px; background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
        <h3 style="margin: 0;"><?= __('tracking_history') ?></h3>
      </div>
      <div style="padding: 40px;">
        <?php foreach ($events as $index => $event): 
          $timestamp = strtotime($event['timestamp'] ?? 'now');
          $isLatest = $index === 0;
        ?>
        <div style="display: flex; gap: 32px; margin-bottom: 32px;">
          <div style="width: 100px; text-align: right; flex-shrink: 0;">
            <div style="font-weight: 800; color: var(--primary); font-size: 0.9rem;"><?= date('M j', $timestamp) ?></div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><?= date('H:i', $timestamp) ?></div>
          </div>
          <div style="width: 12px; height: 12px; background: <?= $isLatest ? 'var(--accent)' : '#f1f5f9' ?>; border-radius: 50%; margin-top: 6px;"></div>
          <div>
            <div style="font-weight: 800; color: var(--primary); margin-bottom: 4px;"><?= htmlspecialchars($event['description'] ?? 'Status Update') ?></div>
            <div style="font-size: 0.9rem; color: var(--text-muted);">📍 <?= htmlspecialchars($event['location'] ?? '') ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Help Section -->
    <div style="margin-top: 40px; background: var(--primary); border-radius: var(--radius-lg); padding: 40px; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
      <div>
        <h3 style="color: white; margin-bottom: 8px;">Need Assistance?</h3>
        <p style="opacity: 0.8;">Contact our support team for help with your shipment.</p>
      </div>
      <a href="/contact" class="btn-modern btn-accent">Contact Support</a>
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
