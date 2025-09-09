// ==============================
// jobflow/app/core/Response.php
// ==============================
<?php
namespace App\Core;

final class Response {
  public static function json($data,int $code=200){ http_response_code($code); header('Content-Type:application/json'); echo json_encode($data); exit; }
}
