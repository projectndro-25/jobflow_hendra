<?php
// View: Dashboard » Jobs List
// Variabel yang diharapkan dari controller:
// - $rows : array daftar jobs (tiap item: id, title, slug, type, location, status, deadline, applicants_count, updated_at)
// - $meta : ['page'=>int,'pages'=>int,'total'=>int] untuk pagination (opsional)
// - $q    : ['q','status','type','location'] (opsional) – fallback ke $_GET jika tidak ada

$qs = $q ?? $_GET ?? [];
$val = fn($k,$d='') => htmlspecialchars($qs[$k] ?? $d, ENT_QUOTES, 'UTF-8');
?>
<section class="container" style="padding-top:18px">

  <!-- Header + Create -->
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
    <h1 class="text-2xl" style="margin:0;font-weight:800">Jobs</h1>
    <a class="btn" href="<?= url('/dashboard/jobs/create') ?>">+ Buat Job</a>
  </div>

  <!-- Filter toolbar -->
  <form method="get" action="<?= url('/dashboard/jobs') ?>"
        style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:10px;margin-bottom:14px">
    <input class="input" type="text" name="q" placeholder="Cari judul / lokasi / slug" value="<?= $val('q') ?>">
    <select class="input" name="status" aria-label="Status">
      <?php $s = $qs['status'] ?? ''; ?>
      <option value="" <?= $s===''?'selected':'' ?>>Status</option>
      <option value="open"   <?= $s==='open'  ?'selected':'' ?>>open</option>
      <option value="closed" <?= $s==='closed'?'selected':'' ?>>closed</option>
    </select>
    <select class="input" name="type" aria-label="Tipe">
      <?php $t = $qs['type'] ?? ''; ?>
      <option value="" <?= $t===''?'selected':'' ?>>Tipe</option>
      <option value="internship" <?= $t==='internship'?'selected':'' ?>>internship</option>
      <option value="contract"   <?= $t==='contract'  ?'selected':'' ?>>contract</option>
      <option value="fulltime"   <?= $t==='fulltime'  ?'selected':'' ?>>fulltime</option>
    </select>
    <input class="input" type="text" name="location" placeholder="Lokasi" value="<?= $val('location') ?>">
    <button class="btn">Filter</button>
  </form>

  <!-- Table card -->
  <div class="card" style="overflow:auto">
    <table class="table" style="min-width:980px">
      <thead>
        <tr>
          <th style="width:28%">Title</th>
          <th style="width:10%">Type</th>
          <th style="width:14%">Location</th>
          <th style="width:10%">Status</th>
          <th style="width:12%">Deadline</th>
          <th style="width:10%">Applicants</th>
          <th style="width:12%">Updated</th>
          <th style="width:24%;text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $r): ?>
          <?php
            $id   = (int)($r['id'] ?? 0);
            $slug = htmlspecialchars($r['slug'] ?? '', ENT_QUOTES, 'UTF-8');
            $title= htmlspecialchars($r['title'] ?? '-', ENT_QUOTES, 'UTF-8');
            $type = htmlspecialchars($r['type'] ?? '-', ENT_QUOTES, 'UTF-8');
            $loc  = htmlspecialchars($r['location'] ?? '-', ENT_QUOTES, 'UTF-8');
            $st   = htmlspecialchars($r['status'] ?? '-', ENT_QUOTES, 'UTF-8');
            $dl   = !empty($r['deadline']) ? date('Y-m-d', strtotime((string)$r['deadline'])) : '-';
            $apps = (int)($r['applicants_count'] ?? 0);
            $upd  = !empty($r['updated_at']) ? date('Y-m-d H:i:s', strtotime((string)$r['updated_at'])) : '-';
          ?>
          <tr>
            <td>
              <div style="font-weight:700"><?= $title ?></div>
              <div class="text-muted" style="font-size:.85rem">/jobs/<?= $slug ?></div>
            </td>
            <td><span class="badge" style="text-transform:lowercase"><?= $type ?></span></td>
            <td><?= $loc ?></td>
            <td>
              <?php if ($st === 'open'): ?>
                <span class="badge" style="background:rgba(34,197,94,.18);color:#34d399">open</span>
              <?php else: ?>
                <span class="badge" style="background:rgba(239,68,68,.18);color:#f87171">closed</span>
              <?php endif; ?>
            </td>
            <td><?= $dl ?></td>
            <td><?= $apps ?></td>
            <td><?= $upd ?></td>
            <td style="text-align:right">
              <div style="display:inline-flex;gap:8px;flex-wrap:wrap;justify-content:flex-end">
                <a class="btn" href="<?= url('/dashboard/jobs/'.$id.'/edit') ?>">Edit</a>

                <!-- Toggle open/close -->
                <form method="post" action="<?= url('/dashboard/jobs/'.$id.'/toggle') ?>" style="display:inline">
                  <?= csrf_field() ?>
                  <button class="btn secondary" type="submit"><?= $st==='open'?'Close':'Open' ?></button>
                </form>

                <!-- Copy public link -->
                <button class="btn secondary copy-btn"
                        data-url="<?= url('/jobs/'.$slug) ?>"
                        type="button">Copy Link</button>

                <!-- Pipeline -->
                <a class="btn" href="<?= url('/dashboard/pipeline?job_id='.$id) ?>">Pipeline</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-muted" style="text-align:center;padding:28px">
            Belum ada lowongan. <a class="btn" href="<?= url('/dashboard/jobs/create') ?>" style="margin-left:8px">+ Buat Job</a>
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>

    <?php if (isset($meta)) { $meta = $meta; include BASE_PATH.'/app/views/partials/pagination.php'; } ?>
  </div>
</section>

<script>
// Copy to clipboard + feedback
document.addEventListener('click', (e)=>{
  const btn = e.target.closest('.copy-btn');
  if(!btn) return;
  const url = btn.getAttribute('data-url') || '';
  if(!url) return;
  navigator.clipboard.writeText(url).then(()=>{
    const old = btn.textContent;
    btn.textContent = 'Copied!';
    btn.disabled = true;
    setTimeout(()=>{ btn.textContent = old; btn.disabled = false; }, 1200);
  });
});
</script>
