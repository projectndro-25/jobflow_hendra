// ==============================
// jobflow/app/models/CandidateSkill.php
// ==============================
<?php
namespace App\Models;
use App\Core\Model;

class CandidateSkill extends Model {
  public function setSkills(int $candidateId, array $skillIds){
    $this->query("DELETE FROM candidate_skills WHERE candidate_id=?",[$candidateId]);
    foreach($skillIds as $sid){ $this->query("INSERT INTO candidate_skills (candidate_id,skill_id) VALUES (?,?)",[$candidateId,$sid]); }
  }
}
