<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function laporan()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporan = $db->table('laporan')
            ->select('
                laporan.*,
                users.nama AS nama_pelapor,
                lokasi.nama_lokasi
            ')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->orderBy('laporan.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $statistik = [
            'total' => $db->table('laporan')->countAllResults(),

            'menunggu' => $db->table('laporan')
                ->where('status', 'MENUNGGU')
                ->countAllResults(),

            'diproses' => $db->table('laporan')
                ->where('status', 'DIPROSES')
                ->countAllResults(),

            'selesai' => $db->table('laporan')
                ->where('status', 'SELESAI')
                ->countAllResults(),

            'dibatalkan' => $db->table('laporan')
                ->where('status', 'DIBATALKAN')
                ->countAllResults(),
        ];

        return view('admin/laporan', [
            'laporan' => $laporan,
            'statistik' => $statistik,
        ]);
    }

    public function detail($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporan = $db->table('laporan')
            ->select('
                laporan.*,
                users.nama AS nama_pelapor,
                users.email AS email_pelapor,
                users.nim AS nim_pelapor,
                lokasi.nama_lokasi,
                lokasi.latitude,
                lokasi.longitude,
                lokasi.akurasi_gps
            ')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->where('laporan.id', $id)
            ->get()
            ->getRowArray();

        if (!$laporan) {
            return redirect()->to('/admin/laporan')
                ->with('error', 'Laporan tidak ditemukan.');
        }

        $tugas = $db->table('tugas')
            ->select('
                tugas.*,
                users.nama AS nama_petugas,
                users.email AS email_petugas
            ')
            ->join('users', 'users.id = tugas.petugas_id')
            ->where('tugas.laporan_id', $id)
            ->orderBy('tugas.id', 'DESC')
            ->get()
            ->getRowArray();

        $riwayat = $db->table('riwayat_status')
            ->select('
                riwayat_status.*,
                users.nama AS nama_pengguna
            ')
            ->join('users', 'users.id = riwayat_status.user_id', 'left')
            ->where('riwayat_status.laporan_id', $id)
            ->orderBy('riwayat_status.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/detail_laporan', [
            'laporan' => $laporan,
            'tugas' => $tugas,
            'riwayat' => $riwayat,
        ]);
    }

    public function batalkan($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $alasan = trim((string) $this->request->getPost('alasan'));

        if ($alasan === '' || mb_strlen($alasan) < 5 || mb_strlen($alasan) > 1000) {
            return redirect()->back()
                ->with('error', 'Alasan pembatalan wajib diisi, minimal 5 dan maksimal 1000 karakter.');
        }

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $laporan = $db->query(
                'SELECT * FROM laporan WHERE id = ? FOR UPDATE',
                [$id]
            )->getRowArray();

            if (!$laporan) {
                throw new \RuntimeException('Laporan tidak ditemukan.');
            }

            if (!in_array($laporan['status'], ['MENUNGGU', 'DIPROSES'], true)) {
                throw new \RuntimeException(
                    'Laporan yang sudah selesai atau dibatalkan tidak dapat dibatalkan lagi.'
                );
            }

            $tugasAktif = $db->table('tugas')
                ->where('laporan_id', $id)
                ->whereIn('status', ['DITUGASKAN', 'DIKERJAKAN'])
                ->get()
                ->getResultArray();

            if (!empty($tugasAktif)) {
                $db->table('tugas')
                    ->where('laporan_id', $id)
                    ->whereIn('status', ['DITUGASKAN', 'DIKERJAKAN'])
                    ->update([
                        'status' => 'DIBATALKAN',
                    ]);
            }

            $db->table('laporan')
                ->where('id', $id)
                ->update([
                    'status' => 'DIBATALKAN',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $db->table('riwayat_status')->insert([
                'laporan_id' => $id,
                'user_id' => session()->get('user_id'),
                'status_sebelumnya' => $laporan['status'],
                'status_baru' => 'DIBATALKAN',
                'keterangan' => 'Dibatalkan oleh Admin. Alasan: ' . $alasan,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $db->table('notifikasi')->insert([
                'user_id' => $laporan['user_id'],
                'laporan_id' => $id,
                'judul' => 'Laporan Dibatalkan',
                'pesan' => 'Laporan "' . $laporan['judul'] . '" dibatalkan oleh Admin. Alasan: ' . $alasan,
                'tipe' => 'pembatalan',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            foreach ($tugasAktif as $tugas) {
                $db->table('notifikasi')->insert([
                    'user_id' => $tugas['petugas_id'],
                    'laporan_id' => $id,
                    'judul' => 'Tugas Dibatalkan',
                    'pesan' => 'Tugas untuk laporan "' . $laporan['judul'] . '" dibatalkan oleh Admin. Alasan: ' . $alasan,
                    'tipe' => 'pembatalan',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Terjadi kesalahan saat menyimpan pembatalan.');
            }

            $db->transCommit();

            return redirect()->to('/admin/laporan/detail/' . $id)
                ->with('success', 'Laporan berhasil dibatalkan.');

        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function peta()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporanPeta = $db->table('laporan')
            ->select('
                laporan.id,
                laporan.judul,
                laporan.kategori,
                laporan.status,
                users.nama AS nama_pelapor,
                lokasi.nama_lokasi,
                lokasi.latitude,
                lokasi.longitude
            ')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id')
            ->where('lokasi.latitude IS NOT NULL')
            ->where('lokasi.longitude IS NOT NULL')
            ->orderBy('laporan.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/peta_laporan', [
            'laporanPeta' => $laporanPeta,
        ]);
    }
}