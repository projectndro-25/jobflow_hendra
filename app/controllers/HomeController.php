<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Job;

class HomeController extends Controller
{
    // Halaman publik Home
    public function index()
    {
        $jobModel = new Job();

        // Ambil 6 lowongan open terbaru (pakai search() yang sudah ada)
        $result = $jobModel->search([
            'search'   => '',
            'location' => '',
            'type'     => '',
        ], 1, 6);

        $latestJobs = $result['items'] ?? [];

        // Tentukan slug target untuk CTA "Lamar Sekarang"
        // Prioritas internship; jika tidak ada, pakai job terbaru pertama
        $applySlug = null;
        foreach ($latestJobs as $row) {
            if (isset($row['type']) && strtolower((string)$row['type']) === 'internship') {
                $applySlug = $row['slug'] ?? null;
                break;
            }
        }
        if (!$applySlug && !empty($latestJobs)) {
            $applySlug = $latestJobs[0]['slug'] ?? null;
        }

        $data = [
            'title'            => 'JobFlow — Home',
            'headline'         => 'Temukan Karier Impianmu',
            'latestJobs'       => $latestJobs,
            'latestApplySlug'  => $applySlug,  // bisa null -> view akan fallback ke /jobs
        ];
        $this->view('public/home', $data, 'main');
    }

    // Dashboard KPI ringkas (tidak diubah)
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard — JobFlow',
            'kpi'   => [
                'open_jobs'   => 0,
                'candidates'  => 0,
                'applications'=> 0,
            ],
        ];
        $this->view('dashboard/index', $data, 'dashboard');
    }
}
