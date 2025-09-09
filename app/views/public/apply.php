<?php
// jobflow/app/views/public/apply.php
// Variabel: $job, optional $errors, $old

$old = $old ?? [];
$backUrl = url('/apply'); // ← selalu kembali ke halaman daftar "Lamar Sekarang"
?>
<section class="container" style="max-width:900px;margin:40px auto;">

  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px">
    <div>
      <h2 style="margin:0 0 6px;font-weight:800;background:linear-gradient(90deg,#6d5dfc,#a78bfa);-webkit-background-clip:text;background-clip:text;color:transparent;">
        Lamar: <?= h($job['title'] ?? 'Posisi') ?>
      </h2>
      <?php if (!empty($job['location']) || !empty($job['type'])): ?>
        <div class="badge"><?= h($job['location'] ?? '-') ?> • <?= h($job['type'] ?? '-') ?></div>
      <?php endif; ?>
    </div>

    <!-- Jika ingin hanya 1 tombol kembali, boleh hapus anchor ini. -->
    <a href="<?= $backUrl ?>" class="btn secondary"
       style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc;white-space:nowrap">
      ← Kembali
    </a>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="card" style="margin:12px 0;background:#271b2f;border:1px solid #ef4444;color:#fca5a5;border-radius:12px;padding:12px">
      <strong>Periksa lagi:</strong>
      <ul style="margin:6px 0 0 18px">
        <?php foreach ($errors as $e): ?>
          <li><?= h($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php
    $val = function(string $key) use ($old) {
      return $old[$key] ?? ($_POST[$key] ?? '');
    };
  ?>

  <form method="post" action="" class="card-cta" style="padding:20px;border-radius:16px">
    <?= csrf_field(); ?>
    <input type="hidden" name="job_id" value="<?= (int)($job['id'] ?? 0) ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <div>
        <label style="display:block;margin:0 0 6px;color:var(--muted)">Nama</label>
        <input class="input" type="text" name="name" required placeholder="Nama lengkap"
               value="<?= h($val('name')) ?>" autofocus>
      </div>

      <div>
        <label style="display:block;margin:0 0 6px;color:var(--muted)">Email</label>
        <input class="input" type="email" name="email" required placeholder="email@contoh.com"
               value="<?= h($val('email')) ?>">
      </div>

      <div>
        <label style="display:block;margin:0 0 6px;color:var(--muted)">Phone</label>
        <input class="input" type="text" name="phone" placeholder="+62… / 08…"
               value="<?= h($val('phone')) ?>">
      </div>

      <div>
        <label style="display:block;margin:0 0 6px;color:var(--muted)">Portfolio / CV URL</label>
        <input class="input" type="url" name="portfolio_url" placeholder="https://"
               value="<?= h($val('portfolio_url')) ?>">
      </div>

      <div style="grid-column:1/3">
        <label style="display:block;margin:0 0 6px;color:var(--muted)">Catatan</label>
        <textarea class="input" name="notes" rows="4" placeholder="Pesan singkat atau ringkasan pengalaman…"><?= h($val('notes')) ?></textarea>
      </div>
    </div>

    <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
      <button class="btn" type="submit">Kirim Lamaran</button>
      <a href="<?= $backUrl ?>" class="btn secondary"
         style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc">← Kembali</a>
    </div>
  </form>
</section>
