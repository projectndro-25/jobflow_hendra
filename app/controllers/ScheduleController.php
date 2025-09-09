<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Application;
use App\Models\Schedule;

final class ScheduleController extends Controller
{
    /** Form buat jadwal dari pipeline */
    public function create(): void
    {
        Auth::require();

        $appId = (int)(Request::get('application_id') ?? 0);
        $application = $appId ? (new Application())->findById($appId) : null;

        $this->view('dashboard/schedules/create', [
            'title'       => 'Buat Jadwal',
            'application' => $application,
        ], 'dashboard');
    }

    /** Simpan jadwal */
    public function store(): void
    {
        Auth::require();

        $appId = (int)Request::post('application_id');
        $jobId = (int)(Request::post('job_id') ?? 0);

        $ok = (new Schedule())->create([
            'application_id' => $appId,
            'title'          => (string)Request::post('title'),
            'datetime'       => (string)Request::post('datetime'),
            'location'       => (string)Request::post('location'),
            'notes'          => (string)Request::post('notes'),
        ]);

        if ($ok) flash_set('ok', 'Jadwal berhasil dibuat.');
        else     flash_set('err', 'Gagal menyimpan jadwal.');

        $this->redirect('/dashboard/pipeline?job_id=' . $jobId);
    }
}
