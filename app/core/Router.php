<?php
namespace App\Core;

/**
 * jobflow/app/core/Router.php
 * Router sederhana: mapping path -> Controller@method
 */
final class Router
{
    private array $routes;

    public function __construct(array $routes) { $this->routes = $routes; }

    public function dispatch(string $method, string $path): void
    {
        // Normalisasi ekstra: hilangkan trailing slash kecuali root
        $path = rtrim($path, '/') ?: '/';

        // Support ajax override
        if (isset($_GET['route']) && isset($_GET['__ajax'])) {
            $path = $_GET['route'];
        }

        foreach ($this->routes as $r) {
            [$m, $pattern, $action] = $r;
            if (strtoupper($m) !== strtoupper($method)) continue;

            // Ubah {param} menjadi named group yang menerima selain "/"
            $regex = preg_replace('#\{([a-z_][a-z0-9_]*)\}#i', '(?P<$1>[^/]+)', $pattern);

            // ✅ Perbaikan: root "/" jangan sampai jadi string kosong
            $regex = rtrim($regex, '/');
            if ($regex === '') { $regex = '/'; }
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $path, $matches)) {
                [$controller, $fn] = explode('@', $action);
                $class = "\\App\\Controllers\\{$controller}";
                if (!class_exists($class)) {
                    $this->abort(500, "Controller $class tidak ditemukan");
                }
                $obj    = new $class;
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                if (!method_exists($obj, $fn)) {
                    $this->abort(500, "Method {$class}::{$fn}() tidak ditemukan");
                }
                call_user_func_array([$obj, $fn], $params);
                return;
            }
        }
        $this->abort(404, 'Halaman tidak ditemukan');
    }

    public static function url(string $path): string
    {
        return rtrim($_ENV['APP_URL'] ?? '', '/') . $path;
    }

    private function abort(int $code, string $msg): void
    {
        http_response_code($code);
        echo "<h2 style='color:#fff;font-family:Inter;background:#0f1220;padding:24px;margin:0'>{$code} • {$msg}</h2>";
        exit;
    }
}
