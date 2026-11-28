<?php

namespace App\Controllers;

class AdminRingkasanController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // 1. KPI Statistik Status Laporan
        $menunggu = $db->table('laporan')
            ->where('status', 'MENUNGGU')
            ->countAllResults();

        $diproses = $db->table('laporan')
            ->where('status', 'DIPROSES')
            ->countAllResults();

        $selesai = $db->table('laporan')
            ->where('status', 'SELESAI')
            ->countAllResults();

        $dibatalkan = $db->table('laporan')
            ->where('status', 'DIBATALKAN')
            ->countAllResults();

        // Rata-rata selisih menit antara waktu laporan dibuat (laporan.created_at)
        // dan waktu tugas diambil (tugas.waktu_diambil)
        $avgRow = $db->table('tugas')
            ->select('AVG(TIMESTAMPDIFF(MINUTE, laporan.created_at, tugas.waktu_diambil)) AS avg_menit')
            ->join('laporan', 'laporan.id = tugas.laporan_id')
            ->where('tugas.waktu_diambil IS NOT NULL')
            ->where('laporan.created_at IS NOT NULL')
            ->get()
            ->getRowArray();

        $rataRataRespon = ($avgRow && $avgRow['avg_menit'] !== null)
            ? (int) round((float) $avgRow['avg_menit'])
            : 0;

        // 2. Data Grafik Batang "Laporan 7 hari terakhir" (Chart.js)
        $grafikLabels = [];
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $label = date('d M', strtotime($tgl));
            $count = $db->table('laporan')
                ->where('DATE(created_at)', $tgl)
                ->countAllResults();

            $grafikLabels[] = $label;
            $grafikData[] = $count;
        }

        // 3. Mini Peta Leaflet: Pin laporan aktif (status MENUNGGU dan DIPROSES)
        $laporanAktifPeta = $db->table('laporan')
            ->select('
                laporan.id,
                laporan.judul,
                laporan.kategori,
                laporan.status,
                laporan.created_at,
                users.nama AS nama_pelapor,
                lokasi.nama_lokasi,
                lokasi.latitude,
                lokasi.longitude
            ')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id')
            ->whereIn('laporan.status', ['MENUNGGU', 'DIPROSES'])
            ->where('lokasi.latitude IS NOT NULL')
            ->where('lokasi.longitude IS NOT NULL')
            ->orderBy('laporan.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // 4. Tabel 5 Laporan Terbaru
        $laporanTerbaru = $db->table('laporan')
            ->select('
                laporan.id,
                laporan.judul,
                laporan.deskripsi,
                laporan.kategori,
                laporan.status,
                laporan.created_at,
                users.nama AS nama_pelapor,
                lokasi.nama_lokasi
            ')
            ->join('users', 'users.id = laporan.user_id')
            ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
            ->orderBy('laporan.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('admin/dashboard_ringkasan', [
            'judul' => 'Dashboard Ringkasan Admin',
            'kpi' => [
                'menunggu'         => $menunggu,
                'diproses'         => $diproses,
                'selesai'          => $selesai,
                'dibatalkan'       => $dibatalkan,
                'rata_rata_respon' => $rataRataRespon,
            ],
            'grafikLabels'     => $grafikLabels,
            'grafikData'       => $grafikData,
            'laporanAktifPeta' => $laporanAktifPeta,
            'laporanTerbaru'   => $laporanTerbaru,
        ]);
    }
}
