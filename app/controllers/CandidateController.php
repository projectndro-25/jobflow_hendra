<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Candidate;
use App\Models\Application;

class CandidateController extends Controller
{
    public function index()
    {
        $q = $_GET['q'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        [$rows, $meta] = Candidate::paginate($page, 12, $q);
        $this->view('dashboard/candidates/list', [
            'title' => 'Candidates',
            'rows'  => $rows,
            'meta'  => $meta,
            'q'     => $q
        ], 'dashboard');
    }

    public function show(string $id)
    {
        $candidate = Candidate::find((int)$id);
        if (!$candidate) {
            http_response_code(404);
            echo "<h2>Candidate not found</h2>"; return;
        }
        $applications = Application::byCandidate((int)$id);
        $this->view('dashboard/candidates/detail', [
            'title' => 'Candidate Detail',
            'c'     => $candidate,
            'apps'  => $applications
        ], 'dashboard');
    }
}
