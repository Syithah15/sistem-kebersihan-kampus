<?php

namespace App\Controllers;

use Config\Database;

class AdminRingkasanController extends BaseController
{
    public function index()
    {
        $session = session();

        // Hanya admin yang boleh membuka dashboard
        if (
            !$session->get('isLoggedIn') ||
            $session->get('role') !== 'admin'
        ) {
            return redirect()->to('/');
        }

        $db = Database::connect();

        // Ringkasan seluruh laporan
        $totalLaporan = $db->table('laporan')
            ->countAllResults();

        $statusList = [
            'MENUNGGU',
            'DIPROSES',
            'SELESAI',
            'DIBATALKAN'
        ];

        $status = [];

        foreach ($statusList as $namaStatus) {
            $status[$namaStatus] = $db->table('laporan')
                ->where('status', $namaStatus)
                ->countAllResults();
        }

        // Grafik laporan 7 hari terakhir
        $grafik = [];

        for ($i = 6; $i >= 0; $i--) {
            $timestamp = strtotime("-$i days");
            $tanggal = date('Y-m-d', $timestamp);

            $jumlah = $db->table('laporan')
                ->where('DATE(created_at)', $tanggal)
                ->countAllResults();

            $grafik[] = [
                'tanggal' => date('d/m', $timestamp),
                'jumlah'  => $jumlah
            ];
        }

        // Lima laporan terbaru beserta pelapor dan lokasi
        $laporanTerbaru = $db->table('laporan l')
            ->select(
                'l.id, l.judul, l.status, l.created_at,
                 u.nama AS pelapor, lo.nama_lokasi'
            )
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('lokasi lo', 'lo.laporan_id = l.id', 'left')
            ->orderBy('l.created_at', 'DESC')
            ->orderBy('l.id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

      return view('admin/dashboard_ringkasan', [
    'totalLaporan'   => $totalLaporan,
    'status'         => $status,
    'grafik'         => $grafik,
    'laporanTerbaru' => $laporanTerbaru
]);
    }
}