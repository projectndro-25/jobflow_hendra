<?php
// Variabel: $range, $start_date, $end_date, $statusDist, $medianTTH
?>
<section class="container" style="padding-top:18px">
  <form method="get" style="display:flex;gap:10px;align-items:end;margin-bottom:16px">
    <div>
      <label class="text-muted" style="display:block;margin-bottom:6px">Rentang</label>
      <select name="range" class="input" onchange="this.form.submit()">
        <option value="30d"  <?= $range==='30d'  ? 'selected':'' ?>>30 Hari</option>
        <option value="custom" <?= $range==='custom' ? 'selected':'' ?>>Custom</option>
      </select>
    </div>
    <div>
      <label class="text-muted" style="display:block;margin-bottom:6px">Mulai</label>
      <input type="date" name="start_date" value="<?= h($start_date ?? '') ?>" class="input">
    </div>
    <div>
      <label class="text-muted" style="display:block;margin-bottom:6px">Selesai</label>
      <input type="date" name="end_date" value="<?= h($end_date ?? '') ?>" class="input">
    </div>
    <button class="btn" style="height:42px">Terapkan</button>
  </form>

  <div class="grid grid-2" style="margin-bottom:16px">
    <div class="card">
      <h3 style="margin:0 0 10px">Distribusi Status</h3>
      <?php if (empty($statusDist)): ?>
        <p class="text-muted" style="margin:0">Tidak ada data.</p>
      <?php else: ?>
        <table class="table">
          <thead><tr><th>Status</th><th>Total</th></tr></thead>
          <tbody>
            <?php foreach ($statusDist as $row): ?>
              <tr><td><?= h($row['status']) ?></td><td><?= (int)$row['total'] ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <div class="card">
      <h3 style="margin:0 0 10px">Time-to-Hire (Median)</h3>
      <p style="font-size:2rem;font-weight:800;margin:0"><?= (int)$medianTTH ?> <span class="text-muted" style="font-size:1rem">hari</span></p>
    </div>
  </div>

  <form method="post" action="<?= url('/dashboard/export') ?>" class="card" style="display:flex;gap:10px;align-items:center">
    <?= csrf_field(); ?>
    <input type="hidden" name="start_date" value="<?= h($start_date) ?>">
    <input type="hidden" name="end_date"   value="<?= h($end_date) ?>">
    <button class="btn">Export Applications (CSV)</button>
    <span class="text-muted">berdasarkan rentang tanggal di atas</span>
  </form>
</section>
