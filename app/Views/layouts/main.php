<?php
$role = session()->get('role');
$nama = session()->get('nama');
$userId = session()->get('user_id');

$jumlahNotifikasiBelumDibaca = 0;

if ($userId) {
    try {
        $jumlahNotifikasiBelumDibaca = \Config\Database::connect()
            ->table('notifikasi')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    } catch (\Throwable $e) {
        $jumlahNotifikasiBelumDibaca = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($judul ?? 'Lapor Bersih Kampus') ?></title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#198754">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <?= $this->renderSection('styles') ?>

    <style>
        body {
            background-color: #f5f7f5;
            min-height: 100vh;
        }

        .navbar {
            background-color: #198754;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .navbar .nav-link {
            color: rgba(255, 255, 255, 0.9);
        }

        .navbar .nav-link:hover {
            color: #ffffff;
        }

        .main-content {
            min-height: calc(100vh - 150px);
        }

        .content-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid #e5e5e5;
            padding: 18px 0;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand" href="/">
                <i class="bi bi-recycle me-2"></i>
                Lapor Bersih Kampus
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarUtama"
                aria-controls="navbarUtama"
                aria-expanded="false"
                aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarUtama">

                <?php if (session()->get('isLoggedIn')): ?>

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <?php if ($role === 'pelapor'): ?>
                                <a class="nav-link" href="/pelapor/dashboard">
                                    <i class="bi bi-speedometer2 me-1"></i>
                                    Dashboard
                                </a>
                            <?php elseif ($role === 'petugas'): ?>
                                <a class="nav-link" href="/petugas/dashboard">
                                    <i class="bi bi-speedometer2 me-1"></i>
                                    Dashboard
                                </a>
                            <?php elseif ($role === 'admin'): ?>
                                <a class="nav-link" href="/admin/dashboard">
                                    <i class="bi bi-speedometer2 me-1"></i>
                                    Dashboard
                                </a>
                            <?php endif; ?>
                        </li>

                        <!-- Menu Pelapor -->
                        <?php if ($role === 'pelapor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/pelapor/laporan/buat">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Buat Laporan
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Menu Admin -->
                        <?php if ($role === 'admin'): ?>

                            <li class="nav-item">
                                <a class="nav-link" href="/admin/laporan">
                                    <i class="bi bi-clipboard-data me-1"></i>
                                    Rekap Laporan
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/admin/peta-laporan">
                                    <i class="bi bi-map me-1"></i>
                                    Peta Laporan
                                </a>
                            </li>

                        <?php endif; ?>

                        <!-- Notifikasi -->
                        <li class="nav-item">
                            <a class="nav-link" href="/notifikasi">
                                <i class="bi bi-bell me-1"></i>
                                Notifikasi

                                <?php if ($jumlahNotifikasiBelumDibaca > 0): ?>
                                    <span class="badge bg-danger">
                                        <?= $jumlahNotifikasiBelumDibaca ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>

                    </ul>

                    <!-- Informasi Pengguna -->
                    <div class="d-flex align-items-center gap-3">

                        <span class="text-white">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= esc($nama ?? 'Pengguna') ?>
                        </span>

                        <form action="/logout" method="post" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Keluar
                            </button>
                        </form>

                    </div>

                <?php endif; ?>

            </div>
        </div>
    </nav>

    <!-- Konten Halaman -->
    <main class="container-fluid main-content py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            &copy; <?= date('Y') ?> Lapor Bersih Kampus.
            Sistem Pelaporan Kebersihan Kampus.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Registrasi Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function (registration) {
                        console.log(
                            'Service Worker berhasil didaftarkan:',
                            registration.scope
                        );
                    })
                    .catch(function (error) {
                        console.error(
                            'Service Worker gagal didaftarkan:',
                            error
                        );
                    });
            });
        }
    </script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>