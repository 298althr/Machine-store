<div class="container-modern section-padding payment-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a> 
    <span class="separator">/</span> 
    <a href="/order/<?= $order['id'] ?>">Order #<?= htmlspecialchars($order['order_number']) ?></a>
    <span class="separator">/</span> 
    <span class="current">Payment Portal</span>
  </div>

  <section class="page-hero-modern bg-primary color-white mb-60">
    <div class="hero-bg-overlay financial"></div>
    <div class="hero-content-modern relative z-1 p-60">
      <div class="badge badge-accent mb-24">Payment Required</div>
      <h1 class="text-5xl mb-12"><?= $lang === 'de' ? 'Transaktion abschließen' : 'Complete Your Payment' ?></h1>
      <p class="text-xl opacity-90">
        <?= $lang === 'de' ? 'Sicheres Zahlungsportal für Auftrag' : 'Secure financial portal for Order' ?> #<?= htmlspecialchars($order['order_number']) ?>
      </p>
    </div>
  </section>
  
  <div class="grid grid-sidebar items-start mb-120">
    <!-- Main Content -->
    <main class="grid gap-40">
      <!-- Status Card -->
      <div class="status-summary-card card-modern flex items-center gap-32">
        <div class="status-icon-wrapper bg-light border-subtle">
          <i data-lucide="shield-check" class="text-accent"></i>
        </div>
        <div>
          <div class="detail-label-sm">Order Status</div>
          <div class="text-2xl font-black color-primary"><?= get_status_label($order['status']) ?></div>
          <p class="text-muted mt-12 mb-0 font-medium">
            <?php if ($order['status'] === 'awaiting_payment'): ?>
              Please transfer the funds to the account details below and upload your transaction receipt for verification.
            <?php elseif ($order['status'] === 'payment_uploaded'): ?>
              Payment receipt received. Our finance team is currently verifying the transaction.
            <?php else: ?>
              Payment verified. Your order is being processed for shipment.
            <?php endif; ?>
          </p>
        </div>
      </div>

      <!-- Banking Details -->
      <div class="banking-registry-card card-modern no-padding overflow-hidden">
        <div class="px-40 py-24 bg-light border-bottom">
          <h3 class="text-lg font-bold color-primary mb-0 uppercase tracking-wider">Banking Information</h3>
        </div>
        <div class="p-40">
          <div class="grid grid-2 gap-40 mb-48">
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">Banking Institution</div>
              <div class="font-bold color-primary text-lg"><?= htmlspecialchars($settings['bank_name'] ?? 'Deutsche Bank AG') ?></div>
            </div>
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">Account Beneficiary</div>
              <div class="font-bold color-primary text-lg"><?= htmlspecialchars($settings['account_holder'] ?? 'MAX STREICHER GmbH') ?></div>
            </div>
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">IBAN</div>
              <div class="font-bold color-primary text-xl font-mono tracking-wider"><?= htmlspecialchars($settings['iban'] ?? 'DE89 3704 0044 0532 0130 00') ?></div>
            </div>
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">BIC / SWIFT</div>
              <div class="font-bold color-primary text-xl font-mono tracking-wider"><?= htmlspecialchars($settings['bic'] ?? 'COBADEFFXXX') ?></div>
            </div>
          </div>
          
          <div class="reference-alert card-modern bg-warning-light border-dashed text-center">
            <div class="text-xs font-black text-warning-dark uppercase tracking-widest mb-12">Mandatory Payment Reference</div>
            <div class="text-4xl font-black text-warning-dark font-display mb-12"><?= htmlspecialchars($order['order_number']) ?></div>
            <p class="text-warning-dark font-bold mb-0 max-w-400 mx-auto">Include this exact number in your bank transfer description.</p>
          </div>
        </div>
      </div>

      <!-- Upload Section -->
      <div class="upload-portal-card card-modern p-48">
        <h3 class="text-2xl font-black color-primary mb-32">Upload Payment Receipt</h3>
        
        <form action="/order/<?= $order['id'] ?>/payment" method="POST" enctype="multipart/form-data" class="grid gap-32">
          <div id="dropZone" class="upload-drop-zone card-modern bg-light border-dashed py-60 text-center cursor-pointer" onclick="document.getElementById('receiptFile').click()">
            <i data-lucide="upload-cloud" class="mb-24 opacity-30" size="64"></i>
            <div class="font-bold color-primary text-lg mb-8">Select or Drop Receipt File</div>
            <div class="text-sm text-muted font-semibold">PDF, JPG, or PNG (Max 10MB)</div>
            <input type="file" name="receipt" id="receiptFile" accept=".jpg,.jpeg,.png,.gif,.pdf" class="hidden" required onchange="showPreview(this.files[0])">
          </div>
          
          <div id="filePreview" class="file-preview-card card-modern bg-light border-subtle p-24 flex items-center gap-20" style="display: none;">
            <i data-lucide="file-check" class="text-success" size="40"></i>
            <div class="flex-1">
              <div id="fileName" class="font-black color-primary"></div>
              <div id="fileSize" class="text-sm text-muted font-bold"></div>
            </div>
            <div class="text-success font-black text-xs uppercase tracking-widest">Ready</div>
          </div>
          
          <?php render_component('form_field', [
            'label' => 'Payment Remarks',
            'name' => 'notes',
            'type' => 'textarea',
            'placeholder' => 'Transaction ID, originating bank, dates...'
          ]); ?>
          
          <?php render_component('button', [
            'type' => 'submit',
            'variant' => 'accent',
            'label' => 'Submit Payment Proof',
            'class' => 'h-72 text-lg uppercase tracking-wider',
            'icon' => 'check-circle'
          ]); ?>
        </form>
      </div>
    </main>

    <!-- Sidebar Summary -->
    <aside class="sticky-top-120">
      <div class="card-modern p-40">
        <h3 class="text-xl font-black color-primary mb-32">Order Summary</h3>
        
        <div class="order-items-summary mb-40">
          <?php foreach ($items as $item): ?>
          <div class="flex-between py-16 border-bottom">
            <div>
              <div class="font-bold color-primary line-tight"><?= htmlspecialchars($item['product_name'] ?? $item['sku']) ?></div>
              <div class="text-xs text-muted font-bold mt-4">Qty: <?= (int)$item['quantity'] ?></div>
            </div>
            <div class="font-bold color-primary"><?= format_price((float)$item['total_price']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="pt-32 border-top-thick">
          <div class="flex-between items-baseline mb-8">
            <span class="text-xs font-black text-muted uppercase tracking-widest">Grand Total</span>
            <span class="text-3xl font-black color-primary font-display"><?= format_price((float)$order['total_amount']) ?></span>
          </div>
          <div class="text-right text-xs text-muted font-black uppercase tracking-widest">Includes 19% VAT</div>
        </div>

        <div class="security-badges mt-60 pt-40 border-top text-center opacity-40">
          <div class="flex-center gap-32">
            <i data-lucide="lock" size="32"></i>
            <i data-lucide="landmark" size="32"></i>
            <i data-lucide="gavel" size="32"></i>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>


<script>
function showPreview(file) {
  if (!file) return;
  document.getElementById('fileName').textContent = file.name;
  document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
  document.getElementById('filePreview').style.display = 'flex';
}
</script>
