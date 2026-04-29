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

<div class="container-modern section-padding tracking-page">
  <!-- Search Form -->
  <?php if (!$shipment): ?>
  <div class="tracking-search-container card-modern">
    <div class="text-center mb-40">
      <h1 class="mb-16"><?= __('track_order') ?></h1>
      <p class="text-muted"><?= __('track_order_text') ?></p>
    </div>

    <form action="/track" method="GET">
      <?php render_component('form_field', [
        'label' => __('tracking_number'),
        'name' => 'tracking',
        'required' => true,
        'placeholder' => 'STR...',
        'value' => $trackingNumber ?? '',
        'class' => 'mb-24'
      ]); ?>
      <?php render_component('button', [
        'type' => 'submit',
        'variant' => 'accent',
        'label' => __('track'),
        'class' => 'w-full'
      ]); ?>
    </form>
    
    <?php if ($trackingNumber && !$shipment): ?>
    <div class="alert alert-error mt-24">
      <i data-lucide="alert-circle"></i>
      <span><?= __('tracking_not_found') ?></span>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  
  <?php if ($shipment): ?>
  <!-- Tracking Header -->
  <div class="tracking-container-lg">
    <header class="tracking-header grid grid-2">
      <div>
        <div class="tracking-tag">Order Shipment</div>
        <h1 class="mb-0"><?= htmlspecialchars($shipment['tracking_number']) ?></h1>
        <p class="text-muted mt-12">Carrier: <strong><?= htmlspecialchars($shipment['carrier'] ?? 'Streicher Logistics') ?></strong></p>
      </div>
      <div class="text-right">
        <span class="status-badge-lg">
          <?= $statusBannerText ?>
        </span>
      </div>
    </header>
    
    <!-- Progress Stepper -->
    <div class="stepper-card card-modern mb-40">
      <div class="stepper-modern">
        <?php 
        $steps = [
          ['icon' => 'package', 'label' => 'Ordered'],
          ['icon' => 'factory', 'label' => 'Shipped'],
          ['icon' => 'truck', 'label' => 'Transit'],
          ['icon' => 'map-pin', 'label' => 'Delivery'],
          ['icon' => 'check-circle', 'label' => 'Delivered']
        ];
        foreach ($steps as $i => $step): $idx = $i + 1; 
          $isActive = $progressStep >= $idx;
        ?>
        <div class="step-item <?= $isActive ? 'active' : '' ?>">
          <div class="step-icon">
            <i data-lucide="<?= $step['icon'] ?>"></i>
          </div>
          <div class="step-label">
            <?= $step['label'] ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Shipment Details -->
    <div class="grid grid-3 mb-40">
      <div class="detail-card-modern">
        <div class="detail-label-sm">Origin</div>
        <div class="detail-value-lg">
          <?= htmlspecialchars($shipment['origin_city'] ?? 'Regensburg') ?>, <?= htmlspecialchars($shipment['origin_country'] ?? 'DE') ?>
        </div>
      </div>
      <div class="detail-card-modern">
        <div class="detail-label-sm">Destination</div>
        <div class="detail-value-lg">
          <?= htmlspecialchars($shipment['destination'] ?? 'Order Address') ?>
        </div>
      </div>
      <div class="detail-card-modern">
        <div class="detail-label-sm">Estimated Delivery</div>
        <div class="detail-value-lg">
          <?= !empty($shipment['estimated_delivery']) ? date('M j, Y', strtotime($shipment['estimated_delivery'])) : 'Pending' ?>
        </div>
      </div>
    </div>
    
    <!-- Event History -->
    <div class="history-card card-modern mb-40 no-padding overflow-hidden">
      <div class="card-header-modern bg-light px-40 py-24 border-bottom">
        <h3 class="mb-0"><?= __('tracking_history') ?></h3>
      </div>
      <div class="card-body-modern p-40">
        <?php foreach ($events as $index => $event): 
          $timestamp = strtotime($event['timestamp'] ?? 'now');
          $isLatest = $index === 0;
        ?>
        <div class="timeline-event <?= $isLatest ? 'latest' : '' ?>">
          <div class="event-time">
            <div class="time-main"><?= date('M j', $timestamp) ?></div>
            <div class="time-sub"><?= date('H:i', $timestamp) ?></div>
          </div>
          <div class="event-marker"></div>
          <div class="event-content">
            <div class="event-title"><?= htmlspecialchars($event['description'] ?? 'Status Update') ?></div>
            <div class="event-location"><i data-lucide="map-pin"></i> <?= htmlspecialchars($event['location'] ?? '') ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Help Section -->
    <div class="help-section-modern bg-primary border-radius-lg p-40 color-white flex-between flex-wrap gap-20">
      <div>
        <h3 class="color-white mb-8">Need Assistance?</h3>
        <p class="opacity-80">Contact our support team for help with your shipment.</p>
      </div>
      <?php render_component('button', [
        'href' => '/contact',
        'variant' => 'accent',
        'label' => 'Contact Support'
      ]); ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Communication Portal Modal -->
