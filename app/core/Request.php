<?php
declare(strict_types=1);

namespace App\Core;

final class Request
{
    /** HTTP method (GET/POST/PUT/DELETE …) */
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Path tanpa query string dan TANPA base-folder (mis. /jobflow/public).
     * Target akhir untuk router adalah path seperti: /apply/thanks, /jobs, /jobs/{slug}, dst.
     */
    public static function path(): string
    {
        // 1) Ambil path murni (tanpa query)
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $uri = str_replace('\\', '/', $uri);

        // 2) Tentukan base dari lokasi index.php
        //    Contoh: SCRIPT_NAME = /jobflow/public/index.php => base = /jobflow/public
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $base = rtrim(dirname($scriptName), '/');

        // 3) Kandidat prefix yang perlu dihapus (paling spesifik dulu)
        $candidates = [];
        if ($base && $base !== '/') {
            $candidates[] = $base . '/index.php';
            $candidates[] = $base;
        }
        // Beberapa server bisa memasukkan pola ini:
        $candidates[] = '/index.php';
        $candidates[] = '/public';

        foreach ($candidates as $prefix) {
            if ($prefix !== '' && str_starts_with($uri, $prefix)) {
                $uri = substr($uri, strlen($prefix));
                break; // cukup hapus satu prefix yang match
            }
        }

        // 4) Normalisasi: pastikan diawali slash
        if ($uri === '' || $uri[0] !== '/') {
            $uri = '/' . ltrim($uri, '/');
        }

        // 5) Buang trailing slash kecuali root
        $uri = rtrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    /** Ambil nilai dari $_GET; jika $key null, kembalikan seluruh array */
    public static function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /** Ambil nilai dari $_POST; jika $key null, kembalikan seluruh array */
    public static function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /** Ambil dari POST dulu, kalau tidak ada cari di GET */
    public static function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /** Gabungan GET + POST (POST override GET) */
    public static function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    /** Cek apakah key ada di GET/POST */
    public static function has(string $key): bool
    {
        return array_key_exists($key, $_POST) || array_key_exists($key, $_GET);
    }
}
