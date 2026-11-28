<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">

    <div class="content-card">

        <h4 class="fw-bold mb-2">
            <i class="bi bi-check-circle text-success"></i>
            Selesaikan Tugas
        </h4>

        <p class="text-muted">
            Unggah foto kondisi sebelum dan sesudah pekerjaan dilakukan.
        </p>

        <hr>

        <div class="mb-4">
            <h5><?= esc($tugas['judul']) ?></h5>

            <span class="badge bg-primary">
                <?= esc($tugas['kategori']) ?>
            </span>

            <span class="badge bg-warning text-dark">
                <?= esc($tugas['prioritas']) ?>
            </span>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('petugas/tugas/selesai/simpan/' . $tugas['id']) ?>"
              method="post"
              enctype="multipart/form-data">

            <?= csrf_field() ?>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Foto Sebelum Dibersihkan
                </label>

                <input type="file"
                       name="foto_sebelum"
                       class="form-control"
                       accept="image/jpeg,image/png,image/webp"
                       capture="environment"
                       required>

                <small class="text-muted">
                    Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                </small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Foto Sesudah Dibersihkan
                </label>

                <input type="file"
                       name="foto_sesudah"
                       class="form-control"
                       accept="image/jpeg,image/png,image/webp"
                       capture="environment"
                       required>

                <small class="text-muted">
                    Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                </small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Catatan Pekerjaan (Opsional)
                </label>

                <textarea name="catatan"
                          class="form-control"
                          rows="4"
                          maxlength="1000"
                          placeholder="Tuliskan keterangan pekerjaan yang telah dilakukan..."><?= esc(old('catatan')) ?></textarea>
            </div>

            <div class="d-flex gap-2 flex-wrap">

                <a href="<?= site_url('petugas/dashboard') ?>"
                   class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

                <button type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Apakah Anda yakin pekerjaan sudah selesai?')">
                    <i class="bi bi-check-circle"></i>
                    Selesaikan Tugas
                </button>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>