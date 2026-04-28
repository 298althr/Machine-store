<div class="container-modern section-padding" style="padding-top: 40px;">
  <div class="breadcrumb" style="margin-bottom: 24px; font-size: 0.9rem; color: var(--text-muted);">
    <a href="/" style="text-decoration: none; color: var(--accent);"><?= __('home') ?></a> 
    <span style="margin: 0 8px;">/</span> 
    <a href="/order/<?= $order['id'] ?>" style="text-decoration: none; color: var(--accent);">Asset #<?= htmlspecialchars($order['order_number']) ?></a>
    <span style="margin: 0 8px;">/</span> 
    <span style="color: var(--text-main); font-weight: 600;">Financial Portal</span>
  </div>

  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 60px; min-height: 250px; display: flex; align-items: center; padding: 60px; background: #0f172a;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white;">
      <div style="display: inline-block; padding: 6px 16px; background: var(--accent); border-radius: 30px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; margin-bottom: 24px; letter-spacing: 2px;">
        Institutional Settlement
      </div>
      <h1 style="font-size: 3.5rem; font-family: 'Outfit', sans-serif; line-height: 1.1; margin-bottom: 12px;"><?= $lang === 'de' ? 'Transaktion abschließen' : 'Complete Transaction' ?></h1>
      <p style="font-size: 1.25rem; opacity: 0.9;">
        <?= $lang === 'de' ? 'Sicheres Zahlungsportal für Auftrag' : 'Secure financial portal for Asset' ?> #<?= htmlspecialchars($order['order_number']) ?>
      </p>
    </div>
  </section>
  
  <div style="display: grid; grid-template-columns: 1fr 400px; gap: 60px; align-items: start; margin-bottom: 120px;">
    <!-- Main Content Infrastructure -->
    <main>
      <!-- Status Intelligence -->
      <div style="background: white; border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 40px; display: flex; align-items: center; gap: 32px;">
        <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0; border: 1px solid #f1f5f9;">🛡️</div>
        <div>
          <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-weight: 900; margin-bottom: 6px;">Lifecycle Status</div>
          <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif;"><?= get_status_label($order['status']) ?></div>
          <p style="color: var(--text-muted); font-size: 1rem; margin-top: 12px; line-height: 1.6; font-weight: 500;">
            <?php if ($order['status'] === 'awaiting_payment'): ?>
              Transfer the institutional funds to the STREICHER corporate registry and upload the verified transaction receipt.
            <?php elseif ($order['status'] === 'payment_uploaded'): ?>
              Verification protocol active. Our interdisciplinary finance department is auditing the submitted documentation.
            <?php else: ?>
              Transaction fully authenticated. Industrial logistics operations are in progress.
            <?php endif; ?>
          </p>
        </div>
      </div>

      <!-- Corporate Financial Registry -->
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 40px;">
        <div style="background: #f8fafc; padding: 24px 40px; border-bottom: 1px solid #f1f5f9;">
          <h3 style="font-size: 1.1rem; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; color: var(--primary); margin: 0;">Institutional Banking Registry</h3>
        </div>
        <div style="padding: 48px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 48px;">
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">Banking Institution</div>
              <div style="font-weight: 800; color: var(--primary); font-size: 1.2rem;"><?= htmlspecialchars($settings['bank_name'] ?? 'Deutsche Bank AG') ?></div>
            </div>
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">Account Beneficiary</div>
              <div style="font-weight: 800; color: var(--primary); font-size: 1.2rem;"><?= htmlspecialchars($settings['account_holder'] ?? 'MAX STREICHER GmbH') ?></div>
            </div>
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">IBAN Identifier</div>
              <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; letter-spacing: 1px; font-family: monospace;"><?= htmlspecialchars($settings['iban'] ?? 'DE89 3704 0044 0532 0130 00') ?></div>
            </div>
            <div>
              <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 900; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">BIC / SWIFT Protocol</div>
              <div style="font-weight: 900; color: var(--primary); font-size: 1.25rem; letter-spacing: 1px; font-family: monospace;"><?= htmlspecialchars($settings['bic'] ?? 'COBADEFFXXX') ?></div>
            </div>
          </div>
          
          <div style="background: #fff7ed; border: 2px dashed #f59e0b; border-radius: var(--radius-lg); padding: 32px; text-align: center; box-shadow: var(--shadow-sm);">
            <div style="font-size: 0.85rem; color: #92400e; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; margin-bottom: 12px;">MANDATORY INSTITUTIONAL REFERENCE</div>
            <div style="font-size: 2.5rem; font-weight: 900; color: #7c2d12; font-family: 'Outfit', sans-serif; line-height: 1;"><?= htmlspecialchars($order['order_number']) ?></div>
            <div style="font-size: 1rem; color: #92400e; margin-top: 16px; font-weight: 700; max-width: 400px; margin-left: auto; margin-right: auto;">Include this identifier in the transaction narrative for automated interdisciplinary verification.</div>
          </div>
        </div>
      </div>

      <!-- Verification Upload Portal -->
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(0,0,0,0.05); padding: 60px 48px;">
        <h3 style="font-size: 1.5rem; font-family: 'Outfit', sans-serif; margin-bottom: 32px; font-weight: 800; color: var(--primary);">Institutional Payment Verification</h3>
        
        <form action="/order/<?= $order['id'] ?>/payment" method="POST" enctype="multipart/form-data">
          <div id="dropZone" onclick="document.getElementById('receiptFile').click()" style="border: 3px dashed #f1f5f9; border-radius: var(--radius-lg); padding: 60px; text-align: center; cursor: pointer; transition: all 0.4s; background: #fafafa;" onmouseover="this.style.borderColor='var(--accent)'; this.style.background='#fffefb';" onmouseout="this.style.borderColor='#f1f5f9'; this.style.background='#fafafa';">
            <div style="font-size: 4rem; margin-bottom: 24px; opacity: 0.2;">📁</div>
            <div style="font-weight: 800; color: var(--primary); font-size: 1.2rem; margin-bottom: 8px;">Upload Transaction Protocol</div>
            <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">PDF, JPG, or PNG specification (Institutional Max 10MB)</div>
            <input type="file" name="receipt" id="receiptFile" accept=".jpg,.jpeg,.png,.gif,.pdf" style="display: none;" required onchange="showPreview(this.files[0])">
          </div>
          
          <div id="filePreview" style="display: none; margin-top: 32px; padding: 24px; background: #f8fafc; border-radius: var(--radius-md); border: 1px solid #e2e8f0; align-items: center; gap: 20px;">
            <div style="font-size: 2.5rem;">📎</div>
            <div style="flex: 1;">
              <div id="fileName" style="font-weight: 900; color: var(--primary); font-size: 1rem;"></div>
              <div id="fileSize" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;"></div>
            </div>
            <div style="color: #10b981; font-weight: 900; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">VERIFICATION READY</div>
          </div>
          
          <div style="margin-top: 48px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; letter-spacing: 1px;">Institutional Remarks (Technical)</label>
            <textarea name="notes" rows="4" style="width: 100%; border: 2px solid #f1f5f9; border-radius: var(--radius-md); padding: 20px; font-family: inherit; font-size: 1rem; outline: none; transition: var(--transition); font-weight: 500;" onfocus="this.style.borderColor='var(--accent)'" placeholder="Interdisciplinary wire reference, originating bank, authorization codes..."></textarea>
          </div>
          
          <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 72px; font-size: 1.2rem; font-weight: 900; margin-top: 48px; text-transform: uppercase; letter-spacing: 2px;">
            Authorize Submission Protocol
          </button>
        </form>
      </div>
    </main>

    <!-- Financial Intelligence Sidebar -->
    <aside style="position: sticky; top: 120px;">
      <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid rgba(0,0,0,0.05); padding: 40px;">
        <h3 style="font-size: 1.25rem; margin-bottom: 32px; font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--primary);">Asset Summary</h3>
        
        <div style="margin-bottom: 40px;">
          <?php foreach ($items as $item): ?>
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 1px solid #f8fafc; padding-bottom: 16px;">
            <div>
              <div style="font-weight: 800; font-size: 1rem; color: var(--primary); line-height: 1.3;"><?= htmlspecialchars($item['product_name'] ?? $item['sku']) ?></div>
              <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; margin-top: 4px;">QTY: <?= (int)$item['quantity'] ?> Units</div>
            </div>
            <div style="font-weight: 900; color: var(--primary); font-size: 1rem;"><?= format_price((float)$item['total_price']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="padding-top: 32px; border-top: 3px solid #f1f5f9;">
          <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px;">
            <span style="font-weight: 800; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Settlement Total</span>
            <span style="font-size: 2.2rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; line-height: 1;"><?= format_price((float)$order['total_amount']) ?></span>
          </div>
          <div style="text-align: right; font-size: 0.8rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Includes Statutory VAT</div>
        </div>

        <div style="margin-top: 60px; padding: 40px 0; border-top: 1px solid #f1f5f9; text-align: center;">
          <h4 style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 24px; letter-spacing: 2px;">Security Protocol Matrix</h4>
          <div style="display: flex; justify-content: center; gap: 32px; opacity: 0.5;">
            <span style="font-size: 2.5rem;">🔒</span>
            <span style="font-size: 2.5rem;">🏛️</span>
            <span style="font-size: 2.5rem;">⚖️</span>
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
  document.getElementById('fileSize').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
  document.getElementById('filePreview').style.display = 'flex';
}
</script>

<script>
function showPreview(file) {
  if (!file) return;
  document.getElementById('fileName').textContent = file.name;
  document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
  document.getElementById('filePreview').style.display = 'flex';
}
</script>
