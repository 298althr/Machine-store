<?php
// Get hardware products that have software activation enabled
$products_with_software = [];
$current_currency = $_SESSION['display_currency'] ?? 'EUR';
try {
    $stmt = $pdo->query('SELECT id, name, description, short_desc, sku, unit_price, software_price, software_currency, requires_serial, requires_activation_code
                           FROM products 
                           WHERE is_active = 1 AND has_software_activation = 1 AND software_price IS NOT NULL 
                           ORDER BY name');
    $products = $stmt->fetchAll();
    
    foreach ($products as $product) {
        // Convert software price to selected currency if needed
        $display_price = $product['software_currency'] === $current_currency ? 
            (float)$product['software_price'] : 
            convert_price((float)$product['software_price'], $current_currency);
        
        $products_with_software[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'description' => $product['short_desc'] ?? $product['description'],
            'sku' => $product['sku'],
            'hardware_price' => (float)$product['unit_price'],
            'software_price' => $display_price,
            'currency' => $current_currency,
            'requires_serial' => (bool)($product['requires_serial'] ?? false),
            'requires_activation_code' => (bool)($product['requires_activation_code'] ?? false),
            'activation_instructions' => $product['requires_serial'] || $product['requires_activation_code'] ? 
                'Enter your device details from the product manual to activate the software.' : 
                'Click continue to proceed with your software activation.'
        ];
    }
} catch (Exception $e) {
    // If table doesn't exist, show message
    $products_with_software = [];
}
?>

<div class="page-header">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
      <h1>Software Activation</h1>
      <p>Activate your software license by providing your device details and payment information</p>
    </div>
    <div>
      <?php include __DIR__ . '/../components/currency-toggle.php'; ?>
    </div>
  </div>
</div>

<!-- Token Check Section -->
<div class="card" style="max-width: 600px; margin: 0 auto 2rem auto;">
  <div class="card-header">
    <h3 class="card-title">Check Existing Activation Status</h3>
  </div>
  <div class="card-body">
    <p style="margin-bottom: 1rem; color: #6b7280;">Already started an activation? Enter your activation token to check the status.</p>
    <form method="GET" action="/software-activation/status" style="display: flex; gap: 8px;">
      <input type="text" 
             name="token" 
             placeholder="Enter your activation token..." 
             class="form-control" 
             style="flex: 1;"
             required
             pattern="[a-f0-9]{64}"
             title="Activation token should be 64 characters long">
      <button type="submit" class="btn btn-primary">Check Status</button>
    </form>
    <div class="form-text">Your activation token was sent to your email when you started the activation process.</div>
  </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <div class="card-header">
    <h2 class="card-title">Start Activation Process</h2>
  </div>
  <div class="card-body">
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="/software-activation">
      <div class="form-group">
        <label for="product_id" class="form-label">Hardware Product *</label>
        <select id="product_id" name="product_id" class="form-control" required onchange="updateProductInfo()">
          <option value="">Select your hardware product...</option>
          <?php foreach ($products_with_software as $product): ?>
          <option value="<?= $product['id'] ?>" 
                  data-software-price="<?= $product['software_price'] ?>"
                  data-requires-serial="<?= $product['requires_serial'] ?>"
                  data-requires-code="<?= $product['requires_activation_code'] ?>"
                  data-instructions="<?= htmlspecialchars($product['activation_instructions']) ?>"
                  <?= (isset($input['product_id']) && $input['product_id'] == $product['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku']) ?>) - Software: <?= format_price($product['software_price'], $product['currency']) ?>
          </option>
          <?php endforeach; ?>
        </select>
        <?php if (empty($products_with_software)): ?>
        <div class="form-text" style="color: #ca8a04;">No hardware products with software are currently available for activation.</div>
        <?php endif; ?>
        <div class="form-text">Select the hardware product you purchased that includes software activation</div>
      </div>
      
      <div id="product-info" style="display: none; background: #f8fafc; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem;">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; color: #374151;">Software Activation Details:</h4>
        <div id="product-details"></div>
      </div>
      
      <div class="form-group">
        <label for="customer_email" class="form-label">Email Address *</label>
        <input type="email" id="customer_email" name="customer_email" class="form-control" 
               value="<?= htmlspecialchars($input['customer_email'] ?? '') ?>" required>
        <div class="form-text">License key and activation status will be sent to this email</div>
      </div>
      
      <div class="form-group">
        <label for="customer_name" class="form-label">Full Name</label>
        <input type="text" id="customer_name" name="customer_name" class="form-control" 
               value="<?= htmlspecialchars($input['customer_name'] ?? '') ?>">
      </div>
      
      <div class="form-group" id="serial_number_group">
        <label for="serial_number" class="form-label">Device Serial Number *</label>
        <input type="text" id="serial_number" name="serial_number" class="form-control" 
               value="<?= htmlspecialchars($input['serial_number'] ?? '') ?>" required>
        <div class="form-text">Enter the serial number of your device or hardware dongle</div>
      </div>
      
      <div class="form-group" id="activation_code_group">
        <label for="activation_code" class="form-label">Software Activation Code *</label>
        <input type="text" id="activation_code" name="activation_code" class="form-control" 
               value="<?= htmlspecialchars($input['activation_code'] ?? '') ?>" required>
        <div class="form-text">Enter the activation code that came with your software package</div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block">Continue to Payment</button>
      </div>
    </form>
  </div>
