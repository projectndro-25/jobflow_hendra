<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Job;
use App\Models\Application;
use App\Models\EmailLog;

final class ApplicationController extends Controller
{
    /** === PUBLIK: GET /apply => daftar job dengan tombol Apply per item === */
    public function listPublic()
    {
        $jobModel = new Job();

        $page    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $search  = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
        $filters = [
            'location' => $_GET['location'] ?? '',
            'type'     => $_GET['type'] ?? '',
            'skills'   => $_GET['skills'] ?? '',
        ];

        $result = $jobModel->search([
            'search'   => $search,
            'location' => $filters['location'],
            'type'     => $filters['type'],
        ], $page, 10);

        $this->render('public/apply_list', [
            'title'  => 'Lamar Sekarang',
            'result' => $result,
            'q'      => [
                'search'   => $search,
                'location' => $filters['location'],
                'type'     => $filters['type'],
            ],
        ], 'main');
    }

    /** === PUBLIK: GET /apply/{slug} */
    public function createPublic(string $slug)
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $jobModel = new Job();
        $job = $jobModel->findPublicBySlug($slug);
        if (!$job) {
            $this->render('errors/404', ['title' => 'Lowongan tidak ditemukan'], 'main');
            return;
        }

        $this->render('public/apply', [
            'title' => 'Lamar — ' . ($job['title'] ?? 'Job'),
            'job'   => $job,
        ], 'main');
    }

    /** === PUBLIK: POST /apply/{slug} */
    public function storePublic(string $slug)
    {
        $jobModel = new Job();

        $jobId = (int) Request::input('job_id', 0);
        $job   = $jobId > 0 ? $jobModel->findById($jobId) : null;
        if (!$job) {
            $job = $jobModel->findPublicBySlug($slug) ?? $jobModel->findBySlug($slug);
        }
        if (!$job) {
            $this->render('errors/404', ['title' => 'Lowongan tidak ditemukan'], 'main');
            return;
        }

        $name  = trim((string) Request::input('name'));
        $email = trim((string) Request::input('email'));
        $phone = trim((string) Request::input('phone'));
        $url   = trim((string) Request::input('portfolio_url'));
        $notes = trim((string) Request::input('notes'));

        $errors = [];
        if ($name === '')  $errors[] = 'Nama wajib diisi.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
        if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL))      $errors[] = 'URL portfolio tidak valid.';

        if ($errors) {
            $this->render('public/apply', [
                'title'  => 'Lamar — ' . ($job['title'] ?? 'Job'),
                'job'    => $job,
                'errors' => $errors,
                'old'    => [
                    'name' => $name, 'email' => $email, 'phone' => $phone,
                    'portfolio_url' => $url, 'notes' => $notes,
                ],
            ], 'main');
            return;
        }

        if (class_exists(Application::class) && method_exists(Application::class, 'createPublic')) {
            $appModel = new Application();
            $appModel->createPublic([
                'job_id'        => (int)$job['id'],
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'portfolio_url' => $url,
                'notes'         => $notes,
            ]);
        }

        $this->redirect('/apply/thanks?title=' . rawurlencode($job['title']));
    }

    /** === PUBLIK: GET /apply/thanks */
    public function success()
    {
        $title = (string)($_GET['title'] ?? 'Lamaran terkirim');
        $this->render('public/apply_success', [
            'title'     => 'Berhasil • ' . $title,
            'job_title' => $title,
        ], 'main');
    }

    /* ===========================
     * ====== DASHBOARD ==========
     * ===========================
     */

    /** GET /dashboard/applications */
    public function index()
    {
        Auth::require();
        // sementara arahkan ke pipeline
        $this->redirect('/dashboard/pipeline');
    }

    /** GET /dashboard/applications/{id} — detail lamaran */
    public function show($id)
    {
        Auth::require();

        $app = (new Application())->findById((int)$id);
        if (!$app) {
            $this->render('errors/404', ['title' => 'Application tidak ditemukan'], 'dashboard');
            return;
        }

        $this->render('dashboard/applications/detail', [
            'title' => 'Application Detail',
            'app'   => $app,
        ], 'dashboard');
    }

    /** POST /dashboard/applications/{id}/email — simpan log email */
    public function sendEmail($id)
    {
        Auth::require();

        $to      = trim((string)Request::post('to'));
        $subject = trim((string)Request::post('subject'));
        $body    = trim((string)Request::post('body'));

        if ($to !== '' && $subject !== '') {
            (new EmailLog())->log((int)$id, $to, $subject, $body);
            flash_set('ok', 'Email tercatat di log.');
        } else {
            flash_set('err', 'Isi alamat & subject.');
        }

        $this->redirect('/dashboard/applications/' . (int)$id . '#email');
    }
}
