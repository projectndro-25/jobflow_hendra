<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return (bool) self::user();
    }

    public static function require(): void
    {
        if (!self::check()) {
            // Hitung base /public dari lokasi index.php
            $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
            $base   = rtrim(dirname($script), '/');      // contoh: /jobflow/public
            $login  = $base . '/login';
            header('Location: ' . $login);
            exit;
        }
    }

    public static function login(string $email, string $password): bool
    {
        $u = (new User())->findByEmail($email);
        if (!$u) return false;

        $hash = $u['password_hash'] ?? '';

        if ($hash && password_verify($password, $hash)) {
            $_SESSION['user'] = $u;
            return true;
        }

        // Opsi awal: jika hash kosong—izinkan admin/hr demo
        if (!$hash && in_array($u['role'] ?? '', ['admin','hr'], true)) {
            $_SESSION['user'] = $u;
            return true;
        }

        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    public static function hasRole(array $roles): bool
    {
        $u = self::user();
        return $u && in_array($u['role'] ?? '', $roles, true);
    }
}
