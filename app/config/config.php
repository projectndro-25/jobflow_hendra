<?php
// Simple .env loader
$envPath = BASE_PATH . '/.env';
if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        $_ENV[$k] = trim($v, "\"'");
    }
}

$_ENV['APP_NAME']     = $_ENV['APP_NAME']     ?? 'JobFlow';

/**
 * ✅ Perbaikan: default-kan APP_URL ke /public
 * Supaya semua url('/...') menjadi http://localhost/jobflow/public/...
 * dan redirect('/apply/thanks') mengarah ke /jobflow/public/apply/thanks
 */
$_ENV['APP_URL']      = $_ENV['APP_URL']      ?? 'http://localhost/jobflow/public';

$_ENV['APP_TIMEZONE'] = $_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta';

$_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? '127.0.0.1';
$_ENV['DB_PORT'] = $_ENV['DB_PORT'] ?? '3306';
$_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'jobflow_db';
$_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
$_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';
