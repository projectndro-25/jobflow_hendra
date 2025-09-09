<?php
// jobflow/app/views/public/job_detail.php

// Tentukan URL kembali tergantung asal
$from     = strtolower($_GET['from'] ?? '');
$backUrl  = ($from === 'apply') ? url('/apply') : url('/jobs');

// Ambil info status & deadline (untuk ditampilkan sebagai informasi)
$status   = $job['status'] ?? null;
$deadline = $job['deadline'] ?? ($job['deadline_at'] ?? ($job['expires_at'] ?? null));
?>
<a href="<?= $backUrl ?>" class="btn secondary" style="border:1px solid #6d5dfc;background:transparent;color:#6d5dfc">← Kembali</a>

<h2><?= h($job['title']) ?></h2>
<div class="badge"><?= h($job['location']) ?> • <?= h($job['type']) ?></div>

<?php if (!empty($job['summary'])): ?>
  <div style="margin-top:14px">
    <strong>Ringkasan</strong>
    <div><?= nl2br(h($job['summary'])) ?></div>
  </div>
<?php endif; ?>

<?php if (!empty($job['type']) || !empty($job['location']) || !empty($status) || !empty($deadline)): ?>
  <div style="margin-top:10px">
    <?php if (!empty($job['type'])): ?>
      <div><strong>Tipe</strong><br><?= h($job['type']) ?></div>
    <?php endif; ?>
    <?php if (!empty($job['location'])): ?>
      <div style="margin-top:6px"><strong>Lokasi</strong><br><?= h($job['location']) ?></div>
    <?php endif; ?>
    <?php if (!empty($status)): ?>
      <div style="margin-top:6px"><strong>Status</strong><br><?= h($status) ?></div>
    <?php endif; ?>
    <?php if (!empty($deadline)): ?>
      <div style="margin-top:6px"><strong>Deadline</strong><br><?= h(date('d M Y', strtotime((string)$deadline))) ?></div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (!empty($job['description'])): ?>
  <div style="margin-top:14px">
    <strong>Deskripsi Pekerjaan</strong>
    <p style="margin-top:6px;white-space:pre-wrap"><?= nl2br(h($job['description'])) ?></p>
  </div>
<?php endif; ?>

<?php if (!empty($skills) && is_array($skills)): ?>
  <div style="margin-top:14px">
    <strong>Persyaratan</strong>
    <ul style="margin:6px 0 0 18px">
      <?php foreach ($skills as $s): ?>
        <li><?= h($s['name'] ?? '') ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
