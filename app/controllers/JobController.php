<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Helpers\Slugger;
use App\Models\Job;
use App\Models\JobSkill;
use App\Models\Skill;

final class JobController extends Controller
{
    /* =======================
     *  PUBLIC PAGES
     * ======================= */

    /** Aliasing rute lama: /jobs (list publik) */
    public function publicIndex()
    {
        $jobModel = new Job();

        $page   = max(1, (int)Request::get('page', 1));
        $search = trim((string)Request::get('q', ''));
        $filters = [
            'location' => (string)Request::get('location', ''),
            'type'     => (string)Request::get('type', ''),
        ];

        $result = $jobModel->search([
            'search'   => $search,
            'location' => $filters['location'],
            'type'     => $filters['type'],
        ], $page, 10);

        $this->render('public/jobs', [
            'title'  => 'Lowongan',
            'result' => $result,
            'q'      => [
                'search'   => $search,
                'location' => $filters['location'],
                'type'     => $filters['type'],
            ],
        ], 'main');
    }

    /** Detail job publik */
    public function show($slug)
    {
        $jobModel = new Job();
        $job = $jobModel->findBySlug((string)$slug);
        if (!$job) {
            $this->render('errors/404', ['title' => 'Lowongan tidak ditemukan'], 'main');
            return;
        }
        $skills = $jobModel->skills((int)$job['id']);
        $this->render('public/job_detail', [
            'title'  => $job['title'] ?? 'Detail Lowongan',
            'job'    => $job,
            'skills' => $skills,
        ], 'main');
    }

    /* =======================
     *  DASHBOARD PAGES
     * ======================= */

    /** List Jobs (Dashboard) */
    public function index()
    {
        Auth::require(); // harus login

        $allowed = ['admin','hr','interviewer','viewer'];
        if (!Auth::hasRole($allowed)) {
            $this->render('errors/404', ['title' => 'Tidak diizinkan'], 'dashboard');
            return;
        }

        $q        = trim((string)Request::get('q', ''));
        $status   = (string)Request::get('status', '');
        $type     = (string)Request::get('type', '');
        $location = (string)Request::get('location', '');
        $page     = max(1, (int)Request::get('page', 1));
        $per      = 10;

        $jobModel = new Job();
        $list = $jobModel->listWithCounts([
            'q'        => $q,
            'status'   => $status,
            'type'     => $type,
            'location' => $location,
        ], $page, $per);

        $this->view('dashboard/jobs/list', [
            'title'  => 'Jobs — Dashboard',
            'rows'   => $list['items'],
            'meta'   => [
                'page'  => $page,
                'pages' => (int)ceil(($list['total'] ?: 0)/$per),
                'total' => $list['total'],
            ],
            'filters'=> compact('q','status','type','location'),
        ], 'dashboard');
    }

    /** Form create */
    public function create()
    {
        Auth::require();
        if (!Auth::hasRole(['admin','hr'])) {
            $this->render('errors/404', ['title'=>'Tidak diizinkan'], 'dashboard');
            return;
        }
        $skills = (new Skill())->all();
        $this->view('dashboard/jobs/create', [
            'title'  => 'Buat Job',
            'skills' => $skills,
        ], 'dashboard');
    }

    /** Store create */
    public function store()
    {
        Auth::require();
        if (!Auth::hasRole(['admin','hr'])) {
            $this->render('errors/404', ['title'=>'Tidak diizinkan'], 'dashboard');
            return;
        }

        $title   = trim((string)Request::post('title',''));
        $location= trim((string)Request::post('location',''));
        $type    = trim((string)Request::post('type',''));
        $status  = trim((string)Request::post('status','open')) ?: 'open';
        $desc    = (string)Request::post('description','');
        $deadline= (string)Request::post('deadline','');
        $salMin  = (int)Request::post('salary_min', 0);
        $salMax  = (int)Request::post('salary_max', 0);
        $slugRaw = trim((string)Request::post('slug',''));
        $skillIds= (array)(Request::post('skills', []) ?? []);

        $errors = [];
        if ($title==='')   $errors[]='Judul wajib diisi.';
        if ($location==='')$errors[]='Lokasi wajib diisi.';
        if ($type==='')    $errors[]='Tipe wajib dipilih.';
        if ($salMin && $salMax && $salMin>$salMax) $errors[]='Gaji min tidak boleh lebih besar dari gaji max.';
        if ($deadline!=='' && !strtotime($deadline)) $errors[]='Tanggal deadline tidak valid.';

        $jobModel = new Job();
        $slug = $jobModel->ensureUniqueSlug($slugRaw !== '' ? $slugRaw : Slugger::slug($title));

        if ($errors) {
            $skills = (new Skill())->all();
            $this->view('dashboard/jobs/create', [
                'title'=>'Buat Job',
                'errors'=>$errors,
                'old'   => $_POST,
                'skills'=> $skills,
            ], 'dashboard');
            return;
        }

        $jobId = $jobModel->create([
            'created_by' => Auth::user()['id'] ?? null,
            'title'      => $title,
            'slug'       => $slug,
            'description'=> $desc,
            'location'   => $location,
            'type'       => $type,
            'status'     => $status ?: 'open',
            'salary_min' => $salMin ?: null,
            'salary_max' => $salMax ?: null,
            'deadline'   => $deadline ?: null,
        ]);

        (new JobSkill())->setSkills($jobId, array_map('intval',$skillIds));

        // kembali ke list
        $this->redirect('/dashboard/jobs?ok=' . rawurlencode('Job berhasil dibuat.'));
    }

