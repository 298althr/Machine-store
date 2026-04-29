<?php
$class = $class ?? '';
$content = $content ?? '';
$title = $title ?? null;
$icon = $icon ?? null;
?>
<div class="card-modern <?= htmlspecialchars($class) ?>">
    <?php if ($icon || $title): ?>
    <div class="card-header-modern" style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
        <?php if ($icon): ?><i data-lucide="<?= htmlspecialchars($icon) ?>" style="color: var(--accent); width: 24px; height: 24px;"></i><?php endif; ?>
        <?php if ($title): ?><h3 style="margin: 0; font-size: 1.25rem;"><?= htmlspecialchars($title) ?></h3><?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="card-body-modern">
        <?= $content ?>
    </div>
</div>