</div>

<style>
.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #374151;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-text {
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

.form-actions {
  margin-top: 2rem;
}

.alert {
  padding: 1rem;
  border-radius: 0.375rem;
  margin-bottom: 1rem;
}

.alert-danger {
  background-color: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
}

.alert-info {
  background-color: #eff6ff;
  border: 1px solid #bfdbfe;
  color: #1e40af;
}

.card {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  overflow: hidden;
}

.card-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  background-color: #f9fafb;
}

.card-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.card-body {
  padding: 1.5rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  border: 1px solid transparent;
  border-radius: 0.375rem;
  font-size: 1rem;
  font-weight: 500;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.15s ease-in-out;
}

.btn-primary {
  background-color: #3b82f6;
  border-color: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background-color: #2563eb;
  border-color: #2563eb;
}

.btn-block {
  width: 100%;
}

.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-header h1 {
  margin: 0 0 0.5rem 0;
  font-size: 2rem;
  font-weight: 700;
  color: #111827;
}

.page-header p {
  margin: 0;
  color: #6b7280;
  font-size: 1.125rem;
}
</style>

<script>
function updateProductInfo() {
  const select = document.getElementById('product_id');
  const selectedOption = select.options[select.selectedIndex];
  const productInfo = document.getElementById('product-info');
  const productDetails = document.getElementById('product-details');
  const serialGroup = document.getElementById('serial_number_group');
  const codeGroup = document.getElementById('activation_code_group');
  const serialInput = document.getElementById('serial_number');
  const codeInput = document.getElementById('activation_code');
  
  if (selectedOption.value) {
    const price = selectedOption.dataset.softwarePrice;
    const requiresSerial = selectedOption.dataset.requiresSerial === '1' || selectedOption.dataset.requiresSerial === 'true';
    const requiresCode = selectedOption.dataset.requiresCode === '1' || selectedOption.dataset.requiresCode === 'true';
    const instructions = selectedOption.dataset.instructions;
    
    // Show product info
    productInfo.style.display = 'block';
    productDetails.innerHTML = `
      <div><strong>Software Price:</strong> ${price}</div>
      <div><strong>Instructions:</strong> ${instructions}</div>
    `;
    
    // Show/hide serial number field
    serialGroup.style.display = requiresSerial ? 'block' : 'none';
    serialInput.required = requiresSerial;
    
    // Show/hide activation code field
    codeGroup.style.display = requiresCode ? 'block' : 'none';
    codeInput.required = requiresCode;
  } else {
    productInfo.style.display = 'none';
    serialGroup.style.display = 'block';
    codeGroup.style.display = 'block';
    serialInput.required = true;
    codeInput.required = true;
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  updateProductInfo();
});
</script>
