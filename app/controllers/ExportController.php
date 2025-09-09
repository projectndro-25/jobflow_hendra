<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\CSV;
use App\Models\Application;

class ExportController extends Controller
{
    public function csv()
    {
        $rows = Application::exportRows();
        $filename = 'applications_' . date('Ymd_His') . '.csv';
        CSV::download($filename, $rows, ['ID','Job ID','Candidate ID','Status','Source','Applied At']);
    }
}
