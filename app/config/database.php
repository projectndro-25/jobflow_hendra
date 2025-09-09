<?php
declare(strict_types=1);

use App\Core\Model;

// Ambil dari .env / config.php (fallback nilai default)
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$name = $_ENV['DB_NAME'] ?? 'jobflow_db';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$name};charset={$charset}";

try {
    $pdo = new \PDO($dsn, $user, $pass, [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (\Throwable $e) {
    http_response_code(500);
    exit('DB connection failed: '.$e->getMessage());
}

// Penting: pasang PDO ke base Model (sesuai implementasi Model kamu)
Model::setPDO($pdo);
