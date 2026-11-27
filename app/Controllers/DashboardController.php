<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $rows = $db->table('laporan')
            ->select('status, COUNT(*) AS jumlah')
            ->groupBy('status')
            ->get()->getResultArray();

        $hitung = ['MENUNGGU' => 0, 'DIPROSES' => 0, 'SELESAI' => 0, 'DIBATALKAN' => 0];
        foreach ($rows as $r) {
            $hitung[$r['status']] = (int) $r['jumlah'];
        }

        $terbaru = $db->table('laporan')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return view('dashboard/index', [
            'title'   => 'Dashboard',
            'total'   => array_sum($hitung),
            'hitung'  => $hitung,
            'terbaru' => $terbaru,
        ]);
    }
}