<?php
$type = $type ?? 'button';
$variant = $variant ?? 'primary'; // primary, outline, accent, white
$size = $size ?? 'md'; // sm, md, lg
$class = $class ?? '';
$icon = $icon ?? null;
$label = $label ?? '';
$attr = $attr ?? '';
$href = $href ?? null;

$baseClass = "btn-modern";
$variantClass = "btn-{$variant}";
$sizeClass = "btn-{$size}";
$fullClass = "{$baseClass} {$variantClass} {$sizeClass} {$class}";

if ($href): ?>
    <a href="<?= htmlspecialchars($href) ?>" class="<?= htmlspecialchars($fullClass) ?>" <?= $attr ?>>
        <?php if ($icon): ?><i data-lucide="<?= htmlspecialchars($icon) ?>"></i><?php endif; ?>
        <span><?= htmlspecialchars($label) ?></span>
    </a>
<?php else: ?>
    <button type="<?= htmlspecialchars($type) ?>" class="<?= htmlspecialchars($fullClass) ?>" <?= $attr ?>>
        <?php if ($icon): ?><i data-lucide="<?= htmlspecialchars($icon) ?>"></i><?php endif; ?>
        <span><?= htmlspecialchars($label) ?></span>
    </button>
<?php endif; ?>
