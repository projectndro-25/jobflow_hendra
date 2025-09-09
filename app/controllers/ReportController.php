<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Application;

class ReportController extends Controller
{
    public function index()
    {
        $metrics = [
            'by_status' => Application::aggregateByStatus(),
            'by_job'    => Application::aggregateByJob(10),
        ];
        $this->view('dashboard/reports/index', [
            'title'=>'Reports',
            'metrics'=>$metrics
        ], 'dashboard');
    }
}
