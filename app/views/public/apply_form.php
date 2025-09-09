<?php
// app/views/public/apply_form.php
// Variabel tersedia: $job
$action = url('/public/apply/' . urlencode($job['slug']));
?>
<section class="container" style="max-width:900px;margin:40px auto;">
  <h1 style="margin:0 0 6px;font-weight:800;background:linear-gradient(90deg,#6d5dfc,#a78bfa);-webkit-background-clip:text;background-clip:text;color:transparent;">
    Lamar: <?= h($job['title'] ?? '') ?>
  </h1>
  <div class="badge" style="margin:6px 0 18px">
    <?= h($job['location'] ?? '-') ?> • <?= h($job['type'] ?? '-') ?>
  </div>

  <form method="post" action="<?= $action ?>" style="display:grid;gap:12px">
    <div>
      <label style="display:block;margin-bottom:6px;color:var(--muted)">Nama</label>
      <input class="input" type="text" name="name" required placeholder="Nama lengkap">
    </div>

    <div>
      <label style="display:block;margin-bottom:6px;color:var(--muted)">Email</label>
      <input class="input" type="email" name="email" required placeholder="email@contoh.com">
    </div>

    <div>
      <label style="display:block;margin-bottom:6px;color:var(--muted)">Link CV (Google Drive/URL)</label>
      <input class="input" type="url" name="cv" placeholder="https://drive.google.com/…">
    </div>

    <div style="display:flex;gap:10px;margin-top:6px">
      <button class="btn" type="submit">Kirim Lamaran</button>
      <button type="button" onclick="history.back()" class="btn secondary" style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc">
        ← Kembali
      </button>
    </div>
  </form>
</section>
