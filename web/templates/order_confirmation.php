<?php
$billingAddress = json_decode($order['billing_address'] ?? '{}', true) ?: [];
?>

<div class="container-modern section-padding" style="padding-top: 60px;">
  <div style="max-width: 900px; margin: 0 auto; text-align: center;">
    <div style="font-size: 6rem; margin-bottom: 32px; filter: drop-shadow(0 10px 20px rgba(16, 185, 129, 0.2));">🛡️</div>
    <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; color: var(--primary); margin: 0 0 16px 0; font-weight: 800; letter-spacing: -1px;">Procurement Protocol Initialized</h1>
    <p style="font-size: 1.3rem; color: var(--text-muted); margin-bottom: 48px; max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.6;">
      Thank you for your institutional order. We have received your interdisciplinary payment verification and will begin formal authentication shortly.
    </p>
    
    <!-- Institutional Order Summary -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 60px;">
      <div style="padding: 48px;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; text-align: center;">
          <div style="border-right: 1px solid #f1f5f9;">
            <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Order ID</div>
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;">#<?= htmlspecialchars($order['order_number']) ?></div>
          </div>
          <div style="border-right: 1px solid #f1f5f9;">
            <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Investment</div>
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= format_price((float)$order['total']) ?></div>
          </div>
          <div>
            <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Status</div>
            <div>
              <span style="display: inline-block; padding: 6px 16px; background: #dcfce7; color: #15803d; border-radius: 4px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">PAYMENT VERIFYING</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Roadmap Protocol -->
    <div style="background: #f8fafc; border-radius: var(--radius-lg); padding: 60px; margin-bottom: 60px; border: 1px solid rgba(0,0,0,0.02);">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin-bottom: 48px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">Verification Roadmap</h3>
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; text-align: center;">
        <div>
          <div style="width: 56px; height: 56px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; font-weight: 900; box-shadow: var(--shadow-sm); border: 4px solid white;">✓</div>
          <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; margin-bottom: 4px;">Receipt Received</div>
          <div style="font-size: 0.8rem; color: #10b981; font-weight: 900; text-transform: uppercase;">Completed</div>
        </div>
        <div>
          <div style="width: 56px; height: 56px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; font-weight: 900; box-shadow: var(--shadow-sm); border: 4px solid white; animation: pulse 2s infinite;">⏳</div>
          <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; margin-bottom: 4px;">Institutional Review</div>
          <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700;">1-2 Business Days</div>
        </div>
        <div>
          <div style="width: 56px; height: 56px; background: white; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; font-weight: 900; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0;">📦</div>
          <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; margin-bottom: 4px;">Asset Preparation</div>
          <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700;">After Confirmation</div>
        </div>
        <div>
          <div style="width: 56px; height: 56px; background: white; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; font-weight: 900; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0;">🚚</div>
          <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; margin-bottom: 4px;">Global Dispatch</div>
          <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700;">With Tracking ID</div>
        </div>
      </div>
    </div>
    
    <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--accent); padding: 40px; text-align: left; display: flex; gap: 32px; align-items: flex-start; margin-bottom: 60px;">
      <div style="font-size: 2.5rem;">📧</div>
      <div>
        <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.2rem; color: var(--primary); margin-bottom: 8px; font-weight: 800;">Confirmation Protocol Sent</h4>
        <p style="color: var(--text-muted); font-size: 1.05rem; margin: 0; line-height: 1.6;">
          An institutional confirmation has been dispatched to <strong><?= htmlspecialchars($billingAddress['email'] ?? 'the representative email') ?></strong>. You will receive a second technical notification once the payment verification process is finalized.
        </p>
      </div>
    </div>
    
    <div style="display: flex; gap: 24px; justify-content: center; margin-top: 60px;">
      <a href="/order/<?= $order['id'] ?>" class="btn-modern btn-accent" style="padding: 20px 48px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">View Order Status</a>
      <a href="/catalog" class="btn-modern" style="padding: 20px 48px; border: 2px solid var(--primary); color: var(--primary); font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Procurement Catalog</a>
    </div>
  </div>
</div>

<style>
@keyframes pulse {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
  70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(220, 38, 38, 0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
}
</style>
