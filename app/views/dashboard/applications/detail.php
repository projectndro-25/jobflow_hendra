<?php require BASE_PATH.'/app/views/partials/alerts.php'; ?>

<h1 class="text-2xl font-bold mb-4">Application Detail</h1>

<div class="mb-3" style="display:flex;gap:.5rem;flex-wrap:wrap">
  <!-- Kembali ke halaman sebelumnya -->
  <a class="btn secondary" href="javascript:history.back()">← Kembali</a>
  <?php if (!empty($app['job_id'])): ?>
    <a class="btn" href="<?= url('/dashboard/pipeline?job_id='.(int)$app['job_id']) ?>">Ke Pipeline</a>
  <?php endif; ?>
</div>

<div class="grid grid-2" style="gap:1rem">
  <!-- ===================== LEFT: Info Kandidat ===================== -->
  <div class="card">
    <div class="card-title">Informasi Kandidat</div>

    <div class="grid" style="grid-template-columns:160px 1fr; gap:.6rem">
      <div class="text-muted">Job</div>
      <div><?= h($app['job_title'] ?? $app['job'] ?? '-') ?></div>

      <div class="text-muted">Nama</div>
      <div><?= h($app['name'] ?? '-') ?></div>

      <div class="text-muted">Email</div>
      <div>
        <?php if(!empty($app['email'])): ?>
          <a href="mailto:<?= h($app['email']) ?>"><?= h($app['email']) ?></a>
        <?php else: ?>
          -
        <?php endif; ?>
      </div>

      <div class="text-muted">Phone</div>
      <div><?= h($app['phone'] ?? '-') ?></div>

      <div class="text-muted">Portfolio</div>
      <div>
        <?php if(!empty($app['portfolio_url'])): ?>
          <a target="_blank" rel="noopener" href="<?= h($app['portfolio_url']) ?>"><?= h($app['portfolio_url']) ?></a>
        <?php else: ?>
          -
        <?php endif; ?>
      </div>

      <div class="text-muted">Applied At</div>
      <div>
        <?= !empty($app['applied_at']) ? date('Y-m-d H:i:s', strtotime((string)$app['applied_at'])) :
             (!empty($app['created_at']) ? date('Y-m-d H:i:s', strtotime((string)$app['created_at'])) : '-') ?>
      </div>

      <div class="text-muted">Status</div>
      <div>
        <span class="badge"><?= h(strtolower($app['status'] ?? 'applied')) ?></span>
      </div>

      <div class="text-muted">Notes</div>
      <div><?= nl2br(h($app['notes'] ?? '-')) ?></div>
    </div>
  </div>

  <!-- ===================== RIGHT: Kirim Email ===================== -->
  <div class="card">
    <div class="card-title">Kirim Email</div>

    <form action="<?= url('/dashboard/applications/'.(int)$app['id'].'/email') ?>" method="post" id="emailForm">
      <!-- CSRF -->
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(\App\Core\CSRF::token(), ENT_QUOTES) ?>">
      <input type="hidden" name="application_id" value="<?= (int)($app['id'] ?? 0) ?>">

      <div class="grid" style="grid-template-columns:90px 1fr; gap:.6rem; align-items:center">
        <label class="text-muted" for="to_email">To</label>
        <input class="input" id="to_email" name="to_email"
               value="<?= h($app['email'] ?? '') ?>" placeholder="candidate@email.com" required>

        <label class="text-muted" for="subject">Subject</label>
        <input class="input" id="subject" name="subject"
               placeholder="Subject..."
               value="">

        <label class="text-muted" for="body">Body</label>
        <textarea class="input" id="body" name="body" rows="8"
                  placeholder="Tulis pesan..."></textarea>
      </div>

      <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:12px">
        <button class="btn" type="submit">Simpan Log</button>
        <a class="btn secondary" id="mailtoBtn" href="#" target="_blank" rel="noopener">Buka di Mail Client</a>
      </div>

      <div class="text-muted" style="font-size:.9rem;margin-top:.5rem">
        * “Buka di Mail Client” akan membuka aplikasi email default Anda dengan subject & body dari form ini.
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const to      = document.getElementById('to_email');
  const subject = document.getElementById('subject');
  const body    = document.getElementById('body');
  const mailBtn = document.getElementById('mailtoBtn');

  // Subject default: jika kosong, isi dengan judul job
  const jobTitle = "<?= htmlspecialchars(($app['job_title'] ?? $app['job'] ?? 'JobFlow'), ENT_QUOTES) ?>";
  if(!subject.value){ subject.placeholder = "Terkait " + jobTitle; }

  function buildMailto(){
    const toVal  = (to.value || '').trim();
    const sbjVal = (subject.value || subject.placeholder || '').trim();
    const bodyVal= (body.value || '').trim();

    const href = "mailto:" + encodeURIComponent(toVal)
               + "?subject=" + encodeURIComponent(sbjVal)
               + "&body=" + encodeURIComponent(bodyVal);

    mailBtn.href = href;
  }

  ['input','change'].forEach(evt=>{
    to.addEventListener(evt, buildMailto);
    subject.addEventListener(evt, buildMailto);
    body.addEventListener(evt, buildMailto);
  });

  // set awal
  buildMailto();
})();
</script>
