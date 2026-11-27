<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Menampilkan halaman login
    public function index()
    {
        // Jika sudah login, arahkan ke dashboard
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }

        return view('auth/login');
    }

    // Memproses login
    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari pengguna berdasarkan email
        $user = $this->userModel
            ->where('email', $email)
            ->first();

        // Periksa akun dan password
        if (
            !$user ||
            !password_verify($password, $user['password'])
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Periksa status akun
        if ((int) $user['is_active'] !== 1) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun kamu sedang tidak aktif.');
        }

        // Regenerasi session untuk keamanan
        session()->regenerate(true);

        // Simpan informasi pengguna ke session
        session()->set([
            'user_id'    => $user['id'],
            'nama'       => $user['nama'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        return $this->redirectDashboard();
    }

    // Mengarahkan pengguna berdasarkan role
    private function redirectDashboard()
    {
        $role = session()->get('role');

        switch ($role) {
            case 'pelapor':
                return redirect()->to('/pelapor/dashboard');

            case 'petugas':
                return redirect()->to('/petugas/dashboard');

            case 'admin':
                return redirect()->to('/admin/dashboard');

            default:
                session()->destroy();

                return redirect()->to('/');
        }
    }

    // Logout
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/')
            ->with('success', 'Kamu berhasil logout.');
    }
}