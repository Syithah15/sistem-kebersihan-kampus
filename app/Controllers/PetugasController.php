<?php

namespace App\Controllers;

class PetugasController extends BaseController
{
    public function detail($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== 'petugas') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporan = $db->table('laporan')
            ->select('
                laporan.*,
                users.nama AS nama_pelapor,
                lokasi.nama_lokasi,
                lokasi.latitude,
                lokasi.longitude
            ')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->where('laporan.id', $id)
            ->get()
            ->getRowArray();

        if (!$laporan) {
            return redirect()->to('/petugas/dashboard')
                ->with('error', 'Laporan tidak ditemukan.');
        }

        return view('petugas/detail_laporan', [
            'laporan' => $laporan,
        ]);
    }

    public function ambilTugas($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== 'petugas') {
            return redirect()->to('/');
        }

        // Validasi prioritas
        $prioritas = $this->request->getPost('prioritas');

        $prioritasDiizinkan = [
            'RENDAH',
            'SEDANG',
            'TINGGI',
            'DARURAT',
        ];

        if (!in_array($prioritas, $prioritasDiizinkan, true)) {
            return redirect()->back()
                ->with('error', 'Prioritas tugas tidak valid.');
        }

        $db = \Config\Database::connect();

        $db->transBegin();

        try {

            // Mengunci laporan agar tidak diambil dua petugas sekaligus.
            $laporan = $db->query(
                'SELECT * FROM laporan WHERE id = ? FOR UPDATE',
                [$id]
            )->getRowArray();

            if (!$laporan) {
                throw new \RuntimeException(
                    'Laporan tidak ditemukan.'
                );
            }

            // Hanya laporan MENUNGGU yang dapat diambil.
            if ($laporan['status'] !== 'MENUNGGU') {
                throw new \RuntimeException(
                    'Laporan ini sudah diambil atau tidak tersedia.'
                );
            }

            // Memastikan belum ada tugas aktif untuk laporan ini.
            $tugasAktif = $db->table('tugas')
                ->where('laporan_id', $id)
                ->whereIn('status', ['DITUGASKAN', 'DIKERJAKAN'])
                ->get()
                ->getRowArray();

            if ($tugasAktif) {
                throw new \RuntimeException(
                    'Laporan ini sudah memiliki Petugas.'
                );
            }

            // Membuat tugas baru.
            $berhasilTugas = $db->table('tugas')->insert([
                'laporan_id' => $id,
                'petugas_id' => session()->get('user_id'),
                'prioritas' => $prioritas,
                'status' => 'DITUGASKAN',
                'waktu_diambil' => date('Y-m-d H:i:s'),
            ]);

            if (!$berhasilTugas) {
                throw new \RuntimeException(
                    'Gagal membuat tugas.'
                );
            }

            // Mengubah status laporan.
            $berhasilUpdate = $db->table('laporan')
                ->where('id', $id)
                ->where('status', 'MENUNGGU')
                ->update([
                    'status' => 'DIPROSES',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            if (!$berhasilUpdate || $db->affectedRows() !== 1) {
                throw new \RuntimeException(
                    'Gagal memperbarui status laporan.'
                );
            }

            // Mencatat riwayat perubahan status.
            $berhasilRiwayat = $db->table('riwayat_status')->insert([
                'laporan_id' => $id,
                'user_id' => session()->get('user_id'),
                'status_sebelumnya' => 'MENUNGGU',
                'status_baru' => 'DIPROSES',
                'keterangan' => 'Laporan diambil oleh Petugas.',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$berhasilRiwayat) {
                throw new \RuntimeException(
                    'Gagal menyimpan riwayat status.'
                );
            }

            // Memberi notifikasi kepada Pelapor.
            $db->table('notifikasi')->insert([
                'user_id' => $laporan['user_id'],
                'laporan_id' => $id,
                'judul' => 'Laporan Sedang Diproses',
                'pesan' => 'Laporan kamu sudah diambil oleh Petugas dan sedang diproses.',
                'tipe' => 'status',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();

            return redirect()->to('/petugas/dashboard')
                ->with('success', 'Tugas berhasil diambil!');

        } catch (\Throwable $e) {

            $db->transRollback();

            log_message('error', $e->getMessage());

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function foto($namaFile)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(403);
        }

        $role = session()->get('role');

        if (!in_array($role, ['petugas', 'admin'], true)) {
            return $this->response->setStatusCode(403);
        }

        $namaFile = basename($namaFile);

        $path = WRITEPATH . 'uploads/laporan/' . $namaFile;

        if (!is_file($path)) {
            return $this->response->setStatusCode(404);
        }

        $mimeType = mime_content_type($path);

        $jenisDiizinkan = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($mimeType, $jenisDiizinkan, true)) {
            return $this->response->setStatusCode(404);
        }

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'private, no-store')
            ->setBody(file_get_contents($path));
    }
}