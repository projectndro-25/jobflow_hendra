<h1 class="text-2xl font-bold mb-4">Applications</h1>
<div class="card">
  <table class="table">
    <thead><tr><th>Candidate</th><th>Job</th><th>Status</th><th>Applied</th><th></th></tr></thead>
    <tbody>
      <?php foreach(($rows??[]) as $r): ?>
      <tr>
        <td><?= h($r['candidate_name']) ?></td>
        <td><?= h($r['job_title']) ?></td>
        <td><span class="badge"><?= h($r['status']) ?></span></td>
        <td><?= h($r['applied_at']) ?></td>
        <td><a class="btn" href="<?= url('/public/dashboard/applications/'.$r['id']) ?>">Detail</a></td>
      </tr>
      <?php endforeach; if(empty($rows)): ?>
      <tr><td colspan="5" class="text-muted">Belum ada data.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <?php if(isset($meta)) include BASE_PATH.'/app/views/partials/pagination.php'; ?>
</div>
