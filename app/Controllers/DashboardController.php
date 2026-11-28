<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function pelapor()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== 'pelapor') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporanSaya = $db->table('laporan')
            ->select('laporan.*, lokasi.nama_lokasi')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->where('laporan.user_id', session()->get('user_id'))
            ->orderBy('laporan.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'judul' => 'Dashboard Pelapor',
            'nama' => session()->get('nama'),
            'role' => 'pelapor',
            'laporanSaya' => $laporanSaya,
        ]);
    }

    public function petugas()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== 'petugas') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // Mengambil laporan yang belum diambil petugas
        $laporanMasuk = $db->table('laporan')
            ->select('laporan.*, lokasi.nama_lokasi, users.nama AS nama_pelapor')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->join('users', 'users.id = laporan.user_id')
            ->where('laporan.status', 'MENUNGGU')
            ->orderBy('laporan.created_at', 'ASC')
            ->get()
            ->getResultArray();

        // Mengambil tugas yang sudah diambil oleh petugas yang sedang login
        $tugasSaya = $db->table('tugas')
            ->select('
                tugas.*,
                laporan.judul,
                laporan.kategori,
                laporan.status AS status_laporan,
                lokasi.nama_lokasi,
                users.nama AS nama_pelapor
            ')
            ->join('laporan', 'laporan.id = tugas.laporan_id')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->where('tugas.petugas_id', session()->get('user_id'))
            ->whereIn('tugas.status', ['DITUGASKAN', 'DIKERJAKAN'])
            ->orderBy('tugas.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'judul' => 'Dashboard Petugas Kebersihan',
            'nama' => session()->get('nama'),
            'role' => 'petugas',
            'laporanMasuk' => $laporanMasuk,
            'tugasSaya' => $tugasSaya,
        ]);
    }

    public function admin()
    {
        return $this->tampilkanDashboard(
            'admin',
            'Dashboard Administrator'
        );
    }

    private function tampilkanDashboard($role, $judul)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== $role) {
            return redirect()->to('/');
        }

        return view('dashboard/index', [
            'judul' => $judul,
            'nama' => session()->get('nama'),
            'role' => $role,
            'laporanSaya' => [],
            'laporanMasuk' => [],
            'tugasSaya' => [],
        ]);
    }
}