<?php

namespace App\Controllers;

use Config\Database;

class AdminMonitoringController extends BaseController
{
    public function index()
    {
        $session = session();

        if (
            !$session->get('isLoggedIn') ||
            $session->get('role') !== 'admin'
        ) {
            return redirect()->to('/');
        }

        $db = Database::connect();

        $tugas = $db->table('tugas t')
            ->select(
                't.id, t.prioritas, t.status,
                 t.waktu_diambil, t.waktu_selesai,
                 u.nama AS nama_petugas,
                 l.judul AS judul_laporan'
            )
            ->join('users u', 'u.id = t.petugas_id', 'left')
            ->join('laporan l', 'l.id = t.laporan_id', 'left')
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/monitoring_petugas', [
            'tugas' => $tugas
        ]);
    }
}