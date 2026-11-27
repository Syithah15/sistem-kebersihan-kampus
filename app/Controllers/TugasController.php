<?php

namespace App\Controllers;

use App\Models\TugasModel;

/**
 * Controller TugasController
 *
 * Mengelola daftar tugas, detail tugas, dan pembaruan status tindak lanjut oleh petugas (US-4).
 */
class TugasController extends BaseController
{
    protected $tugasModel;

    public function __construct()
    {
        $this->tugasModel = new TugasModel();
    }

    /**
     * Memeriksa otentikasi dan hak akses role petugas.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    protected function checkPetugas()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($session->get('role') !== 'petugas') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk petugas kebersihan.');
        }

        return null;
    }

    /**
     * Menampilkan daftar tugas milik petugas yang login dengan opsi filter status.
     */
    public function index()
    {
        if ($redirect = $this->checkPetugas()) {
            return $redirect;
        }

        $petugasId = (int) session()->get('user_id');
        $filterStatus = $this->request->getGet('status');

        // Validasi filter status agar aman
        $allowedStatus = ['DITUGASKAN', 'DIKERJAKAN', 'SELESAI', 'DIBATALKAN'];
        if ($filterStatus && !in_array(strtoupper($filterStatus), $allowedStatus, true)) {
            $filterStatus = null;
        }

        $daftarTugas = $this->tugasModel->getTugasByPetugas($petugasId, $filterStatus);
        $ringkasan   = $this->tugasModel->getRingkasanStatus($petugasId);

        return view('petugas/tugas_list', [
            'judul'        => 'Daftar Tugas Saya',
            'daftarTugas'  => $daftarTugas,
            'ringkasan'    => $ringkasan,
            'filterStatus' => $filterStatus ? strtoupper($filterStatus) : 'SEMUA',
        ]);
    }

