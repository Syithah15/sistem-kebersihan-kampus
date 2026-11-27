<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model TugasModel
 *
 * Mengelola data tugas untuk petugas kebersihan (US-4).
 * Menghubungkan tabel tugas dengan laporan, lokasi, dan pengguna terkait.
 */
class TugasModel extends Model
{
    protected $table            = 'tugas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Kolom-kolom yang diizinkan untuk diisi atau diperbarui
    protected $allowedFields = [
        'laporan_id',
        'petugas_id',
        'prioritas',
        'status',
        'waktu_diambil',
        'waktu_selesai',
        'foto_sebelum',
        'foto_sesudah',
        'catatan',
    ];

    // Pengaturan timestamps otomatis
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Aturan validasi input tugas
    protected $validationRules = [
        'laporan_id' => 'required|numeric',
        'petugas_id' => 'required|numeric',
        'prioritas'  => 'in_list[RENDAH,SEDANG,TINGGI,DARURAT]',
        'status'     => 'in_list[DITUGASKAN,DIKERJAKAN,SELESAI,DIBATALKAN]',
    ];

    protected $validationMessages = [
        'prioritas' => [
            'in_list' => 'Prioritas harus salah satu dari: RENDAH, SEDANG, TINGGI, DARURAT.',
        ],
        'status' => [
            'in_list' => 'Status harus salah satu dari: DITUGASKAN, DIKERJAKAN, SELESAI, DIBATALKAN.',
        ],
    ];

    /**
     * Mengambil daftar tugas milik petugas tertentu dengan join ke laporan, lokasi, dan pelapor.
     *
     * @param int         $petugasId ID petugas yang sedang login
     * @param string|null $status    Filter status tugas (opsional)
     * @return array
     */
    public function getTugasByPetugas(int $petugasId, ?string $status = null): array
    {
        $builder = $this->builder();
        $builder->select('
            tugas.*,
            laporan.judul AS laporan_judul,
            laporan.deskripsi AS laporan_deskripsi,
            laporan.kategori AS laporan_kategori,
            laporan.foto AS laporan_foto,
            laporan.status AS laporan_status,
            laporan.created_at AS laporan_created_at,
            lokasi.nama_lokasi,
            lokasi.latitude,
            lokasi.longitude,
            users.nama AS nama_pelapor
        ');
        $builder->join('laporan', 'laporan.id = tugas.laporan_id', 'inner');
        $builder->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left');
        $builder->join('users', 'users.id = laporan.user_id', 'left');
        $builder->where('tugas.petugas_id', $petugasId);

        if (!empty($status) && in_array(strtoupper($status), ['DITUGASKAN', 'DIKERJAKAN', 'SELESAI', 'DIBATALKAN'], true)) {
            $builder->where('tugas.status', strtoupper($status));
        }

        $builder->orderBy('tugas.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil informasi detail satu tugas berdasarkan ID dan ID petugas (memastikan kepemilikan).
     *
     * @param int $id        ID tugas
     * @param int $petugasId ID petugas yang login
     * @return array|null
     */
    public function getDetailTugas(int $id, int $petugasId): ?array
    {
        $builder = $this->builder();
        $builder->select('
            tugas.*,
            laporan.judul AS laporan_judul,
            laporan.deskripsi AS laporan_deskripsi,
            laporan.kategori AS laporan_kategori,
            laporan.foto AS laporan_foto,
            laporan.status AS laporan_status,
            laporan.created_at AS laporan_created_at,
            lokasi.nama_lokasi,
            lokasi.latitude,
            lokasi.longitude,
            lokasi.akurasi_gps,
            users.nama AS nama_pelapor,
            users.email AS email_pelapor
        ');
        $builder->join('laporan', 'laporan.id = tugas.laporan_id', 'inner');
        $builder->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left');
        $builder->join('users', 'users.id = laporan.user_id', 'left');
        $builder->where('tugas.id', $id);
        $builder->where('tugas.petugas_id', $petugasId);

        $result = $builder->get()->getRowArray();

        return $result ?: null;
    }

    /**
     * Menghitung ringkasan jumlah tugas per status untuk petugas tertentu.
     *
     * @param int $petugasId ID petugas yang login
     * @return array
     */
    public function getRingkasanStatus(int $petugasId): array
    {
        $rows = $this->db->table($this->table)
            ->select('status, COUNT(*) AS jumlah')
            ->where('petugas_id', $petugasId)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $ringkasan = [
            'DITUGASKAN' => 0,
            'DIKERJAKAN' => 0,
            'SELESAI'    => 0,
            'DIBATALKAN' => 0,
            'TOTAL'      => 0,
        ];

        foreach ($rows as $row) {
            $status = strtoupper($row['status']);
            if (isset($ringkasan[$status])) {
                $ringkasan[$status] = (int) $row['jumlah'];
            }
        }

        $ringkasan['TOTAL'] = $ringkasan['DITUGASKAN'] + $ringkasan['DIKERJAKAN'] + $ringkasan['SELESAI'] + $ringkasan['DIBATALKAN'];

        return $ringkasan;
    }

    /**
     * Mengambil sejumlah tugas terbaru milik petugas tertentu.
     *
     * @param int $petugasId ID petugas yang login
     * @param int $limit     Jumlah tugas maksimal
     * @return array
     */
    public function getTugasTerbaru(int $petugasId, int $limit = 5): array
    {
        $builder = $this->builder();
        $builder->select('
            tugas.*,
            laporan.judul AS laporan_judul,
            laporan.kategori AS laporan_kategori,
            laporan.status AS laporan_status,
            lokasi.nama_lokasi
        ');
        $builder->join('laporan', 'laporan.id = tugas.laporan_id', 'inner');
        $builder->join('lokasi', 'lokasi.laporan_id = laporan.id', 'left');
        $builder->where('tugas.petugas_id', $petugasId);
        $builder->orderBy('tugas.created_at', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil riwayat perubahan status untuk suatu laporan.
     *
     * @param int $laporanId ID laporan
     * @return array
     */
    public function getRiwayatStatus(int $laporanId): array
    {
        return $this->db->table('riwayat_status')
            ->select('riwayat_status.*, users.nama AS nama_pengubah, users.role AS role_pengubah')
            ->join('users', 'users.id = riwayat_status.user_id', 'left')
            ->where('riwayat_status.laporan_id', $laporanId)
            ->orderBy('riwayat_status.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
}
