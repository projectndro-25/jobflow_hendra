<?php
// Layout dashboard TANPA sidebar lama.
// View halaman (mis. app/views/dashboard/index.php) yang menggambar sidebar modern.
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title ?? 'Dashboard — JobFlow') ?></title>
  <!-- ✅ pakai path robust (tanpa /public) -->
  <link rel="stylesheet" href="<?= url('/assets/css/main.css') ?>">
</head>
<body class="bg-slate-50">
  <?php require BASE_PATH . '/app/views/partials/navbar.php'; ?>

  <main class="min-h-screen">
    <?= $content ?>
  </main>

  <!-- ✅ pakai path robust (tanpa /public) -->
  <script src="<?= url('/assets/js/main.js') ?>"></script>
</body>
</html>
