<h1 class="text-2xl font-bold mb-4">Schedules</h1>
<a class="btn" href="<?= url('/public/dashboard/schedules/create') ?>">+ Add</a>
<div class="card" style="margin-top:1rem">
  <table class="table">
    <thead><tr><th>Date</th><th>Method</th><th>Location</th><th>Candidate</th><th>Job</th></tr></thead>
    <tbody>
      <?php foreach(($rows??[]) as $r): ?>
      <tr>
        <td><?= h($r['datetime']) ?></td>
        <td><?= h($r['method']) ?></td>
        <td><?= h($r['location']??'-') ?></td>
        <td><?= h($r['candidate_name']??'-') ?></td>
        <td><?= h($r['job_title']??'-') ?></td>
      </tr>
      <?php endforeach; if(empty($rows)): ?>
      <tr><td colspan="5" class="text-muted">Belum ada jadwal.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
