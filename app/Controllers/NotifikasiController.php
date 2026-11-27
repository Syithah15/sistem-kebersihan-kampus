<?php

namespace App\Controllers;

class NotifikasiController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');

        $builder = db_connect()->table('notifikasi')->orderBy('created_at', 'DESC');
        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return view('notifikasi/index', [
            'title'      => 'Notifikasi',
            'notifikasi' => $builder->get()->getResultArray(),
        ]);
    }

    public function baca($id)
    {
        $builder = db_connect()->table('notifikasi')->where('id', (int) $id);

        $userId = session()->get('user_id');
        if ($userId) {
            $builder->where('user_id', $userId);
        }

        $builder->update(['is_read' => 1]);

        return redirect()->to('/notifikasi');
    }
}