<?php if ($shipment): ?>
<div id="communicationModal" class="modal-overlay-modern">
  <div class="chat-modal-inner">
    <div class="chat-header">
      <div>
        <h3 class="mb-0">Shipment Support</h3>
        <p class="tracking-tag mb-0">Secure Communication Portal</p>
      </div>
      <button type="button" class="close-modal" onclick="closeCommunicationModal()">&times;</button>
    </div>
    
    <div class="chat-grid">
      <!-- Sidebar -->
      <aside class="chat-sidebar">
        <div class="mb-32">
          <div class="detail-label-sm">Tracking Number</div>
          <div class="detail-value-lg"><?= htmlspecialchars($shipment['tracking_number']) ?></div>
        </div>
        
        <div class="security-info card-modern p-20">
          <div class="detail-label-sm mb-16">Security Protocol</div>
          <ul class="security-list grid gap-12">
            <li><i data-lucide="lock" class="icon-success"></i> <span>Encrypted</span></li>
            <li><i data-lucide="shield-check" class="icon-success"></i> <span>Authorized</span></li>
            <li><i data-lucide="file-check" class="icon-success"></i> <span>Verified</span></li>
          </ul>
        </div>
      </aside>
      
      <!-- Main Chat -->
      <div class="chat-main flex flex-col overflow-hidden">
        <div class="chat-messages" id="messagesContainer">
          <?php if (empty($communications)): ?>
          <div class="text-center py-80 opacity-40">
            <i data-lucide="message-square" size="48" class="mb-16"></i>
            <p class="font-bold">No messages yet.</p>
            <p class="text-sm">Start a conversation with our logistics team.</p>
          </div>
          <?php else: ?>
          <?php foreach ($communications as $comm): 
            $isCustomer = $comm['sender_type'] === 'customer';
          ?>
          <div class="message-wrapper <?= $isCustomer ? 'customer' : 'admin' ?>">
            <div class="message-meta">
              <span><?= $isCustomer ? 'You' : htmlspecialchars($comm['sender_name'] ?? 'Streicher Logistics') ?></span>
              <span class="dot">•</span>
              <span><?= date('M j, g:i A', strtotime($comm['created_at'])) ?></span>
            </div>
            
            <div class="message-bubble <?= $isCustomer ? 'customer' : 'admin' ?>">
              <?php if (!empty($comm['document_path'])): ?>
              <div class="message-attachment card-modern mb-12">
                <i data-lucide="file-text"></i>
                <div class="attachment-info">
                  <div class="attachment-name"><?= htmlspecialchars($comm['document_name']) ?></div>
                  <a href="<?= htmlspecialchars($comm['document_path']) ?>" target="_blank" class="attachment-link">Download File</a>
                </div>
              </div>
              <?php endif; ?>
              
              <div class="message-text">
                <?= nl2br(htmlspecialchars($comm['message'])) ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        <!-- Input Area -->
        <div class="chat-input-area">
          <form action="/api/tracking/<?= htmlspecialchars($shipment['tracking_number']) ?>/message" method="POST" enctype="multipart/form-data" id="messageForm">
            <textarea name="message" class="message-textarea" placeholder="Type your message here..."></textarea>
            
            <div class="flex-between">
              <label class="attachment-trigger">
                <i data-lucide="paperclip"></i>
                <span>Attach File</span>
                <input type="file" name="document" class="hidden" onchange="showFileName(this)">
              </label>
              <span id="fileName" class="text-accent text-sm font-bold ml-12"></span>
              
              <?php render_component('button', [
                'type' => 'submit',
                'variant' => 'accent',
                'label' => 'Send Message',
                'icon' => 'send'
              ]); ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

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

document.getElementById('messageForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const submitBtn = this.querySelector('button[type="submit"]');
  submitBtn.disabled = true;
  
  const formData = new FormData(this);
  try {
    const res = await fetch(this.action, { method: 'POST', body: formData });
    if (res.ok) {
      window.location.reload();
    } else {
      alert('Failed to send message. Please try again.');
      submitBtn.disabled = false;
    }
  } catch (err) {
    alert('Network error. Please try again.');
    submitBtn.disabled = false;
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeCommunicationModal();
});
</script>
<?php endif; ?>
