<?php

namespace App\Controllers;

class MonitoringPetugasController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // Ambil semua pengguna dengan role 'petugas'
        $petugasRaw = $db->table('users')
            ->where('role', 'petugas')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();

        $petugasList = [];
        $petugasAktifCount = 0;
        $sedangBertugasCount = 0;
        $tersediaCount = 0;
        $laporanDitanganiCount = 0;
        $rekomendasiPetugas = [];

        foreach ($petugasRaw as $p) {
            $isAktif = (int) ($p['is_active'] ?? 1);
            if ($isAktif === 1) {
                $petugasAktifCount++;
            }

            // Ambil semua tugas yang ditugaskan ke petugas ini
            $daftarTugas = $db->table('tugas')
                ->select('
                    tugas.id AS tugas_id,
                    tugas.laporan_id,
                    tugas.prioritas,
                    tugas.status AS status_tugas,
                    tugas.waktu_diambil,
                    tugas.waktu_selesai,
                    tugas.catatan,
                    tugas.created_at AS tugas_created_at,
                    laporan.judul AS judul_laporan,
                    laporan.kategori,
                    laporan.status AS status_laporan,
                    lokasi.nama_lokasi
                ')
                ->join('laporan', 'laporan.id = tugas.laporan_id')
                ->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left')
                ->where('tugas.petugas_id', $p['id'])
                ->orderBy('tugas.created_at', 'DESC')
                ->get()
                ->getResultArray();

            $tugasAktifList = [];
            $tugasSelesaiCount = 0;
            $areaTugasList = [];
            $waktuTerakhir = $p['updated_at'] ?: $p['created_at'];

            foreach ($daftarTugas as $t) {
                if (in_array($t['status_tugas'], ['DITUGASKAN', 'DIKERJAKAN'], true)) {
                    $tugasAktifList[] = $t;
                    if (!empty($t['nama_lokasi'])) {
                        $areaTugasList[] = $t['nama_lokasi'];
                    }
                    if (!empty($t['waktu_diambil'])) {
                        if (!$waktuTerakhir || strtotime($t['waktu_diambil']) > strtotime($waktuTerakhir)) {
                            $waktuTerakhir = $t['waktu_diambil'];
                        }
                    }
                } elseif ($t['status_tugas'] === 'SELESAI') {
                    $tugasSelesaiCount++;
                    if (!empty($t['waktu_selesai'])) {
                        if (!$waktuTerakhir || strtotime($t['waktu_selesai']) > strtotime($waktuTerakhir)) {
                            $waktuTerakhir = $t['waktu_selesai'];
                        }
                    }
                }
            }

            $jumlahTugasAktif = count($tugasAktifList);
            $laporanDitanganiCount += $jumlahTugasAktif;

            if ($isAktif === 1) {
                if ($jumlahTugasAktif > 0) {
                    $sedangBertugasCount++;
                    $statusKerja = 'Bertugas';
                } else {
                    $tersediaCount++;
                    $statusKerja = 'Tersedia';
                }
            } else {
                $statusKerja = 'Non-Aktif';
            }

            // Tentukan area tugas ringkas
            $areaTugasList = array_values(array_unique($areaTugasList));
            if (!empty($areaTugasList)) {
                $areaTugas = implode(', ', $areaTugasList);
            } else {
                $areaTugas = 'Standby (Semua Area Kampus)';
            }

            $dataPetugas = [
                'id'                  => $p['id'],
                'kode'                => 'PTG-' . sprintf('%03d', $p['id']),
                'nama'                => $p['nama'],
                'email'               => $p['email'] ?? '-',
                'nim'                 => $p['nim'] ?? '-',
                'is_active'           => $isAktif,
                'status_kerja'        => $statusKerja,
                'area_tugas'          => $areaTugas,
                'jumlah_tugas_aktif'  => $jumlahTugasAktif,
                'jumlah_selesai'      => $tugasSelesaiCount,
                'aktif_terakhir'      => $waktuTerakhir ? date('d-m-Y H:i', strtotime($waktuTerakhir)) : '-',
                'tugas_aktif_list'    => $tugasAktifList,
                'total_semua_tugas'   => count($daftarTugas),
            ];

            $petugasList[] = $dataPetugas;

            // Jika aktif dan sedang tersedia (0 tugas aktif), masukkan ke rekomendasi
            if ($isAktif === 1 && $jumlahTugasAktif === 0) {
                $rekomendasiPetugas[] = $dataPetugas;
            }
        }

        return view('admin/monitoring_petugas', [
            'judul' => 'Monitoring Petugas Aktif',
            'kpi'   => [
                'petugas_aktif'    => $petugasAktifCount,
                'sedang_bertugas'  => $sedangBertugasCount,
                'tersedia'         => $tersediaCount,
                'laporan_ditangani'=> $laporanDitanganiCount,
            ],
            'petugasList'        => $petugasList,
            'rekomendasiPetugas' => $rekomendasiPetugas,
        ]);
    }
}
