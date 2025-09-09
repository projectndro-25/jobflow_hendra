<?php
// $content diisi oleh Controller::render()
// helper url() & h() tersedia dari app/helpers/functions.php
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title ?? 'JobFlow') ?></title>

  <!-- Styles -->
  <!-- ✅ tanpa /public -->
  <link rel="stylesheet" href="<?= url('/assets/css/main.css') ?>">
  <!-- <link rel="stylesheet" href="<?= url('/assets/css/tailwind.css') ?>"> -->
</head>
<body>
  <?php require BASE_PATH . '/app/views/partials/navbar.php'; ?>

  <main class="container">
    <?= $content ?? '' ?>
  </main>

  <?php require BASE_PATH . '/app/views/partials/footer.php'; ?>

  <!-- Scripts -->
  <!-- ✅ tanpa /public -->
  <script src="<?= url('/assets/js/main.js') ?>"></script>
</body>
</html>
