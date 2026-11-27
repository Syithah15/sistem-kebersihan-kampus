<?php

// Mode halaman
$mode = $mode ?? 'list';

// Helper untuk keamanan output dan URL
$e = static fn($value) => esc((string) $value);
$url = static fn($path) => site_url($path);

// Data dari controller
$rows      = $rows ?? [];
$counts    = $counts ?? [];
$locations = $locations ?? [];

$q      = $q ?? '';
$filter = $filter ?? '';
$loc    = $loc ?? '';
$urut   = $urut ?? 'baru';

$page  = max(1, (int) ($page ?? 1));
$pages = max(1, (int) ($pages ?? 1));

$row = $row ?? [];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EcoCampus Admin</title>

    <style>
        /* =========================
           RESET DAN WARNA
        ========================= */

        :root {
            --green-dark: #10472f;
            --green-main: #21834b;
            --green-light: #e4f2e9;
            --green-soft: #eaf4ec;
            --text-main: #164c35;
            --text-muted: #527565;
            --border: #d2e5d9;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: var(--text-main);
            background: var(--green-light);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        h1 {
            margin: 0;
        }

        h2 {
            font-size: 18px;
        }

        .muted {
            color: var(--text-muted);
        }

        /* =========================
           LAYOUT DAN SIDEBAR
        ========================= */

        .layout {
            display: flex;
            min-height: 100vh;
        }

        aside {
            position: sticky;
            top: 0;
            width: 210px;
            height: 100vh;
            flex-shrink: 0;
            padding: 24px 14px;
            background: var(--green-dark);
            color: var(--white);
        }

        aside h2 {
            margin: 0 0 25px;
        }

        nav a {
            display: block;
            margin: 5px 0;
            padding: 12px;
            border-radius: 8px;
        }

        nav a:hover,
        nav a.active {
            background: var(--green-main);
        }

        main {
            flex: 1;
            min-width: 0;
            padding: 28px;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .panel {
            margin-top: 18px;
            padding: 20px;
            border-radius: 14px;
            background: var(--white);
        }

        /* =========================
           FILTER DAN TOMBOL
        ========================= */

        .filters,
        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 16px 0;
        }

        input,
        select,
        button,
        .btn {
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 7px;
            font: inherit;
        }

        input,
        select {
            background: var(--white);
            color: var(--text-main);
        }

        button,
        .btn {
            background: var(--green-main);
            color: var(--white);
            cursor: pointer;
        }

        button:hover,
        .btn:hover {
            background: var(--green-dark);
        }

        .chips a {
            padding: 9px 14px;
            border-radius: 7px;
            background: var(--white);
        }

        /* =========================
           TABEL DAN STATUS
        ========================= */

        .tablewrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 650px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #dce9e0;
            text-align: left;
        }

        th {
            background: #edf5ef;
        }

        .badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 5px;
            font-size: 11px;
            white-space: nowrap;
        }

        .MENUNGGU {
            background: #fff0bc;
        }

        .DIPROSES {
            background: #dce8ff;
        }

        .SELESAI {
            background: #d1f0d9;
        }

        .DIBATALKAN {
            background: #ffe0e0;
        }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .pagination a {
            padding: 8px;
            border-radius: 5px;
            background: var(--green-light);
        }

        /* =========================
           PETA SEBARAN
        ========================= */

        .map {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 15px;
            background: var(--green-soft);
        }

        .tile {
            min-height: 110px;
            padding: 20px;
            border-radius: 10px;
            background: #c9e4d0;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {
            .layout {
                display: block;
            }

            aside {
                position: static;
                width: 100%;
                height: auto;
            }

            nav {
                display: flex;
                flex-wrap: wrap;
            }

            main {
                padding: 14px;
            }

            .map {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside>
        <h2>EcoCampus</h2>

        <nav>
            <a href="<?= $e($url('admin/dashboard')) ?>">
                Ringkasan
            </a>

            <a
                href="<?= $e($url('admin/daftar-laporan')) ?>"
                class="<?= $mode === 'list' ? 'active' : '' ?>"
            >
                Daftar Laporan
            </a>

            <a
                href="<?= $e($url('admin/peta')) ?>"
                class="<?= $mode === 'map' ? 'active' : '' ?>"
            >
                Peta Sebaran
            </a>

            <a href="<?= $e($url('admin/monitoring-petugas')) ?>">
                Petugas
            </a>
        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <main>

        <?php if ($mode === 'list'): ?>

            <!-- DAFTAR LAPORAN -->
            <div class="head">
                <div>
                    <h1>Daftar Laporan</h1>

                    <p class="muted">
                        Kelola laporan kebersihan dan pantau tindak lanjutnya.
                    </p>
                </div>

                <span class="badge SELESAI">Sistem aktif</span>
            </div>

            <!-- FORM FILTER -->
            <form class="filters" method="get">

                <input
                    type="text"
                    name="q"
                    placeholder="Cari ID, lokasi, pelapor..."
                    value="<?= $e($q) ?>"
                >

                <select name="status">
                    <option value="">Semua status</option>

                    <?php foreach ($counts as $status => $jumlah): ?>
                        <option
                            value="<?= $e($status) ?>"
                            <?= $filter === $status ? 'selected' : '' ?>
                        >
                            <?= $e($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="lokasi">
                    <option value="">Semua lokasi</option>

                    <?php foreach ($locations as $location): ?>
                        <option
                            value="<?= $e($location['nama_lokasi']) ?>"
                            <?= $loc === $location['nama_lokasi'] ? 'selected' : '' ?>
                        >
                            <?= $e($location['nama_lokasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="urut">
                    <option value="baru" <?= $urut === 'baru' ? 'selected' : '' ?>>
                        Terbaru
                    </option>

                    <option value="lama" <?= $urut === 'lama' ? 'selected' : '' ?>>
                        Terlama
                    </option>
                </select>

                <button type="submit">Cari</button>

                <a class="btn" href="<?= $e($url('admin/daftar-laporan')) ?>">
                    Reset
                </a>
            </form>

            <!-- RINGKASAN STATUS -->
            <div class="chips">
                <a href="<?= $e($url('admin/daftar-laporan')) ?>">
                    Semua <?= array_sum($counts) ?>
                </a>

                <?php foreach ($counts as $status => $jumlah): ?>
                    <a
                        class="badge <?= $e($status) ?>"
                        href="?status=<?= $e($status) ?>"
                    >
                        <?= $e($status) ?> <?= (int) $jumlah ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- TABEL LAPORAN -->
            <section class="panel">
                <h2>Semua Laporan</h2>

                <div class="tablewrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Lokasi & Laporan</th>
                                <th>Pelapor</th>
                                <th>Petugas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="6">
                                        Belum ada laporan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rows as $report): ?>
                                    <tr>
                                        <td>
                                            LP-<?= str_pad(
                                                (string) $report['id'],
                                                4,
                                                '0',
                                                STR_PAD_LEFT
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= $e($report['nama_lokasi'] ?? '-') ?>
                                            <br>
                                            <?= $e($report['judul']) ?>
                                        </td>

                                        <td>
                                            <?= $e($report['pelapor'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= $e($report['petugas'] ?? 'Belum ditugaskan') ?>
                                        </td>

                                        <td>
                                            <span class="badge <?= $e($report['status']) ?>">
                                                <?= $e($report['status']) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <a href="<?= $e($url('admin/laporan/' . $report['id'])) ?>">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="pagination">
                    <span>Halaman <?= $page ?> / <?= $pages ?></span>

                    <?php
                    $params = $_GET;

                    $pagination = [
                        max(1, $page - 1) => 'Sebelumnya',
                        min($pages, $page + 1) => 'Berikutnya',
                    ];
                    ?>

                    <?php foreach ($pagination as $targetPage => $label): ?>
                        <?php $params['page'] = $targetPage; ?>

                        <a href="?<?= $e(http_build_query($params)) ?>">
                            <?= $e($label) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

        <?php elseif ($mode === 'map'): ?>

            <!-- PETA SEBARAN -->
            <h1>Peta Sebaran Laporan</h1>

            <p class="muted">
                Sebaran laporan aktif berdasarkan lokasi (skema, bukan peta GPS).
            </p>

            <?php
            $group = [];

            foreach ($rows as $report) {
                $locationName = $report['nama_lokasi'] ?? 'Lokasi belum tersedia';
                $group[$locationName][] = $report;
            }
            ?>

            <div class="map">
                <?php if (empty($group)): ?>
                    <p>Belum ada laporan aktif.</p>
                <?php else: ?>
                    <?php foreach ($group as $name => $items): ?>
                        <div class="tile">
                            <strong><?= $e($name) ?></strong>

                            <p><?= count($items) ?> laporan aktif</p>

                            <?php foreach ($items as $report): ?>
                                <a href="<?= $e($url('admin/laporan/' . $report['id'])) ?>">
                                    <?= $e($report['judul']) ?>
                                </a>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <!-- DETAIL LAPORAN -->
            <section class="panel">
                <h1>Detail Laporan</h1>

                <?php
                $detailFields = [
                    'judul'       => 'Judul',
                    'deskripsi'   => 'Deskripsi',
                    'nama_lokasi' => 'Lokasi',
                    'pelapor'     => 'Pelapor',
                    'petugas'     => 'Petugas',
                    'status'      => 'Status',
                ];
                ?>

                <?php foreach ($detailFields as $key => $label): ?>
                    <p>
                        <strong><?= $e($label) ?>:</strong>
                        <?= $e($row[$key] ?? '-') ?>
                    </p>
                <?php endforeach; ?>

                <a class="btn" href="<?= $e($url('admin/daftar-laporan')) ?>">
                    Kembali
                </a>
            </section>

        <?php endif; ?>

    </main>
</div>

</body>
</html>