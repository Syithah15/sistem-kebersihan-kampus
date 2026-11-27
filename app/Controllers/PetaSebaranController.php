<?php

namespace App\Controllers;

class PetaSebaranController extends BaseController
{
    public function index()
    {
        $lokasi = db_connect()->table('lokasi')
            ->select('lokasi.latitude, lokasi.longitude, lokasi.nama_lokasi, laporan.judul, laporan.status')
            ->join('laporan', 'laporan.id = lokasi.laporan_id')
            ->get()->getResultArray();

        return view('dashboard/peta', [
            'title'  => 'Peta Sebaran Laporan',
            'lokasi' => $lokasi,
        ]);
    }
}