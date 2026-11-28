<?php

namespace App\Controllers;

class NotifikasiController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $notifikasi = $db->table('notifikasi')
            ->where('user_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $jumlahBelumDibaca = $db->table('notifikasi')
            ->where('user_id', session()->get('user_id'))
            ->where('is_read', 0)
            ->countAllResults();

        return view('notifikasi/index', [
            'notifikasi' => $notifikasi,
            'jumlahBelumDibaca' => $jumlahBelumDibaca,
        ]);
    }

    public function baca($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // Hanya pemilik notifikasi yang boleh mengubah status bacanya.
        $notifikasi = $db->table('notifikasi')
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$notifikasi) {
            return redirect()->to('/notifikasi')
                ->with('error', 'Notifikasi tidak ditemukan.');
        }

        $db->table('notifikasi')
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->update([
                'is_read' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return redirect()->to('/notifikasi')
            ->with('success', 'Notifikasi telah ditandai sudah dibaca.');
    }
}