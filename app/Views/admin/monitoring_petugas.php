<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --eco-green: #15803D;
        --eco-dark-green: #14532D;
        --eco-light-green: #F2F9F5;
    }

    .bg-eco-primary {
        background-color: var(--eco-green) !important;
        color: #ffffff !important;
    }

    .bg-eco-dark {
        background-color: var(--eco-dark-green) !important;
        color: #ffffff !important;
    }

    .bg-eco-soft {
        background-color: var(--eco-light-green) !important;
    }

    .text-eco-primary {
        color: var(--eco-green) !important;
    }

    .btn-eco-primary {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: #ffffff;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-eco-primary:hover {
        background-color: var(--eco-dark-green);
        border-color: var(--eco-dark-green);
        color: #ffffff;
    }

    .btn-eco-outline {
        border: 1.5px solid var(--eco-green);
        color: var(--eco-green);
        background-color: transparent;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-eco-outline:hover {
        background-color: var(--eco-green);
        color: #ffffff;
    }

    .btn-touch {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .kpi-card {
        border-radius: 12px;
        border: 1px solid rgba(21, 128, 61, 0.12);
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
    }

    .kpi-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .petugas-table th {
        background-color: var(--eco-light-green);
        color: var(--eco-dark-green);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .rekomendasi-card {
        border-left: 4px solid var(--eco-green);
        border-radius: 8px;
        background: #ffffff;
        transition: transform 0.2s ease;
    }

    .rekomendasi-card:hover {
        background: var(--eco-light-green);
        transform: translateX(2px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid py-2">

    <!-- HEADER HALAMAN -->
    <div class="content-card mb-4 bg-eco-soft border-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-eco-primary mb-2 px-3 py-2">
                    <i class="bi bi-people-fill me-1"></i> Modul 5 — Monitoring Petugas
                </span>
                <h3 class="fw-bold mb-1 text-dark">
                    <?= esc($judul ?? 'Monitoring Petugas Aktif') ?>
                </h3>
                <p class="text-muted mb-0">
                    Pantau ketersediaan, beban tugas aktif, dan distribusi penanganan laporan petugas kebersihan kampus.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="<?= site_url('admin/ringkasan') ?>" class="btn btn-eco-outline">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Ringkasan
                </a>
                <a href="<?= site_url('admin/peta-laporan') ?>" class="btn btn-outline-secondary btn-touch">
                    <i class="bi bi-map me-1"></i> Peta Sebaran
                </a>
                <a href="<?= site_url('admin/laporan') ?>" class="btn btn-outline-secondary btn-touch">
                    <i class="bi bi-clipboard-data me-1"></i> Rekap Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KARTU KPI BOOTSTRAP -->
    <div class="row g-3 mb-4">
        <!-- 1. Petugas Aktif -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Petugas Aktif</span>
                        <h2 class="fw-bold text-dark mb-0">
                            <?= esc($kpi['petugas_aktif'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-eco-soft text-eco-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-check-circle me-1"></i>Total akun petugas siap kerja
                </div>
            </div>
        </div>

        <!-- 2. Sedang Bertugas -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Sedang Bertugas</span>
                        <h2 class="fw-bold text-warning mb-0">
                            <?= esc($kpi['sedang_bertugas'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-clock-history me-1"></i>Memiliki tugas aktif &gt; 0
                </div>
            </div>
        </div>

        <!-- 3. Tersedia -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100" style="border-left: 4px solid var(--eco-green);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Tersedia</span>
                        <h2 class="fw-bold text-success mb-0">
                            <?= esc($kpi['tersedia'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-success bg-opacity-10 text-success">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-lightning-charge me-1"></i>Siap ditugaskan (tugas aktif = 0)
                </div>
            </div>
        </div>

        <!-- 4. Laporan Ditangani -->
        <div class="col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Laporan Ditangani</span>
                        <h2 class="fw-bold text-primary mb-0">
                            <?= esc($kpi['laporan_ditangani'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-gear-wide-connected me-1"></i>Total tugas Ditugaskan &amp; Dikerjakan
                </div>
            </div>
        </div>
    </div>

    <!-- PANEL DISTRIBUSI TUGAS AKTIF & REKOMENDASI PETUGAS TERSEDIA -->
    <div class="row g-4 mb-4">
        <!-- REKOMENDASI PETUGAS TERSEDIA -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-stars text-eco-primary me-2"></i>
                            Rekomendasi Petugas Tersedia
                        </h5>
                        <small class="text-muted">Petugas aktif tanpa beban kerja saat ini, siap menerima tugas baru</small>
                    </div>
                    <span class="badge bg-success">
                        <?= count($rekomendasiPetugas ?? []) ?> Tersedia
                    </span>
                </div>

                <div class="card-body p-3">
                    <?php if (!empty($rekomendasiPetugas)): ?>
                        <div class="d-flex flex-column gap-2" style="max-height: 320px; overflow-y: auto;">
                            <?php foreach ($rekomendasiPetugas as $rek): ?>
                                <div class="rekomendasi-card p-3 border shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-light text-dark border font-monospace fw-bold">
                                                <?= esc($rek['kode']) ?>
                                            </span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-check2 me-1"></i>Siap Ditugaskan
                                            </span>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark"><?= esc($rek['nama']) ?></h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-envelope me-1"></i><?= esc($rek['email']) ?>
                                            <span class="mx-1">&bull;</span>
                                            <i class="bi bi-award me-1"></i>Tuntas: <strong><?= $rek['jumlah_selesai'] ?></strong> laporan
                                        </div>
                                    </div>

                                    <button type="button"
                                            class="btn btn-outline-success btn-sm btn-touch px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetailPetugas<?= $rek['id'] ?>">
                                        <i class="bi bi-eye me-1"></i> Profil
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 bg-eco-soft rounded">
                            <i class="bi bi-info-circle text-muted fs-2 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Tidak Ada Petugas yang Tersedia</h6>
                            <p class="text-muted small mb-0">
                                Semua petugas saat ini sedang menangani tugas kebersihan atau belum ada petugas aktif.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- PANEL DISTRIBUSI BEBAN TUGAS AKTIF -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-pie-chart text-eco-primary me-2"></i>
                            Distribusi Tugas Aktif
                        </h5>
                        <small class="text-muted">Penyebaran beban kerja seluruh petugas kebersihan</small>
                    </div>
                    <span class="badge bg-eco-soft text-eco-primary border">
                        <?= count($petugasList ?? []) ?> Petugas Terdata
                    </span>
                </div>

                <div class="card-body p-3">
                    <?php if (!empty($petugasList)): ?>
                        <div class="d-flex flex-column gap-3" style="max-height: 320px; overflow-y: auto;">
                            <?php foreach ($petugasList as $p): ?>
                                <?php
                                $totalTugasP = $p['jumlah_tugas_aktif'];
                                $persentase = ($kpi['laporan_ditangani'] > 0)
                                    ? round(($totalTugasP / $kpi['laporan_ditangani']) * 100)
                                    : 0;
                                ?>
                                <div class="p-2 rounded border bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <span class="fw-bold text-dark"><?= esc($p['nama']) ?></span>
                                            <span class="text-muted small ms-1">(<?= esc($p['kode']) ?>)</span>
                                        </div>
                                        <div>
                                            <?php if ($totalTugasP > 0): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <?= $totalTugasP ?> Tugas Aktif (<?= $persentase ?>%)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    0 Beban Tugas
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar <?= $totalTugasP > 2 ? 'bg-danger' : ($totalTugasP > 0 ? 'bg-warning' : 'bg-success') ?>"
                                             role="progressbar"
                                             style="width: <?= max(5, $persentase) ?>%;"
                                             aria-valuenow="<?= $persentase ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px; margin-top: 4px;">
                                        <span>Area: <?= esc($p['area_tugas']) ?></span>
                                        <span>Aktif: <?= esc($p['aktif_terakhir']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 bg-light rounded text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Data Petugas</h6>
                            <p class="small text-muted mb-0">Belum ada akun user ber-role petugas yang terdaftar di sistem.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL SEMUA USER BER-ROLE PETUGAS -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-table text-eco-primary me-2"></i>
                    Daftar Petugas Kebersihan
                </h5>
                <small class="text-muted">Seluruh user dengan role petugas beserta status operasional dan tugas aktifnya</small>
            </div>
            <span class="badge bg-eco-primary px-3 py-2">
                Total: <?= count($petugasList ?? []) ?> Petugas
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 petugas-table">
                    <thead>
                        <tr>
                            <th class="ps-3 py-3" style="width: 110px;">Kode</th>
                            <th class="py-3">Nama Petugas</th>
                            <th class="py-3">Area Tugas</th>
                            <th class="py-3 text-center" style="width: 140px;">Status</th>
                            <th class="py-3 text-center" style="width: 140px;">Tugas Aktif</th>
                            <th class="py-3" style="width: 170px;">Aktif Terakhir</th>
                            <th class="pe-3 py-3 text-end" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($petugasList)): ?>
                            <?php foreach ($petugasList as $p): ?>
                                <tr>
                                    <!-- KODE -->
                                    <td class="ps-3">
                                        <span class="badge bg-light text-dark border font-monospace fw-bold">
                                            <?= esc($p['kode']) ?>
                                        </span>
                                    </td>

                                    <!-- NAMA & KONTAK -->
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($p['nama']) ?></div>
                                        <div class="text-muted small">
                                            <i class="bi bi-envelope me-1"></i><?= esc($p['email']) ?>
                                            <?php if (!empty($p['nim']) && $p['nim'] !== '-'): ?>
                                                <span class="mx-1">&bull;</span>
                                                <i class="bi bi-person-badge me-1"></i>NIP/NIM: <?= esc($p['nim']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- AREA TUGAS -->
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-geo-alt text-eco-primary"></i>
                                            <span class="text-dark small fw-medium"><?= esc($p['area_tugas']) ?></span>
                                        </div>
                                    </td>

                                    <!-- BADGE STATUS (BERTUGAS / TERSEDIA) -->
                                    <td class="text-center">
                                        <?php if ($p['status_kerja'] === 'Bertugas'): ?>
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-briefcase-fill me-1"></i>Bertugas
                                            </span>
                                        <?php elseif ($p['status_kerja'] === 'Tersedia'): ?>
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary px-3 py-2">
                                                <?= esc($p['status_kerja']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- JUMLAH TUGAS AKTIF -->
                                    <td class="text-center">
                                        <?php if ($p['jumlah_tugas_aktif'] > 0): ?>
                                            <span class="badge bg-primary px-3 py-2 fs-6">
                                                <?= $p['jumlah_tugas_aktif'] ?> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-2 py-1">
                                                0 Aktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- AKTIF TERAKHIR -->
                                    <td>
                                        <span class="small text-muted">
                                            <i class="bi bi-clock me-1"></i><?= esc($p['aktif_terakhir']) ?>
                                        </span>
                                    </td>

                                    <!-- AKSI MODAL DETAIL -->
                                    <td class="pe-3 text-end">
                                        <button type="button"
                                                class="btn btn-outline-success btn-sm btn-touch px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetailPetugas<?= $p['id'] ?>"
                                                title="Lihat Detail Petugas">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                                    Belum ada data petugas yang terdaftar di sistem.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL BOOTSTRAP DETAIL PER PETUGAS -->
<?php if (!empty($petugasList)): ?>
    <?php foreach ($petugasList as $p): ?>
        <div class="modal fade" id="modalDetailPetugas<?= $p['id'] ?>" tabindex="-1" aria-labelledby="modalLabelPetugas<?= $p['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <!-- Modal Header -->
                    <div class="modal-header bg-eco-soft border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="kpi-icon-wrap bg-white text-eco-primary border" style="width: 44px; height: 44px;">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark" id="modalLabelPetugas<?= $p['id'] ?>">
                                    Detail Petugas &mdash; <?= esc($p['nama']) ?>
                                </h5>
                                <span class="badge bg-light text-muted border font-monospace">
                                    <?= esc($p['kode']) ?>
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4">
                        <!-- Profil Petugas -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border h-100">
                                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Informasi Akun</h6>
                                    <div class="mb-1"><strong>Nama:</strong> <?= esc($p['nama']) ?></div>
                                    <div class="mb-1"><strong>Email:</strong> <?= esc($p['email']) ?></div>
                                    <div class="mb-1"><strong>NIM / NIP:</strong> <?= esc($p['nim']) ?></div>
                                    <div>
                                        <strong>Status Akun:</strong>
                                        <span class="badge bg-<?= $p['is_active'] ? 'success' : 'secondary' ?>">
                                            <?= $p['is_active'] ? 'Aktif' : 'Non-Aktif' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border h-100">
                                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Performa Penanganan</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Status Kerja:</span>
                                        <span class="badge bg-<?= $p['status_kerja'] === 'Bertugas' ? 'warning text-dark' : ($p['status_kerja'] === 'Tersedia' ? 'success' : 'secondary') ?>">
                                            <?= esc($p['status_kerja']) ?>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tugas Sedang Aktif:</span>
                                        <strong class="text-primary"><?= $p['jumlah_tugas_aktif'] ?> Tugas</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tugas Selesai:</span>
                                        <strong class="text-success"><?= $p['jumlah_selesai'] ?> Laporan</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total Rekam Tugas:</span>
                                        <span class="text-muted"><?= $p['total_semua_tugas'] ?> Penugasan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Tugas yang Sedang Aktif Dikerjakan -->
                        <div class="border rounded p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="bi bi-list-task text-eco-primary me-1"></i>
                                    Daftar Tugas Aktif Saat Ini
                                </h6>
                                <span class="badge bg-primary">
                                    <?= count($p['tugas_aktif_list']) ?> Berjalan
                                </span>
                            </div>

                            <?php if (!empty($p['tugas_aktif_list'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Laporan</th>
                                                <th>Prioritas</th>
                                                <th>Status Tugas</th>
                                                <th>Waktu Diambil</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($p['tugas_aktif_list'] as $task): ?>
                                                <?php
                                                $prioritasColor = [
                                                    'RENDAH'  => 'secondary',
                                                    'SEDANG'  => 'info',
                                                    'TINGGI'  => 'warning',
                                                    'DARURAT' => 'danger',
                                                ];
                                                $prColor = $prioritasColor[$task['prioritas']] ?? 'secondary';
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark text-truncate" style="max-width: 260px;">
                                                            <?= esc($task['judul_laporan']) ?>
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="bi bi-geo-alt"></i> <?= esc($task['nama_lokasi'] ?: 'Area Kampus') ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?= $prColor ?>">
                                                            <?= esc($task['prioritas']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?= $task['status_tugas'] === 'DIKERJAKAN' ? 'warning text-dark' : 'primary' ?>">
                                                            <?= esc($task['status_tugas']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="small text-muted">
                                                        <?= $task['waktu_diambil'] ? date('d-m-Y H:i', strtotime($task['waktu_diambil'])) : '-' ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="<?= site_url('/admin/laporan/detail/' . $task['laporan_id']) ?>"
                                                           class="btn btn-sm btn-outline-success btn-touch"
                                                           target="_blank">
                                                            Detail <i class="bi bi-box-arrow-up-right ms-1"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted bg-light rounded">
                                    <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                                    <strong>Petugas sedang tidak memiliki tugas aktif (Tersedia).</strong>
                                    <p class="small mb-0">Petugas ini dapat diprioritaskan untuk penugasan laporan baru yang masuk.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-touch px-4" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
