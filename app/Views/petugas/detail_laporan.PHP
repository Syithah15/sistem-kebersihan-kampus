<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="content-card">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Detail Laporan
            </h3>

            <p class="text-muted mb-0">
                Informasi lengkap laporan kebersihan.
            </p>
        </div>

        <a href="<?= site_url('petugas/dashboard') ?>"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            KEMBALI

        </a>

    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <hr>

    <h5 class="fw-bold mb-3">
        Informasi Laporan
    </h5>

    <div class="row mb-3">

        <div class="col-md-6 mb-3">
            <small class="text-muted">Judul Laporan</small>

            <div class="fw-semibold">
                <?= esc($laporan['judul']) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <small class="text-muted">Kategori</small>

            <div class="fw-semibold">
                <?= esc($laporan['kategori']) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <small class="text-muted">Nama Pelapor</small>

            <div class="fw-semibold">
                <?= esc($laporan['nama_pelapor']) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <small class="text-muted">Tanggal Laporan</small>

            <div class="fw-semibold">
                <?= date('d-m-Y H:i', strtotime($laporan['created_at'])) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <small class="text-muted">Status</small>

            <div>
                <?php
                $warnaStatus = [
                    'MENUNGGU' => 'warning',
                    'DIPROSES' => 'primary',
                    'SELESAI' => 'success',
                    'DIBATALKAN' => 'danger',
                ];

                $warna = $warnaStatus[$laporan['status']] ?? 'secondary';
                ?>

                <span class="badge bg-<?= $warna ?>">
                    <?= esc($laporan['status']) ?>
                </span>
            </div>
        </div>

    </div>

    <hr>

    <h5 class="fw-bold mb-3">
        Deskripsi Permasalahan
    </h5>

    <div class="bg-light p-3 rounded-3 mb-4">
        <?= nl2br(esc($laporan['deskripsi'])) ?>
    </div>

    <h5 class="fw-bold mb-3">
        Foto Kondisi
    </h5>

    <?php if (!empty($laporan['foto'])) : ?>

        <div class="mb-4">

            <img
                src="<?= site_url('petugas/foto/' . rawurlencode($laporan['foto'])) ?>"
                alt="Foto laporan"
                class="img-fluid rounded-3"
                style="max-height: 400px; object-fit: contain;"
            >

        </div>

    <?php else : ?>

        <div class="alert alert-secondary">
            Foto laporan tidak tersedia.
        </div>

    <?php endif; ?>

    <hr>

    <h5 class="fw-bold mb-3">
        <i class="bi bi-geo-alt-fill text-success"></i>
        Lokasi Permasalahan
    </h5>

    <div class="mb-3">

        <small class="text-muted">
            Nama Lokasi
        </small>

        <div class="fw-semibold">
            <?= esc($laporan['nama_lokasi'] ?? 'Belum tersedia') ?>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <small class="text-muted">Latitude</small>

            <div>
                <?= esc($laporan['latitude'] ?? '-') ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <small class="text-muted">Longitude</small>

            <div>
                <?= esc($laporan['longitude'] ?? '-') ?>
            </div>
        </div>

    </div>

    <hr>

    <!-- FORM AMBIL TUGAS -->
    <?php if ($laporan['status'] === 'MENUNGGU') : ?>

        <h5 class="fw-bold mb-3">
            <i class="bi bi-hand-index-thumb text-success"></i>
            Ambil Tugas
        </h5>

        <form action="<?= site_url('petugas/laporan/ambil/' . $laporan['id']) ?>"
              method="post">

            <?= csrf_field() ?>

            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Prioritas Penanganan
                </label>

                <select name="prioritas" class="form-select" required>

                    <option value="RENDAH">Rendah</option>

                    <option value="SEDANG" selected>Sedang</option>

                    <option value="TINGGI">Tinggi</option>

                    <option value="DARURAT">Darurat</option>

                </select>

            </div>

            <button type="submit" class="btn btn-success">

                <i class="bi bi-check-circle"></i>
                Ambil Tugas Ini

            </button>

        </form>

    <?php else : ?>

        <div class="alert alert-info mb-0">

            <i class="bi bi-info-circle"></i>

            Laporan ini sudah tidak tersedia untuk diambil.
            Status saat ini:
            <strong><?= esc($laporan['status']) ?></strong>.

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>