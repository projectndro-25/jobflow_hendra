<?php
$ok = flash_get('ok') ?? ($_GET['ok'] ?? null);
$err = flash_get('err') ?? ($_GET['err'] ?? null);
if ($ok): ?>
  <div class="card" style="border-left:6px solid #22c55e;margin-bottom:12px">
    <strong>Sukses:</strong> <?= h($ok) ?>
  </div>
<?php endif; if ($err): ?>
  <div class="card" style="border-left:6px solid #ef4444;margin-bottom:12px">
    <strong>Error:</strong> <?= h($err) ?>
  </div>
<?php endif; ?>
