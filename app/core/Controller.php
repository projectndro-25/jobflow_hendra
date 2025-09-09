<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], ?string $layout = null): void
    {
        $this->render($template, $data, $layout);
    }

    protected function render(string $template, array $data = [], ?string $layout = null): void
    {
        $viewPath = BASE_PATH . '/app/views/' . ltrim($template, '/');
        if (!str_ends_with($viewPath, '.php')) {
            $viewPath .= '.php';
        }

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "<h2 style='padding:24px;background:#0f1220;color:#fff'>View not found: {$template}</h2>";
            return;
        }

        extract($data, EXTR_SKIP);

        if ($layout) {
            $layoutPath = BASE_PATH . '/app/views/layouts/' . ltrim($layout, '/');
            if (!str_ends_with($layoutPath, '.php')) {
                $layoutPath .= '.php';
            }

            // render view -> $content -> inject ke layout
            ob_start();
            require $viewPath;
            $content = ob_get_clean();

            require $layoutPath;
            return;
        }

        // render view tanpa layout
        require $viewPath;
    }

    protected function redirect(string $path): void
    {
        $base = rtrim($_ENV['APP_URL'] ?? '', '/');
        header('Location: ' . $base . $path);
        exit;
    }

    protected function json($payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
