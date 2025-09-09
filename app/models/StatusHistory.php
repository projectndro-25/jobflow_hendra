// ==============================
// jobflow/app/models/StatusHistory.php
// ==============================
<?php
namespace App\Models;
use App\Core\Model;

class StatusHistory extends Model {
  public function add(int $appId, ?string $from, string $to, ?int $by, ?string $notes=null){
    $this->query("INSERT INTO application_status_history (application_id,from_status,to_status,changed_by,notes) VALUES (?,?,?,?,?)",
      [$appId,$from,$to,$by,$notes]);
  }
  public function timeline(int $appId){
    return $this->query("SELECT * FROM application_status_history WHERE application_id=? ORDER BY changed_at",[$appId])->fetchAll();
  }
}
