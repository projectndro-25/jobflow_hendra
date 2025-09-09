<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class EmailLog extends Model
{
    public function log(int $applicationId, string $to, string $subject, string $body): void
    {
        try {
            $this->query(
                "INSERT INTO email_logs (application_id, to_email, subject, body, created_at)
                 VALUES (?,?,?, ?, NOW())",
                [$applicationId, $to, $subject, $body]
            );
        } catch (\Throwable $e) {
            // aman diabaikan jika struktur berbeda
        }
    }
}
