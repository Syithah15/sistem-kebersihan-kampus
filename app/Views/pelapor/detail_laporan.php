<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="content-card mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h4 class="fw-bold mb-1">Detail Laporan</h4>
                <p class="text-muted mb-0">
                    Informasi dan perkembangan laporan Anda.
                </p>
            </div>

            <a href="<?= site_url('pelapor/dashboard') ?>"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

        </div>

        <hr>

        <h5 class="fw-bold">
            <?= esc($laporan['judul']) ?>
        </h5>

        <?php
        $warnaStatus = [
            'MENUNGGU' => 'warning',
            'DIPROSES' => 'primary',
            'SELESAI' => 'success',
            'DIBATALKAN' => 'danger',
        ];
        ?>

        <span class="badge bg-<?= $warnaStatus[$laporan['status']] ?? 'secondary' ?>">
            <?= esc($laporan['status']) ?>
        </span>

        <div class="row mt-4">

            <div class="col-md-6 mb-3">
                <h6 class="fw-bold">Kategori</h6>
                <p><?= esc($laporan['kategori']) ?></p>
            </div>

            <div class="col-md-6 mb-3">
                <h6 class="fw-bold">Tanggal Laporan</h6>
                <p>
                    <?= date('d-m-Y H:i', strtotime($laporan['created_at'])) ?>
                </p>
            </div>

            <div class="col-12 mb-3">
                <h6 class="fw-bold">Deskripsi</h6>
                <p><?= nl2br(esc($laporan['deskripsi'])) ?></p>
            </div>

            <div class="col-12 mb-3">
                <h6 class="fw-bold">Lokasi</h6>
                <p><?= esc($laporan['nama_lokasi'] ?? 'Belum tersedia') ?></p>

                <?php if ($laporan['latitude'] !== null && $laporan['longitude'] !== null) : ?>
                    <p class="text-muted small">
                        Latitude: <?= esc($laporan['latitude']) ?><br>
                        Longitude: <?= esc($laporan['longitude']) ?>
                    </p>
                <?php endif; ?>
            </div>

        </div>

        <?php if (!empty($laporan['foto'])) : ?>

            <hr>

            <h6 class="fw-bold mb-3">Foto Laporan</h6>

            <img src="<?= site_url('pelapor/laporan/foto/' . $laporan['id']) ?>"
                 alt="Foto laporan"
                 class="img-fluid rounded"
                 style="max-width: 450px;">

        <?php endif; ?>

    </div>

    <!-- INFORMASI PETUGAS -->

    <div class="content-card mb-4">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-person-badge text-success"></i>
            Informasi Penanganan
        </h5>

        <?php if (!empty($tugas)) : ?>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Petugas</h6>
                    <p><?= esc($tugas['nama_petugas']) ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Prioritas</h6>
                    <p><?= esc($tugas['prioritas']) ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Status Tugas</h6>
                    <p><?= esc($tugas['status']) ?></p>
                </div>

                <?php if (!empty($tugas['waktu_diambil'])) : ?>
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Waktu Diambil</h6>
                        <p>
                            <?= date('d-m-Y H:i', strtotime($tugas['waktu_diambil'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($tugas['waktu_selesai'])) : ?>
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Waktu Selesai</h6>
                        <p>
                            <?= date('d-m-Y H:i', strtotime($tugas['waktu_selesai'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div>

            <?php if ($tugas['status'] === 'SELESAI') : ?>

                <hr>

                <h6 class="fw-bold mb-3">Bukti Pekerjaan</h6>

                <div class="row">

                    <?php if (!empty($tugas['foto_sebelum'])) : ?>
                        <div class="col-md-6 mb-3">
                            <h6>Sebelum Dibersihkan</h6>

                            <img src="<?= site_url('pelapor/laporan/foto-tugas/' . $laporan['id'] . '/sebelum') ?>"
                                 class="img-fluid rounded"
                                 alt="Foto sebelum dibersihkan">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tugas['foto_sesudah'])) : ?>
                        <div class="col-md-6 mb-3">
                            <h6>Sesudah Dibersihkan</h6>

                            <img src="<?= site_url('pelapor/laporan/foto-tugas/' . $laporan['id'] . '/sesudah') ?>"
                                 class="img-fluid rounded"
                                 alt="Foto sesudah dibersihkan">
                        </div>
                    <?php endif; ?>

                </div>

                <?php if (!empty($tugas['catatan'])) : ?>
                    <div class="mt-3">
                        <h6 class="fw-bold">Catatan Petugas</h6>
                        <p><?= nl2br(esc($tugas['catatan'])) ?></p>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        <?php else : ?>

            <p class="text-muted mb-0">
                Laporan belum diambil oleh petugas.
            </p>

        <?php endif; ?>

    </div>

    <!-- RIWAYAT STATUS -->

    <div class="content-card mb-4">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-clock-history text-success"></i>
            Riwayat Laporan
        </h5>

        <?php if (!empty($riwayat)) : ?>

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Status Sebelumnya</th>
                            <th>Status Baru</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($riwayat as $item) : ?>

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
                                    <?= date('d-m-Y H:i', strtotime($item['created_at'])) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else : ?>

            <p class="text-muted mb-0">
                Belum ada riwayat perubahan status.
            </p>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>