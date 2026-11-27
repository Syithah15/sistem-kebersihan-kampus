<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; background: #f4f6f8; }
        .kartu-wrap { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .kartu { background: #fff; padding: 16px 20px; border-radius: 8px; min-width: 140px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .kartu b { display: block; font-size: 28px; }
        table { width: 100%; background: #fff; border-collapse: collapse; }
        th, td { padding: 8px 12px; border-bottom: 1px solid #ddd; text-align: left; }
        nav a { margin-right: 12px; }
    </style>
</head>
<body>
    <nav>
        <a href="/dashboard">Dashboard</a>
        <a href="/peta">Peta Sebaran</a>
        <a href="/notifikasi">Notifikasi</a>
    </nav>

    <h1><?= esc($title) ?></h1>

    <div class="kartu-wrap">
        <div class="kartu"><b><?= $total ?></b>Total laporan</div>
        <div class="kartu"><b><?= $hitung['MENUNGGU'] ?></b>Menunggu</div>
        <div class="kartu"><b><?= $hitung['DIPROSES'] ?></b>Diproses</div>
        <div class="kartu"><b><?= $hitung['SELESAI'] ?></b>Selesai</div>
        <div class="kartu"><b><?= $hitung['DIBATALKAN'] ?></b>Dibatalkan</div>
    </div>

    <h2>Laporan terbaru</h2>
    <table>
        <tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr>
        <?php if (empty($terbaru)): ?>
            <tr><td colspan="4">Belum ada laporan.</td></tr>
        <?php else: foreach ($terbaru as $l): ?>
            <tr>
                <td><?= esc($l['judul']) ?></td>
                <td><?= esc($l['kategori']) ?></td>
                <td><?= esc($l['status']) ?></td>
                <td><?= esc($l['created_at']) ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </table>
</body>
</html>