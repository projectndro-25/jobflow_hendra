// ==============================
// jobflow/app/helpers/Slugger.php
// ==============================
<?php
namespace App\Helpers;

final class Slugger {
  public static function slug(string $t): string {
    $t = strtolower(trim($t));
    $t = preg_replace('/[^a-z0-9]+/','-',$t);
    return trim($t,'-');
  }
}