    /** Form edit */
    public function edit($id)
    {
        Auth::require();
        if (!Auth::hasRole(['admin','hr'])) {
            $this->render('errors/404', ['title'=>'Tidak diizinkan'], 'dashboard');
            return;
        }
        $jobModel = new Job();
        $job = $jobModel->findById((int)$id);
        if (!$job) {
            $this->render('errors/404', ['title'=>'Job tidak ditemukan'], 'dashboard');
            return;
        }
        $skills = (new Skill())->all();
        $selected = $jobModel->skillIds((int)$id);

        $this->view('dashboard/jobs/edit', [
            'title'    => 'Edit Job',
            'job'      => $job,
            'skills'   => $skills,
            'selected' => $selected,
        ], 'dashboard');
    }

    /** Update edit */
    public function update($id)
    {
        Auth::require();
        if (!Auth::hasRole(['admin','hr'])) {
            $this->render('errors/404', ['title'=>'Tidak diizinkan'], 'dashboard');
            return;
        }

        $id      = (int)$id;
        $title   = trim((string)Request::post('title',''));
        $location= trim((string)Request::post('location',''));
        $type    = trim((string)Request::post('type',''));
        $status  = trim((string)Request::post('status','open')) ?: 'open';
        $desc    = (string)Request::post('description','');
        $deadline= (string)Request::post('deadline','');
        $salMin  = (int)Request::post('salary_min', 0);
        $salMax  = (int)Request::post('salary_max', 0);
        $slugRaw = trim((string)Request::post('slug',''));
        $skillIds= (array)(Request::post('skills', []) ?? []);

        $errors = [];
        if ($title==='')   $errors[]='Judul wajib diisi.';
        if ($location==='')$errors[]='Lokasi wajib diisi.';
        if ($type==='')    $errors[]='Tipe wajib dipilih.';
        if ($salMin && $salMax && $salMin>$salMax) $errors[]='Gaji min tidak boleh lebih besar dari gaji max.';
        if ($deadline!=='' && !strtotime($deadline)) $errors[]='Tanggal deadline tidak valid.';

        $jobModel = new Job();
        $slug = $jobModel->ensureUniqueSlug(
            $slugRaw !== '' ? $slugRaw : Slugger::slug($title),
            $id
        );

        if ($errors) {
            $skills = (new Skill())->all();
            $selected = $jobModel->skillIds($id);
            $job = $jobModel->findById($id);
            $this->view('dashboard/jobs/edit', [
                'title'=>'Edit Job',
                'errors'=>$errors,
                'job'   => $job,
                'skills'=> $skills,
                'selected'=>$selected,
            ], 'dashboard');
            return;
        }

        $jobModel->update($id, [
            'title'      => $title,
            'slug'       => $slug,
            'description'=> $desc,
            'location'   => $location,
            'type'       => $type,
            'status'     => $status,
            'salary_min' => $salMin ?: null,
            'salary_max' => $salMax ?: null,
            'deadline'   => $deadline ?: null,
        ]);
        (new JobSkill())->setSkills($id, array_map('intval',$skillIds));

        $this->redirect('/dashboard/jobs?ok=' . rawurlencode('Job berhasil diupdate.'));
    }

    /** Toggle open/closed */
    public function toggle($id)
    {
        Auth::require();
        if (!Auth::hasRole(['admin','hr'])) {
            $this->render('errors/404', ['title'=>'Tidak diizinkan'], 'dashboard');
            return;
        }
        (new Job())->toggle((int)$id);
        $this->redirect('/dashboard/jobs?ok=' . rawurlencode('Status job diperbarui.'));
    }

    /** Delete (admin only) */
    public function destroy($id)
    {
        Auth::require();
        if (!Auth::hasRole(['admin'])) {
            $this->render('errors/404', ['title'=>'Tidak diizinkan'], 'dashboard');
            return;
        }
        (new Job())->delete((int)$id);
        $this->redirect('/dashboard/jobs?ok=' . rawurlencode('Job dihapus.'));
    }
}
