<?php
declare(strict_types=1);

namespace App\Core;

class Model   // <-- BUKAN final
{
    /** @var \PDO|null */
    protected static ?\PDO $pdo = null;

    /** @var \PDO */
    protected \PDO $db;

    /**
     * Dipanggil sekali dari app/config/database.php
     * untuk menyuntikkan koneksi PDO yang dishare ke semua model.
     */
    public static function setPDO(\PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    public function __construct()
    {
        if (!self::$pdo) {
            throw new \RuntimeException(
                "PDO belum diinisialisasi. Pastikan app/config/database.php diload " .
                "oleh bootstrap/autoload.php sebelum model digunakan."
            );
        }
        $this->db = self::$pdo;
    }

    /**
     * Helper query sederhana (prepared statement).
     */
    protected function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
