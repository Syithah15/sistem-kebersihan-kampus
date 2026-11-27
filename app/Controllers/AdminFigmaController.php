<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class AdminFigmaController extends BaseController
{
    /**
     * Batasi akses hanya untuk admin yang sudah login.
     */
    private function guard()
    {
        $session = session();

        if (
            !$session->get('isLoggedIn')
            || $session->get('role') !== 'admin'
        ) {
            return redirect()->to('/');
        }

        return null;
    }

    /**
     * Daftar laporan dengan filter dan pagination.
     */
    public function index()
    {
        if ($response = $this->guard()) {
            return $response;
        }

        $db = db_connect();

        $statusList = [
            'MENUNGGU',
            'DIPROSES',
            'SELESAI',
            'DIBATALKAN',
        ];

        // Jumlah laporan untuk setiap status.
        $counts = [];

        foreach ($statusList as $status) {
            $counts[$status] = $db->table('laporan')
                ->where('status', $status)
                ->countAllResults();
        }

        // Query utama laporan.
        $builder = $db->table('laporan l')
            ->select(
                'l.id, l.judul, l.status, l.created_at,
                 u.nama AS pelapor,
                 lo.nama_lokasi,
                 t.petugas_id,
                 p.nama AS petugas'
            )
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('lokasi lo', 'lo.laporan_id = l.id', 'left')
            ->join('tugas t', 't.laporan_id = l.id', 'left')
            ->join('users p', 'p.id = t.petugas_id', 'left');

        // Filter status.
        $filter = (string) $this->request->getGet('status');

        if (in_array($filter, $statusList, true)) {
            $builder->where('l.status', $filter);
        }

        // Pencarian berdasarkan ID, judul, lokasi, atau pelapor.
        $q = trim((string) $this->request->getGet('q'));

        if ($q !== '') {
            $builder->groupStart()
                ->like('l.judul', $q)
                ->orLike('lo.nama_lokasi', $q)
                ->orLike('u.nama', $q);

            if (ctype_digit($q)) {
                $builder->orWhere('l.id', (int) $q);
            }

            $builder->groupEnd();
        }

        // Filter lokasi.
        $loc = trim((string) $this->request->getGet('lokasi'));

        if ($loc !== '') {
            $builder->like('lo.nama_lokasi', $loc);
        }

        // Urutan laporan.
        $urut = $this->request->getGet('urut') === 'lama'
            ? 'lama'
            : 'baru';

        $direction = $urut === 'lama' ? 'ASC' : 'DESC';

        // Pagination.
        $perPage = 8;

        $total = (clone $builder)->countAllResults();

        $pages = max(1, (int) ceil($total / $perPage));

        $page = max(
            1,
            (int) $this->request->getGet('page')
        );

        $page = min($page, $pages);

        $offset = ($page - 1) * $perPage;

        // Ambil laporan sesuai urutan dan halaman.
        $rows = $builder
            ->orderBy('l.created_at', $direction)
            ->orderBy('l.id', $direction)
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        // Daftar lokasi untuk pilihan filter.
        $locations = $db->table('lokasi')
            ->select('nama_lokasi')
            ->distinct()
            ->orderBy('nama_lokasi', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/figma', [
            'mode'      => 'list',
            'rows'      => $rows,
            'counts'    => $counts,
            'total'     => $total,
            'page'      => $page,
            'pages'     => $pages,
            'q'         => $q,
            'filter'    => $filter,
            'loc'       => $loc,
            'urut'      => $urut,
            'locations' => $locations,
        ]);
    }

    /**
     * Peta sebaran laporan aktif.
     */
    public function map()
    {
        if ($response = $this->guard()) {
            return $response;
        }

        $rows = db_connect()
            ->table('laporan l')
            ->select('lo.nama_lokasi, l.id, l.judul, l.status')
            ->join('lokasi lo', 'lo.laporan_id = l.id')
            ->whereIn('l.status', ['MENUNGGU', 'DIPROSES'])
            ->orderBy('l.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/figma', [
            'mode' => 'map',
            'rows' => $rows,
        ]);
    }

    /**
     * Detail satu laporan.
     */
    public function detail($id)
    {
        if ($response = $this->guard()) {
            return $response;
        }

        $row = db_connect()
            ->table('laporan l')
            ->select(
                'l.*, u.nama AS pelapor,
                 lo.nama_lokasi,
                 t.petugas_id,
                 p.nama AS petugas'
            )
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('lokasi lo', 'lo.laporan_id = l.id', 'left')
            ->join('tugas t', 't.laporan_id = l.id', 'left')
            ->join('users p', 'p.id = t.petugas_id', 'left')
            ->where('l.id', (int) $id)
            ->get()
            ->getRowArray();

        if (!$row) {
            throw PageNotFoundException::forPageNotFound(
                'Laporan tidak ditemukan.'
            );
        }

        return view('admin/figma', [
            'mode' => 'detail',
            'row'  => $row,
        ]);
    }
}
