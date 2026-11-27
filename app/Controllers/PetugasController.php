<?php

namespace App\Controllers;

use App\Models\TugasModel;

/**
 * Controller PetugasController
 *
 * Mengelola dashboard utama untuk pengguna dengan role 'petugas' (US-4).
 */
class PetugasController extends BaseController
{
    protected $tugasModel;

    public function __construct()
    {
        $this->tugasModel = new TugasModel();
    }

    /**
     * Memeriksa otentikasi dan otorisasi role petugas.
     * Mengembalikan response redirect jika tidak valid, atau null jika valid.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    protected function checkPetugas()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah pengguna memiliki role petugas
        if ($session->get('role') !== 'petugas') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk petugas kebersihan.');
        }

        return null;
    }

    /**
     * Menampilkan dashboard utama petugas (ringkasan statistik tugas & tugas terbaru).
     */
    public function index()
    {
        if ($redirect = $this->checkPetugas()) {
            return $redirect;
        }

        $petugasId = (int) session()->get('user_id');

        // Mengambil ringkasan jumlah tugas per status
        $ringkasan = $this->tugasModel->getRingkasanStatus($petugasId);

        // Mengambil daftar 5 tugas terbaru milik petugas
        $tugasTerbaru = $this->tugasModel->getTugasTerbaru($petugasId, 5);

        return view('petugas/dashboard', [
            'judul'        => 'Dashboard Petugas',
            'namaPetugas'  => session()->get('nama'),
            'ringkasan'    => $ringkasan,
            'tugasTerbaru' => $tugasTerbaru,
        ]);
    }
}
