<?php
// app/views/errors/404.php
?>
<section class="container" style="max-width:900px;margin:40px auto;">
  <div class="card-cta" style="padding:24px;border-radius:16px;">
    <h2 style="margin:0 0 8px;font-weight:800;color:#a78bfa;">404 • Halaman tidak ditemukan</h2>
    <p style="margin:0 0 16px;color:var(--muted)">
      Maaf, halaman yang kamu cari tidak tersedia atau sudah dipindahkan.
    </p>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="<?= url('/public/') ?>" class="btn">Ke Beranda</a>
      <button onclick="history.back()" class="btn secondary" style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc">
        ← Kembali
      </button>
    </div>
  </div>
</section>
