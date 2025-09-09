<?php require BASE_PATH.'/app/views/partials/alerts.php'; ?>

<h1 class="text-2xl font-bold mb-4">Buat Jadwal</h1>

<?php if (empty($application)): ?>
  <div class="card"><div class="text-muted">Application tidak ditemukan.</div></div>
<?php else: ?>
  <div class="card" style="margin-bottom:1rem">
    <div class="card-title">Kandidat</div>
    <div><strong><?= h($application['name'] ?: $application['email']) ?></strong></div>
    <div class="text-muted"><?= h($application['email'] ?? '-') ?></div>
    <div class="text-muted">Job: <?= h($application['job_title'] ?? '-') ?></div>
  </div>

  <form method="post" action="<?= url('/dashboard/schedules') ?>" class="grid grid-2">
    <?= csrf_field(); ?>
    <input type="hidden" name="application_id" value="<?= (int)$application['id'] ?>">
    <input type="hidden" name="job_id" value="<?= (int)($application['job_id'] ?? 0) ?>">

    <div style="grid-column:1/-1">
      <label>Judul</label>
      <input class="input" name="title" value="<?= h('Interview - '.($application['name'] ?? '')) ?>" required>
    </div>

    <div>
      <label>Tanggal & Waktu</label>
      <input class="input" type="datetime-local" name="datetime" required>
    </div>

    <div>
      <label>Lokasi (opsional)</label>
      <input class="input" name="location" placeholder="Zoom/Meet/Office...">
    </div>

    <div style="grid-column:1/-1">
      <label>Catatan (opsional)</label>
      <textarea class="input" name="notes" rows="4"></textarea>
    </div>

    <div style="grid-column:1/-1">
      <button class="btn">Simpan Jadwal</button>
      <a class="btn secondary" href="<?= url('/dashboard/pipeline?job_id='.(int)($application['job_id'] ?? 0)) ?>">Batal</a>
    </div>
  </form>
<?php endif; ?>
