<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid dashboard-redesign">
    

    <!-- Header Dashboard -->
    <div class="content-card mb-4 dashboard-hero">
        <h3 class="fw-bold mb-2">
            <?= esc($judul) ?>
        </h3>

        <p class="text-muted mb-0">
            Selamat datang, <?= esc($nama) ?>!
        </p>
    </div>

    <!-- ===================================== -->
    <!-- DASHBOARD PELAPOR -->
    <!-- ===================================== -->

    <?php if ($role === 'pelapor') : ?>

        <!-- Pintasan Pelapor -->
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <a href="<?= site_url('pelapor/laporan/buat') ?>"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle rounded p-3">
                                <i class="bi bi-plus-circle text-success fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Buat Laporan
                                </h6>
                                <small class="text-muted">
                                    Laporkan masalah kebersihan.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col-md-4">
                <a href="#laporan-saya"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle rounded p-3">
                                <i class="bi bi-clipboard-data text-primary fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Laporan Saya
                                </h6>
                                <small class="text-muted">
                                    Pantau laporan yang dikirim.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col-md-4">
                <a href="<?= site_url('notifikasi') ?>"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning-subtle rounded p-3">
                                <i class="bi bi-bell text-warning fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Notifikasi
                                </h6>
                                <small class="text-muted">
                                    Lihat pemberitahuan terbaru.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

        </div>

        <!-- Tabel Laporan Pelapor -->
        <div class="content-card mb-4" id="laporan-saya">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <h5 class="fw-bold mb-1">Laporan Saya</h5>

                    <p class="text-muted mb-0">
                        Pantau perkembangan laporan kebersihan Anda.
                    </p>
                </div>

                <a href="<?= site_url('pelapor/laporan/buat') ?>"
                   class="btn btn-success">
                    <i class="bi bi-plus-circle"></i>
                    Buat Laporan
                </a>

            </div>

            <hr>

            <h6>
                Total Laporan:
                <span class="badge bg-success">
                    <?= count($laporanSaya) ?>
                </span>
            </h6>

            <div class="table-responsive mt-3">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($laporanSaya)) : ?>

                            <?php $no = 1; ?>

                            <?php foreach ($laporanSaya as $laporan) : ?>

                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td>
                                        <?= esc($laporan['judul']) ?>
                                    </td>

                                    <td>
                                        <?= esc($laporan['kategori']) ?>
                                    </td>

                                    <td>
                                        <?= esc($laporan['nama_lokasi'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= date('d-m-Y H:i', strtotime($laporan['created_at'])) ?>
                                    </td>

                                    <td>

                                        <?php
                                        $warna = [
                                            'MENUNGGU' => 'warning',
                                            'DIPROSES' => 'primary',
                                            'SELESAI' => 'success',
                                            'DIBATALKAN' => 'danger',
                                        ];

                                        $status = $laporan['status'];
                                        ?>

                                        <span class="badge bg-<?= $warna[$status] ?? 'secondary' ?>">
                                            <?= esc($status) ?>
                                        </span>

                                    </td>

                                    <td>
                                        <a href="<?= site_url('pelapor/laporan/detail/' . $laporan['id']) ?>"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada laporan.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <!-- ===================================== -->
    <!-- DASHBOARD PETUGAS -->
    <!-- ===================================== -->

    <?php elseif ($role === 'petugas') : ?>

        <!-- Pintasan Petugas -->
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <a href="#tugas-saya"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle rounded p-3">
                                <i class="bi bi-clipboard-check text-primary fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Tugas Saya
                                </h6>
                                <small class="text-muted">
                                    Lihat tugas yang sedang dikerjakan.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col-md-4">
                <a href="#laporan-masuk"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning-subtle rounded p-3">
                                <i class="bi bi-inbox text-warning fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Laporan Masuk
                                </h6>
                                <small class="text-muted">
                                    Lihat laporan yang belum diambil.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col-md-4">
                <a href="<?= site_url('notifikasi') ?>"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle rounded p-3">
                                <i class="bi bi-bell text-success fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Notifikasi
                                </h6>
                                <small class="text-muted">
                                    Lihat pemberitahuan terbaru.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

        </div>

        <!-- Tugas Saya -->
        <div class="content-card mb-4" id="tugas-saya">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-clipboard-check text-success"></i>
                Tugas Saya
            </h5>

            <p class="text-muted">
                Daftar laporan yang sudah Anda ambil dan sedang dikerjakan.
            </p>

            <hr>

            <h6>
                Total Tugas Aktif:
                <span class="badge bg-primary">
                    <?= count($tugasSaya) ?>
                </span>
            </h6>

            <div class="table-responsive mt-3">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Judul Laporan</th>
                            <th>Kategori</th>
                            <th>Pelapor</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Status Tugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($tugasSaya)) : ?>

                            <?php $no = 1; ?>

                            <?php foreach ($tugasSaya as $tugas) : ?>

                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td><?= esc($tugas['judul']) ?></td>

                                    <td><?= esc($tugas['kategori']) ?></td>

                                    <td><?= esc($tugas['nama_pelapor']) ?></td>

                                    <td>
                                        <?= esc($tugas['nama_lokasi'] ?? '-') ?>
                                    </td>

                                    <td>

                                        <?php
                                        $warnaPrioritas = [
                                            'RENDAH' => 'secondary',
                                            'SEDANG' => 'info',
                                            'TINGGI' => 'warning',
                                            'DARURAT' => 'danger',
                                        ];

                                        $prioritas = $tugas['prioritas'];
                                        ?>

                                        <span class="badge bg-<?= $warnaPrioritas[$prioritas] ?? 'secondary' ?>">
                                            <?= esc($prioritas) ?>
                                        </span>

                                    </td>

                                    <td>

                                        <?php
                                        $warnaTugas = [
                                            'DITUGASKAN' => 'primary',
                                            'DIKERJAKAN' => 'warning',
                                        ];

                                        $statusTugas = $tugas['status'];
                                        ?>

                                        <span class="badge bg-<?= $warnaTugas[$statusTugas] ?? 'secondary' ?>">
                                            <?= esc($statusTugas) ?>
                                        </span>

                                    </td>

                                    <td>

                                        <?php if ($tugas['status'] === 'DITUGASKAN') : ?>

                                            <form action="<?= site_url('petugas/tugas/mulai/' . $tugas['id']) ?>"
                                                  method="post"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin mulai mengerjakan tugas ini?')">

                                                <?= csrf_field() ?>

                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-play-circle"></i>
                                                    Mulai Kerjakan
                                                </button>

                                            </form>

                                        <?php elseif ($tugas['status'] === 'DIKERJAKAN') : ?>

                                            <a href="<?= site_url('petugas/tugas/selesai/' . $tugas['id']) ?>"
                                               class="btn btn-sm btn-success">
                                                <i class="bi bi-check-circle"></i>
                                                Selesaikan
                                            </a>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    Anda belum memiliki tugas aktif.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Laporan Masuk -->
        <div class="content-card mb-4" id="laporan-masuk">

            <h5 class="fw-bold mb-1">
                <i class="bi bi-inbox text-success"></i>
                Laporan Masuk
            </h5>

            <p class="text-muted">
                Laporan baru yang belum diambil oleh petugas.
            </p>

            <hr>

            <h6>
                Total Laporan Masuk:
                <span class="badge bg-warning text-dark">
                    <?= count($laporanMasuk) ?>
                </span>
            </h6>

            <div class="table-responsive mt-3">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pelapor</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($laporanMasuk)) : ?>

                            <?php $no = 1; ?>

                            <?php foreach ($laporanMasuk as $laporan) : ?>

                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td><?= esc($laporan['judul']) ?></td>

                                    <td><?= esc($laporan['kategori']) ?></td>

                                    <td><?= esc($laporan['nama_pelapor']) ?></td>

                                    <td>
                                        <?= esc($laporan['nama_lokasi'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= date('d-m-Y H:i', strtotime($laporan['created_at'])) ?>
                                    </td>

                                    <td>
                                        <a href="<?= site_url('petugas/laporan/detail/' . $laporan['id']) ?>"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada laporan masuk.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <!-- ===================================== -->
    <!-- DASHBOARD ADMIN -->
    <!-- ===================================== -->

    <?php elseif ($role === 'admin') : ?>

        <!-- Pintasan Admin -->
        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <a href="<?= site_url('admin/laporan') ?>"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle rounded p-3">
                                <i class="bi bi-clipboard-data text-success fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Rekap Laporan
                                </h6>
                                <small class="text-muted">
                                    Lihat seluruh laporan dan statistik kampus.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col-md-6">
                <a href="<?= site_url('notifikasi') ?>"
                   class="text-decoration-none">

                    <div class="content-card h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning-subtle rounded p-3">
                                <i class="bi bi-bell text-warning fs-3"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    Notifikasi
                                </h6>
                                <small class="text-muted">
                                    Lihat pemberitahuan terbaru.
                                </small>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

        </div>

        <div class="content-card">

            <h5 class="fw-bold">
                <i class="bi bi-shield-check text-success"></i>
                Selamat Datang, Administrator!
            </h5>

            <p class="text-muted mb-0">
                Gunakan menu Rekap Laporan untuk memantau seluruh laporan kebersihan kampus.
            </p>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>