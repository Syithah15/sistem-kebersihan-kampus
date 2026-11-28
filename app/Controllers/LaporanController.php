<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\LokasiModel;
use App\Models\RiwayatStatusModel;

class LaporanController extends BaseController
{
    protected $laporanModel;
    protected $lokasiModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->lokasiModel = new LokasiModel();
        $this->riwayatModel = new RiwayatStatusModel();
    }

    public function create()
    {
        return view('pelapor/buat_laporan');
    }

    public function store()
    {
        $rules = [
            'judul' => 'required|min_length[5]|max_length[150]',
            'kategori' => 'required|in_list[Sampah,Saluran Air,Toilet,Ruang Kelas,Lingkungan,Lainnya]',
            'deskripsi' => 'required|min_length[10]',
            'foto' => 'uploaded[foto]|is_image[foto]|max_size[foto,2048]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'akurasi_gps' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[999999.99]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $latitude = (float) $this->request->getPost('latitude');
        $longitude = (float) $this->request->getPost('longitude');

        $akurasiInput = $this->request->getPost('akurasi_gps');

        $akurasiGps = (
            $akurasiInput !== null &&
            trim((string) $akurasiInput) !== ''
        )
            ? (float) $akurasiInput
            : null;

        if (
            $latitude < -90 || $latitude > 90 ||
            $longitude < -180 || $longitude > 180
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Koordinat lokasi tidak valid.');
        }

        $namaLokasi = trim(
            strip_tags((string) $this->request->getPost('nama_lokasi'))
        );

        $namaLokasi = $namaLokasi !== ''
            ? mb_substr($namaLokasi, 0, 150)
            : null;

        $foto = $this->request->getFile('foto');

        if (!$foto || !$foto->isValid() || $foto->hasMoved()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Foto tidak valid atau gagal diunggah.');
        }

        $uploadPath = WRITEPATH . 'uploads/laporan';

        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0775, true) && !is_dir($uploadPath)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Folder penyimpanan foto tidak dapat dibuat.');
            }
        }

        $namaFoto = $foto->getRandomName();

        try {
            $foto->move($uploadPath, $namaFoto);
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Foto gagal disimpan.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $laporanId = $this->laporanModel->insert([
                'user_id' => session()->get('user_id'),
                'judul' => trim($this->request->getPost('judul')),
                'kategori' => $this->request->getPost('kategori'),
                'deskripsi' => trim($this->request->getPost('deskripsi')),
                'foto' => $namaFoto,
                'status' => 'MENUNGGU',
            ]);

            if (!$laporanId) {
                throw new \RuntimeException('Gagal menyimpan data laporan.');
            }

            $lokasiBerhasil = $this->lokasiModel->insert([
                'laporan_id' => $laporanId,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'nama_lokasi' => $namaLokasi,
                'akurasi_gps' => $akurasiGps,
            ]);

            if (!$lokasiBerhasil) {
                throw new \RuntimeException('Gagal menyimpan lokasi laporan.');
            }

            $riwayatBerhasil = $this->riwayatModel->insert([
                'laporan_id' => $laporanId,
                'user_id' => session()->get('user_id'),
                'status_sebelumnya' => null,
                'status_baru' => 'MENUNGGU',
                'keterangan' => 'Laporan berhasil dibuat.',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$riwayatBerhasil || $db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();

        } catch (\Throwable $e) {
            $db->transRollback();

            $pathFoto = $uploadPath . DIRECTORY_SEPARATOR . $namaFoto;

            if (is_file($pathFoto)) {
                unlink($pathFoto);
            }

            log_message('error', $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Laporan gagal disimpan. Silakan coba kembali.');
        }

        return redirect()->to('/pelapor/dashboard')
            ->with('success', 'Laporan berhasil dikirim!');
    }
}