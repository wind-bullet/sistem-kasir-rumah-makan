<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Menu Makanan & Minuman</h1>
    <a href="<?= base_url('menu/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-2"></i> Tambah Menu</a>
</div>

<!-- Search & Filter Controls -->
<div class="card card-custom mb-4">
    <div class="card-body card-body-custom py-3">
        <form action="<?= base_url('menu') ?>" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="keyword" placeholder="Cari nama menu..." value="<?= esc($keyword) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" name="kategori">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= ($kategoriId == $k['id_kategori']) ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary-custom px-4"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="<?= base_url('menu') ?>" class="btn btn-secondary-custom px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Menu List Table -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-egg-fried me-2 text-primary"></i> Daftar Menu</span>
            </div>
            <div class="card-body card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Gambar</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($menu)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Tidak ada menu yang cocok dengan pencarian.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($menu as $m): ?>
                                    <tr>
                                        <td>
                                            <?php if ($m['gambar'] && file_exists(ROOTPATH . 'public/assets/uploads/menu/' . $m['gambar'])): ?>
                                                <img src="<?= base_url('assets/uploads/menu/' . $m['gambar']) ?>" alt="<?= esc($m['nama_menu']) ?>" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #eee;">
                                            <?php else: ?>
                                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-primary-custom" style="width: 60px; height: 60px; border: 1px solid #eee;">
                                                    <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?= esc($m['nama_menu']) ?></div>
                                            <small class="text-muted">ID: #MNU-<?= sprintf('%04d', $m['id_menu']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><?= esc($m['nama_kategori']) ?></span>
                                        </td>
                                        <td class="fw-bold text-success">Rp <?= number_format($m['harga'], 0, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= base_url('menu/edit/' . $m['id_menu']) ?>" class="btn btn-sm btn-light border text-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <a href="#" class="btn btn-sm btn-light border text-danger btn-delete" data-url="<?= base_url('menu/delete/' . $m['id_menu']) ?>" title="Hapus"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Menu ini akan dihapus dari sistem secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5e36',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#fff',
                color: '#333'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
