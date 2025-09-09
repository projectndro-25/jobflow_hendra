<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Application;
use App\Models\Job;

final class PipelineController extends Controller
{
    /** Tampilkan board pipeline */
    public function board(): void
    {
        Auth::require();

        $jobModel = new Job();
        $appModel = new Application();

        $jobId   = (int) (Request::get('job_id') ?? 0);
        $jobsOpt = $jobModel->listOpenForSelect(); // id, title untuk dropdown
        $job     = $jobId ? $jobModel->findById($jobId) : null;

        // Siapkan grup status
        $grouped = [
            'applied'   => [],
            'screening' => [],
            'interview' => [],
            'offer'     => [],
            'hired'     => [],
            'rejected'  => [],
        ];

        if ($job) {
            foreach ($appModel->forJob($jobId) as $row) {
                $st = strtolower($row['status'] ?? 'applied');
                if (!isset($grouped[$st])) $st = 'applied';
                $grouped[$st][] = $row;
            }
        }

        $this->view('dashboard/pipeline/board', [
            'title'   => 'Pipeline',
            'jobId'   => $jobId,
            'job'     => $job,
            'jobsOpt' => $jobsOpt,
            'grouped' => $grouped,
        ], 'dashboard');
    }

    /** AJAX: update status aplikasi */
    public function updateStatus()
    {
        Auth::require();

        $id = (int) Request::input('application_id');
        $to = (string) Request::input('to_status');

        if (!$id || $to === '') {
            return Response::json(['ok' => false, 'msg' => 'Invalid'], 422);
        }

        // gunakan method Anda sendiri (sudah ada di kode Anda)
        (new Application())->changeStatus($id, $to, Auth::user()['id'] ?? null);

        return Response::json(['ok' => true, 'id' => $id, 'to' => $to]);
    }
}
