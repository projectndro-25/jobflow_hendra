<?php require BASE_PATH.'/app/views/partials/alerts.php'; ?>
<h1 class="text-2xl font-bold mb-4">Pipeline</h1>

<div class="mb-4" style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap">
  <a class="btn secondary" href="<?= url('/dashboard/jobs') ?>">← Kembali ke Jobs</a>

  <form method="get" action="<?= url('/dashboard/pipeline') ?>" style="display:flex;gap:.5rem;align-items:center">
    <select name="job_id" class="input" onchange="this.form.submit()">
      <option value="">— Pilih Job (open) —</option>
      <?php foreach ($jobsOpt as $opt): ?>
        <option value="<?= (int)$opt['id'] ?>" <?= (int)$opt['id']===$jobId ? 'selected':'' ?>>
          <?= h($opt['title']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <?php if (!empty($job)): ?>
      <span class="badge"><?= h($job['location'] ?? '-') ?> • <?= h($job['type'] ?? '-') ?></span>
      <span class="badge"><?= h($job['status'] ?? '-') ?></span>
    <?php endif; ?>
  </form>

  <!-- 🔎 Search kecil -->
  <?php if (!empty($job)): ?>
    <div style="margin-left:auto;display:flex;gap:.5rem;align-items:center">
      <input id="boardSearch" class="input" type="text" placeholder="Cari kandidat (nama / email)" style="min-width:260px">
    </div>
  <?php endif; ?>
</div>

<?php if (empty($job)): ?>
  <div class="card">
    <div class="text-muted">Pilih job terlebih dahulu untuk melihat pipeline.</div>
  </div>
<?php else: ?>
  <!-- Hidden CSRF untuk fetch di kanban.js -->
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(\App\Core\CSRF::token(), ENT_QUOTES) ?>">

  <?php
    $cols = [
      'applied'   => 'Applied',
      'screening' => 'Screening',
      'interview' => 'Interview',
      'offer'     => 'Offer',
      'hired'     => 'Hired',
      'rejected'  => 'Rejected'
    ];
  ?>

  <div id="kanban" class="grid" style="grid-template-columns:repeat(6,minmax(0,1fr));gap:1rem">
    <?php foreach ($cols as $key => $label): ?>
      <?php $count = isset($grouped[$key]) ? count($grouped[$key]) : 0; ?>
      <div class="card" data-col-key="<?= h($key) ?>">
        <div class="card-title" style="display:flex;justify-content:space-between;align-items:center">
          <span><?= h($label) ?></span>
          <span class="badge" style="opacity:.85"><span class="col-count"><?= (int)$count ?></span></span>
        </div>

        <div class="kanban-col" data-status="<?= h($key) ?>"
             style="min-height:240px;padding:6px;border-radius:12px">
          <?php foreach ($grouped[$key] as $app): ?>
            <div class="card candidate-card"
                 draggable="true"
                 data-id="<?= (int)$app['id'] ?>"
                 data-name="<?= h($app['name'] ?? '') ?>"
                 data-email="<?= h($app['email'] ?? '') ?>"
                 title="Double-click untuk membuka detail"
                 style="margin-bottom:8px;cursor:grab">
              <div style="display:flex;justify-content:space-between;gap:.5rem;align-items:flex-start">
                <div>
                  <div style="font-weight:700"><?= h($app['name'] ?: ($app['email'] ?? '')) ?></div>
                  <div class="text-muted" style="font-size:.9rem"><?= h($app['email'] ?? '') ?></div>
                  <div class="badge" style="margin-top:6px">
                    <?= date('d M Y', strtotime((string)($app['applied_at'] ?? $app['created_at'] ?? 'now'))) ?>
                  </div>
                </div>

                <!-- ⚡ Quick actions -->
                <div class="card-actions" style="display:flex;gap:.35rem">
                  <a class="btn secondary" title="Buat jadwal"
                     href="<?= url('/dashboard/schedules/create?application_id='.(int)$app['id']) ?>"
                     style="padding:.25rem .5rem">📅</a>
                  <a class="btn secondary" title="Kirim email"
                     href="<?= url('/dashboard/applications/'.(int)$app['id']).'#email' ?>"
                     style="padding:.25rem .5rem">✉️</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <script src="<?= url('/assets/js/kanban.js') ?>"></script>
  <script>
  (function(){
    const cols   = document.querySelectorAll('.kanban-col');
    let dragged  = null;

    // Drag handlers
    document.querySelectorAll('.kanban-col .candidate-card').forEach(card=>{
      card.addEventListener('dragstart', e=>{ dragged = card; card.style.opacity='.6'; });
      card.addEventListener('dragend',   e=>{ dragged = null; card.style.opacity='1'; });
      // 👇 Double-click -> buka detail
      card.addEventListener('dblclick',  e=>{
        const id = card.getAttribute('data-id');
        if (id) location.href = "<?= url('/dashboard/applications/') ?>" + id;
      });
    });

    cols.forEach(col=>{
      col.addEventListener('dragover',  e=>{ e.preventDefault(); col.style.outline='1px dashed rgba(109,93,252,.7)'; });
      col.addEventListener('dragleave', e=>{ col.style.outline='none'; });
      col.addEventListener('drop', async e=>{
        e.preventDefault();
        col.style.outline='none';
        if(!dragged) return;
        const id = dragged.getAttribute('data-id');
        const to = col.getAttribute('data-status');
        try{
          const res = await window.Pipeline.move(id,to);
          if(res && res.ok){
            col.appendChild(dragged);
            recalcCounts();
          }else{
            alert('Gagal memindahkan: ' + (res?.msg || 'unknown error'));
          }
        }catch{ alert('Network error'); }
      });
    });

    // 🔎 Search (nama/email)
    const search = document.getElementById('boardSearch');
    if (search){
      const applyFilter = () => {
        const q = search.value.trim().toLowerCase();
        document.querySelectorAll('.candidate-card').forEach(card=>{
          const text = (card.dataset.name+' '+card.dataset.email).toLowerCase();
          card.style.display = text.includes(q) ? '' : 'none';
        });
        recalcCounts();
      };
      search.addEventListener('input', applyFilter);
    }

    // Hitung ulang badge jumlah per kolom
    function recalcCounts(){
      document.querySelectorAll('[data-col-key]').forEach(wrap=>{
        const visible = wrap.querySelectorAll('.candidate-card:not([style*="display: none"])').length;
        const badge = wrap.querySelector('.col-count');
        if (badge) badge.textContent = visible;
      });
    }
  })();
  </script>
<?php endif; ?>
