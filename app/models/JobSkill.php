<?php
namespace App\Models;

use App\Core\Model;

class JobSkill extends Model
{
    public function setSkills(int $jobId, array $skillIds): void
    {
        $this->query("DELETE FROM job_skills WHERE job_id=?", [$jobId]);
        foreach ($skillIds as $sid) {
            $sid = (int)$sid;
            if ($sid>0) {
                $this->query("INSERT INTO job_skills (job_id,skill_id) VALUES (?,?)", [$jobId,$sid]);
            }
        }
    }
}
