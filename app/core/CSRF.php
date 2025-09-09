<?php
declare(strict_types=1);

namespace App\Core;

final class CSRF
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        $t = self::token();
        return '<input type="hidden" name="_csrf" value="'.htmlspecialchars($t, ENT_QUOTES).'">';
    }

    public static function validate(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postToken = $_POST['_csrf'] ?? '';
            return hash_equals($_SESSION['_csrf'] ?? '', $postToken);
        }
        return true;
    }
}
