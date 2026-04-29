<?php
$billing = json_decode($order['billing_address'] ?? '{}', true) ?: [];
$shipping = json_decode($order['shipping_address'] ?? '{}', true) ?: [];
?>

<header class="admin-header">
  <div>
    <div class="flex items-center gap-16 mb-8">
      <a href="/admin/orders" class="text-muted text-sm font-bold uppercase tracking-wider hover-text-accent">← Back to Registry</a>
    </div>
    <h1 class="mb-0">Registry ID: <?= htmlspecialchars($order['order_number']) ?></h1>
  </div>
  <div>
    <?php 
      $variant = 'info';
      if ($order['status'] === 'delivered') $variant = 'success';
      if ($order['status'] === 'payment_uploaded') $variant = 'warning';
      if ($order['status'] === 'payment_declined') $variant = 'error';
      render_component('badge', [
        'label' => get_status_label($order['status']),
        'variant' => $variant,
        'class' => 'text-lg px-24 py-8'
      ]); 
    ?>
  </div>
</header>

<!-- Action Banners Based on Status -->
<?php if ($order['status'] === 'payment_uploaded'): ?>
<div class="alert alert-warning mb-40 flex-between p-32 border-radius-lg">
  <div>
    <div class="alert-title font-black text-xs uppercase tracking-widest mb-4">Action Required: Payment Verification</div>
    <p class="mb-0 font-medium">An institutional settlement protocol has been uploaded. Authoritative validation is pending.</p>
  </div>
  <div class="flex gap-12">
    <?php render_component('button', [
      'variant' => 'outline',
      'label' => 'Decline Protocol',
      'icon' => 'x-circle',
      'attr' => 'onclick="document.getElementById(\'decline-modal\').classList.add(\'active\')"'
    ]); ?>
    <form action="/admin/orders/<?= $order['id'] ?>/confirm-payment" method="POST">
      <?php render_component('button', [
        'type' => 'submit',
        'variant' => 'accent',
        'label' => 'Confirm Settlement',
        'icon' => 'check-circle'
      ]); ?>
    </form>
  </div>
</div>

<!-- Decline Payment Modal -->
<div id="decline-modal" class="modal-overlay-modern">
  <div class="card-modern max-w-500 w-full p-40">
    <div class="flex-between mb-24">
      <h3 class="mb-0">Decline Settlement</h3>
      <button type="button" class="close-modal" onclick="document.getElementById('decline-modal').classList.remove('active')">&times;</button>
    </div>
    <p class="text-muted mb-32">Provide the technical justification for declining this transaction protocol.</p>
    <form action="/admin/orders/<?= $order['id'] ?>/decline-payment" method="POST" class="grid gap-24">
      <div class="form-group-modern">
        <label class="label-modern mb-8">Justification Code *</label>
        <select name="decline_reason" class="input-modern w-full" required>
          <option value="">Select justification...</option>
          <option value="invalid_receipt">Invalid or unreadable receipt specification</option>
          <option value="amount_mismatch">Settlement value mismatch</option>
          <option value="payment_not_received">Registry sync failure (Funds not received)</option>
          <option value="duplicate_submission">Duplicate protocol submission</option>
          <option value="fraudulent">Authentication failure (Suspected fraud)</option>
          <option value="other">Other (Specify in remarks)</option>
        </select>
      </div>
      <div class="form-group-modern">
        <label class="label-modern mb-8">Technical Remarks</label>
        <textarea name="decline_notes" class="input-modern w-full h-100 py-12" placeholder="Detailed audit trail notes..."></textarea>
      </div>
      <div class="flex flex-end gap-12 pt-12">
        <?php render_component('button', [
          'variant' => 'outline',
          'label' => 'Cancel',
          'attr' => 'onclick="document.getElementById(\'decline-modal\').classList.remove(\'active\')"'
        ]); ?>
        <?php render_component('button', [
          'type' => 'submit',
          'variant' => 'accent',
          'label' => 'Decline Protocol'
        ]); ?>
      </div>
    </form>
  </div>
