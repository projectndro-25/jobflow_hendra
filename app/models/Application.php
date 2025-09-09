<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Application extends Model
{
    public function createPublic(array $data): int
    {
        $this->query(
            "INSERT INTO applications (job_id, name, email, phone, portfolio_url, notes, created_at)
             VALUES (?,?,?,?,?,?,NOW())",
            [
                $data['job_id'] ?? null,
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['portfolio_url'] ?? '',
                $data['notes'] ?? '',
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function forJob(int $jobId): array
    {
        return $this->query(
            "SELECT * FROM applications WHERE job_id = ? ORDER BY created_at DESC",
            [$jobId]
        )->fetchAll();
    }

    /** ✅ Detail 1 application + judul job (untuk halaman detail & jadwal) */
    public function findById(int $id): ?array
    {
        $row = $this->query(
            "SELECT a.*, j.title AS job_title
               FROM applications a
          LEFT JOIN jobs j ON j.id = a.job_id
              WHERE a.id = ?
              LIMIT 1",
            [$id]
        )->fetch();
        return $row ?: null;
    }

    /** ✅ Update status + simpan history */
    public function changeStatus(int $id, string $to, ?int $userId = null): void
    {
        $to = strtolower(trim($to));
        $valid = ['applied','screening','interview','offer','hired','rejected'];
        if (!in_array($to, $valid, true)) { $to = 'applied'; }

        $row  = $this->query("SELECT status FROM applications WHERE id=?", [$id])->fetch();
        $from = $row['status'] ?? null;

        $this->query("UPDATE applications SET status=?, updated_at=NOW() WHERE id=?", [$to, $id]);

        // simpan history jika tabelnya ada
        try {
            $this->query(
                "INSERT INTO application_status_history (application_id, from_status, to_status, changed_by, changed_at)
                 VALUES (?,?,?,?,NOW())",
                [$id, $from, $to, $userId]
            );
        } catch (\Throwable $e) {
            // aman diabaikan jika tabel belum ada
        }
    }
}
