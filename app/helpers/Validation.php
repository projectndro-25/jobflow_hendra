// ==============================
// jobflow/app/helpers/Validation.php
// ==============================
<?php
namespace App\Helpers;

final class Validation {
  public static function email($v){ return filter_var($v, FILTER_VALIDATE_EMAIL); }
  public static function url($v){ return !$v || filter_var($v, FILTER_VALIDATE_URL); }
  public static function required($v){ return isset($v) && $v!==''; }
}
