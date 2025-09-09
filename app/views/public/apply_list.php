<?php
// jobflow/app/views/public/apply_list.php
// Variabel yang diharapkan: $result (items = daftar job), $q (opsional)
$homeUrl = url('/');
?>
<h2 class="mb-3">Lamar Sekarang</h2>

<a href="<?= $homeUrl ?>" class="btn secondary"
   style="margin:0 0 12px;border:1px solid #6d5dfc;background:transparent;color:#6d5dfc">
  ← Kembali
</a>

<form method="get" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:10px;margin:10px 0 16px">
  <input class="input" type="text" name="q" value="<?= h($q['search'] ?? '') ?>" placeholder="Cari judul / lokasi">
  <input class="input" type="text" name="location" value="<?= h($q['location'] ?? '') ?>" placeholder="Lokasi">
  <select class="input" name="type">
    <option value="">Tipe</option>
    <?php foreach (['internship','contract','fulltime'] as $t): ?>
      <option value="<?= h($t) ?>" <?= (($q['type'] ?? '') === $t) ? 'selected' : '' ?>><?= h($t) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn">Filter</button>
</form>

<?php if (empty($result['items'])): ?>
  <p>Tidak ada lowongan yang sesuai.</p>
<?php else: ?>
  <div class="grid grid-3">
    <?php foreach ($result['items'] as $j): ?>
      <div class="card">
        <strong><?= h($j['title']) ?></strong>
        <div class="badge" style="margin:8px 0">
          <?= h($j['location']) ?> • <?= h($j['type']) ?>
        </div>

        <!-- Detail: tambahkan ?from=apply supaya tombol Kembali di detail balik ke /apply -->
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <a class="btn secondary"
             style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc"
             href="<?= url('/jobs/' . urlencode($j['slug'])) ?>?from=apply">Detail</a>

          <a class="btn" href="<?= url('/apply/' . urlencode($j['slug'])) ?>">Apply</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
