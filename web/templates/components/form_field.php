<?php
$label = $label ?? '';
$name = $name ?? '';
$type = $type ?? 'text';
$placeholder = $placeholder ?? '';
$value = $value ?? '';
$required = $required ?? false;
$class = $class ?? '';
?>
<div class="form-group-modern <?= htmlspecialchars($class) ?>">
    <?php if ($label): ?>
    <label class="label-modern" style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">
        <?= htmlspecialchars($label) ?>
        <?php if ($required): ?><span style="color: var(--accent); margin-left: 4px;">*</span><?php endif; ?>
    </label>
    <?php endif; ?>
    <input type="<?= htmlspecialchars($type) ?>" 
           name="<?= htmlspecialchars($name) ?>" 
           class="input-modern"
           style="width: 100%; height: 52px; padding: 0 16px; border: 1px solid var(--border-medium); border-radius: 8px; font-family: var(--font-main); font-size: 1rem; transition: var(--transition);"
           placeholder="<?= htmlspecialchars($placeholder) ?>"
           value="<?= htmlspecialchars($value) ?>"
           <?= $required ? 'required' : '' ?>>
</div>
