<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    /**
     * Ambil user berdasarkan email (case-insensitive).
     * Tabel: users (id, name, email, role, password_hash, created_at, updated_at)
     */
    public function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));
        $row = $this->query(
            "SELECT * FROM users WHERE LOWER(email) = ? LIMIT 1",
            [$email]
        )->fetch();

        return $row ?: null;
    }
}
