<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; background: #f4f6f8; }
        .notif { background: #fff; padding: 12px 16px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #ccc; }
        .notif.baru { border-left-color: #2b7de9; background: #eef5ff; }
        .notif small { color: #666; }
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

    <?php if (empty($notifikasi)): ?>
        <p>Belum ada notifikasi.</p>
    <?php else: foreach ($notifikasi as $n): ?>
        <div class="notif <?= $n['is_read'] ? '' : 'baru' ?>">
            <b><?= esc($n['judul']) ?></b><br>
            <?= esc($n['pesan']) ?><br>
            <small><?= esc($n['created_at']) ?></small>
            <?php if (! $n['is_read']): ?>
                | <a href="/notifikasi/baca/<?= $n['id'] ?>">Tandai sudah dibaca</a>
            <?php endif; ?>
        </div>
    <?php endforeach; endif; ?>
</body>
</html>