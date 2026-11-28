<?php
namespace App\Controllers;
class PetaSebaranController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $laporanPeta = $db->table('laporan')
            ->select('
                laporan.id,
                laporan.judul,
                laporan.deskripsi,
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