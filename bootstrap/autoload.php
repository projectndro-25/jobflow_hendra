<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base = BASE_PATH . '/app/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $rel = substr($class, strlen($prefix));
    $file = $base . str_replace('\\', '/', $rel) . '.php';
    if (is_file($file)) require $file;
});

// helpers & config
require BASE_PATH . '/app/helpers/functions.php';
require BASE_PATH . '/app/config/config.php';

// DB harus sebelum model dipakai!
require BASE_PATH . '/app/config/database.php';

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