</div>
<?php elseif ($order['status'] === 'payment_pending_upload'): ?>
<div class="alert alert-warning mb-40 p-32 border-radius-lg">
  <div class="flex items-center gap-16">
    <i data-lucide="clock" class="text-warning" size="24"></i>
    <div>
      <div class="alert-title font-black text-xs uppercase tracking-widest mb-4">Awaiting Verification Matrix</div>
      <p class="mb-0 font-medium">Customer has claimed settlement. Interdisciplinary registry is awaiting protocol upload.</p>
    </div>
  </div>
</div>
<?php elseif ($order['status'] === 'payment_confirmed'): ?>
<div class="alert alert-info mb-40 flex-between p-32 border-radius-lg">
  <div>
    <div class="alert-title font-black text-xs uppercase tracking-widest mb-4">Authorized for Dispatch</div>
    <p class="mb-0 font-medium">Settlement fully authenticated. Ready to send to Gorfos for shipping.</p>
    <?php if (!empty($order['sent_to_gorfos_at'])): ?>
    <p class="mb-0 text-xs text-success mt-4"><i data-lucide="check" style="width: 12px; height: 12px;"></i> Sent to Gorfos on <?= date('F j, Y g:i A', strtotime($order['sent_to_gorfos_at'])) ?></p>
    <?php endif; ?>
  </div>
  <div class="flex gap-12">
    <?php if (empty($order['sent_to_gorfos_at'])): ?>
    <form action="/admin/orders/<?= $order['id'] ?>/send-to-gorfos" method="POST" onsubmit="return confirm('Send this order to Gorfos.com for shipping? They will be notified immediately via webhook.');">
      <?php render_component('button', [
        'type' => 'submit',
        'variant' => 'accent',
        'label' => 'Send to Gorfos',
        'icon' => 'truck'
      ]); ?>
    </form>
    <?php endif; ?>
    <a href="#ship" class="btn btn-outline">Manual Ship</a>
  </div>
</div>
<?php elseif ($order['status'] === 'payment_declined'): ?>
<div class="alert alert-error mb-40 flex-between p-32 border-radius-lg">
  <div>
    <div class="alert-title font-black text-xs uppercase tracking-widest mb-4">Settlement Declined</div>
    <p class="mb-4 font-bold text-accent">Reason: <?= htmlspecialchars($order['decline_reason'] ?? 'No justification provided') ?></p>
    <?php if (!empty($order['payment_declined_at'])): ?>
    <p class="mb-0 text-xs opacity-70">Audit Timestamp: <?= date('F j, Y g:i A', strtotime($order['payment_declined_at'])) ?></p>
    <?php endif; ?>
  </div>
  <form action="/admin/orders/<?= $order['id'] ?>/revert-to-awaiting" method="POST">
    <?php render_component('button', [
      'type' => 'submit',
      'variant' => 'outline',
      'label' => 'Revert to Awaiting',
      'icon' => 'rotate-ccw'
    ]); ?>
  </form>
</div>
<?php endif; ?>

