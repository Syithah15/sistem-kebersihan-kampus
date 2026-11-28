<?php

namespace App\Controllers;

class TugasController extends BaseController
{
    public function mulai($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== 'petugas') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        try {
            $db->transBegin();

            $tugas = $db->query(
                'SELECT * FROM tugas
                 WHERE id = ? AND petugas_id = ?
                 FOR UPDATE',
                [$id, session()->get('user_id')]
            )->getRowArray();

            if (!$tugas) {
                $db->transRollback();

                return redirect()->to('/petugas/dashboard')
                    ->with('error', 'Tugas tidak ditemukan atau bukan milik Anda.');
            }

            if ($tugas['status'] !== 'DITUGASKAN') {
                $db->transRollback();

                return redirect()->to('/petugas/dashboard')
                    ->with('error', 'Tugas ini sudah dimulai atau tidak dapat dikerjakan.');
            }

            $db->table('tugas')
                ->where('id', $id)
                ->where('petugas_id', session()->get('user_id'))
                ->where('status', 'DITUGASKAN')
                ->update([
                    'status' => 'DIKERJAKAN',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            if ($db->affectedRows() !== 1) {
                throw new \RuntimeException('Status tugas gagal diperbarui.');
            }

            if (!$db->transStatus()) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();

            return redirect()->to('/petugas/dashboard')
                ->with('success', 'Tugas berhasil dimulai. Selamat bekerja!');

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Gagal memulai tugas: ' . $e->getMessage());

            return redirect()->to('/petugas/dashboard')
                ->with('error', 'Terjadi kesalahan saat memulai tugas.');
        }
    }

    // Menampilkan formulir penyelesaian tugas
    public function formSelesai($id)
    {
        if (!session()->get('isLoggedIn') ||
            session()->get('role') !== 'petugas') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        $tugas = $db->table('tugas')
            ->select('tugas.*, laporan.judul, laporan.kategori')
            ->join('laporan', 'laporan.id = tugas.laporan_id')
            ->where('tugas.id', $id)
            ->where('tugas.petugas_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$tugas || $tugas['status'] !== 'DIKERJAKAN') {
            return redirect()->to('/petugas/dashboard')
                ->with('error', 'Tugas tidak ditemukan atau belum mulai dikerjakan.');
        }

        return view('petugas/selesai_tugas', [
            'tugas' => $tugas,
        ]);
    }

    // Menyimpan bukti dan menyelesaikan tugas
    public function simpanSelesai($id)
    {
        if (!session()->get('isLoggedIn') ||
            session()->get('role') !== 'petugas') {
            return redirect()->to('/');
        }

        $aturan = [
            'foto_sebelum' => [
                'label' => 'Foto sebelum dibersihkan',
                'rules' => 'uploaded[foto_sebelum]|is_image[foto_sebelum]|max_size[foto_sebelum,2048]',
            ],
            'foto_sesudah' => [
                'label' => 'Foto sesudah dibersihkan',
                'rules' => 'uploaded[foto_sesudah]|is_image[foto_sesudah]|max_size[foto_sesudah,2048]',
            ],
            'catatan' => [
                'label' => 'Catatan',
                'rules' => 'permit_empty|max_length[1000]',
            ],
        ];

        if (!$this->validate($aturan)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $fotoSebelum = $this->request->getFile('foto_sebelum');
        $fotoSesudah = $this->request->getFile('foto_sesudah');

        $mimeDiizinkan = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        foreach ([$fotoSebelum, $fotoSesudah] as $foto) {
            if (!$foto->isValid() ||
                !isset($mimeDiizinkan[$foto->getMimeType()])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Foto harus berformat JPG, PNG, atau WEBP.');
            }
        }

        $db = \Config\Database::connect();
        $folder = WRITEPATH . 'uploads/tugas/';

        if (!is_dir($folder) &&
            !mkdir($folder, 0755, true) &&
            !is_dir($folder)) {
            return redirect()->back()
                ->with('error', 'Folder penyimpanan foto tidak dapat dibuat.');
        }

        $namaFotoSebelum = 'sebelum_' . bin2hex(random_bytes(12)) .
            '.' . $mimeDiizinkan[$fotoSebelum->getMimeType()];

        $namaFotoSesudah = 'sesudah_' . bin2hex(random_bytes(12)) .
            '.' . $mimeDiizinkan[$fotoSesudah->getMimeType()];

        $pathSebelum = $folder . $namaFotoSebelum;
        $pathSesudah = $folder . $namaFotoSesudah;

        try {
            $fotoSebelum->move($folder, $namaFotoSebelum);
            $fotoSesudah->move($folder, $namaFotoSesudah);

            $db->transBegin();

            $tugas = $db->query(
                'SELECT * FROM tugas
                 WHERE id = ? AND petugas_id = ?
                 FOR UPDATE',
                [$id, session()->get('user_id')]
            )->getRowArray();

            if (!$tugas || $tugas['status'] !== 'DIKERJAKAN') {
                throw new \RuntimeException(
                    'Tugas tidak ditemukan atau sudah diselesaikan.'
                );
            }

            $laporan = $db->query(
                'SELECT * FROM laporan WHERE id = ? FOR UPDATE',
                [$tugas['laporan_id']]
            )->getRowArray();

            if (!$laporan || $laporan['status'] !== 'DIPROSES') {
                throw new \RuntimeException(
                    'Status laporan tidak sesuai.'
                );
            }

            $waktuSelesai = date('Y-m-d H:i:s');

            // Perbarui data tugas
            $db->table('tugas')
                ->where('id', $id)
                ->where('petugas_id', session()->get('user_id'))
                ->where('status', 'DIKERJAKAN')
                ->update([
                    'status' => 'SELESAI',
                    'waktu_selesai' => $waktuSelesai,
                    'foto_sebelum' => $namaFotoSebelum,
                    'foto_sesudah' => $namaFotoSesudah,
                    'catatan' => trim((string) $this->request->getPost('catatan')),
                    'updated_at' => $waktuSelesai,
                ]);

            if ($db->affectedRows() !== 1) {
                throw new \RuntimeException('Data tugas gagal diperbarui.');
            }

            // Perbarui status laporan
            $db->table('laporan')
                ->where('id', $tugas['laporan_id'])
                ->where('status', 'DIPROSES')
                ->update([
                    'status' => 'SELESAI',
                    'updated_at' => $waktuSelesai,
                ]);

            if ($db->affectedRows() !== 1) {
                throw new \RuntimeException('Status laporan gagal diperbarui.');
            }

            // Simpan riwayat perubahan status
            $db->table('riwayat_status')->insert([
                'laporan_id' => $tugas['laporan_id'],
                'user_id' => session()->get('user_id'),
                'status_sebelumnya' => 'DIPROSES',
                'status_baru' => 'SELESAI',
                'keterangan' => 'Pekerjaan kebersihan telah diselesaikan oleh petugas.',
                'created_at' => $waktuSelesai,
            ]);

            // Kirim notifikasi kepada pelapor
            $db->table('notifikasi')->insert([
                'user_id' => $laporan['user_id'],
                'laporan_id' => $tugas['laporan_id'],
                'judul' => 'Laporan Telah Selesai',
                'pesan' => 'Laporan Anda telah selesai ditangani oleh petugas.',
                'tipe' => 'status',
                'is_read' => 0,
                'created_at' => $waktuSelesai,
                'updated_at' => $waktuSelesai,
            ]);

            if (!$db->transStatus()) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();

            return redirect()->to('/petugas/dashboard')
                ->with('success', 'Tugas berhasil diselesaikan!');

        } catch (\Throwable $e) {
            $db->transRollback();

            if (is_file($pathSebelum)) {
                unlink($pathSebelum);
            }

            if (is_file($pathSesudah)) {
                unlink($pathSesudah);
            }

            log_message('error', 'Gagal menyelesaikan tugas: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Tugas gagal diselesaikan. Silakan coba kembali.');
        }
    }
}