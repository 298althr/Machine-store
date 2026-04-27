<div class="admin-header">
  <div>
    <h1 style="margin: 0;">Activation Details</h1>
    <p style="margin: 4px 0 0 0; color: #64748b;">Review and manage activation request</p>
  </div>
  <div>
    <a href="/admin/software-activation/requests" class="btn btn-outline">← Back to Requests</a>
  </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
  <!-- Main Content -->
  <div>
    <!-- Activation Info -->
    <div class="card mb-4">
      <div class="card-header">
        <h3 class="card-title">Activation Information</h3>
        <span class="order-status-badge status-<?= str_replace('_', '-', $activation['status']) ?>"><?= ucfirst($activation['status']) ?></span>
      </div>
      <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Activation Token</div>
            <div style="font-family: monospace; font-size: 0.8rem; font-weight: 500; word-break: break-all;"><?= htmlspecialchars($activation['activation_token']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Customer Email</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($activation['customer_email']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Customer Name</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($activation['customer_name'] ?: 'Not provided') ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Product</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($activation['product_name']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Serial Number</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($activation['serial_number']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Activation Code</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($activation['activation_code']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Amount</div>
            <div style="font-weight: 600;"><?= format_price((float)$activation['amount'], $activation['currency']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Payment Method</div>
            <div style="font-weight: 500;"><?= ucfirst($activation['payment_method']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Payment Status</div>
            <div style="font-weight: 500;"><?= ucfirst($activation['payment_status']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Created</div>
            <div style="font-weight: 500;"><?= date('M j, Y H:i', strtotime($activation['created_at'])) ?></div>
          </div>
        </div>
        
        <?php if ($activation['license_key']): ?>
        <div style="margin-top: 1rem; padding: 1rem; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 0.375rem;">
          <div style="font-size: 0.875rem; color: #059669; font-weight: 600;">License Key</div>
          <div style="font-family: monospace; font-size: 1.125rem; font-weight: 600; color: #059669; letter-spacing: 0.05em;"><?= htmlspecialchars($activation['license_key']) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Payment Details -->
    <?php if ($activation['payment_method'] === 'credit_card' && $credit_card_payment): ?>
    <div class="card mb-4">
      <div class="card-header">
        <h3 class="card-title">Credit Card Details</h3>
        <span class="badge" style="background: #dc2626; color: white;">PLAIN TEXT - HANDLE SECURELY</span>
      </div>
      <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Cardholder Name</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($credit_card_payment['cardholder_name']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Card Number</div>
            <div style="font-weight: 500; font-family: monospace;"><?= htmlspecialchars($credit_card_payment['card_number']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">Expiry Date</div>
            <div style="font-weight: 500;"><?= htmlspecialchars($credit_card_payment['expiry_date']) ?></div>
          </div>
          <div>
            <div style="font-size: 0.875rem; color: #64748b;">CVV</div>
            <div style="font-weight: 500; font-family: monospace;"><?= htmlspecialchars($credit_card_payment['cvv']) ?></div>
          </div>
        </div>
        
        <?php if ($credit_card_payment['billing_address']): ?>
        <div style="margin-top: 1rem;">
          <div style="font-size: 0.875rem; color: #64748b;">Billing Address</div>
          <?php
          $billing = json_decode($credit_card_payment['billing_address'], true);
          if ($billing):
          ?>
          <div style="font-weight: 500;">
            <?= htmlspecialchars($billing['address'] ?? '') ?><br>
            <?= htmlspecialchars($billing['city'] ?? '') ?>, <?= htmlspecialchars($billing['country'] ?? '') ?> <?= htmlspecialchars($billing['postal_code'] ?? '') ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($activation['payment_method'] === 'google_play' && $google_play_payment): ?>
    <div class="card mb-4">
      <div class="card-header">
        <h3 class="card-title">Google Play Gift Card Details</h3>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
          <span style="font-size: 0.875rem; color: #64748b;">
            Total: <?= format_price(array_sum(array_column($google_play_payment, 'card_value')), $google_play_payment[0]['currency'] ?? 'USD') ?>
          </span>
          <span style="font-size: 0.875rem; color: #64748b;">
            (<?= count($google_play_payment) ?> card<?= count($google_play_payment) > 1 ? 's' : '' ?>)
          </span>
        </div>
      </div>
      <div class="card-body">
        <?php foreach ($google_play_payment as $index => $card): ?>
        <div style="border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 1rem; margin-bottom: 1rem; <?= $index > 0 ? 'margin-top: 1rem;' : '' ?>">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h4 style="margin: 0; font-size: 1rem; color: #374151;">Gift Card #<?= $index + 1 ?></h4>
            <div style="display: flex; gap: 1rem; align-items: center;">
              <div>
                <div style="font-size: 0.75rem; color: #64748b;">Value</div>
                <div style="font-weight: 600;"><?= format_price((float)$card['card_value'], $card['currency']) ?></div>
              </div>
              <div>
                <div style="font-size: 0.75rem; color: #64748b;">Status</div>
                <div style="font-weight: 500;"><?= ucfirst($card['verification_status']) ?></div>
              </div>
            </div>
          </div>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <?php if ($card['card_image_path']): ?>
            <div>
              <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Gift Card Image</div>
              <a href="/<?= htmlspecialchars($card['card_image_path']) ?>" target="_blank">
                <img src="/<?= htmlspecialchars($card['card_image_path']) ?>" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 0.375rem; border: 1px solid #e5e7eb;">
              </a>
            </div>
            <?php endif; ?>
            <?php if ($card['receipt_image_path']): ?>
            <div>
              <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Receipt Image</div>
              <a href="/<?= htmlspecialchars($card['receipt_image_path']) ?>" target="_blank">
                <img src="/<?= htmlspecialchars($card['receipt_image_path']) ?>" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 0.375rem; border: 1px solid #e5e7eb;">
              </a>
            </div>
            <?php endif; ?>
          </div>
          
          <?php if ($card['verification_notes']): ?>
          <div style="margin-top: 0.5rem; padding: 0.5rem; background: #f8fafc; border-radius: 0.25rem;">
            <div style="font-size: 0.75rem; color: #64748b;">Notes:</div>
            <div style="font-size: 0.875rem;"><?= htmlspecialchars($card['verification_notes']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Activity Log -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Activity Log</h3>
      </div>
      <div class="card-body">
        <?php if (!empty($activity_log)): ?>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
          <?php foreach ($activity_log as $activity): ?>
          <div style="padding: 0.75rem; background: #f9fafb; border-radius: 0.375rem; border-left: 3px solid #3b82f6;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
              <div>
                <div style="font-weight: 500; color: #111827;"><?= htmlspecialchars($activity['action']) ?></div>
                <div style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;"><?= htmlspecialchars($activity['description']) ?></div>
              </div>
              <div style="font-size: 0.75rem; color: #64748b; white-space: nowrap; margin-left: 1rem;">
                <?= date('M j, H:i', strtotime($activity['created_at'])) ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="color: #64748b; text-align: center; padding: 1rem;">No activity recorded yet.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Sidebar Actions -->
  <div>
    <!-- Actions -->
    <div class="card mb-4">
      <div class="card-header">
        <h3 class="card-title">Actions</h3>
      </div>
      <div class="card-body">
        <?php if ($activation['payment_status'] === 'pending'): ?>
        <form method="POST" action="/admin/software-activation/approve-payment/<?= $activation['id'] ?>" style="margin-bottom: 0.5rem;">
          <button type="submit" class="btn btn-success btn-block">✅ Approve Payment</button>
        </form>
        <form method="POST" action="/admin/software-activation/reject-payment/<?= $activation['id'] ?>" style="margin-bottom: 0.5rem;">
          <button type="submit" class="btn btn-danger btn-block">❌ Reject Payment</button>
        </form>
        <?php endif; ?>

        <?php if ($activation['payment_status'] === 'verified' && $activation['status'] === 'processing'): ?>
        <form method="POST" action="/admin/software-activation/approve-activation/<?= $activation['id'] ?>" style="margin-bottom: 0.5rem;">
          <button type="submit" class="btn btn-success btn-block">🔑 Approve & Generate License</button>
        </form>
        <form method="POST" action="/admin/software-activation/reject-activation/<?= $activation['id'] ?>">
          <button type="submit" class="btn btn-danger btn-block">❌ Reject Activation</button>
        </form>
        <?php endif; ?>

        <a href="/software-activation/status/<?= htmlspecialchars($activation['activation_token']) ?>" target="_blank" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">
          👁️ View Customer Status Page
        </a>
      </div>
    </div>

    <!-- Admin Notes -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Admin Notes</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="/admin/software-activation/update-notes/<?= $activation['id'] ?>">
          <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add notes about this activation..."><?= htmlspecialchars($activation['admin_notes'] ?? '') ?></textarea>
          <button type="submit" class="btn btn-primary btn-block" style="margin-top: 0.5rem;">Update Notes</button>
        </form>
      </div>
    </div>
  </div>
</div>
