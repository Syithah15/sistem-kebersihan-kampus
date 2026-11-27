<?php
$active = 'dashboard';

$labels = [
    'MENUNGGU'    => 'Menunggu',
    'DIPROSES'    => 'Diproses',
    'SELESAI'     => 'Selesai',
    'DIBATALKAN'  => 'Dibatalkan',
];

$grafik = $grafik ?? [];
$laporanTerbaru = $laporanTerbaru ?? [];
$max = max(1, max(array_column($grafik, 'jumlah') ?: [0]));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ringkasan Admin - EcoCampus</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #e2f0e7;
            color: #153d2c;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            flex-shrink: 0;
            background: #10462e;
            color: white;
            padding: 24px 16px;
        }

        .sidebar h2 {
            margin: 0 0 28px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px;
            border-radius: 9px;
            margin: 8px 0;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #21854b;
        }

        /* Konten utama */
        main {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        h1 {
            margin: 0 0 8px;
        }

        .muted {
            color: #496b5a;
        }

        /* Kartu status */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .card,
        .panel {
            background: white;
            border-radius: 16px;
            padding: 22px;
        }

        .card p {
            margin: 0 0 10px;
        }

        .card strong {
            font-size: 30px;
        }

        .panel {
            margin-top: 20px;
            overflow-x: auto;
        }

        .panel h2 {
            margin-top: 0;
        }

        /* Grafik */
        .chart {
            height: 180px;
            display: flex;
            align-items: stretch;
            gap: 12px;
        }

        .bar-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            gap: 5px;
        }

        .bar {
            width: 28px;
            min-height: 2px;
            background: #8ac79c;
            border-radius: 5px 5px 0 0;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #dce8df;
        }

        th {
            background: #edf6ef;
        }

        .badge {
            display: inline-block;
            background: #e5f3e9;
            border-radius: 16px;
            padding: 5px 9px;
        }

        @media (max-width: 750px) {
            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            main {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <h2>EcoCampus</h2>

        <a class="<?= $active === 'dashboard' ? 'active' : '' ?>"
           href="<?= site_url('admin/dashboard') ?>">
            Ringkasan
        </a>

        <a href="<?= site_url('admin/monitoring-petugas') ?>">
            Petugas
        </a>
    </aside>

    <main>
        <h1>Ringkasan Kebersihan</h1>
        <p class="muted">
            Pantau laporan dan penanganan kebersihan kampus.
        </p>

        <section class="cards">
            <?php foreach ($status as $namaStatus => $jumlah): ?>
                <div class="card">
                    <p><?= esc($labels[$namaStatus] ?? $namaStatus) ?></p>
                    <strong><?= (int) $jumlah ?></strong>
                </div>
            <?php endforeach ?>
        </section>

        <section class="panel">
            <h2>Total Laporan</h2>
            <strong><?= (int) $totalLaporan ?></strong>
        </section>

        <section class="panel">
            <h2>Laporan 7 Hari Terakhir</h2>

            <div class="chart">
                <?php foreach ($grafik as $dataGrafik): ?>
                    <div class="bar-column">
                        <span><?= (int) $dataGrafik['jumlah'] ?></span>

                        <div
                            class="bar"
                            style="height: <?= max(2, 130 * $dataGrafik['jumlah'] / $max) ?>px"
                        ></div>

                        <small><?= esc($dataGrafik['tanggal']) ?></small>
                    </div>
                <?php endforeach ?>
            </div>
        </section>

        <section class="panel">
            <h2>Laporan Terbaru</h2>

            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pelapor</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporanTerbaru)): ?>
                        <tr>
                            <td colspan="4">Belum ada laporan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($laporanTerbaru as $laporan): ?>
                            <tr>
                                <td><?= esc($laporan['judul']) ?></td>
                                <td><?= esc($laporan['pelapor'] ?? '-') ?></td>
                                <td><?= esc($laporan['nama_lokasi'] ?? '-') ?></td>
                                <td>
                                    <span class="badge">
                                        <?= esc($labels[$laporan['status']] ?? $laporan['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </section>
    </main>

</div>

</body>
</html>