<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Job extends Model
{
    /* ======= Public listing (sudah ada) ======= */
    public function openLatest(int $limit = 6): array
    {
        $limit = max(1, (int)$limit);
        return $this->query(
            "SELECT * FROM jobs WHERE status='open' ORDER BY created_at DESC LIMIT {$limit}"
        )->fetchAll();
    }

    public function search(array $q, int $page = 1, int $per = 12): array
    {
        $where = ["j.status='open'"];
        $p = [];

        if (!empty($q['search'])) {
            $where[] = "(j.title LIKE ? OR j.location LIKE ?)";
            $p[] = '%'.$q['search'].'%';
            $p[] = '%'.$q['search'].'%';
        }
        if (!empty($q['location'])) { $where[] = "j.location = ?"; $p[] = $q['location']; }
        if (!empty($q['type']))     { $where[] = "j.type = ?";     $p[] = $q['type']; }

        $whereSql = 'WHERE '.implode(' AND ', $where);
        $total = (int)($this->query("SELECT COUNT(*) c FROM jobs j {$whereSql}", $p)->fetch()['c'] ?? 0);

        $page = max(1,$page); $per = max(1,$per); $off = ($page-1)*$per;
        $items = $this->query("
            SELECT j.* FROM jobs j {$whereSql}
            ORDER BY j.created_at DESC
            LIMIT {$per} OFFSET {$off}", $p)->fetchAll();

        return ['total'=>$total,'items'=>$items,'page'=>$page,'per'=>$per];
    }

    /* ======= Dashboard listing with applicants count ======= */
    public function listWithCounts(array $filters, int $page, int $per): array
    {
        $w = []; $p = [];
        if ($filters['q'] ?? '') {
            $w[] = "(j.title LIKE :q OR j.location LIKE :q OR j.slug LIKE :q)";
        }
        if ($filters['status'] ?? '')   $w[] = "j.status = :status";
        if ($filters['type'] ?? '')     $w[] = "j.type = :type";
        if ($filters['location'] ?? '') $w[] = "j.location = :location";
        $where = $w ? 'WHERE '.implode(' AND ', $w) : '';

        $sqlCount = "SELECT COUNT(*) AS c FROM jobs j {$where}";
        $stmt = $this->db->prepare($sqlCount);
        if ($filters['q'] ?? '')        $stmt->bindValue(':q', '%'.$filters['q'].'%');
        if ($filters['status'] ?? '')   $stmt->bindValue(':status', $filters['status']);
        if ($filters['type'] ?? '')     $stmt->bindValue(':type', $filters['type']);
        if ($filters['location'] ?? '') $stmt->bindValue(':location', $filters['location']);
        $stmt->execute();
        $total = (int)($stmt->fetch()['c'] ?? 0);

        $page = max(1,$page); $per = max(1,$per); $off = ($page-1)*$per;

        $sql = "
        SELECT j.*,
          (SELECT COUNT(*) FROM applications a WHERE a.job_id=j.id) AS applicants_count
        FROM jobs j
        {$where}
        ORDER BY j.updated_at DESC
        LIMIT {$per} OFFSET {$off}";
        $stmt2 = $this->db->prepare($sql);
        if ($filters['q'] ?? '')        $stmt2->bindValue(':q', '%'.$filters['q'].'%');
        if ($filters['status'] ?? '')   $stmt2->bindValue(':status', $filters['status']);
        if ($filters['type'] ?? '')     $stmt2->bindValue(':type', $filters['type']);
        if ($filters['location'] ?? '') $stmt2->bindValue(':location', $filters['location']);
        $stmt2->execute();
        $items = $stmt2->fetchAll();

        return compact('total','items');
    }

    public function findPublicBySlug(string $slug): ?array
    {
        $row = $this->query("SELECT * FROM jobs WHERE slug=? AND status='open' LIMIT 1", [$slug])->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $row = $this->query("SELECT * FROM jobs WHERE id=? LIMIT 1", [$id])->fetch();
        return $row ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->query("SELECT * FROM jobs WHERE slug=? LIMIT 1", [$slug])->fetch();
        return $row ?: null;
    }

    public function skills(int $jobId): array
    {
        return $this->query("
            SELECT s.* FROM job_skills js
            JOIN skills s ON s.id=js.skill_id
            WHERE js.job_id=?", [$jobId])->fetchAll();
    }

    public function skillIds(int $jobId): array
    {
        $rows = $this->query("SELECT skill_id FROM job_skills WHERE job_id=?",[$jobId])->fetchAll();
        return array_map(fn($r)=> (int)$r['skill_id'], $rows);
    }

    public function ensureUniqueSlug(string $slug, ?int $excludeId=null): string
    {
        $slug = trim($slug) ?: 'job';
        $base = $slug; $i=2;
        while (true) {
            $params = [$slug];
            $sql = "SELECT COUNT(*) c FROM jobs WHERE slug=?";
            if ($excludeId) { $sql .= " AND id<>?"; $params[]=$excludeId; }
            $c = (int)($this->query($sql, $params)->fetch()['c'] ?? 0);
            if ($c===0) return $slug;
            $slug = $base.'-'.$i++;
        }
    }

    public function create(array $d): int
    {
        $this->query("
            INSERT INTO jobs (created_by,title,slug,description,location,type,status,salary_min,salary_max,deadline,created_at,updated_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,NOW(),NOW())", [
            $d['created_by'],$d['title'],$d['slug'],$d['description'],$d['location'],
            $d['type'],$d['status'],$d['salary_min'],$d['salary_max'],$d['deadline']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $this->query("
            UPDATE jobs
               SET title=?, slug=?, description=?, location=?, type=?, status=?,
                   salary_min=?, salary_max=?, deadline=?, updated_at=NOW()
             WHERE id=?", [
            $d['title'],$d['slug'],$d['description'],$d['location'],$d['type'],$d['status'],
            $d['salary_min'],$d['salary_max'],$d['deadline'],$id
        ]);
    }

    public function toggle(int $id): void
    {
        $row = $this->findById($id);
        if (!$row) return;
        $next = (strtolower($row['status'])==='open') ? 'closed' : 'open';
        $this->query("UPDATE jobs SET status=?, updated_at=NOW() WHERE id=?", [$next,$id]);
    }

    public function delete(int $id): void
    {
        $this->query("DELETE FROM job_skills WHERE job_id=?", [$id]);
        $this->query("DELETE FROM jobs WHERE id=?", [$id]);
    }

    /** ✅ Tambahan: daftar job OPEN untuk dropdown Pipeline */
    public function listOpenForSelect(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, title FROM jobs WHERE status='open' ORDER BY updated_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