<div class="grid grid-sidebar items-start">
  <div class="grid gap-32">
    <!-- Order Items -->
    <div class="card-modern no-padding overflow-hidden">
      <div class="px-32 py-24 bg-light border-bottom">
        <h3 class="mb-0 text-lg">Registry Item Assets</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Asset Specification</th>
              <th>Registry SKU</th>
              <th>Unit Value</th>
              <th>Quantity</th>
              <th class="text-right">Total Settlement</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
              <td class="font-bold color-primary"><?= htmlspecialchars($item['product_name'] ?? $item['sku']) ?></td>
              <td class="text-xs font-bold text-muted tracking-wider uppercase"><?= htmlspecialchars($item['sku']) ?></td>
              <td><?= format_price((float)$item['unit_price']) ?></td>
              <td class="font-bold"><?= (int)($item['quantity'] ?? $item['qty'] ?? 0) ?></td>
              <td class="text-right font-black color-primary"><?= format_price((float)($item['total_price'] ?? $item['total'] ?? 0)) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="bg-light">
              <td colspan="4" class="text-right font-bold text-muted uppercase tracking-widest p-24">Final Operational Settlement:</td>
              <td class="text-right font-black text-2xl color-primary p-24"><?= format_price((float)$order['total']) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    
    <!-- Payment Uploads -->
    <?php if (!empty($paymentUploads)): ?>
    <div class="card-modern no-padding overflow-hidden">
      <div class="px-32 py-24 bg-light border-bottom">
        <h3 class="mb-0 text-lg">Settlement Protocols</h3>
      </div>
      <div class="p-32 grid gap-16">
        <?php foreach ($paymentUploads as $upload): ?>
        <div class="flex items-center gap-24 p-24 card-modern bg-light border-subtle">
          <div class="protocol-icon bg-white p-12 border-radius-md border-subtle shadow-sm">
            <i data-lucide="<?= strpos($upload['mime_type'], 'pdf') !== false ? 'file-text' : 'image' ?>" class="text-accent" size="32"></i>
          </div>
          <div class="flex-1">
            <div class="font-bold color-primary mb-4"><?= htmlspecialchars($upload['original_filename']) ?></div>
            <div class="text-xs text-muted font-bold tracking-wider uppercase">
              <?= date('M j, Y g:i A', strtotime($upload['created_at'])) ?>
              <span class="opacity-30 mx-8">•</span>
              <?= number_format($upload['file_size'] / 1024, 1) ?> KB
            </div>
            <?php if (!empty($upload['notes'])): ?>
            <div class="mt-12 p-12 bg-white border-radius-sm border-subtle text-sm text-muted italic">
              "<?= htmlspecialchars($upload['notes']) ?>"
            </div>
            <?php endif; ?>
          </div>
          <div class="flex items-center gap-20">
            <?php render_component('badge', [
              'label' => ucfirst($upload['status']),
              'variant' => $upload['status'] === 'verified' ? 'success' : 'warning'
            ]); ?>
            <?php render_component('button', [
              'href' => '/' . htmlspecialchars($upload['file_path']),
              'variant' => 'outline',
              'size' => 'sm',
              'label' => 'Audit File',
              'attr' => 'target="_blank"'
            ]); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    
    <!-- Shipments -->
    <?php if (!empty($shipments)): ?>
    <div class="card-modern no-padding overflow-hidden mb-32">
      <div class="px-32 py-24 bg-light border-bottom">
        <h3 class="mb-0 text-lg">Industrial Logistics Registry</h3>
      </div>
      <div class="p-32 grid gap-32">
        <?php foreach ($shipments as $shipment): 
          $events = json_decode($shipment['events'] ?? '[]', true) ?: [];
        ?>
        <div class="shipment-record card-modern bg-light border-subtle p-24">
          <div class="flex-between mb-24">
            <div>
              <div class="font-black color-primary text-xl"><?= htmlspecialchars($shipment['tracking_number']) ?></div>
              <div class="text-sm font-bold text-muted uppercase tracking-wider"><?= htmlspecialchars($shipment['carrier']) ?></div>
            </div>
            <?php render_component('badge', [
              'label' => get_status_label($shipment['status']),
              'variant' => $shipment['status'] === 'delivered' ? 'success' : 'info'
            ]); ?>
          </div>
          
          <!-- Add Tracking Update -->
          <form action="/admin/shipments/<?= $shipment['id'] ?>/update-tracking" method="POST" class="grid gap-16 bg-white p-24 border-radius-md border-subtle mb-24">
            <h4 class="text-sm font-black uppercase tracking-widest mb-8">Add Tracking Milestone</h4>
            <div class="grid grid-2">
              <select name="status" class="input-modern">
                <option value="picked_up">Picked Up from Warehouse</option>
                <option value="in_transit">In Transit</option>
                <option value="arrived_hub">Arrived at Hub</option>
                <option value="departed_hub">Departed Hub</option>
                <option value="customs_hold">Customs Hold</option>
                <option value="customs_cleared">Customs Cleared</option>
                <option value="arrived_destination">Arrived at Destination Country</option>
                <option value="out_for_delivery">Out for Delivery</option>
                <option value="delivery_attempted">Delivery Attempted</option>
                <option value="delivered">Delivered</option>
              </select>
              <input type="text" name="location" class="input-modern" placeholder="Location (e.g., Munich, DE)">
            </div>
            <input type="text" name="description" class="input-modern" placeholder="Status description">
            <?php render_component('button', [
              'type' => 'submit',
              'variant' => 'outline',
              'size' => 'sm',
              'label' => 'Append Milestone',
              'icon' => 'plus'
            ]); ?>
          </form>
          
          <!-- Customs Hold Controls -->
          <?php if ($shipment['status'] !== 'delivered'): ?>
          <div class="customs-control-card p-24 border-radius-md mb-24 <?= $shipment['status'] === 'customs_hold' ? 'bg-error-light border-error' : 'bg-warning-light border-warning' ?>" style="border: 2px dashed;">
            <h4 class="text-sm font-black uppercase tracking-widest mb-16 flex items-center gap-8">
              <i data-lucide="shield-alert"></i> Customs Protocol
            </h4>
            <?php if ($shipment['status'] === 'customs_hold'): ?>
            <div class="bg-white p-16 border-radius-sm mb-16 border-subtle shadow-sm">
              <div class="font-black text-error uppercase text-xs mb-8">Currently on Customs Hold</div>
              <?php if (!empty($shipment['customs_memo'])): ?>
              <p class="text-sm text-muted mb-8 italic">"<?= htmlspecialchars($shipment['customs_memo']) ?>"</p>
              <?php endif; ?>
              <?php if (!empty($shipment['customs_duty_amount'])): ?>
              <div class="font-bold text-primary">Duty Value: <?= number_format((float)$shipment['customs_duty_amount'], 2) ?> <?= htmlspecialchars($shipment['customs_duty_currency'] ?? 'EUR') ?></div>
              <?php endif; ?>
            </div>
            <form action="/admin/shipments/<?= $shipment['id'] ?>/clear-customs" method="POST">
              <input type="hidden" name="location" value="Customs Facility">
              <?php render_component('button', [
                'type' => 'submit',
                'variant' => 'accent',
                'size' => 'sm',
                'label' => 'Authorize Customs Release',
                'icon' => 'unlock'
              ]); ?>
            </form>
            <?php else: ?>
            <form action="/admin/shipments/<?= $shipment['id'] ?>/customs-hold" method="POST" class="grid gap-12">
              <input type="text" name="customs_memo" class="input-modern" placeholder="Justification for hold...">
              <div class="grid grid-2">
                <input type="number" name="duty_amount" class="input-modern" placeholder="Duty value" step="0.01">
                <select name="duty_currency" class="input-modern">
                  <option value="EUR">EUR</option>
                  <option value="USD">USD</option>
                  <option value="GBP">GBP</option>
                </select>
              </div>
              <?php render_component('button', [
                'type' => 'submit',
                'variant' => 'outline',
                'size' => 'sm',
                'label' => 'Initialize Customs Hold',
                'icon' => 'lock',
                'class' => 'text-warning'
              ]); ?>
            </form>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          
          <!-- Recent Events -->
          <?php if (!empty($events)): ?>
          <div class="tracking-history-mini pt-24 border-top">
            <h4 class="text-xs font-black uppercase tracking-widest mb-16 text-muted">Latest Milestones</h4>
            <div class="grid gap-16">
              <?php foreach (array_slice($events, 0, 5) as $event): ?>
              <div class="flex gap-16 text-sm">
                <div class="text-muted font-bold whitespace-nowrap w-100">
                  <?= date('M j, g:i A', strtotime($event['timestamp'] ?? $event['ts'] ?? 'now')) ?>
                </div>
                <div>
                  <div class="font-bold color-primary"><?= htmlspecialchars($event['description'] ?? $event['status'] ?? '') ?></div>
                  <div class="text-xs text-muted font-medium"><?= htmlspecialchars($event['location'] ?? '') ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    
    <!-- Tracking Communications -->
    <?php if (!empty($shipments)): ?>
    <?php foreach ($shipments as $shipment): 
      $trackingNumber = $shipment['tracking_number'];
      $commStmt = $pdo->prepare("SELECT * FROM tracking_communications WHERE order_id = ? ORDER BY created_at ASC");
      $commStmt->execute([$order['id']]);
      $communications = $commStmt->fetchAll();
    ?>
    <div class="card-modern no-padding overflow-hidden mb-32" id="communications-<?= $shipment['id'] ?>">
      <div class="px-32 py-24 bg-light border-bottom flex-between items-center">
        <h3 class="mb-0 text-lg flex items-center gap-12">
          <i data-lucide="message-square" class="text-accent"></i>
          Communication Matrix - <?= htmlspecialchars($trackingNumber) ?>
        </h3>
        <span class="badge badge-info"><?= count($communications) ?> Messages</span>
      </div>
      <div class="p-32">
        <div class="chat-registry-container bg-light border-radius-md p-24 mb-24 max-h-500 overflow-y-auto">
          <?php if (empty($communications)): ?>
          <div class="text-center py-40 opacity-30">
            <i data-lucide="message-circle-x" size="48" class="mb-16"></i>
            <p class="font-bold">No technical exchange recorded.</p>
          </div>
          <?php else: ?>
          <?php foreach ($communications as $comm): 
            $isSystem = $comm['sender_type'] === 'system';
            $isAdmin = $comm['sender_type'] === 'admin';
          ?>
          <div class="mb-24 flex flex-col <?= $isAdmin ? 'items-end' : 'items-start' ?>">
            <div class="flex items-center gap-8 mb-4 text-xs font-black uppercase tracking-widest text-muted">
              <span><?= $isAdmin ? '👨‍💼 Admin Protocol' : ($isSystem ? '🤖 System Automated' : '👤 External Partner') ?></span>
              <span class="opacity-30">•</span>
              <span><?= date('M j, g:i A', strtotime($comm['created_at'])) ?></span>
            </div>
            
            <div class="message-block p-16 border-radius-md max-w-80 shadow-sm border <?= $isAdmin ? 'bg-primary text-white border-primary' : ($isSystem ? 'bg-warning-light border-warning text-warning-dark' : 'bg-white border-subtle text-primary') ?>">
              <?php if (!empty($comm['document_path'])): ?>
              <div class="mb-12 flex items-center gap-12 p-8 border-radius-sm <?= $isAdmin ? 'bg-white/10 border-white/20' : 'bg-light border-subtle' ?>" style="border: 1px solid;">
                <i data-lucide="file-text" size="20"></i>
                <div class="flex-1 overflow-hidden">
                  <div class="text-xs font-bold truncate"><?= htmlspecialchars($comm['document_name']) ?></div>
                  <a href="/<?= htmlspecialchars(ltrim($comm['document_path'], '/')) ?>" target="_blank" class="text-xs font-black uppercase underline tracking-tighter opacity-80">Audit Spec</a>
                </div>
              </div>
              <?php endif; ?>
              
              <div class="text-sm font-medium leading-relaxed">
                <?= nl2br(htmlspecialchars($comm['message'] ?? '')) ?>
              </div>
              
              <div class="flex flex-end gap-8 mt-12 opacity-50 hover-opacity-100 transition">
                <button onclick="editMessage(<?= $comm['id'] ?>)" class="text-xs font-black uppercase tracking-widest hover-text-accent">Edit</button>
                <form action="/admin/communications/<?= $comm['id'] ?>/delete" method="POST" onsubmit="return confirm('Authorize deletion of registry entry?');">
                  <button type="submit" class="text-xs font-black uppercase tracking-widest hover-text-error">Wipe</button>
                </form>
              </div>
            </div>

            <div id="message-edit-<?= $comm['id'] ?>" class="hidden mt-16 w-full max-w-80 bg-white p-24 border-radius-md border-subtle shadow-lg">
              <form action="/admin/communications/<?= $comm['id'] ?>/update" method="POST" class="grid gap-16">
                <textarea name="message" class="input-modern h-100 py-12"><?= htmlspecialchars($comm['message'] ?? '') ?></textarea>
                <div class="flex flex-between items-center">
                  <div class="flex gap-12">
                    <label class="flex items-center gap-4 text-xs font-bold">
                      <input type="radio" name="sender_type" value="admin" <?= $isAdmin ? 'checked' : '' ?>> Admin
                    </label>
                    <label class="flex items-center gap-4 text-xs font-bold">
                      <input type="radio" name="sender_type" value="customer" <?= $comm['sender_type'] === 'customer' ? 'checked' : '' ?>> Customer
                    </label>
                    <label class="flex items-center gap-4 text-xs font-bold">
                      <input type="radio" name="sender_type" value="system" <?= $isSystem ? 'checked' : '' ?>> System
                    </label>
                  </div>
                  <input type="datetime-local" name="created_at" class="input-modern h-32 px-8 text-xs" value="<?= date('Y-m-d\TH:i', strtotime($comm['created_at'])) ?>">
                </div>
                <div class="flex flex-end gap-8">
                  <?php render_component('button', [
                    'variant' => 'outline',
                    'size' => 'sm',
                    'label' => 'Abort',
                    'attr' => 'onclick="cancelEdit('.$comm['id'].')"'
                  ]); ?>
                  <?php render_component('button', [
                    'type' => 'submit',
                    'variant' => 'accent',
                    'size' => 'sm',
                    'label' => 'Overwrite'
                  ]); ?>
                </div>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        <!-- Admin Reply Form -->
        <form action="/admin/shipments/<?= $shipment['id'] ?>/send-message" method="POST" enctype="multipart/form-data" class="bg-light border-radius-md p-24 border-subtle shadow-inner">
          <h4 class="text-sm font-black uppercase tracking-widest mb-16 flex items-center gap-8">
            <i data-lucide="reply"></i> Dispatch Reply to Partner
          </h4>
          <textarea name="message" class="input-modern w-full h-100 py-12 mb-16" placeholder="Technical communication narrative..."></textarea>
          <div class="flex-between">
            <div class="flex items-center gap-12">
              <label class="attachment-trigger card-modern bg-white px-16 py-8 border-radius-sm border-subtle shadow-sm flex items-center gap-8 cursor-pointer hover-bg-subtle transition">
                <i data-lucide="paperclip" size="16"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Attach Spec</span>
                <input type="file" name="document" class="hidden" accept=".pdf,.png,.jpg,.jpeg" onchange="this.nextElementSibling.textContent = this.files[0] ? '✓ ' + this.files[0].name : ''">
                <span class="text-xs font-black text-accent ml-4 truncate max-w-150"></span>
              </label>
            </div>
            <?php render_component('button', [
              'type' => 'submit',
              'variant' => 'accent',
              'label' => 'Initialize Dispatch',
              'icon' => 'send'
            ]); ?>
          </div>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Create Shipment Form -->
    <?php if ($order['status'] === 'payment_confirmed' && empty($shipments)): ?>
    <div class="card-modern no-padding overflow-hidden mb-32" id="ship">
      <div class="px-32 py-24 bg-primary color-white flex-between items-center">
        <h3 class="mb-0 text-lg color-white flex items-center gap-12">
          <i data-lucide="truck"></i> Initialize Logistics Protocol
        </h3>
        <span class="text-xs font-black text-accent uppercase tracking-widest">Authorized Operations</span>
      </div>
      <div class="p-40">
        <form action="/admin/orders/<?= $order['id'] ?>/create-shipment" method="POST" class="grid gap-32">
          <div class="grid grid-2">
            <div class="form-group-modern">
              <label class="label-modern mb-8">Authorized Carrier</label>
              <select name="carrier" class="input-modern">
                <option value="Streicher Logistics" selected>🚛 Streicher Logistics</option>
              </select>
            </div>
            <?php render_component('form_field', [
              'label' => 'Registry ID (Tracking)',
              'name' => 'tracking_number',
              'placeholder' => 'Leave blank for auto-generation'
            ]); ?>
          </div>
          <div class="grid grid-2">
            <div class="form-group-modern">
              <label class="label-modern mb-8">Transit Specification</label>
              <select name="shipping_method" class="input-modern">
                <option value="air_freight">✈️ Air Freight (International)</option>
                <option value="sea_freight">🚢 Sea Freight (Heavy Cargo)</option>
                <option value="local_van">🚐 Local Van Delivery (Germany)</option>
                <option value="motorcycle">🏍️ Motorcycle Courier (Express Local)</option>
              </select>
            </div>
            <div class="form-group-modern">
              <label class="label-modern mb-8">Containment Protocol</label>
              <select name="package_type" class="input-modern">
                <option value="crate">📦 Industrial Crate</option>
                <option value="carton">📦 Carton Box</option>
                <option value="pallet">🏗️ Pallet</option>
                <option value="container">🚛 Container</option>
              </select>
            </div>
          </div>
          <?php render_component('form_field', [
            'label' => 'Destination Registry',
            'name' => 'destination',
            'value' => ($shipping['city'] ?? '') . ', ' . ($shipping['country'] ?? '')
          ]); ?>
          <?php render_component('button', [
            'type' => 'submit',
            'variant' => 'accent',
            'label' => 'Initialize Logistics & Set Shipped Status',
            'class' => 'h-72 text-lg uppercase tracking-wider',
            'icon' => 'package'
          ]); ?>
        </form>
      </div>
    </div>
    <?php endif; ?>
  </div>
  
  <!-- Sidebar -->
  <aside class="grid gap-32">
    <!-- Order Info -->
    <div class="card-modern no-padding overflow-hidden">
      <div class="px-24 py-16 bg-light border-bottom">
        <h4 class="mb-0 text-sm uppercase tracking-widest font-black">Registry Data</h4>
      </div>
      <div class="p-24 grid gap-20">
        <div>
          <div class="text-xs font-black text-muted uppercase tracking-widest mb-4">Registry ID</div>
          <div class="font-bold color-primary text-lg"><?= htmlspecialchars($order['order_number']) ?></div>
        </div>
        <div>
          <div class="text-xs font-black text-muted uppercase tracking-widest mb-4">Enrollment Date</div>
          <div class="font-bold color-primary"><?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></div>
        </div>
        <div>
          <div class="text-xs font-black text-muted uppercase tracking-widest mb-4">Settlement Method</div>
          <div class="font-bold color-primary">Bank Wire Transfer</div>
        </div>
        <?php if (!empty($order['payment_confirmed_at'])): ?>
        <div class="pt-16 border-top">
          <div class="text-xs font-black text-success uppercase tracking-widest mb-4">Settlement Validated</div>
          <div class="font-bold color-primary"><?= date('F j, Y g:i A', strtotime($order['payment_confirmed_at'])) ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($order['shipped_at'])): ?>
        <div class="pt-16 border-top">
          <div class="text-xs font-black text-info uppercase tracking-widest mb-4">Logistics Initialized</div>
          <div class="font-bold color-primary"><?= date('F j, Y g:i A', strtotime($order['shipped_at'])) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Partner Info -->
    <div class="card-modern no-padding overflow-hidden">
      <div class="px-24 py-16 bg-light border-bottom">
        <h4 class="mb-0 text-sm uppercase tracking-widest font-black">Partner Protocol</h4>
      </div>
      <div class="p-24 grid gap-20">
        <div>
          <div class="font-black color-primary text-lg mb-2 truncate"><?= htmlspecialchars($billing['company'] ?? 'N/A') ?></div>
          <div class="text-sm font-medium text-muted"><?= htmlspecialchars($billing['name'] ?? '') ?></div>
        </div>
        <div class="pt-16 border-top">
          <div class="text-xs font-black text-muted uppercase tracking-widest mb-4">Communication Line</div>
          <a href="mailto:<?= htmlspecialchars($billing['email'] ?? '') ?>" class="font-bold text-accent underline truncate block"><?= htmlspecialchars($billing['email'] ?? 'N/A') ?></a>
          <div class="mt-4 font-bold color-primary text-sm"><?= htmlspecialchars($billing['phone'] ?? 'N/A') ?></div>
        </div>
      </div>
    </div>
    
    <!-- Dispatch Address -->
    <div class="card-modern no-padding overflow-hidden">
      <div class="px-24 py-16 bg-light border-bottom">
        <h4 class="mb-0 text-sm uppercase tracking-widest font-black">Logistics Destination</h4>
      </div>
      <div class="p-24">
        <div class="text-sm font-bold color-primary leading-loose">
          <div class="font-black uppercase tracking-wider mb-8"><?= htmlspecialchars($shipping['company'] ?? $billing['company'] ?? '') ?></div>
          <?= htmlspecialchars($shipping['name'] ?? $billing['name'] ?? '') ?><br>
          <?= htmlspecialchars($shipping['address'] ?? $billing['address'] ?? '') ?><br>
          <span class="bg-light px-8 py-2 border-radius-sm"><?= htmlspecialchars(($shipping['zip'] ?? $billing['zip'] ?? '') . ' ' . ($shipping['city'] ?? $billing['city'] ?? '')) ?></span><br>
          <div class="flex items-center gap-8 mt-8">
            <i data-lucide="globe" size="14"></i>
            <?= htmlspecialchars($shipping['country'] ?? $billing['country'] ?? '') ?>
          </div>
        </div>
      </div>
    </div>
  </aside>
</div>

<script>
function editMessage(id) {
  const el = document.getElementById('message-edit-' + id);
  el.classList.toggle('hidden');
}

function cancelEdit(id) {
  document.getElementById('message-edit-' + id).classList.add('hidden');
}
</script>
