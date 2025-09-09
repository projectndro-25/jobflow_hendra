<h1 class="text-2xl font-bold mb-4">Edit Job</h1>

<?php if(!empty($errors)): ?>
  <div class="card" style="border-left:6px solid #ef4444;margin-bottom:12px">
    <strong>Periksa lagi:</strong>
    <ul style="margin:6px 0 0 18px"><?php foreach($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form class="grid grid-2" action="<?= url('/dashboard/jobs/'.(int)($job['id']??0)) ?>" method="post">
  <?= csrf_field() ?>
  <div><label>Judul</label><input class="input" name="title" value="<?= h($job['title']??'') ?>" required></div>
  <div><label>Slug</label><input class="input" name="slug" value="<?= h($job['slug']??'') ?>"></div>

  <div><label>Lokasi</label><input class="input" name="location" value="<?= h($job['location']??'') ?>" required></div>
  <div>
    <label>Tipe</label>
    <select class="input" name="type" required>
      <?php foreach(['fulltime','contract','internship'] as $t): ?>
        <option value="<?= $t ?>" <?= (($job['type']??'')===$t)?'selected':'' ?>><?= $t ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label>Status</label>
    <select class="input" name="status">
      <?php foreach(['open','closed'] as $s): ?>
        <option value="<?= $s ?>" <?= (($job['status']??'open')===$s)?'selected':'' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div><label>Deadline</label><input class="input" type="date" name="deadline" value="<?= h($job['deadline']??'') ?>"></div>

  <div class="grid grid-2" style="grid-column:1/-1">
    <div><label>Gaji Min</label><input class="input" type="number" name="salary_min" value="<?= h($job['salary_min']??'') ?>"></div>
    <div><label>Gaji Max</label><input class="input" type="number" name="salary_max" value="<?= h($job['salary_max']??'') ?>"></div>
  </div>

  <div style="grid-column:1/-1">
    <label>Deskripsi</label>
    <textarea class="input" name="description" rows="6"><?= h($job['description']??'') ?></textarea>
  </div>

  <div style="grid-column:1/-1">
    <label>Skills</label>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
      <?php $sel = array_flip($selected ?? []); foreach(($skills??[]) as $s): $checked = isset($sel[$s['id']]); ?>
        <label class="badge" style="cursor:pointer">
          <input type="checkbox" name="skills[]" value="<?= (int)$s['id'] ?>" <?= $checked?'checked':'' ?> style="margin-right:6px">
          <?= h($s['name']) ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <div style="grid-column:1/-1;display:flex;gap:.5rem;flex-wrap:wrap">
    <button class="btn">Update</button>
    <a class="btn secondary" href="<?= url('/dashboard/jobs') ?>">Kembali</a>
    <a class="btn secondary" href="<?= url('/dashboard/pipeline?job_id='.(int)$job['id']) ?>">Lihat Pipeline</a>
    <button type="button" class="btn secondary" onclick="copyLink('<?= h(url('/jobs/'.rawurlencode($job['slug']??''))) ?>')">Copy Public Link</button>
  </div>
</form>

<script>
function copyLink(txt){ navigator.clipboard.writeText(txt).then(()=>alert('Public link disalin: '+txt)); }
</script>
