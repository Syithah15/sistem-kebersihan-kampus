<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="content-card mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-bell text-success"></i>
                    Notifikasi
                </h4>

                <p class="text-muted mb-0">
                    Informasi terbaru mengenai laporan dan aktivitas Anda.
                </p>
            </div>

            <a href="<?= site_url(session()->get('role') . '/dashboard') ?>"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Dashboard
            </a>

        </div>

        <hr>

        <h6>
            Belum Dibaca:
            <span class="badge bg-danger">
                <?= esc($jumlahBelumDibaca) ?>
            </span>
        </h6>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if (!empty($notifikasi)) : ?>

        <?php foreach ($notifikasi as $item) : ?>

            <div class="content-card mb-3
                <?= $item['is_read'] == 0 ? 'border-start border-4 border-success' : '' ?>">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                    <div class="flex-grow-1">

                        <div class="d-flex align-items-center gap-2 mb-2">

                            <i class="bi bi-bell-fill text-success"></i>

                            <h6 class="fw-bold mb-0">
                                <?= esc($item['judul']) ?>
                            </h6>

                            <?php if ($item['is_read'] == 0) : ?>
                                <span class="badge bg-danger">
                                    Baru
                                </span>
                            <?php else : ?>
                                <span class="badge bg-secondary">
                                    Sudah Dibaca
                                </span>
                            <?php endif; ?>

                        </div>

                        <p class="mb-2">
                            <?= esc($item['pesan']) ?>
                        </p>

                        <small class="text-muted">
                            <i class="bi bi-clock"></i>

                            <?= date('d-m-Y H:i', strtotime($item['created_at'])) ?>
                        </small>

                    </div>

                    <?php if ($item['is_read'] == 0) : ?>

                        <form action="<?= site_url('notifikasi/baca/' . $item['id']) ?>"
                              method="post">

                            <?= csrf_field() ?>

                            <button type="submit"
                                    class="btn btn-sm btn-outline-success">
                                Tandai Dibaca
                            </button>

                        </form>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else : ?>

        <div class="content-card text-center py-5">

            <i class="bi bi-bell-slash text-muted"
               style="font-size: 3rem;"></i>

            <h5 class="mt-3">Belum Ada Notifikasi</h5>

            <p class="text-muted mb-0">
                Pemberitahuan akan muncul di sini ketika ada aktivitas terbaru.
            </p>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>