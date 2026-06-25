<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Meja</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-table me-2 text-primary"></i> Form Tambah Meja</span>
            </div>
            <div class="card-body card-body-custom">
                <form action="<?= base_url('meja/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nomor_meja" class="form-label text-secondary fw-semibold">Nomor Meja</label>
                        <input type="number" class="form-control <?= (validation_errors() && isset(validation_errors()['nomor_meja'])) ? 'is-invalid' : '' ?>" id="nomor_meja" name="nomor_meja" placeholder="Masukkan nomor meja (angka)" value="<?= old('nomor_meja') ?>" min="1" required autofocus>
                        <?php if (validation_errors() && isset(validation_errors()['nomor_meja'])): ?>
                            <div class="invalid-feedback">
                                <?= validation_errors()['nomor_meja'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('meja') ?>" class="btn btn-secondary-custom">Kembali</a>
                        <button type="submit" class="btn btn-primary-custom">Simpan Meja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
