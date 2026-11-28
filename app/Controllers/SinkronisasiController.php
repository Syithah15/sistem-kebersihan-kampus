<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\LokasiModel;
use App\Models\RiwayatStatusModel;

class SinkronisasiController extends BaseController
{
    private function responsJSON($status, $pesan, $kode = 200, $tambahan = [])
    {
        return $this->response
            ->setStatusCode($kode)
            ->setJSON(array_merge([
                'status' => $status,
                'pesan' => $pesan,
                'csrfHash' => csrf_hash()
            ], $tambahan));
    }

    public function simpan()
    {
        if (
            !session()->get('isLoggedIn') ||
            session()->get('role') !== 'pelapor'
        ) {
            return $this->responsJSON(
                'error',
                'Sesi pengguna tidak valid.',
                401
            );
        }

        $aturan = [
            'judul' => 'required|min_length[5]|max_length[150]',
            'kategori' => 'required|in_list[Sampah,Saluran Air,Toilet,Ruang Kelas,Lingkungan,Lainnya]',
            'deskripsi' => 'required|min_length[10]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'akurasi_gps' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[999999.99]',
        ];

        if (!$this->validate($aturan)) {
            return $this->responsJSON(
                'error',
                'Data laporan tidak valid.',
                422,
                [
                    'errors' => $this->validator->getErrors()
                ]
            );
        }

        $foto = $this->request->getFile('foto');

        if (!$foto || !$foto->isValid() || $foto->hasMoved()) {
            return $this->responsJSON(
                'error',
                'Foto laporan tidak valid.',
                422
            );
        }

        $jenisFoto = $foto->getMimeType();

        if (!in_array($jenisFoto, [
            'image/jpeg',
            'image/png',
            'image/webp'
        ])) {
            return $this->responsJSON(
                'error',
                'Format foto harus JPG, PNG, atau WEBP.',
                422
            );
        }

        if ($foto->getSizeByUnit('kb') > 2048) {
            return $this->responsJSON(
                'error',
                'Ukuran foto maksimal 2 MB.',
                422
            );
        }

        $latitude = (float) $this->request->getPost('latitude');
        $longitude = (float) $this->request->getPost('longitude');

        if (
            $latitude < -90 || $latitude > 90 ||
            $longitude < -180 || $longitude > 180
        ) {
            return $this->responsJSON(
                'error',
                'Koordinat lokasi tidak valid.',
                422
            );
        }

        // Ambil akurasi GPS.
        // Jika kosong, simpan sebagai NULL.
        $akurasiInput = $this->request->getPost('akurasi_gps');

        $akurasiGps = (
            $akurasiInput !== null &&
            trim((string) $akurasiInput) !== ''
        )
            ? (float) $akurasiInput
            : null;

        $namaLokasi = trim(strip_tags(
            (string) $this->request->getPost('nama_lokasi')
        ));

        $namaLokasi = mb_substr($namaLokasi, 0, 150);

        if ($namaLokasi === '') {
            $namaLokasi = null;
        }

        $folderUpload = WRITEPATH . 'uploads/laporan/';

        if (!is_dir($folderUpload)) {
            mkdir($folderUpload, 0755, true);
        }

        $namaFile = $foto->getRandomName();

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $foto->move($folderUpload, $namaFile);

            $laporanModel = new LaporanModel();
            $lokasiModel = new LokasiModel();
            $riwayatModel = new RiwayatStatusModel();

            $laporanModel->insert([
                'user_id' => session()->get('user_id'),
                'judul' => $this->request->getPost('judul'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'kategori' => $this->request->getPost('kategori'),
                'foto' => $namaFile,
                'status' => 'MENUNGGU'
            ]);

            $laporanId = $laporanModel->getInsertID();

            if (!$laporanId) {
                throw new \RuntimeException(
                    'Gagal menyimpan laporan.'
                );
            }

            $lokasiModel->insert([
                'laporan_id' => $laporanId,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'nama_lokasi' => $namaLokasi,
                'akurasi_gps' => $akurasiGps
            ]);

            $riwayatModel->insert([
                'laporan_id' => $laporanId,
                'user_id' => session()->get('user_id'),
                'status_sebelumnya' => null,
                'status_baru' => 'MENUNGGU',
                'keterangan' => 'Laporan dikirim melalui sinkronisasi offline.'
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();

            return $this->responsJSON(
                'success',
                'Laporan berhasil disinkronkan.',
                200,
                [
                    'laporan_id' => $laporanId
                ]
            );

        } catch (\Throwable $e) {

            $db->transRollback();

            $pathFoto = $folderUpload . $namaFile;

            if (is_file($pathFoto)) {
                unlink($pathFoto);
            }

            log_message(
                'error',
                'Sinkronisasi laporan gagal: ' . $e->getMessage()
            );

            return $this->responsJSON(
                'error',
                'Terjadi kesalahan saat menyimpan laporan.',
                500
            );
        }
    }
}