<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Edit Meja</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Form Edit Meja</span>
            </div>
            <div class="card-body card-body-custom">
                <form action="<?= base_url('meja/update/' . $meja['id_meja']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nomor_meja" class="form-label text-secondary fw-semibold">Nomor Meja</label>
                        <input type="number" class="form-control <?= (validation_errors() && isset(validation_errors()['nomor_meja'])) ? 'is-invalid' : '' ?>" id="nomor_meja" name="nomor_meja" placeholder="Masukkan nomor meja (angka)" value="<?= old('nomor_meja', $meja['nomor_meja']) ?>" min="1" required>
                        <?php if (validation_errors() && isset(validation_errors()['nomor_meja'])): ?>
                            <div class="invalid-feedback">
                                <?= validation_errors()['nomor_meja'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label text-secondary fw-semibold">Status Meja</label>
                        <select class="form-select <?= (validation_errors() && isset(validation_errors()['status'])) ? 'is-invalid' : '' ?>" id="status" name="status" required>
                            <option value="kosong" <?= old('status', $meja['status']) === 'kosong' ? 'selected' : '' ?>>Kosong</option>
                            <option value="terisi" <?= old('status', $meja['status']) === 'terisi' ? 'selected' : '' ?>>Terisi</option>
                        </select>
                        <?php if (validation_errors() && isset(validation_errors()['status'])): ?>
                            <div class="invalid-feedback">
                                <?= validation_errors()['status'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('meja') ?>" class="btn btn-secondary-custom">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">Update Meja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
