<?php
declare(strict_types=1);

// tampilkan semua error supaya jelas kalau ada masalah
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// pastikan autoload ada
$autoload = BASE_PATH . '/bootstrap/autoload.php';
if (!file_exists($autoload)) {
    die("Autoload not found: " . $autoload);
}
require $autoload;

use App\Core\Router;
use App\Core\Request;

// coba tangkap error routing
try {
    $router = new Router(require BASE_PATH . '/app/config/routes.php');
    $router->dispatch(Request::method(), Request::path());
} catch (Throwable $e) {
    echo "<h1>Application Error</h1>";
    echo "<pre>" . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "</pre>";
    exit;
}
