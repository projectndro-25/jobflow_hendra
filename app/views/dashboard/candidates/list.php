<?php require BASE_PATH.'/app/views/partials/alerts.php'; ?>
<h1 class="text-2xl font-bold mb-4">Candidates</h1>
<div class="mb-3">
  <?php $action=url('/public/dashboard/candidates'); $q=$_GET['q']??''; $extras=[]; include BASE_PATH.'/app/views/partials/filters.php'; ?>
</div>
<div class="card">
  <table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th></th></tr></thead>
    <tbody>
      <?php foreach(($rows??[]) as $r): ?>
      <tr>
        <td><?= h($r['name']) ?></td>
        <td><?= h($r['email']) ?></td>
        <td><?= h($r['phone']??'-') ?></td>
        <td><a class="btn" href="<?= url('/public/dashboard/candidates/'.$r['id']) ?>">Detail</a></td>
      </tr>
      <?php endforeach; if(empty($rows)): ?>
      <tr><td colspan="4" class="text-muted">Belum ada data.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <?php if(isset($meta)) include BASE_PATH.'/app/views/partials/pagination.php'; ?>
</div>