    /**
     * Menampilkan detail tugas beserta informasi laporan dan riwayat status.
     *
     * @param int $id ID tugas
     */
    public function detail($id)
    {
        if ($redirect = $this->checkPetugas()) {
            return $redirect;
        }

        $petugasId = (int) session()->get('user_id');
        $tugas = $this->tugasModel->getDetailTugas((int) $id, $petugasId);

        if (!$tugas) {
            return redirect()->to('/petugas/tugas')
                ->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses ke tugas ini.');
        }

        // Ambil riwayat status dari laporan terkait
        $riwayat = $this->tugasModel->getRiwayatStatus((int) $tugas['laporan_id']);

        return view('petugas/tugas_detail', [
            'judul'   => 'Detail Tugas #' . $tugas['id'],
            'tugas'   => $tugas,
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Menampilkan halaman formulir pembaruan status tindak lanjut.
     *
     * @param int $id ID tugas
     */
    public function formUpdate($id)
    {
        if ($redirect = $this->checkPetugas()) {
            return $redirect;
        }

        $petugasId = (int) session()->get('user_id');
        $tugas = $this->tugasModel->getDetailTugas((int) $id, $petugasId);

        if (!$tugas) {
            return redirect()->to('/petugas/tugas')
                ->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses ke tugas ini.');
        }

        return view('petugas/form_update', [
            'judul'  => 'Tindak Lanjuti Tugas #' . $tugas['id'],
            'tugas'  => $tugas,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    /**
     * Memproses pembaruan status tindak lanjut, catatan, dan foto bukti.
     *
     * @param int $id ID tugas
     */
    public function updateStatus($id)
    {
        if ($redirect = $this->checkPetugas()) {
            return $redirect;
        }

        $petugasId = (int) session()->get('user_id');
        $tugas = $this->tugasModel->getDetailTugas((int) $id, $petugasId);

        if (!$tugas) {
            return redirect()->to('/petugas/tugas')
                ->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses ke tugas ini.');
        }

        // Aturan validasi form
        $rules = [
            'status'       => 'required|in_list[DITUGASKAN,DIKERJAKAN,SELESAI,DIBATALKAN]',
            'catatan'      => 'permit_empty|string|max_length[1000]',
            'foto_sebelum' => 'permit_empty|is_image[foto_sebelum]|mime_in[foto_sebelum,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto_sebelum,2048]',
            'foto_sesudah' => 'permit_empty|is_image[foto_sesudah]|mime_in[foto_sesudah,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto_sesudah,2048]',
        ];

        $messages = [
            'status' => [
                'required' => 'Status tindak lanjut wajib dipilih.',
                'in_list'  => 'Status yang dipilih tidak valid.',
            ],
            'catatan' => [
                'max_length' => 'Catatan maksimal 1000 karakter.',
            ],
            'foto_sebelum' => [
                'is_image' => 'File foto sebelum harus berupa gambar.',
                'mime_in'  => 'Format gambar foto sebelum harus JPG, JPEG, PNG, atau WEBP.',
                'max_size' => 'Ukuran foto sebelum maksimal 2 MB.',
            ],
            'foto_sesudah' => [
                'is_image' => 'File foto sesudah harus berupa gambar.',
                'mime_in'  => 'Format gambar foto sesudah harus JPG, JPEG, PNG, atau WEBP.',
                'max_size' => 'Ukuran foto sesudah maksimal 2 MB.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $statusBaru   = strtoupper($this->request->getPost('status'));
        $catatanBaru  = trim((string) $this->request->getPost('catatan'));
        $statusLama   = $tugas['status'];

        $updateData = [
            'status'  => $statusBaru,
            'catatan' => $catatanBaru !== '' ? $catatanBaru : $tugas['catatan'],
        ];

        // Kelola waktu pengerjaan otomatis
        $sekarang = date('Y-m-d H:i:s');
        if ($statusBaru === 'DIKERJAKAN' && empty($tugas['waktu_diambil'])) {
            $updateData['waktu_diambil'] = $sekarang;
        }

        if ($statusBaru === 'SELESAI' && empty($tugas['waktu_selesai'])) {
            $updateData['waktu_selesai'] = $sekarang;
            if (empty($tugas['waktu_diambil'])) {
                $updateData['waktu_diambil'] = $sekarang;
            }
        }

        // Direktori penyimpanan bukti foto
        $uploadDir = FCPATH . 'uploads/tugas/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Proses unggah foto_sebelum
        $fileSebelum = $this->request->getFile('foto_sebelum');
        if ($fileSebelum && $fileSebelum->isValid() && !$fileSebelum->hasMoved()) {
            $namaSebelum = $fileSebelum->getRandomName();
            $fileSebelum->move($uploadDir, $namaSebelum);
            $updateData['foto_sebelum'] = $namaSebelum;
        }

        // Proses unggah foto_sesudah
        $fileSesudah = $this->request->getFile('foto_sesudah');
        if ($fileSesudah && $fileSesudah->isValid() && !$fileSesudah->hasMoved()) {
            $namaSesudah = $fileSesudah->getRandomName();
            $fileSesudah->move($uploadDir, $namaSesudah);
            $updateData['foto_sesudah'] = $namaSesudah;
        }

        $db = db_connect();
        $db->transStart();

        // 1. Perbarui data tabel tugas
        $this->tugasModel->update((int) $id, $updateData);

        // 2. Sinkronisasi status pada tabel laporan
        // Pemetaan status tugas ke status laporan
        $mapLaporanStatus = [
            'DITUGASKAN' => 'MENUNGGU',
            'DIKERJAKAN' => 'DIPROSES',
            'SELESAI'    => 'SELESAI',
            'DIBATALKAN' => 'DIBATALKAN',
        ];
        $laporanStatusBaru = $mapLaporanStatus[$statusBaru] ?? 'DIPROSES';
        $laporanStatusLama = $tugas['laporan_status'];

        $db->table('laporan')
            ->where('id', (int) $tugas['laporan_id'])
            ->update([
                'status'     => $laporanStatusBaru,
                'updated_at' => $sekarang,
            ]);

        // 3. Catat riwayat perubahan ke tabel riwayat_status jika status berubah atau ada catatan
        if ($statusLama !== $statusBaru || $laporanStatusLama !== $laporanStatusBaru || $catatanBaru !== '') {
            $keteranganRiwayat = $catatanBaru !== ''
                ? $catatanBaru
                : 'Petugas memperbarui status tugas menjadi ' . $statusBaru . ' (Laporan: ' . $laporanStatusBaru . ')';

            $db->table('riwayat_status')->insert([
                'laporan_id'        => (int) $tugas['laporan_id'],
                'user_id'           => $petugasId,
                'status_sebelumnya' => $laporanStatusLama,
                'status_baru'       => $laporanStatusBaru,
                'keterangan'        => $keteranganRiwayat,
                'created_at'        => $sekarang,
            ]);
        }

        // 4. Buat notifikasi untuk pelapor jika ada pelapor terkait
        $laporanRow = $db->table('laporan')->where('id', (int) $tugas['laporan_id'])->get()->getRowArray();
        if ($laporanRow && !empty($laporanRow['user_id'])) {
            $db->table('notifikasi')->insert([
                'user_id'    => (int) $laporanRow['user_id'],
                'laporan_id' => (int) $tugas['laporan_id'],
                'judul'      => 'Pembaruan Tugas: ' . substr($tugas['laporan_judul'], 0, 100),
                'pesan'      => 'Laporan "' . $tugas['laporan_judul'] . '" telah diperbarui oleh petugas ke status ' . $statusBaru . '.',
                'tipe'       => 'status_tugas',
                'is_read'    => 0,
                'created_at' => $sekarang,
                'updated_at' => $sekarang,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }

        return redirect()->to('/petugas/tugas/' . $id)
            ->with('success', 'Status tugas berhasil diperbarui menjadi ' . $statusBaru . '.');
    }
}
