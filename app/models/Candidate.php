// ==============================
// jobflow/app/models/Candidate.php
// ==============================
<?php
namespace App\Models;
use App\Core\Model;

class Candidate extends Model {
  public function findByEmail(string $email){ return $this->query("SELECT * FROM candidates WHERE email=? LIMIT 1",[$email])->fetch(); }
  public function create(array $d){
    $this->query("INSERT INTO candidates (name,email,phone,portfolio_url,cv_url,notes) VALUES (?,?,?,?,?,?)",
      [$d['name'],$d['email'],$d['phone'],$d['portfolio_url'],$d['cv_url'],$d['notes']]);
    return (int)$this->db->lastInsertId();
  }
}
