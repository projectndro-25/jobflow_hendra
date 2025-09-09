<h1 class="text-2xl font-bold mb-2"><?= h($c['name']) ?></h1>
<div class="grid grid-2">
  <div class="card">
    <div class="text-muted">Email</div>
    <div><?= h($c['email']) ?></div>
    <div class="text-muted" style="margin-top:.5rem">Phone</div>
    <div><?= h($c['phone']??'-') ?></div>
    <div class="text-muted" style="margin-top:.5rem">Portfolio</div>
    <div><?= $c['portfolio_url'] ? '<a class="btn" href="'.h($c['portfolio_url']).'" target="_blank">Open</a>' : '-' ?></div>
  </div>
  <div class="card">
    <div class="card-title">Applications</div>
    <table class="table">
      <thead><tr><th>Job</th><th>Status</th><th>Applied</th></tr></thead>
      <tbody>
      <?php foreach(($apps??[]) as $a): ?>
        <tr>
          <td><?= h($a['title']) ?></td>
          <td><span class="badge"><?= h($a['status']) ?></span></td>
          <td><?= h($a['applied_at']) ?></td>
        </tr>
      <?php endforeach; if(empty($apps)): ?>
        <tr><td colspan="3" class="text-muted">Belum ada aplikasi.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
