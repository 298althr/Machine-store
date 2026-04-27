<div class="admin-header">
  <div>
    <h1 style="margin: 0;">
      <?= $is_edit ? 'Edit Software Product' : 'Add Software Product' ?>
    </h1>
    <p style="margin: 4px 0 0 0; color: #64748b;">
      <?= $is_edit ? 'Update software product details' : 'Create a new software product for activation' ?>
    </p>
  </div>
  <div>
    <a href="/admin/software-activation/products" class="btn btn-outline">
      ← Back to Products
    </a>
  </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
  <div class="card-header">
    <h3 class="card-title">Product Information</h3>
  </div>
  <div class="card-body">
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <form method="POST" action="<?= $is_edit ? '/admin/software-activation/products/update/' . $product['id'] : '/admin/software-activation/products/create' ?>">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div class="form-group">
          <label for="sku" class="form-label">SKU *</label>
          <input type="text" id="sku" name="sku" class="form-control" 
                 value="<?= htmlspecialchars($input['sku'] ?? ($product['sku'] ?? '')) ?>" required>
          <div class="form-text">Unique product identifier</div>
        </div>
        
        <div class="form-group">
          <label for="price" class="form-label">Price *</label>
          <input type="number" id="price" name="price" class="form-control" 
                 value="<?= htmlspecialchars($input['price'] ?? ($product['price'] ?? '')) ?>" 
                 step="0.01" min="0" required>
        </div>
      </div>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div class="form-group">
          <label for="currency" class="form-label">Currency *</label>
          <select id="currency" name="currency" class="form-control" required>
            <option value="EUR" <?= (isset($input['currency']) && $input['currency'] === 'EUR') || (isset($product['currency']) && $product['currency'] === 'EUR') ? 'selected' : '' ?>>EUR</option>
            <option value="USD" <?= (isset($input['currency']) && $input['currency'] === 'USD') || (isset($product['currency']) && $product['currency'] === 'USD') ? 'selected' : '' ?>>USD</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="license_key_format" class="form-label">License Key Format *</label>
          <input type="text" id="license_key_format" name="license_key_format" class="form-control" 
                 value="<?= htmlspecialchars($input['license_key_format'] ?? ($product['license_key_format'] ?? '003XXXXXXXXXXXXXX')) ?>" required>
          <div class="form-text">Format: 003XXXXXXXXXXXXXX (16 digits starting with 003)</div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="name" class="form-label">Product Name *</label>
        <input type="text" id="name" name="name" class="form-control" 
               value="<?= htmlspecialchars($input['name'] ?? ($product['name'] ?? '')) ?>" required>
      </div>
      
      <div class="form-group">
        <label for="description" class="form-label">Description *</label>
        <textarea id="description" name="description" class="form-control" rows="4" required><?= htmlspecialchars($input['description'] ?? ($product['description'] ?? '')) ?></textarea>
      </div>
      
      <div class="form-group">
        <label for="features" class="form-label">Features</label>
        <textarea id="features" name="features" class="form-control" rows="3" placeholder="One feature per line"><?= htmlspecialchars($input['features'] ?? ($product['features'] ?? '')) ?></textarea>
        <div class="form-text">Enter each feature on a new line</div>
      </div>
      
      <div class="form-group">
        <label for="system_requirements" class="form-label">System Requirements</label>
        <textarea id="system_requirements" name="system_requirements" class="form-control" rows="3" placeholder="One requirement per line"><?= htmlspecialchars($input['system_requirements'] ?? ($product['system_requirements'] ?? '')) ?></textarea>
        <div class="form-text">Enter each requirement on a new line</div>
      </div>
      
      <div class="form-group">
        <label class="form-label">
          <input type="checkbox" name="is_active" value="1" 
                 <?= (isset($input['is_active']) && $input['is_active']) || (isset($product['is_active']) && $product['is_active']) ? 'checked' : '' ?>>
          Active
        </label>
        <div class="form-text">Inactive products won't be available for activation</div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <?= $is_edit ? 'Update Product' : 'Create Product' ?>
        </button>
        <a href="/admin/software-activation/products" class="btn btn-outline">Cancel</a>
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
  box-sizing: border-box;
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
  display: flex;
  gap: 1rem;
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

.btn-outline {
  background-color: transparent;
  border-color: #d1d5db;
  color: #374151;
}

.btn-outline:hover {
  background-color: #f9fafb;
  border-color: #9ca3af;
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
</style>
