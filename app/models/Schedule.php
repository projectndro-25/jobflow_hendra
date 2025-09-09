<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Schedule extends Model
{
    /** Simpan jadwal interview/meeting */
    public function create(array $d): bool
    {
        // Sesuaikan kolom dengan tabel schedules milikmu
        $st = $this->db->prepare(
            "INSERT INTO schedules (application_id, title, datetime, location, notes, created_at)
             VALUES (?,?,?,?,?, NOW())"
        );
        return $st->execute([
            $d['application_id'] ?? null,
            $d['title'] ?? '',
            $d['datetime'] ?? null,
            $d['location'] ?? null,
            $d['notes'] ?? null,
        ]);
    }
}
