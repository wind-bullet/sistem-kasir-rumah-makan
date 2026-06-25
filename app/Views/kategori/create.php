<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Kategori</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-tag-fill me-2 text-primary"></i> Form Tambah Kategori</span>
            </div>
            <div class="card-body card-body-custom">
                <form action="<?= base_url('kategori/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label text-secondary fw-semibold">Nama Kategori</label>
                        <input type="text" class="form-control <?= (validation_errors() && isset(validation_errors()['nama_kategori'])) ? 'is-invalid' : '' ?>" id="nama_kategori" name="nama_kategori" placeholder="Contoh: Makanan, Minuman, Dessert" value="<?= old('nama_kategori') ?>" required autofocus>
                        <?php if (validation_errors() && isset(validation_errors()['nama_kategori'])): ?>
                            <div class="invalid-feedback">
                                <?= validation_errors()['nama_kategori'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('kategori') ?>" class="btn btn-secondary-custom">Kembali</a>
                        <button type="submit" class="btn btn-primary-custom">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
