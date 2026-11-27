<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .form-card {
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 28px;
    }

    .status-option-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .status-option-card:hover {
        border-color: #198754;
        background-color: #f8fff9;
    }

    .form-check-input:checked ~ .status-label {
        font-weight: 700;
        color: #198754;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 860px;">

    <!-- Navigasi Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/petugas/dashboard" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/petugas/tugas" class="text-decoration-none">Daftar Tugas</a></li>
            <li class="breadcrumb-item"><a href="/petugas/tugas/<?= esc($tugas['id']) ?>" class="text-decoration-none">Detail Tugas #<?= esc($tugas['id']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Tindak Lanjuti</li>
        </ol>
    </nav>

    <!-- Header Formulir -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-success mb-1">
                <i class="bi bi-pencil-square me-2"></i>Perbarui Status Tindak Lanjut
            </h1>
            <p class="text-muted mb-0">
                Tugas #<?= esc($tugas['id']) ?>: <strong><?= esc($tugas['laporan_judul']) ?></strong>
            </p>
        </div>
        <a href="/petugas/tugas/<?= esc($tugas['id']) ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Batal
        </a>
    </div>

    <!-- Alert Kesalahan Validasi -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan input:
            </h6>
            <ul class="mb-0 ps-3">
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Ringkasan Info Tugas -->
    <div class="alert alert-light border mb-4">
        <div class="row g-2 small">
            <div class="col-sm-4">
                <span class="text-muted">Kategori:</span> <strong><?= esc($tugas['laporan_kategori']) ?></strong>
            </div>
            <div class="col-sm-4">
                <span class="text-muted">Lokasi:</span> <strong><?= esc($tugas['nama_lokasi'] ?: 'Area Kampus') ?></strong>
            </div>
            <div class="col-sm-4">
                <span class="text-muted">Status Saat Ini:</span>
                <span class="badge bg-secondary"><?= esc($tugas['status']) ?></span>
            </div>
        </div>
    </div>

    <!-- Form Tindak Lanjut -->
    <div class="form-card">
        <form action="/petugas/tugas/<?= esc($tugas['id']) ?>/update" method="post" enctype="multipart/form-data">
            <!-- Proteksi CSRF -->
            <?= csrf_field() ?>

            <!-- Pilihan Status Baru -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">
                    Pilih Status Baru <span class="text-danger">*</span>
                </label>
                <div class="row g-2">
                    <?php
                    $currentStatus = old('status', $tugas['status']);
                    $statusOptions = [
                        'DITUGASKAN' => [
                            'label' => 'DITUGASKAN',
                            'desc'  => 'Tugas baru / antrean pengerjaan',
                            'color' => 'text-info',
                        ],
                        'DIKERJAKAN' => [
                            'label' => 'DIKERJAKAN',
                            'desc'  => 'Sedang dalam proses pembersihan di lokasi',
                            'color' => 'text-warning',
                        ],
                        'SELESAI' => [
                            'label' => 'SELESAI',
                            'desc'  => 'Pekerjaan telah rampung dan bersih',
                            'color' => 'text-success',
                        ],
                        'DIBATALKAN' => [
                            'label' => 'DIBATALKAN',
                            'desc'  => 'Tidak dapat ditangani / dibatalkan',
                            'color' => 'text-danger',
                        ],
                    ];
                    ?>

                    <?php foreach ($statusOptions as $val => $opt): ?>
                        <div class="col-sm-6">
                            <label class="status-option-card d-block h-100">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="radio" name="status" id="status_<?= $val ?>" value="<?= $val ?>" <?= ($currentStatus === $val) ? 'checked' : '' ?> required>
                                    <span class="status-label d-block ms-1">
                                        <strong class="<?= $opt['color'] ?>"><?= $opt['label'] ?></strong>
                                        <small class="text-muted d-block"><?= $opt['desc'] ?></small>
                                    </span>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Catatan Tindak Lanjut -->
            <div class="mb-4">
                <label for="catatan" class="form-label fw-bold text-dark">
                    Catatan Tindak Lanjut
                </label>
                <textarea
                    class="form-control"
                    id="catatan"
                    name="catatan"
                    rows="4"
                    placeholder="Tuliskan keterangan pekerjaan, kendala yang dihadapi, atau hasil penanganan di lapangan..."><?= esc(old('catatan', $tugas['catatan'])) ?></textarea>
                <div class="form-text">
                    Catatan ini akan dicatat ke dalam riwayat status laporan dan dapat dilihat oleh admin &amp; pelapor.
                </div>
            </div>

            <!-- Unggah Foto Bukti -->
            <div class="row g-3 mb-4">
                <!-- Foto Sebelum -->
                <div class="col-md-6">
                    <label for="foto_sebelum" class="form-label fw-bold text-dark">
                        <i class="bi bi-camera me-1"></i> Foto Bukti Sebelum (Opsional)
                    </label>
                    <input class="form-control" type="file" id="foto_sebelum" name="foto_sebelum" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text small">
                        Format JPG/PNG/WEBP, maksimal 2 MB.
                    </div>
                    <?php if (!empty($tugas['foto_sebelum'])): ?>
                        <div class="mt-2 small text-muted">
                            <i class="bi bi-check-circle-fill text-success me-1"></i> Sudah ada foto sebelumnya:
                            <a href="/uploads/tugas/<?= esc($tugas['foto_sebelum']) ?>" target="_blank" class="text-decoration-none">Lihat foto</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Foto Sesudah -->
                <div class="col-md-6">
                    <label for="foto_sesudah" class="form-label fw-bold text-dark">
                        <i class="bi bi-camera-fill me-1 text-success"></i> Foto Bukti Sesudah (Hasil)
                    </label>
                    <input class="form-control" type="file" id="foto_sesudah" name="foto_sesudah" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text small">
                        Format JPG/PNG/WEBP, maksimal 2 MB. (Disarankan saat status SELESAI).
                    </div>
                    <?php if (!empty($tugas['foto_sesudah'])): ?>
                        <div class="mt-2 small text-muted">
                            <i class="bi bi-check-circle-fill text-success me-1"></i> Sudah ada foto sesudah:
                            <a href="/uploads/tugas/<?= esc($tugas['foto_sesudah']) ?>" target="_blank" class="text-decoration-none">Lihat foto</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="my-4">

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end gap-2">
                <a href="/petugas/tugas/<?= esc($tugas['id']) ?>" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-lg me-1"></i> Simpan Pembaruan
                </button>
            </div>
        </form>
    </div>

</div>
<?= $this->endSection() ?>
