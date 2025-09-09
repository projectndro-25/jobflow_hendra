<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;

final class AuthController extends Controller
{
    /** Form login */
    public function loginForm(): void
    {
        $this->view('auth/login', [
            'title' => 'Masuk — JobFlow',
        ], 'main');
    }

    /** Proses login */
    public function login(): void
    {
        $email = trim((string) Request::post('email', ''));
        $pass  = (string) Request::post('password', '');

        if ($email === '' || $pass === '') {
            $this->view('auth/login', [
                'title'  => 'Masuk — JobFlow',
                'error'  => 'Email dan password wajib diisi.',
                'old'    => ['email' => $email],
            ], 'main');
            return;
        }

        if (Auth::login($email, $pass)) {
            $this->redirect('/dashboard');
            return;
        }

        $this->view('auth/login', [
            'title' => 'Masuk — JobFlow',
            'error' => 'Email atau password salah.',
            'old'   => ['email' => $email],
        ], 'main');
    }

    /** Logout */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
