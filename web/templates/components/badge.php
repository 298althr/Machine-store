<?php
$variant = $variant ?? 'success'; // success, warning, error, info
$label = $label ?? '';
$class = $class ?? '';
?>
<span class="badge badge-<?= htmlspecialchars($variant) ?> <?= htmlspecialchars($class) ?>">
    <?= htmlspecialchars($label) ?>
</span>
