<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .rekap-stat {
        border-left: 4px solid var(--stat-color, #198754) !important;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .rekap-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12) !important;
    }
    .rekap-stat .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--stat-color, #198754);
        background: color-mix(in srgb, var(--stat-color, #198754) 12%, transparent);
    }
    .rekap-table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
    }
    .rekap-table tbody td {
        padding-top: .85rem;
        padding-bottom: .85rem;
    }
    .rekap-table .badge {
        font-weight: 600;
        padding: .45em .8em;
    }
</style>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3"
             style="width:52px;height:52px;font-size:1.5rem;">
            <i class="bi bi-clipboard-data"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-0">Rekap Laporan</h3>
            <p class="text-muted mb-0">
                Kelola dan pantau seluruh laporan kebersihan kampus.
            </p>
        </div>
    </div>

    <!-- PESAN NOTIFIKASI -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div><?= esc(session()->getFlashdata('success')) ?></div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?= esc(session()->getFlashdata('error')) ?></div>
        </div>
    <?php endif; ?>

    <!-- STATISTIK LAPORAN -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-4 col-lg">
            <div class="card rekap-stat border-0 shadow-sm h-100" style="--stat-color:#6c757d;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Laporan</p>
                        <h3 class="fw-bold mb-0">
                            <?= esc($statistik['total']) ?>
                        </h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card rekap-stat border-0 shadow-sm h-100" style="--stat-color:#ffc107;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Menunggu</p>
                        <h3 class="fw-bold text-warning mb-0">
                            <?= esc($statistik['menunggu']) ?>
                        </h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card rekap-stat border-0 shadow-sm h-100" style="--stat-color:#0d6efd;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Diproses</p>
                        <h3 class="fw-bold text-primary mb-0">
                            <?= esc($statistik['diproses']) ?>
                        </h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card rekap-stat border-0 shadow-sm h-100" style="--stat-color:#198754;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Selesai</p>
                        <h3 class="fw-bold text-success mb-0">
                            <?= esc($statistik['selesai']) ?>
                        </h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 col-lg">
            <div class="card rekap-stat border-0 shadow-sm h-100" style="--stat-color:#dc3545;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Dibatalkan</p>
                        <h3 class="fw-bold text-danger mb-0">
                            <?= esc($statistik['dibatalkan']) ?>
                        </h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL LAPORAN -->
    <div class="card border-0 shadow-sm overflow-hidden">

        <div class="card-header bg-success text-white py-3 d-flex align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-clipboard-data me-1"></i>
                Daftar Seluruh Laporan
            </h5>
        </div>

        <div class="card-body p-0 p-md-3">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 rekap-table">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul Laporan</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($laporan)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($laporan as $item): ?>

                                <?php
                                $warnaStatus = [
                                    'MENUNGGU' => 'warning',
                                    'DIPROSES' => 'primary',
                                    'SELESAI' => 'success',
                                    'DIBATALKAN' => 'danger',
                                ];

                                $warna = $warnaStatus[$item['status']] ?? 'secondary';
                                ?>

                                <tr>

                                    <td class="text-muted"><?= $no++ ?></td>

                                    <td class="fw-semibold">
                                        <?= esc($item['judul']) ?>
                                    </td>

                                    <td>
                                        <i class="bi bi-person-circle text-muted me-1"></i>
                                        <?= esc($item['nama_pelapor']) ?>
                                    </td>

                                    <td>
                                        <?= esc($item['kategori']) ?>
                                    </td>

                                    <td>
                                        <i class="bi bi-geo-alt text-muted me-1"></i>
                                        <?= esc($item['nama_lokasi'] ?? 'Belum tersedia') ?>
                                    </td>

                                    <td class="text-nowrap text-muted small">
                                        <?= esc($item['created_at']) ?>
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill bg-<?= $warna ?>">
                                            <?= esc($item['status']) ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <a
                                            href="<?= site_url('admin/laporan/detail/' . $item['id']) ?>"
                                            class="btn btn-sm btn-success rounded-pill px-3"
                                        >
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox d-block fs-1 mb-2"></i>
                                    Belum ada laporan yang masuk.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>