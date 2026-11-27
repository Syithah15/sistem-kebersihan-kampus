<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .detail-card {
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 24px;
        margin-bottom: 24px;
    }

    .detail-label {
        font-size: 0.825rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 1rem;
        color: #212529;
        margin-bottom: 16px;
    }

    .image-preview-box {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 6px;
        background: #f8f9fa;
        text-align: center;
    }

    .image-preview-box img {
        max-height: 240px;
        width: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .timeline {
        position: relative;
        padding-left: 28px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 6px;
        bottom: 6px;
        left: 10px;
        width: 2px;
        background-color: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-badge {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #198754;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 1px #198754;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Navigasi Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/petugas/dashboard" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/petugas/tugas" class="text-decoration-none">Daftar Tugas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Tugas #<?= esc($tugas['id']) ?></li>
        </ol>
    </nav>

    <!-- Header Action -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold text-dark mb-0">Tugas #<?= esc($tugas['id']) ?></h1>
                <?php
                $prioClass = [
                    'RENDAH'  => 'bg-secondary',
                    'SEDANG'  => 'bg-primary',
                    'TINGGI'  => 'bg-warning text-dark',
                    'DARURAT' => 'bg-danger',
                ][$tugas['prioritas']] ?? 'bg-secondary';

                $statusClass = [
                    'DITUGASKAN' => 'bg-info text-dark',
                    'DIKERJAKAN' => 'bg-warning text-dark',
                    'SELESAI'    => 'bg-success',
                    'DIBATALKAN' => 'bg-danger',
                ][$tugas['status']] ?? 'bg-secondary';
                ?>
                <span class="badge <?= $prioClass ?> fs-6">Prioritas: <?= esc($tugas['prioritas']) ?></span>
                <span class="badge <?= $statusClass ?> fs-6">Status: <?= esc($tugas['status']) ?></span>
            </div>
            <p class="text-muted mb-0">
                Laporan terkait: <strong><?= esc($tugas['laporan_judul']) ?></strong>
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="/petugas/tugas" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="/petugas/tugas/<?= esc($tugas['id']) ?>/update" class="btn btn-success">
                <i class="bi bi-pencil-square me-1"></i> Tindak Lanjuti Tugas
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

    <div class="row">
        <!-- Kolom Kiri: Informasi Laporan dan Progres Tugas -->
        <div class="col-lg-8">

            <!-- Card 1: Informasi Laporan Kebersihan -->
            <div class="detail-card">
                <h2 class="h5 fw-bold text-success border-bottom pb-2 mb-3">
                    <i class="bi bi-card-text me-2"></i>Informasi Laporan Masalah
                </h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Judul Laporan</div>
                        <div class="detail-value fw-bold"><?= esc($tugas['laporan_judul']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value">
                            <span class="badge bg-light text-dark border">
                                <?= esc($tugas['laporan_kategori']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Pelapor</div>
                        <div class="detail-value">
                            <i class="bi bi-person me-1"></i><?= esc($tugas['nama_pelapor'] ?: 'Anonim') ?>
                            <?php if (!empty($tugas['email_pelapor'])): ?>
                                <span class="text-muted small">(<?= esc($tugas['email_pelapor']) ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tanggal Pelaporan</div>
                        <div class="detail-value">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= !empty($tugas['laporan_created_at']) ? date('d F Y, H:i', strtotime($tugas['laporan_created_at'])) : '-' ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Lokasi Kejadian</div>
                        <div class="detail-value">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                            <strong><?= esc($tugas['nama_lokasi'] ?: 'Area Kampus') ?></strong>
                            <?php if (!empty($tugas['latitude']) && !empty($tugas['longitude'])): ?>
                                <div class="small text-muted mt-1">
                                    Koordinat: <?= esc($tugas['latitude']) ?>, <?= esc($tugas['longitude']) ?>
                                    <a href="https://maps.google.com/?q=<?= esc($tugas['latitude']) ?>,<?= esc($tugas['longitude']) ?>" target="_blank" rel="noopener noreferrer" class="ms-2 text-decoration-none">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka di Google Maps
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Deskripsi Masalah</div>
                        <div class="detail-value bg-light p-3 rounded">
                            <?= nl2br(esc($tugas['laporan_deskripsi'])) ?>
                        </div>
                    </div>

                    <?php if (!empty($tugas['laporan_foto'])): ?>
                        <div class="col-12 mt-2">
                            <div class="detail-label">Foto Masalah Awal (Dari Pelapor)</div>
                            <div class="image-preview-box" style="max-width: 380px;">
                                <img src="/uploads/laporan/<?= esc($tugas['laporan_foto']) ?>" alt="Foto Masalah" class="img-fluid" onerror="this.src='https://placehold.co/600x400?text=Foto+Laporan';">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Card 2: Progres Pengerjaan Petugas -->
            <div class="detail-card">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h2 class="h5 fw-bold text-success mb-0">
                        <i class="bi bi-tools me-2"></i>Status Tindak Lanjut Petugas
                    </h2>
                    <a href="/petugas/tugas/<?= esc($tugas['id']) ?>/update" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-pencil-square me-1"></i> Perbarui Progres
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Waktu Mulai Dikerjakan</div>
                        <div class="detail-value">
                            <i class="bi bi-play-circle me-1 text-primary"></i>
                            <?= !empty($tugas['waktu_diambil']) ? date('d F Y, H:i', strtotime($tugas['waktu_diambil'])) : '<span class="text-muted">Belum dimulai</span>' ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Waktu Selesai</div>
                        <div class="detail-value">
                            <i class="bi bi-check-circle me-1 text-success"></i>
                            <?= !empty($tugas['waktu_selesai']) ? date('d F Y, H:i', strtotime($tugas['waktu_selesai'])) : '<span class="text-muted">Belum selesai</span>' ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Catatan Tindak Lanjut</div>
                        <div class="detail-value bg-light p-3 rounded">
                            <?= !empty($tugas['catatan']) ? nl2br(esc($tugas['catatan'])) : '<em class="text-muted">Belum ada catatan tindak lanjut dari petugas.</em>' ?>
                        </div>
                    </div>

                    <!-- Bukti Foto Pengerjaan -->
                    <div class="col-md-6 mt-3">
                        <div class="detail-label">Foto Bukti Sebelum Pengerjaan</div>
                        <?php if (!empty($tugas['foto_sebelum'])): ?>
                            <div class="image-preview-box">
                                <img src="/uploads/tugas/<?= esc($tugas['foto_sebelum']) ?>" alt="Foto Sebelum" class="img-fluid" onerror="this.src='https://placehold.co/600x400?text=Foto+Sebelum';">
                            </div>
                        <?php else: ?>
                            <div class="p-3 text-center bg-light rounded text-muted small">
                                <i class="bi bi-image text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                                Belum ada foto sebelum pengerjaan.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="detail-label">Foto Bukti Sesudah Pengerjaan (Hasil)</div>
                        <?php if (!empty($tugas['foto_sesudah'])): ?>
                            <div class="image-preview-box">
                                <img src="/uploads/tugas/<?= esc($tugas['foto_sesudah']) ?>" alt="Foto Sesudah" class="img-fluid" onerror="this.src='https://placehold.co/600x400?text=Foto+Sesudah';">
                            </div>
                        <?php else: ?>
                            <div class="p-3 text-center bg-light rounded text-muted small">
                                <i class="bi bi-image text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                                Belum ada foto sesudah pengerjaan.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Card 3: Riwayat Perubahan Status -->
            <div class="detail-card">
                <h2 class="h5 fw-bold text-success border-bottom pb-2 mb-3">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Perubahan Status
                </h2>

                <?php if (!empty($riwayat)): ?>
                    <div class="timeline mt-3">
                        <?php foreach ($riwayat as $r): ?>
                            <div class="timeline-item">
                                <div class="timeline-badge"></div>
                                <div class="fw-bold text-dark">
                                    Status diubah menjadi:
                                    <span class="badge bg-secondary"><?= esc($r['status_baru']) ?></span>
                                    <?php if (!empty($r['status_sebelumnya'])): ?>
                                        <small class="text-muted fw-normal">(sebelumnya: <?= esc($r['status_sebelumnya']) ?>)</small>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-person me-1"></i><?= esc($r['nama_pengubah'] ?: 'Sistem') ?>
                                    &bull;
                                    <i class="bi bi-clock me-1"></i><?= date('d M Y, H:i', strtotime($r['created_at'])) ?>
                                </div>
                                <?php if (!empty($r['keterangan'])): ?>
                                    <div class="small text-dark mt-1 bg-light p-2 rounded">
                                        <?= esc($r['keterangan']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0 small">Belum ada riwayat perubahan status tercatat.</p>
                <?php endif; ?>
            </div>

        </div>

        <!-- Kolom Kanan: Aksi Cepat & Panduan -->
        <div class="col-lg-4">

            <!-- Card Tindak Lanjut Cepat -->
            <div class="detail-card border-top border-success border-3">
                <h3 class="h6 fw-bold text-dark mb-3">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Tindak Lanjut Tugas
                </h3>
                <p class="small text-muted mb-3">
                    Perbarui status pengerjaan laporan ini, masukkan catatan penanganan, dan lampirkan foto bukti pekerjaan.
                </p>
                <div class="d-grid gap-2">
                    <a href="/petugas/tugas/<?= esc($tugas['id']) ?>/update" class="btn btn-success">
                        <i class="bi bi-pencil-square me-1"></i> Perbarui Status Sekarang
                    </a>
                </div>
            </div>

            <!-- Card Panduan Petugas -->
            <div class="detail-card bg-light">
                <h3 class="h6 fw-bold text-dark mb-2">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i>Alur Status Tugas
                </h3>
                <ol class="small text-muted ps-3 mb-0" style="line-height: 1.7;">
                    <li><strong>DITUGASKAN:</strong> Tugas baru diterima, bersiap menuju lokasi.</li>
                    <li><strong>DIKERJAKAN:</strong> Petugas mulai membersihkan/menindaklanjuti di lokasi.</li>
                    <li><strong>SELESAI:</strong> Pekerjaan tuntas, sertakan foto bukti hasil pengerjaan.</li>
                    <li><strong>DIBATALKAN:</strong> Jika ada kendala lapangan atau laporan tidak valid.</li>
                </ol>
            </div>

        </div>
    </div>

</div>
<?= $this->endSection() ?>
