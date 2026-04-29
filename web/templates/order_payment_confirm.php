<?php
$bankName = $settings['bank_name'] ?? 'Deutsche Bank AG';
$accountHolder = $settings['account_holder'] ?? 'MAX STREICHER GmbH';
$iban = $settings['iban'] ?? 'DE89 3704 0044 0532 0130 00';
$swift = $settings['bic'] ?? 'COBADEFFXXX';
$displayCurrency = $_SESSION['display_currency'] ?? 'EUR';
$currencySymbol = $displayCurrency === 'USD' ? '$' : '€';
?>

<div class="container-modern section-padding payment-confirm-page">
  <div class="breadcrumb">
    <a href="/"><?= __('home') ?></a>
    <span class="separator">/</span>
    <a href="/order/<?= $order['id'] ?>/invoice">Order #<?= htmlspecialchars($order['order_number']) ?></a>
    <span class="separator">/</span>
    <span class="current">Payment Confirmation</span>
  </div>

  <section class="page-hero-modern bg-primary color-white mb-60">
    <div class="hero-bg-overlay financial"></div>
    <div class="hero-content-modern relative z-1 p-60">
      <div class="badge badge-accent mb-24">Institutional Settlement</div>
      <h1 class="text-5xl mb-12">Confirm Payment</h1>
      <p class="text-xl opacity-90">
        Secure financial portal for Order #<?= htmlspecialchars($order['order_number']) ?>
      </p>
    </div>
  </section>

  <div class="grid grid-sidebar items-start mb-120">
    <main class="grid gap-40">
      <div class="status-summary-card card-modern flex items-center gap-32">
        <div class="status-icon-wrapper bg-light border-subtle">
          <i data-lucide="shield-check" class="text-accent"></i>
        </div>
        <div>
          <div class="detail-label-sm">Order Status</div>
          <div class="text-2xl font-black color-primary">Awaiting Payment</div>
          <p class="text-muted mt-12 mb-0 font-medium">
            Please review the institutional banking registry below. Once the wire transfer is complete, click the confirmation button to proceed to receipt upload.
          </p>
        </div>
      </div>

      <div class="banking-registry-card card-modern no-padding overflow-hidden">
        <div class="px-40 py-24 bg-light border-bottom">
          <h3 class="text-lg font-bold color-primary mb-0 uppercase tracking-wider">Banking Information</h3>
        </div>
        <div class="p-40">
          <div class="grid grid-2 gap-40 mb-48">
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">Banking Institution</div>
              <div class="font-bold color-primary text-lg"><?= htmlspecialchars($bankName) ?></div>
            </div>
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">Account Beneficiary</div>
              <div class="font-bold color-primary text-lg"><?= htmlspecialchars($accountHolder) ?></div>
            </div>
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">IBAN</div>
              <div class="font-bold color-primary text-xl font-mono tracking-wider"><?= htmlspecialchars($iban) ?></div>
            </div>
            <div class="bank-detail">
              <div class="detail-label-sm mb-8">BIC / SWIFT</div>
              <div class="font-bold color-primary text-xl font-mono tracking-wider"><?= htmlspecialchars($swift) ?></div>
            </div>
          </div>

          <div class="reference-alert card-modern bg-warning-light border-dashed text-center">
            <div class="text-xs font-black text-warning-dark uppercase tracking-widest mb-12">Mandatory Payment Reference</div>
            <div class="text-4xl font-black text-warning-dark font-display mb-12"><?= htmlspecialchars($order['invoice_number'] ?? $order['order_number']) ?></div>
            <p class="text-warning-dark font-bold mb-0 max-w-400 mx-auto">Include this exact identifier in your bank transfer description.</p>
          </div>
        </div>
      </div>

      <form action="/order/<?= $order['id'] ?>/payment-confirm" method="POST">
        <div class="card-modern p-48 text-center bg-white border-subtle shadow-xl">
          <div class="mb-24 flex justify-center">
            <div class="p-20 bg-light border-radius-full">
              <i data-lucide="landmark" size="48" class="text-accent"></i>
            </div>
          </div>
          <h3 class="text-2xl font-black color-primary mb-16">Have You Completed the Transfer?</h3>
          <p class="text-muted text-lg mb-40 max-w-500 mx-auto font-medium">
            Once the institutional wire transfer has been initiated from your banking facility, confirm below to unlock the receipt upload portal.
          </p>
          <?php render_component('button', [
            'type' => 'submit',
            'variant' => 'accent',
            'label' => 'I Have Made Payment',
            'class' => 'h-80 text-xl w-full uppercase tracking-wider',
            'icon' => 'arrow-right'
          ]); ?>
          <div class="mt-24">
            <a href="/order/<?= $order['id'] ?>/invoice" class="text-muted font-bold text-sm uppercase tracking-widest hover-text-accent">
              <i data-lucide="arrow-left" class="inline-block mr-4" size="14"></i> Back to Invoice
            </a>
          </div>
        </div>
      </form>
    </main>

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
            <div class="font-bold color-primary"><?= format_price((float)($item['total_price'] ?? $item['unit_price'] * $item['quantity']), $displayCurrency) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="pt-32 border-top-thick">
          <div class="flex-between items-baseline mb-8">
            <span class="text-xs font-black text-muted uppercase tracking-widest">Grand Total</span>
            <span class="text-3xl font-black color-primary font-display"><?= format_price((float)$order['total_amount'], $displayCurrency) ?></span>
          </div>
          <div class="text-right text-xs text-muted font-black uppercase tracking-widest">Includes 19% VAT</div>
        </div>
      </div>
    </aside>
  </div>
</div>
