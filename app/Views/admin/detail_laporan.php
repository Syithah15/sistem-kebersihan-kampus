<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Laporan</h3>
            <p class="text-muted mb-0">
                Informasi lengkap laporan kebersihan kampus.
            </p>
        </div>

        <a href="<?= site_url('/admin/laporan') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- PESAN -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <!-- INFORMASI LAPORAN -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-text"></i>
                Informasi Laporan
            </h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Judul Laporan</div>
                <div class="col-md-9 fw-semibold">
                    <?= esc($laporan['judul']) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Kategori</div>
                <div class="col-md-9">
                    <?= esc($laporan['kategori']) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Deskripsi</div>
                <div class="col-md-9">
                    <?= nl2br(esc($laporan['deskripsi'])) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Tanggal Laporan</div>
                <div class="col-md-9">
                    <?= esc($laporan['created_at']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 text-muted">Status</div>
                <div class="col-md-9">

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
    </div>

    <!-- INFORMASI PELAPOR -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-person"></i>
                Informasi Pelapor
            </h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Nama</div>
                <div class="col-md-9 fw-semibold">
                    <?= esc($laporan['nama_pelapor']) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Email</div>
                <div class="col-md-9">
                    <?= esc($laporan['email_pelapor'] ?? '-') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 text-muted">NIM</div>
                <div class="col-md-9">
                    <?= esc($laporan['nim_pelapor'] ?? '-') ?>
                </div>
            </div>

        </div>
    </div>

    <!-- INFORMASI LOKASI -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-geo-alt"></i>
                Informasi Lokasi
            </h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Nama Lokasi</div>
                <div class="col-md-9">
                    <?= esc($laporan['nama_lokasi'] ?? 'Belum tersedia') ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Latitude</div>
                <div class="col-md-9">
                    <?= esc($laporan['latitude'] ?? '-') ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 text-muted">Longitude</div>
                <div class="col-md-9">
                    <?= esc($laporan['longitude'] ?? '-') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 text-muted">Akurasi GPS</div>
                <div class="col-md-9">
                    <?= esc($laporan['akurasi_gps'] ?? '-') ?>
                    <?= !empty($laporan['akurasi_gps']) ? 'meter' : '' ?>
                </div>
            </div>

        </div>
    </div>

    <!-- FOTO LAPORAN -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-camera"></i>
                Foto Laporan
            </h5>
        </div>

        <div class="card-body">

            <?php if (!empty($laporan['foto'])): ?>

                <img
                    src="<?= site_url('admin/laporan/foto/' . rawurlencode(basename($laporan['foto']))) ?>"
                    alt="Foto laporan"
                    class="img-fluid rounded"
                    style="max-width: 500px;"
                >

            <?php else: ?>

                <p class="text-muted mb-0">Tidak ada foto laporan.</p>

            <?php endif; ?>

        </div>
    </div>

    <!-- INFORMASI PETUGAS -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-person-badge"></i>
                Informasi Petugas
            </h5>
        </div>

        <div class="card-body">

            <?php if (!empty($tugas)): ?>

                <div class="row mb-3">
                    <div class="col-md-3 text-muted">Nama Petugas</div>
                    <div class="col-md-9 fw-semibold">
                        <?= esc($tugas['nama_petugas']) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 text-muted">Email Petugas</div>
                    <div class="col-md-9">
                        <?= esc($tugas['email_petugas'] ?? '-') ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 text-muted">Prioritas</div>
                    <div class="col-md-9">
                        <?= esc($tugas['prioritas']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 text-muted">Status Tugas</div>
                    <div class="col-md-9">
                        <?= esc($tugas['status']) ?>
                    </div>
                </div>

            <?php else: ?>

                <p class="text-muted mb-0">
                    Belum ada petugas yang mengambil laporan ini.
                </p>

            <?php endif; ?>

        </div>
    </div>

    <!-- DOKUMENTASI PENGERJAAN -->
    <?php if (!empty($tugas) && $tugas['status'] === 'SELESAI'): ?>

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-images"></i>
                    Dokumentasi Pengerjaan
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Foto Sebelum</h6>

                        <?php if (!empty($tugas['foto_sebelum'])): ?>
                            <img
                                src="<?= site_url('petugas/foto/' . rawurlencode(basename($tugas['foto_sebelum']))) ?>"
                                class="img-fluid rounded"
                                alt="Foto sebelum"
                            >
                        <?php else: ?>
                            <p class="text-muted">Foto belum tersedia.</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Foto Sesudah</h6>

                        <?php if (!empty($tugas['foto_sesudah'])): ?>
                            <img
                                src="<?= site_url('petugas/foto/' . rawurlencode(basename($tugas['foto_sesudah']))) ?>"
                                class="img-fluid rounded"
                                alt="Foto sesudah"
                            >
                        <?php else: ?>
                            <p class="text-muted">Foto belum tersedia.</p>
                        <?php endif; ?>
                    </div>

                </div>

                <?php if (!empty($tugas['catatan'])): ?>
                    <hr>
                    <h6 class="fw-bold">Catatan Petugas</h6>
                    <p class="mb-0"><?= nl2br(esc($tugas['catatan'])) ?></p>
                <?php endif; ?>

            </div>
        </div>

    <?php endif; ?>

    <!-- RIWAYAT STATUS -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-clock-history"></i>
                Riwayat Status Laporan
            </h5>
        </div>

        <div class="card-body">

            <?php if (!empty($riwayat)): ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Status Sebelumnya</th>
                                <th>Status Baru</th>
                                <th>Keterangan</th>
                                <th>Pengguna</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php $no = 1; ?>

                            <?php foreach ($riwayat as $item): ?>

                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td>
                                        <?= esc($item['status_sebelumnya'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= esc($item['status_baru']) ?>
                                    </td>

                                    <td>
                                        <?= esc($item['keterangan'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= esc($item['nama_pengguna'] ?? 'Sistem') ?>
                                    </td>

                                    <td>
                                        <?= esc($item['created_at']) ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <p class="text-muted mb-0">
                    Belum ada riwayat perubahan status.
                </p>

            <?php endif; ?>

        </div>
    </div>

    <!-- PEMBATALAN LAPORAN -->
    <?php if (in_array($laporan['status'], ['MENUNGGU', 'DIPROSES'], true)): ?>

        <div class="card border-danger shadow-sm mb-4">

            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    Pembatalan Laporan
                </h5>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Gunakan fitur ini jika laporan terbukti tidak valid,
                    termasuk laporan palsu atau hoaks.
                    Pembatalan akan menghentikan tugas aktif yang terkait.
                </p>

                <form
                    action="<?= site_url('admin/laporan/batalkan/' . $laporan['id']) ?>"
                    method="post"
                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan laporan ini?');"
                >

                    <?= csrf_field() ?>

                    <div class="mb-3">

                        <label for="alasan" class="form-label fw-semibold">
                            Alasan Pembatalan
                        </label>

                        <textarea
                            name="alasan"
                            id="alasan"
                            class="form-control"
                            rows="4"
                            minlength="5"
                            maxlength="1000"
                            placeholder="Tuliskan alasan pembatalan laporan..."
                            required
                        ></textarea>

                        <small class="text-muted">
                            Minimal 5 karakter dan maksimal 1000 karakter.
                        </small>

                    </div>

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i>
                        Batalkan Laporan
                    </button>

                </form>

            </div>
        </div>

    <?php else: ?>

        <div class="alert alert-secondary">
            <i class="bi bi-info-circle"></i>
            Laporan ini sudah <?= esc($laporan['status']) ?> sehingga tidak dapat dibatalkan.
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>