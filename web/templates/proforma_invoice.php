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
  <title>Order Invoice <?= htmlspecialchars($invoiceNumber) ?></title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      line-height: 1.6;
      color: #1e293b;
      background: #f1f5f9;
      padding: 40px 20px;
    }
    
    .invoice-container {
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      border-radius: 12px;
      overflow: hidden;
    }
    
    .invoice-header {
      padding: 40px 60px;
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      color: white;
    }
    
    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }
    
    .logo-section img {
      height: 60px;
      width: auto;
    }
    
    .invoice-meta {
      text-align: right;
    }
    
    .invoice-type {
      font-size: 32px;
      font-weight: 700;
      color: white;
      margin-bottom: 12px;
      letter-spacing: 1px;
    }
    
    .meta-row {
      display: flex;
      justify-content: flex-end;
      gap: 32px;
      margin-top: 12px;
    }
    
    .meta-item {
      text-align: right;
    }
    
    .meta-label {
      font-size: 11px;
      color: rgba(255,255,255,0.8);
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 500;
    }
    
    .meta-value {
      font-size: 18px;
      font-weight: 700;
      color: white;
      margin-top: 6px;
    }
    
    .parties-section {
      background: #f8fafc;
      padding: 40px 60px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .party-box h3 {
      font-size: 11px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      margin-bottom: 16px;
      letter-spacing: 1.5px;
    }
    
    .party-details {
      font-size: 14px;
      color: #475569;
      line-height: 2;
    }
    
    .party-details strong {
      color: #0f172a;
      font-weight: 600;
    }
    
    .items-section {
      padding: 40px 60px;
    }
    
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 32px;
    }
    
    .items-table thead th {
      background: #f8fafc;
      color: #64748b;
      padding: 16px 20px;
      text-align: left;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      border-bottom: 2px solid #e2e8f0;
    }
    
    .items-table thead th:nth-child(1) { width: 50px; }
    .items-table thead th:nth-child(3),
    .items-table thead th:nth-child(4),
    .items-table thead th:nth-child(5),
    .items-table thead th:nth-child(6) { text-align: right; }
    
    .items-table tbody td {
      padding: 20px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 14px;
      vertical-align: top;
    }
    
    .items-table tbody td:nth-child(3),
    .items-table tbody td:nth-child(4),
    .items-table tbody td:nth-child(5),
    .items-table tbody td:nth-child(6) { text-align: right; }
    
    .item-name {
      font-weight: 600;
      color: #0f172a;
    }
    
    .item-sku {
      font-size: 12px;
      color: #64748b;
      margin-top: 4px;
    }
    
    .totals-section {
      display: flex;
      justify-content: flex-end;
      margin-top: 24px;
    }
    
    .totals-table {
      width: 420px;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
    }
    
    .totals-row {
      display: flex;
      justify-content: space-between;
      padding: 16px 24px;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .totals-row.subtotal {
      font-size: 14px;
      color: #64748b;
    }
    
    .totals-row.total {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      color: white;
      font-size: 20px;
      font-weight: 700;
      border: none;
      padding: 20px 24px;
    }
    
    .payment-section {
      padding: 40px 60px;
      background: #fef2f2;
      border-top: 3px solid #dc2626;
    }
    
    .payment-section h3 {
      font-size: 14px;
      font-weight: 700;
      color: #dc2626;
      margin-bottom: 24px;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .payment-details {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px 40px;
      font-size: 14px;
    }
    
    .payment-row {
      display: flex;
      gap: 12px;
    }
    
    .payment-label {
      color: #64748b;
      min-width: 150px;
      font-weight: 500;
    }
    
    .payment-value {
      font-weight: 600;
      color: #0f172a;
    }
    
    .notes-section {
      padding: 40px 60px;
      background: white;
    }
    
    .notes-section h3 {
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 12px;
      text-transform: uppercase;
    }
    
    .disclaimer-box {
      background: #fff7ed;
      border: 2px solid #f59e0b;
      border-radius: 8px;
      padding: 20px;
      margin-top: 24px;
    }
    
    .disclaimer-box h4 {
      color: #92400e;
      font-size: 14px;
      font-weight: 700;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .disclaimer-box ul {
      margin-left: 20px;
      color: #78350f;
      font-size: 13px;
      line-height: 1.8;
    }
    
    .footer {
      background: #0f172a;
      color: #94a3b8;
      text-align: center;
      padding: 24px;
      font-size: 12px;
    }
    
    .footer-links {
      margin-top: 8px;
    }
    
    .footer-links span {
      color: #475569;
      margin: 0 8px;
    }
    
    @media print {
      body { background: white; }
      .invoice-container { box-shadow: none; }
      .no-print { display: none; }
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
      <div class="header-row">
        <div class="logo-section">
          <img src="/assets/logo.png" alt="Streicher GmbH">
        </div>
        <div class="invoice-meta">
          <div class="invoice-type">ORDER INVOICE</div>
          <div class="meta-row">
            <div class="meta-item">
              <div class="meta-label">Date</div>
              <div class="meta-value"><?= $invoiceDate ?></div>
            </div>
            <div class="meta-item">
              <div class="meta-label">Invoice #</div>
              <div class="meta-value"><?= htmlspecialchars($invoiceNumber) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Parties -->
    <div class="parties-section">
      <div class="party-box">
        <h3>Supplier</h3>
        <div class="party-details">
          <strong>Streicher GmbH</strong><br>
          VAT ID: DE123456789<br>
          Industriestraße 45<br>
          93055 Regensburg<br>
          Germany<br>
          <br>
          Email: store@streichergmbh.com<br>
          Phone: +49 991 330-00
        </div>
      </div>
      <div class="party-box">
        <h3>Customer</h3>
        <div class="party-details">
          <strong><?= htmlspecialchars($order['billing_company'] ?? 'N/A') ?></strong><br>
          <?php if (!empty($order['vat_number'])): ?>
          VAT: <?= htmlspecialchars($order['vat_number']) ?><br>
          <?php endif; ?>
          <?= htmlspecialchars($order['billing_name'] ?? '') ?><br>
          <?= htmlspecialchars($order['billing_address'] ?? '') ?><br>
          <?= htmlspecialchars($order['billing_city'] ?? '') ?>, <?= htmlspecialchars($order['billing_postal'] ?? '') ?><br>
          <?= htmlspecialchars($order['billing_country'] ?? '') ?><br>
          <br>
          <?php if (!empty($order['delivery_facility'])): ?>
          <strong>Delivery Facility:</strong><br>
          <?= nl2br(htmlspecialchars($order['delivery_facility'])) ?><br>
          <br>
          <?php endif; ?>
          Email: <?= htmlspecialchars($order['billing_email'] ?? '') ?><br>
          Phone: <?= htmlspecialchars($order['billing_phone'] ?? '') ?>
        </div>
      </div>
    </div>

    <!-- Line Items -->
    <div class="items-section">
      <table class="items-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Equipment Details</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>VAT (19%)</th>
            <th>Total</th>
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
            <td><?= $index + 1 ?>.</td>
            <td>
              <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
              <div class="item-sku">SKU: <?= htmlspecialchars($item['sku']) ?></div>
              <?php if (!empty($item['serial_number'])): ?>
              <div class="item-sku">Serial: <?= htmlspecialchars($item['serial_number']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= $currencySymbol ?><?= number_format($item['unit_price'], 2) ?></td>
            <td><?= (int)$item['quantity'] ?></td>
            <td><?= $currencySymbol ?><?= number_format($itemVat, 2) ?></td>
            <td><?= $currencySymbol ?><?= number_format($itemTotal, 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Totals -->
      <div class="totals-section">
        <div class="totals-table">
          <div class="totals-row subtotal">
            <span>Subtotal (Excl. VAT):</span>
            <span><?= $currencySymbol ?><?= number_format($subtotal, 2) ?></span>
          </div>
          <div class="totals-row subtotal">
            <span><?= htmlspecialchars($deliveryModeLabel) ?>:</span>
            <span><?= $currencySymbol ?><?= number_format($deliveryCost, 2) ?></span>
          </div>
          <div class="totals-row subtotal">
            <span>VAT (19%):</span>
            <span><?= $currencySymbol ?><?= number_format($vatAmount, 2) ?></span>
          </div>
          <div class="totals-row total">
            <span>TOTAL:</span>
            <span><?= $currencySymbol ?><?= number_format($total, 2) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-section">
      <h3>🏦 Bank Transfer Details</h3>
      <div class="payment-details">
        <div class="payment-row">
          <span class="payment-label">Bank Name:</span>
          <span class="payment-value">Commerzbank AG Frankfurt</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">Account Holder:</span>
          <span class="payment-value">Streicher GmbH</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">IBAN:</span>
          <span class="payment-value">DE91 5004 0000 0123 4567 89</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">BIC/SWIFT:</span>
          <span class="payment-value">COBADEFFXXX</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">Payment Reference:</span>
          <span class="payment-value"><?= htmlspecialchars($invoiceNumber) ?></span>
        </div>
        <div class="payment-row">
          <span class="payment-label">Amount Due:</span>
          <span class="payment-value"><?= $currencySymbol ?><?= number_format($total, 2) ?></span>
        </div>
      </div>
    </div>

    <!-- Notes & Disclaimers -->
    <div class="notes-section">
      <h3>Important Notes</h3>
      <p style="font-size: 14px; color: #475569; line-height: 1.8;">
        This is your official Order Invoice. When you are ready to proceed with payment, please transfer the amount to the bank account above using the invoice number as reference. After making payment, upload your payment receipt below for verification.
      </p>

      <div class="disclaimer-box">
        <h4>⚠️ Import Duties & Documentation</h4>
        <ul>
          <li><strong>Import Duties:</strong> All prices are FOB (Free On Board) and do NOT include import duties, customs fees, or local taxes. The customer is responsible for all import duties, customs clearance, and associated fees when equipment arrives at the destination port.</li>
          <li><strong>Required Documentation:</strong> Commercial invoice, packing list, certificate of origin, and equipment specifications will be provided for customs clearance.</li>
          <li><strong>Delivery Responsibility:</strong> Once equipment is loaded at the port of origin, risk transfers to the customer. Customer is responsible for customs clearance, import duties, and inland transportation to the final delivery facility/offshore platform.</li>
          <li><strong>Payment Terms:</strong> Payment must be received and cleared before shipment. Upload payment receipt for verification (1-2 business days processing).</li>
        </ul>
      </div>

      <?php if (!empty($order['notes'])): ?>
      <div style="margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 8px;">
        <strong style="font-size: 13px; color: #0f172a;">Customer Notes:</strong>
        <p style="font-size: 13px; color: #475569; margin-top: 8px; white-space: pre-wrap;"><?= htmlspecialchars($order['notes']) ?></p>
      </div>
      <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
      <div>Streicher GmbH - German Engineering Excellence Since 1970</div>
      <div class="footer-links">
        store@streichergmbh.com
        <span>|</span>
        +49 991 330-00
        <span>|</span>
        www.streichergmbh.com
      </div>
      <div style="margin-top: 12px; font-size: 11px;">
        VAT ID: DE123456789 | Commercial Register: HRB 12345 Regensburg | ISO 9001:2015 Certified
      </div>
    </div>
  </div>

  <div class="no-print" style="max-width: 1000px; margin: 32px auto 0; display: flex; gap: 16px; justify-content: center; align-items: center;">
    <a href="/order/<?= $order['id'] ?>/payment" style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(220, 38, 38, 0.3); transition: all 0.2s;">
      📤 Upload Payment Receipt
    </a>
    <a href="/order/<?= $order['id'] ?>/invoice/pdf" style="background: white; color: #0f172a; border: 2px solid #e2e8f0; padding: 16px 32px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none;">
      📄 Download PDF Invoice
    </a>
    <button onclick="window.print()" style="background: #f1f5f9; color: #0f172a; border: 2px solid #e2e8f0; padding: 16px 32px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
      🖨️ Print
    </button>
    <a href="/account" style="display: inline-block; padding: 16px 32px; background: #f1f5f9; color: #64748b; text-decoration: none; border-radius: 8px; font-weight: 600;">
      ← Back to Orders
    </a>
  </div>
</body>
</html>
