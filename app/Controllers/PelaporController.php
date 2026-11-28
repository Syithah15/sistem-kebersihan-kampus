<?php

namespace App\Controllers;

class PelaporController extends BaseController
{
    public function detail($id)
    {
        if (!session()->get('isLoggedIn') ||
            session()->get('role') !== 'pelapor') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // Ambil laporan milik pelapor yang sedang login
        $laporan = $db->table('laporan')
            ->select('laporan.*, lokasi.latitude, lokasi.longitude, lokasi.nama_lokasi')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->where('laporan.id', $id)
            ->where('laporan.user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$laporan) {
            return redirect()->to('/pelapor/dashboard')
                ->with('error', 'Laporan tidak ditemukan.');
        }

        // Ambil informasi tugas dan petugas
        $tugas = $db->table('tugas')
            ->select('tugas.*, users.nama AS nama_petugas')
            ->join('users', 'users.id = tugas.petugas_id')
            ->where('tugas.laporan_id', $id)
            ->orderBy('tugas.id', 'DESC')
            ->get()
            ->getRowArray();

        // Ambil riwayat perubahan status
        $riwayat = $db->table('riwayat_status')
            ->select('riwayat_status.*, users.nama AS nama_pengguna')
            ->join('users', 'users.id = riwayat_status.user_id', 'left')
            ->where('riwayat_status.laporan_id', $id)
            ->orderBy('riwayat_status.created_at', 'ASC')
            ->get()
            ->getResultArray();

        return view('pelapor/detail_laporan', [
            'laporan' => $laporan,
            'tugas' => $tugas,
            'riwayat' => $riwayat,
        ]);
    }

    // Menampilkan foto laporan milik pelapor
    public function fotoLaporan($id)
    {
        if (!session()->get('isLoggedIn') ||
            session()->get('role') !== 'pelapor') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporan = $db->table('laporan')
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$laporan || empty($laporan['foto'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $namaFile = basename($laporan['foto']);
        $path = WRITEPATH . 'uploads/laporan/' . $namaFile;

        return $this->kirimFoto($path);
    }

    // Menampilkan foto sebelum atau sesudah pekerjaan
    public function fotoTugas($id, $jenis)
    {
        if (!session()->get('isLoggedIn') ||
            session()->get('role') !== 'pelapor') {
            return redirect()->to('/');
        }

        if (!in_array($jenis, ['sebelum', 'sesudah'], true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();

        // Pastikan laporan memang milik pelapor yang login
        $laporan = $db->table('laporan')
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$laporan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $kolomFoto = $jenis === 'sebelum'
            ? 'foto_sebelum'
            : 'foto_sesudah';

        $tugas = $db->table('tugas')
            ->where('laporan_id', $id)
            ->where('status', 'SELESAI')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if (!$tugas || empty($tugas[$kolomFoto])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $namaFile = basename($tugas[$kolomFoto]);
        $path = WRITEPATH . 'uploads/tugas/' . $namaFile;

        return $this->kirimFoto($path);
    }

    private function kirimFoto($path)
    {
        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);

        $mimeDiizinkan = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($mime, $mimeDiizinkan, true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'private, no-store')
            ->setBody(file_get_contents($path));
    }
}