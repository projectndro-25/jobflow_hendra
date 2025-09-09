// ==============================
// jobflow/app/views/layouts/auth.php
// ==============================
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf" content="<?= \App\Core\CSRF::token(); ?>">
  <title>Login · JobFlow</title>
  <link rel="stylesheet" href="/jobflow/public/assets/css/main.css">
</head>
<body>
  <main class="container" style="max-width:520px">
    <?php include $content_view; ?>
  </main>
</body>
</html>
