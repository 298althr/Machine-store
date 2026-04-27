<div class="admin-header">
  <div>
    <h1 style="margin: 0;">Software Products</h1>
    <p style="margin: 4px 0 0 0; color: #64748b;">Manage software products available for activation</p>
  </div>
  <div>
    <a href="/admin/software-activation/products/add" class="btn btn-primary">
      + Add Software Product
    </a>
  </div>
</div>

<!-- Products List -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Software Products</h3>
    <div>
      <span class="badge" style="background: #3b82f6; color: white;">
        <?= count($products) ?> products
      </span>
    </div>
  </div>
  <div style="overflow-x: auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>SKU</th>
          <th>Price</th>
          <th>Currency</th>
          <th>License Format</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
          <td>
            <div style="font-weight: 500;"><?= htmlspecialchars($product['name']) ?></div>
            <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</div>
          </td>
          <td>
            <span style="font-family: monospace; font-size: 0.8rem;"><?= htmlspecialchars($product['sku']) ?></span>
          </td>
          <td>
            <div style="font-weight: 600;"><?= format_price((float)$product['price'], $product['currency']) ?></div>
          </td>
          <td>
            <span style="font-weight: 500;"><?= htmlspecialchars($product['currency']) ?></span>
          </td>
          <td>
            <span style="font-family: monospace; font-size: 0.8rem;"><?= htmlspecialchars($product['license_key_format']) ?></span>
          </td>
          <td>
            <span class="order-status-badge status-<?= $product['is_active'] ? 'completed' : 'rejected' ?>">
              <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td>
            <div style="font-size: 0.8rem;"><?= date('M j, Y', strtotime($product['created_at'])) ?></div>
            <div style="font-size: 0.75rem; color: #64748b;"><?= date('H:i', strtotime($product['created_at'])) ?></div>
          </td>
          <td>
            <a href="/admin/software-activation/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
            <form method="POST" action="/admin/software-activation/products/delete/<?= $product['id'] ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this software product?')">
              <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    
    <?php if (empty($products)): ?>
    <div style="text-align: center; padding: 2rem; color: #64748b;">
      No software products found. <a href="/admin/software-activation/products/add">Add your first software product</a>.
    </div>
    <?php endif; ?>
  </div>
</div>

<style>
.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
}

.btn-danger {
  background-color: #dc2626;
  border-color: #dc2626;
  color: white;
}

.btn-danger:hover {
  background-color: #b91c1c;
  border-color: #b91c1c;
}
</style>
