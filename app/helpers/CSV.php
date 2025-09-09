// ==============================
// jobflow/app/helpers/CSV.php
// ==============================
<?php
namespace App\Helpers;

final class CSV {
  public static function download(string $name, array $header, array $rows){
    header('Content-Type:text/csv');
    header('Content-Disposition: attachment; filename="'.$name.'"');
    $f = fopen('php://output','w');
    fputcsv($f,$header);
    foreach($rows as $r) fputcsv($f,$r);
    fclose($f); exit;
  }
}
