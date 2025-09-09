<?php
// Expect: $meta = ['page'=>int,'pages'=>int,'total'=>int]
if (!isset($meta) || ($meta['pages']??1) <= 1) return;
$base = strtok($_SERVER['REQUEST_URI'],'?');
$q    = $_GET; unset($q['page']);
?>
<nav class="mt-4">
  <ul style="display:flex;gap:.5rem;list-style:none;padding:0">
    <?php for($p=1;$p<=$meta['pages'];$p++):
      $q['page']=$p; $href = $base.'?'.http_build_query($q); ?>
      <li><a class="btn <?= $p==($meta['page']??1)?'secondary':'' ?>" href="<?= h($href) ?>"><?= $p ?></a></li>
    <?php endfor; ?>
  </ul>
</nav>
