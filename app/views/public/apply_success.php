<?php
// jobflow/app/views/public/apply_success.php
// Variabel: $job_title
?>
<section class="container" style="max-width:900px;margin:40px auto;">
  <div class="card-cta" style="padding:24px;border-radius:16px;">
    <h2 style="margin:0 0 10px;font-weight:800;background:linear-gradient(90deg,#22d3ee,#a78bfa);
               -webkit-background-clip:text;background-clip:text;color:transparent;">
      Lamaran berhasil dikirim ✅
    </h2>
    <p style="margin:0 0 12px;">Terima kasih! Lamaran kamu untuk <strong><?= h($job_title ?? 'Job') ?></strong> sudah kami terima.</p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px">
      <!-- ✅ tanpa /public -->
      <a href="<?= url('/jobs') ?>" class="btn">Lihat Lowongan Lain</a>
      <a href="<?= url('/') ?>" class="btn secondary" style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc;">Kembali ke Beranda</a>
    </div>
  </div>
</section>
