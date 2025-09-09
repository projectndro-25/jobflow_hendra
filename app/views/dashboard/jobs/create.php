<h1 class="text-2xl font-bold mb-4">Buat Job</h1>

<?php if(!empty($errors)): ?>
  <div class="card" style="border-left:6px solid #ef4444;margin-bottom:12px">
    <strong>Periksa lagi:</strong>
    <ul style="margin:6px 0 0 18px"><?php foreach($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form class="grid grid-2" action="<?= url('/dashboard/jobs') ?>" method="post">
  <?= csrf_field() ?>
  <div>
    <label>Judul</label>
    <input class="input" name="title" required value="<?= h($old['title']??'') ?>">
  </div>
  <div>
    <label>Slug (opsional)</label>
    <input class="input" name="slug" placeholder="otomatis dari judul" value="<?= h($old['slug']??'') ?>">
  </div>

  <div>
    <label>Lokasi</label>
    <input class="input" name="location" required value="<?= h($old['location']??'') ?>">
  </div>
  <div>
    <label>Tipe</label>
    <select class="input" name="type" required>
      <?php foreach(['fulltime','contract','internship'] as $t): ?>
        <option value="<?= $t ?>" <?= (($old['type']??'')===$t)?'selected':'' ?>><?= $t ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label>Status</label>
    <select class="input" name="status">
      <?php foreach(['open','closed'] as $s): ?>
        <option value="<?= $s ?>" <?= (($old['status']??'open')===$s)?'selected':'' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label>Deadline</label>
    <input class="input" type="date" name="deadline" value="<?= h($old['deadline']??'') ?>">
  </div>

  <div class="grid grid-2" style="grid-column:1/-1">
    <div><label>Gaji Min</label><input class="input" type="number" name="salary_min" value="<?= h($old['salary_min']??'') ?>"></div>
    <div><label>Gaji Max</label><input class="input" type="number" name="salary_max" value="<?= h($old['salary_max']??'') ?>"></div>
  </div>

  <div style="grid-column:1/-1">
    <label>Deskripsi</label>
    <textarea class="input" name="description" rows="6"><?= h($old['description']??'') ?></textarea>
  </div>

  <div style="grid-column:1/-1">
    <label>Skills</label>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
      <?php foreach(($skills??[]) as $s): ?>
        <label class="badge" style="cursor:pointer">
          <input type="checkbox" name="skills[]" value="<?= (int)$s['id'] ?>" style="margin-right:6px">
          <?= h($s['name']) ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <div style="grid-column:1/-1"><button class="btn">Simpan</button></div>
</form>
