<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .filter-btn.active {
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Navigasi Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/petugas/dashboard" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Tugas</li>
        </ol>
    </nav>

    <!-- Header Halaman -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-success mb-1">
                <i class="bi bi-list-task me-2"></i>Daftar Tugas Petugas
            </h1>
            <p class="text-muted mb-0">
                Kelola dan tindak lanjuti laporan kebersihan yang ditugaskan kepada Anda.
            </p>
        </div>
        <div>
            <a href="/petugas/dashboard" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
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

    <!-- Filter Status Tab -->
    <div class="content-card mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom">
            <div class="fw-bold text-dark">
                <i class="bi bi-funnel me-1"></i> Filter Berdasarkan Status:
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="/petugas/tugas" class="btn btn-sm <?= ($filterStatus === 'SEMUA') ? 'btn-success active' : 'btn-outline-secondary' ?>">
                    Semua <span class="badge bg-light text-dark ms-1"><?= esc($ringkasan['TOTAL'] ?? 0) ?></span>
                </a>
                <a href="/petugas/tugas?status=DITUGASKAN" class="btn btn-sm <?= ($filterStatus === 'DITUGASKAN') ? 'btn-info text-white active' : 'btn-outline-info' ?>">
                    Ditugaskan <span class="badge bg-light text-dark ms-1"><?= esc($ringkasan['DITUGASKAN'] ?? 0) ?></span>
                </a>
                <a href="/petugas/tugas?status=DIKERJAKAN" class="btn btn-sm <?= ($filterStatus === 'DIKERJAKAN') ? 'btn-warning text-dark active' : 'btn-outline-warning' ?>">
                    Sedang Dikerjakan <span class="badge bg-light text-dark ms-1"><?= esc($ringkasan['DIKERJAKAN'] ?? 0) ?></span>
                </a>
                <a href="/petugas/tugas?status=SELESAI" class="btn btn-sm <?= ($filterStatus === 'SELESAI') ? 'btn-success active' : 'btn-outline-success' ?>">
                    Selesai <span class="badge bg-light text-dark ms-1"><?= esc($ringkasan['SELESAI'] ?? 0) ?></span>
                </a>
                <a href="/petugas/tugas?status=DIBATALKAN" class="btn btn-sm <?= ($filterStatus === 'DIBATALKAN') ? 'btn-danger active' : 'btn-outline-danger' ?>">
                    Dibatalkan <span class="badge bg-light text-dark ms-1"><?= esc($ringkasan['DIBATALKAN'] ?? 0) ?></span>
                </a>
            </div>
        </div>

        <!-- Tabel Daftar Tugas -->
        <?php if (!empty($daftarTugas)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Laporan &amp; Deskripsi</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Status Tugas</th>
                            <th>Waktu Ditugaskan</th>
                            <th class="text-center" style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarTugas as $index => $tugas): ?>
                            <tr>
                                <td class="text-muted fw-bold"><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark">
                                        <a href="/petugas/tugas/<?= esc($tugas['id']) ?>" class="text-decoration-none text-dark">
                                            <?= esc($tugas['laporan_judul']) ?>
                                        </a>
                                    </div>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 280px;">
                                        <?= esc($tugas['laporan_deskripsi']) ?>
                                    </small>
                                    <?php if (!empty($tugas['nama_pelapor'])): ?>
                                        <div>
                                            <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">
                                                Pelapor: <?= esc($tugas['nama_pelapor']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= esc($tugas['laporan_kategori']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        <?= esc($tugas['nama_lokasi'] ?: 'Area Kampus') ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $prioClass = [
                                        'RENDAH'  => 'bg-secondary',
                                        'SEDANG'  => 'bg-primary',
                                        'TINGGI'  => 'bg-warning text-dark',
                                        'DARURAT' => 'bg-danger',
                                    ][$tugas['prioritas']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $prioClass ?>">
                                        <?= esc($tugas['prioritas']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'DITUGASKAN' => 'bg-info text-dark',
                                        'DIKERJAKAN' => 'bg-warning text-dark',
                                        'SELESAI'    => 'bg-success',
                                        'DIBATALKAN' => 'bg-danger',
                                    ][$tugas['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= esc($tugas['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= !empty($tugas['created_at']) ? date('d M Y, H:i', strtotime($tugas['created_at'])) : '-' ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/petugas/tugas/<?= esc($tugas['id']) ?>" class="btn btn-outline-primary" title="Detail Tugas">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <a href="/petugas/tugas/<?= esc($tugas['id']) ?>/update" class="btn btn-success" title="Perbarui Status Tindak Lanjut">
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
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <h4 class="h6 fw-bold mt-3">Tidak Ada Tugas Ditemukan</h4>
                <p class="text-muted mb-3">
                    <?php if ($filterStatus !== 'SEMUA'): ?>
                        Tidak ada tugas dengan status <strong><?= esc($filterStatus) ?></strong>.
                    <?php else: ?>
                        Belum ada tugas kebersihan yang ditugaskan kepada Anda saat ini.
                    <?php endif; ?>
                </p>
                <?php if ($filterStatus !== 'SEMUA'): ?>
                    <a href="/petugas/tugas" class="btn btn-outline-success btn-sm">
                        Tampilkan Semua Tugas
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>
