<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .stat-card {
        border-radius: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        border: none;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .bg-icon-primary {
        background-color: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .bg-icon-warning {
        background-color: rgba(255, 193, 7, 0.2);
        color: #ff9800;
    }

    .bg-icon-info {
        background-color: rgba(13, 202, 240, 0.2);
        color: #0aa2c0;
    }

    .bg-icon-success {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Header & Salam -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-success mb-1">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Petugas
            </h1>
            <p class="text-muted mb-0">
                Selamat bertugas, <strong><?= esc($namaPetugas) ?></strong>! Pantau dan perbarui progres tindak lanjut kebersihan kampus.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="/petugas/tugas" class="btn btn-success">
                <i class="bi bi-list-task me-1"></i> Buka Daftar Tugas
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Flashdata -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Kartu Statistik Tugas -->
    <div class="row g-3 mb-4">
        <!-- Total Tugas -->
        <div class="col-sm-6 col-lg-3">
            <div class="content-card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">TOTAL TUGAS</div>
                        <div class="fs-3 fw-bold mt-1 text-dark"><?= esc($ringkasan['TOTAL'] ?? 0) ?></div>
                    </div>
                    <div class="stat-icon bg-icon-primary">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="/petugas/tugas" class="text-decoration-none small text-primary fw-semibold">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tugas Baru / Ditugaskan -->
        <div class="col-sm-6 col-lg-3">
            <div class="content-card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">DITUGASKAN (BARU)</div>
                        <div class="fs-3 fw-bold mt-1 text-info"><?= esc($ringkasan['DITUGASKAN'] ?? 0) ?></div>
                    </div>
                    <div class="stat-icon bg-icon-info">
                        <i class="bi bi-inbox"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="/petugas/tugas?status=DITUGASKAN" class="text-decoration-none small text-info fw-semibold">
                        Perlu ditindaklanjuti <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sedang Dikerjakan -->
        <div class="col-sm-6 col-lg-3">
            <div class="content-card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">SEDANG DIKERJAKAN</div>
                        <div class="fs-3 fw-bold mt-1 text-warning"><?= esc($ringkasan['DIKERJAKAN'] ?? 0) ?></div>
                    </div>
                    <div class="stat-icon bg-icon-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="/petugas/tugas?status=DIKERJAKAN" class="text-decoration-none small text-warning fw-semibold">
                        Dalam proses <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="col-sm-6 col-lg-3">
            <div class="content-card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">SELESAI</div>
                        <div class="fs-3 fw-bold mt-1 text-success"><?= esc($ringkasan['SELESAI'] ?? 0) ?></div>
                    </div>
                    <div class="stat-icon bg-icon-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="/petugas/tugas?status=SELESAI" class="text-decoration-none small text-success fw-semibold">
                        Tugas terselesaikan <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Tugas Terbaru -->
    <div class="content-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history me-2 text-success"></i>Tugas Terbaru
            </h2>
            <a href="/petugas/tugas" class="btn btn-outline-success btn-sm">
                Lihat Semua Tugas
            </a>
        </div>

        <?php if (!empty($tugasTerbaru)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Laporan</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Status Tugas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tugasTerbaru as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <strong><?= esc($row['laporan_judul']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= esc($row['laporan_kategori']) ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-geo-alt text-danger me-1"></i>
                                    <?= esc($row['nama_lokasi'] ?: 'Area Kampus') ?>
                                </td>
                                <td>
                                    <?php
                                    $prioClass = [
                                        'RENDAH'  => 'bg-secondary',
                                        'SEDANG'  => 'bg-primary',
                                        'TINGGI'  => 'bg-warning text-dark',
                                        'DARURAT' => 'bg-danger',
                                    ][$row['prioritas']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $prioClass ?>">
                                        <?= esc($row['prioritas']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'DITUGASKAN' => 'bg-info text-dark',
                                        'DIKERJAKAN' => 'bg-warning text-dark',
                                        'SELESAI'    => 'bg-success',
                                        'DIBATALKAN' => 'bg-danger',
                                    ][$row['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= esc($row['status']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/petugas/tugas/<?= esc($row['id']) ?>" class="btn btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="/petugas/tugas/<?= esc($row['id']) ?>/update" class="btn btn-success" title="Tindak Lanjuti">
                                            <i class="bi bi-pencil-square"></i> Update
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-check2-circle text-success" style="font-size: 3rem;"></i>
                <h3 class="h6 fw-bold mt-2">Belum ada tugas</h3>
                <p class="text-muted mb-0">Saat ini belum ada tugas kebersihan yang ditugaskan kepada Anda.</p>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>
