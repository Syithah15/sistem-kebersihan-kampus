<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Periksa apakah pengguna sudah login.
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil role pengguna.
        $rolePengguna = session()->get('role');

        // Ambil daftar role yang diperbolehkan.
        $roleDiizinkan = $arguments ?? [];

        // Pastikan pengguna memiliki role yang sesuai.
        if (!in_array($rolePengguna, $roleDiizinkan, true)) {

            // Arahkan kembali ke dashboard sesuai role.
            switch ($rolePengguna) {
                case 'pelapor':
                    return redirect()->to('/pelapor/dashboard');

                case 'petugas':
                    return redirect()->to('/petugas/dashboard');

                case 'admin':
                    return redirect()->to('/admin/dashboard');

                default:
                    session()->destroy();

                    return redirect()->to('/')
                        ->with('error', 'Akses ditolak. Silakan login kembali.');
            }
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada proses tambahan setelah halaman dibuka.
    }
}