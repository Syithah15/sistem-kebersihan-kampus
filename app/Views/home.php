<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .hero {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 56px 32px;
    }

    .hero .lead {
        color: rgba(255, 255, 255, 0.9);
    }

    .step-number {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #198754;
        color: #ffffff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }

    .feature-icon {
        font-size: 2rem;
        color: #198754;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$sudahLogin = session()->get('isLoggedIn');
$role       = session()->get('role');
$daftarDashboard = [
    'pelapor' => '/pelapor/dashboard',
    'petugas' => '/petugas/dashboard',
    'admin'   => '/admin/dashboard',
];
$urlDashboard = $daftarDashboard[$role] ?? '/login';
?>

<div class="container">

    <!-- Hero -->
    <section class="hero text-center mb-5">
        <h1 class="display-5 fw-bold">
            <i class="bi bi-recycle me-2"></i>Lapor Bersih Kampus
        </h1>
        <p class="lead mt-3 mb-4">
            Laporkan masalah kebersihan di lingkungan kampus, pantau
            penanganannya, dan jaga kampus tetap bersih bersama.
        </p>

        <?php if ($sudahLogin): ?>
            <a href="<?= esc($urlDashboard) ?>" class="btn btn-light btn-lg">
                <i class="bi bi-speedometer2 me-1"></i> Ke Dashboard
            </a>
        <?php else: ?>
            <a href="/login" class="btn btn-light btn-lg">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </a>
        <?php endif; ?>
    </section>

    <!-- Alur Pelaporan -->
    <section class="mb-5">
        <h2 class="h4 fw-bold text-center mb-4">Bagaimana Cara Kerjanya?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="content-card h-100">
                    <div class="step-number">1</div>
                    <h3 class="h6 fw-bold">Pelapor Membuat Laporan</h3>
                    <p class="text-muted mb-0">
                        Pelapor mengisi laporan masalah kebersihan beserta lokasi dan foto.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card h-100">
                    <div class="step-number">2</div>
                    <h3 class="h6 fw-bold">Petugas Menindaklanjuti</h3>
                    <p class="text-muted mb-0">
                        Petugas mengambil tugas, mengerjakannya, lalu memperbarui statusnya.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card h-100">
                    <div class="step-number">3</div>
                    <h3 class="h6 fw-bold">Admin Memantau</h3>
                    <p class="text-muted mb-0">
                        Admin melihat ringkasan laporan dan memonitor kinerja petugas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section class="mb-4">
        <h2 class="h4 fw-bold text-center mb-4">Fitur Utama</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="content-card h-100 text-center">
                    <i class="bi bi-shield-lock feature-icon"></i>
                    <h3 class="h6 fw-bold mt-2">Login Sesuai Peran</h3>
                    <p class="text-muted mb-0">Akses aplikasi disesuaikan untuk pelapor, petugas, dan admin.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="content-card h-100 text-center">
                    <i class="bi bi-clipboard-check feature-icon"></i>
                    <h3 class="h6 fw-bold mt-2">Laporan &amp; Tugas</h3>
                    <p class="text-muted mb-0">Buat laporan kebersihan dan pantau tindak lanjutnya.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="content-card h-100 text-center">
                    <i class="bi bi-map feature-icon"></i>
                    <h3 class="h6 fw-bold mt-2">Peta Sebaran</h3>
                    <p class="text-muted mb-0">Lihat sebaran lokasi laporan di area kampus.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="content-card h-100 text-center">
                    <i class="bi bi-bell feature-icon"></i>
                    <h3 class="h6 fw-bold mt-2">Notifikasi</h3>
                    <p class="text-muted mb-0">Dapatkan pemberitahuan setiap ada perkembangan laporan.</p>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>
