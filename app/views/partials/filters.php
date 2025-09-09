<?php
// Expect: $action, $q (search), $extras (array of [name=>value])
$action = $action ?? strtok($_SERVER['REQUEST_URI'],'?');
?>
<form action="<?= h($action) ?>" method="get" class="grid grid-3">
  <input class="input" type="text" name="q" value="<?= h($q ?? '') ?>" placeholder="Cari...">
  <?php if(!empty($extras)) foreach($extras as $name=>$value): ?>
    <input type="hidden" name="<?= h($name) ?>" value="<?= h($value) ?>">
  <?php endforeach; ?>
  <button class="btn">Filter</button>
</form>
