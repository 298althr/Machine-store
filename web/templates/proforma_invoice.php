<?php
/**
 * Order Invoice Template
 * For Streicher GmbH Heavy Industrial Equipment
 */
$invoiceDate = date('F d, Y');
$invoiceNumber = $order['invoice_number'] ?? 'INV-' . str_pad($order['id'] ?? '0', 6, '0', STR_PAD_LEFT);
$displayCurrency = $_SESSION['display_currency'] ?? 'EUR';
$currencySymbol = $displayCurrency === 'USD' ? '$' : '€';

// Calculate totals
$subtotal = 0;
foreach ($orderItems as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$deliveryCost = $order['delivery_cost'] ?? 0;
$deliveryMode = $order['delivery_mode'] ?? 'regular';
$deliveryModeLabel = $deliveryMode === 'emergency' ? 'Emergency Delivery (2-4 days)' : 'Regular Delivery (5-9 days)';

$vatRate = 0.19; // 19% VAT
$vatAmount = ($subtotal + $deliveryCost) * $vatRate;
$total = $subtotal + $deliveryCost + $vatAmount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Institutional Invoice <?= htmlspecialchars($invoiceNumber) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #0f172a;
      --accent: #dc2626;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --bg-body: #f8fafc;
      --radius-lg: 16px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      line-height: 1.6;
      color: var(--text-main);
      background: var(--bg-body);
      padding: 60px 20px;
    }
    
    .invoice-container {
      max-width: 1100px;
      margin: 0 auto;
      background: white;
      box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);
      border-radius: var(--radius-lg);
      overflow: hidden;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .invoice-header {
      padding: 60px 80px;
      background: var(--primary);
      color: white;
      position: relative;
    }

    .invoice-header::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 40%;
      height: 100%;
      background: linear-gradient(90deg, transparent 0%, rgba(220, 38, 38, 0.05) 100%);
    }
    
    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      z-index: 1;
    }
    
    .logo-section h2 {
      font-family: 'Outfit', sans-serif;
      font-size: 2rem;
      font-weight: 900;
      letter-spacing: -1px;
    }

    .logo-section span {
      color: var(--accent);
    }
    
    .invoice-meta {
      text-align: right;
    }
    
    .invoice-type {
      font-family: 'Outfit', sans-serif;
      font-size: 2.5rem;
      font-weight: 900;
      color: white;
      margin-bottom: 8px;
      letter-spacing: -1px;
      text-transform: uppercase;
    }

    .meta-badge {
      display: inline-block;
      padding: 6px 16px;
      background: var(--accent);
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 900;
      letter-spacing: 2px;
      text-transform: uppercase;
    }
    
    .parties-section {
      padding: 60px 80px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      border-bottom: 1px solid #f1f5f9;
    }
    
    .party-box h3 {
      font-size: 0.8rem;
      font-weight: 900;
      color: var(--text-muted);
      text-transform: uppercase;
      margin-bottom: 24px;
      letter-spacing: 2px;
    }
    
    .party-details {
      font-size: 1rem;
      color: var(--text-main);
      line-height: 1.8;
    }
    
    .party-details strong {
      font-family: 'Outfit', sans-serif;
      font-size: 1.2rem;
      color: var(--primary);
      font-weight: 800;
      display: block;
      margin-bottom: 8px;
    }
    
    .items-section {
      padding: 40px 80px;
    }
    
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
    }
    
    .items-table thead th {
      padding: 24px;
      text-align: left;
      font-size: 0.75rem;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--text-muted);
      border-bottom: 2px solid #f1f5f9;
    }
    
    .items-table thead th:nth-child(1) { width: 60px; }
    .items-table thead th:nth-child(3),
    .items-table thead th:nth-child(4),
    .items-table thead th:nth-child(5),
    .items-table thead th:nth-child(6) { text-align: right; }
    
    .items-table tbody td {
      padding: 32px 24px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 1rem;
      vertical-align: top;
    }
    
    .items-table tbody td:nth-child(3),
    .items-table tbody td:nth-child(4),
    .items-table tbody td:nth-child(5),
    .items-table tbody td:nth-child(6) { text-align: right; }
    
    .item-name {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      color: var(--primary);
      font-size: 1.1rem;
    }
    
    .item-sku {
      font-size: 0.85rem;
      color: var(--text-muted);
      font-weight: 600;
      margin-top: 6px;
      font-family: monospace;
    }
    
    .totals-section {
      display: flex;
      justify-content: flex-end;
      padding: 0 80px 60px;
    }
    
    .totals-box {
      width: 450px;
      background: #f8fafc;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #f1f5f9;
    }
    
    .totals-row {
      display: flex;
      justify-content: space-between;
      padding: 20px 32px;
      border-bottom: 1px solid #f1f5f9;
    }
    
    .totals-row span:first-child {
      font-weight: 700;
      color: var(--text-muted);
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 1px;
    }

    .totals-row span:last-child {
      font-weight: 800;
      color: var(--primary);
    }
    
    .totals-row.total {
      background: var(--primary);
      color: white;
      padding: 32px;
      border: none;
    }

    .totals-row.total span:first-child {
      color: var(--accent);
      font-size: 1rem;
      font-family: 'Outfit', sans-serif;
    }

    .totals-row.total span:last-child {
      color: white;
      font-size: 2rem;
      font-family: 'Outfit', sans-serif;
      line-height: 1;
    }
    
    .payment-section {
      padding: 60px 80px;
      background: #fef2f2;
      border-top: 4px solid var(--accent);
    }
    
    .payment-section h3 {
      font-family: 'Outfit', sans-serif;
      font-size: 1.25rem;
      font-weight: 900;
      color: var(--accent);
      margin-bottom: 32px;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .payment-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 40px;
    }

    .payment-item label {
      display: block;
      font-size: 0.75rem;
      font-weight: 900;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 8px;
    }

    .payment-item span {
      font-family: 'Outfit', sans-serif;
      font-size: 1.2rem;
      font-weight: 800;
      color: var(--primary);
    }

    .payment-item.reference {
      background: white;
      padding: 24px;
      border-radius: 8px;
      border: 2px dashed var(--accent);
      grid-column: span 2;
      text-align: center;
    }
    
    .disclaimer-section {
      padding: 60px 80px;
      background: white;
    }
    
    .disclaimer-box {
      background: #fff7ed;
      border: 2px solid #f59e0b;
      border-radius: 12px;
      padding: 40px;
    }
    
    .disclaimer-box h4 {
      font-family: 'Outfit', sans-serif;
      color: #92400e;
      font-size: 1.2rem;
      font-weight: 900;
      margin-bottom: 24px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .disclaimer-box ul {
      margin-left: 24px;
      color: #78350f;
      font-size: 0.95rem;
      line-height: 2;
      font-weight: 500;
    }
    
    .footer {
      background: var(--primary);
      color: white;
      text-align: center;
      padding: 60px 80px;
    }

    .footer-brand {
      font-family: 'Outfit', sans-serif;
      font-size: 1.5rem;
      font-weight: 900;
      margin-bottom: 12px;
    }

    .footer-brand span { color: var(--accent); }
    
    .footer-info {
      font-size: 0.9rem;
      opacity: 0.6;
      font-weight: 500;
      max-width: 600px;
      margin: 24px auto 0;
      line-height: 1.8;
    }
    
    .no-print {
      max-width: 1100px;
      margin: 40px auto;
      display: flex;
      gap: 20px;
      justify-content: center;
    }

    .btn-action {
      padding: 20px 40px;
      border-radius: 8px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .btn-primary { background: var(--accent); color: white; border: none; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2); }
    .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(220, 38, 38, 0.3); }
    
    .btn-secondary { background: white; color: var(--primary); border: 2px solid #e2e8f0; }
    .btn-secondary:hover { background: #f8fafc; border-color: var(--primary); }

    @media print {
      body { background: white; padding: 0; }
      .invoice-container { box-shadow: none; border: none; border-radius: 0; width: 100%; max-width: none; }
      .no-print { display: none; }
      .invoice-header { padding: 40px; }
      .parties-section, .items-section, .totals-section, .payment-section, .disclaimer-section { padding: 40px; }
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <!-- Institutional Header -->
    <div class="invoice-header">
      <div class="header-row">
        <div class="logo-section">
          <h2>STREICHER<span>GmbH</span></h2>
          <div style="font-size: 0.8rem; font-weight: 700; opacity: 0.6; margin-top: 4px; letter-spacing: 2px;">INDUSTRIAL EXCELLENCE</div>
        </div>
        <div class="invoice-meta">
          <div class="invoice-type">PROFORMA INVOICE</div>
          <div class="meta-badge">OFFICIAL REGISTRY</div>
          <div style="margin-top: 24px; display: flex; gap: 40px; justify-content: flex-end;">
            <div>
              <div style="font-size: 0.7rem; font-weight: 900; opacity: 0.5; text-transform: uppercase;">Date Issued</div>
              <div style="font-size: 1.1rem; font-weight: 800;"><?= $invoiceDate ?></div>
            </div>
            <div>
              <div style="font-size: 0.7rem; font-weight: 900; opacity: 0.5; text-transform: uppercase;">Invoice Registry #</div>
              <div style="font-size: 1.1rem; font-weight: 800;"><?= htmlspecialchars($invoiceNumber) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Registry Parties -->
    <div class="parties-section">
      <div class="party-box">
        <h3>Institutional Supplier</h3>
        <div class="party-details">
          <strong>MAX STREICHER GmbH & Co. KG aA</strong>
          Industriestraße 45<br>
          93055 Regensburg<br>
          Germany<br>
          <br>
          <span style="font-weight: 800; color: var(--primary);">VAT Registry:</span> DE123456789<br>
          <span style="font-weight: 800; color: var(--primary);">Email:</span> procurement@streicher.de<br>
          <span style="font-weight: 800; color: var(--primary);">Protocol:</span> +49 991 330-00
        </div>
      </div>
      <div class="party-box">
        <h3>Institutional Customer</h3>
        <div class="party-details">
          <strong><?= htmlspecialchars($order['billing_company'] ?? 'N/A') ?></strong>
          <?php if (!empty($order['vat_number'])): ?>
          <span style="font-weight: 800; color: var(--primary);">VAT ID:</span> <?= htmlspecialchars($order['vat_number']) ?><br>
          <?php endif; ?>
          <?= htmlspecialchars($order['billing_name'] ?? '') ?><br>
          <?= htmlspecialchars($order['billing_address'] ?? '') ?><br>
          <?= htmlspecialchars($order['billing_city'] ?? '') ?>, <?= htmlspecialchars($order['billing_postal'] ?? '') ?><br>
          <?= htmlspecialchars($order['billing_country'] ?? '') ?><br>
          <br>
          <?php if (!empty($order['delivery_facility'])): ?>
          <span style="font-weight: 800; color: var(--primary);">Destination Facility:</span><br>
          <?= nl2br(htmlspecialchars($order['delivery_facility'])) ?><br>
          <br>
          <?php endif; ?>
          <span style="font-weight: 800; color: var(--primary);">Representative:</span> <?= htmlspecialchars($order['billing_email'] ?? '') ?>
        </div>
      </div>
    </div>

    <!-- Asset Inventory -->
    <div class="items-section">
      <table class="items-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Interdisciplinary Asset Details</th>
            <th>Unit Valuation</th>
            <th>Qty</th>
            <th>VAT (19%)</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orderItems as $index => $item): ?>
          <?php 
            $itemSubtotal = $item['unit_price'] * $item['quantity'];
            $itemVat = $itemSubtotal * $vatRate;
            $itemTotal = $itemSubtotal + $itemVat;
          ?>
          <tr>
            <td style="font-weight: 900; color: var(--accent);"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></td>
            <td>
              <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
              <div class="item-sku"><?= htmlspecialchars($item['sku']) ?></div>
              <?php if (!empty($item['serial_number'])): ?>
              <div style="font-size: 0.8rem; color: var(--accent); font-weight: 800; margin-top: 4px;">SERIAL: <?= htmlspecialchars($item['serial_number']) ?></div>
              <?php endif; ?>
            </td>
            <td style="font-weight: 700;"><?= $currencySymbol ?><?= number_format($item['unit_price'], 2) ?></td>
            <td style="font-weight: 800; color: var(--primary);"><?= (int)$item['quantity'] ?></td>
            <td style="font-weight: 600; color: var(--text-muted);"><?= $currencySymbol ?><?= number_format($itemVat, 2) ?></td>
            <td style="font-weight: 900; color: var(--primary);"><?= $currencySymbol ?><?= number_format($itemTotal, 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Financial Intelligence Totals -->
    <div class="totals-section">
      <div class="totals-box">
        <div class="totals-row">
          <span>Asset Subtotal</span>
          <span><?= $currencySymbol ?><?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="totals-row">
          <span>Logistics Dispatch (<?= $deliveryMode === 'emergency' ? 'Priority' : 'Regular' ?>)</span>
          <span><?= $currencySymbol ?><?= number_format($deliveryCost, 2) ?></span>
        </div>
        <div class="totals-row">
          <span>Statutory VAT (19%)</span>
          <span><?= $currencySymbol ?><?= number_format($vatAmount, 2) ?></span>
        </div>
        <div class="totals-row total">
          <span>FINAL SETTLEMENT</span>
          <span><?= $currencySymbol ?><?= number_format($total, 2) ?></span>
        </div>
      </div>
    </div>

    <!-- Corporate Settlement Protocol -->
    <div class="payment-section">
      <h3>🏛️ CORPORATE SETTLEMENT PROTOCOL</h3>
      <div class="payment-grid">
        <div class="payment-item">
          <label>Banking Institution</label>
          <span>Commerzbank AG Frankfurt</span>
        </div>
        <div class="payment-item">
          <label>Account Beneficiary</label>
          <span>MAX STREICHER GmbH</span>
        </div>
        <div class="payment-item">
          <label>IBAN Identifier</label>
          <span style="font-family: monospace; letter-spacing: 1px;">DE91 5004 0000 0123 4567 89</span>
        </div>
        <div class="payment-item">
          <label>BIC / SWIFT Protocol</label>
          <span style="font-family: monospace; letter-spacing: 1px;">COBADEFFXXX</span>
        </div>
        <div class="payment-item reference">
          <label>MANDATORY PAYMENT REFERENCE</label>
          <span style="font-size: 2rem; color: var(--accent);"><?= htmlspecialchars($invoiceNumber) ?></span>
          <div style="font-size: 0.8rem; font-weight: 800; color: var(--accent); margin-top: 8px;">Include this identifier for immediate automated verification</div>
        </div>
      </div>
    </div>

    <!-- Legal Intelligence & Disclaimers -->
    <div class="disclaimer-section">
      <div class="disclaimer-box">
        <h4>⚖️ INSTITUTIONAL DISCLAIMERS & LOGISTICS PROTOCOLS</h4>
        <ul>
          <li><strong>Import & Customs Matrix:</strong> All valuations are FOB (Free On Board). The institutional customer maintains full responsibility for all import duties, regional taxes, and interdisciplinary customs clearance protocols at the destination facility.</li>
          <li><strong>Institutional Documentation:</strong> Commercial manifests, packing specifications, and interdisciplinary technical certifications will be provided for global transit authorization.</li>
          <li><strong>Lifecycle Responsibility:</strong> Risk transfer occurs upon asset mobilization at the point of origin. The customer assumes liability for inland transit and interdisciplinary installation at the target facility.</li>
          <li><strong>Settlement Protocol:</strong> Full financial settlement must be authenticated before asset mobilization. Protocol verification requires 1-2 business cycles.</li>
        </ul>
      </div>
    </div>

    <!-- Institutional Footer -->
    <div class="footer">
      <div class="footer-brand">STREICHER<span>GmbH</span></div>
      <div style="font-size: 0.75rem; font-weight: 900; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 32px; opacity: 0.6;">German Engineering Excellence Since 1909</div>
      <div style="display: flex; gap: 40px; justify-content: center; font-weight: 800; font-size: 0.9rem;">
        <span>procurement@streicher.de</span>
        <span>+49 991 330-00</span>
        <span>www.streicher.de</span>
      </div>
      <div class="footer-info">
        MAX STREICHER GmbH & Co. KG aA | VAT ID: DE123456789 | Commercial Register: HRB 12345 Regensburg | Interdisciplinary ISO 9001:2015 Technical Certification Registry
      </div>
    </div>
  </div>

  <div class="no-print">
    <a href="/order/<?= $order['id'] ?>/payment-confirm" class="btn-action btn-primary" style="background: var(--primary);">
      📤 Upload Payment Protocol
    </a>
    <a href="/order/<?= $order['id'] ?>/invoice/pdf" class="btn-action btn-secondary" target="_blank" rel="noopener">
      📄 Download Official PDF
    </a>
    <button onclick="window.print()" class="btn-action btn-secondary">
      🖨️ Print Document
    </button>
    <a href="/account" class="btn-action btn-secondary">
      ← Back to Portal
    </a>
  </div>
</body>
</html>
