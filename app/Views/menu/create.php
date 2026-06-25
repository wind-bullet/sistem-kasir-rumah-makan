<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Menu Baru</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-egg-fried me-2 text-primary"></i> Form Tambah Menu</span>
            </div>
            <div class="card-body card-body-custom">
                <form action="<?= base_url('menu/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_menu" class="form-label text-secondary fw-semibold">Nama Menu</label>
                            <input type="text" class="form-control <?= (validation_errors() && isset(validation_errors()['nama_menu'])) ? 'is-invalid' : '' ?>" id="nama_menu" name="nama_menu" placeholder="Contoh: Nasi Goreng Gila" value="<?= old('nama_menu') ?>" required autofocus>
                            <?php if (validation_errors() && isset(validation_errors()['nama_menu'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['nama_menu'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_kategori" class="form-label text-secondary fw-semibold">Kategori Menu</label>
                            <select class="form-select <?= (validation_errors() && isset(validation_errors()['id_kategori'])) ? 'is-invalid' : '' ?>" id="id_kategori" name="id_kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (validation_errors() && isset(validation_errors()['id_kategori'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['id_kategori'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label text-secondary fw-semibold">Harga (Rupiah)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control <?= (validation_errors() && isset(validation_errors()['harga'])) ? 'is-invalid' : '' ?>" id="harga" name="harga" min="0" placeholder="Contoh: 20000" value="<?= old('harga') ?>" required>
                                <?php if (validation_errors() && isset(validation_errors()['harga'])): ?>
                                    <div class="invalid-feedback"><?= validation_errors()['harga'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label text-secondary fw-semibold">Stok Awal</label>
                            <input type="number" class="form-control <?= (validation_errors() && isset(validation_errors()['stok'])) ? 'is-invalid' : '' ?>" id="stok" name="stok" min="0" placeholder="Contoh: 50" value="<?= old('stok', 0) ?>" required>
                            <?php if (validation_errors() && isset(validation_errors()['stok'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['stok'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="gambar" class="form-label text-secondary fw-semibold">Foto Menu (Opsional)</label>
                        <input type="file" class="form-control <?= (validation_errors() && isset(validation_errors()['gambar'])) ? 'is-invalid' : '' ?>" id="gambar" name="gambar" accept="image/*">
                        <div class="form-text">Maksimal ukuran file: 2MB. Format: JPG, JPEG, PNG.</div>
                        <?php if (validation_errors() && isset(validation_errors()['gambar'])): ?>
                            <div class="invalid-feedback"><?= validation_errors()['gambar'] ?></div>
                        <?php endif; ?>
                        <div class="mt-3 d-none" id="preview-container">
                            <label class="d-block text-secondary mb-2">Pratinjau Foto:</label>
                            <img id="img-preview" src="#" alt="Preview" class="rounded-3 img-thumbnail" style="max-height: 150px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('menu') ?>" class="btn btn-secondary-custom">Kembali</a>
                        <button type="submit" class="btn btn-primary-custom">Simpan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Image preview
    const imageInput = document.getElementById('gambar');
    const previewContainer = document.getElementById('preview-container');
    const imgPreview = document.getElementById('img-preview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('d-none');
            imgPreview.src = '#';
        }
    });
</script>
<?= $this->endSection() ?>
