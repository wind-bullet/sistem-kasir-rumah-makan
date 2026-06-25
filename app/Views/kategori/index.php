<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Kategori Menu</h1>
    <a href="<?= base_url('kategori/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-2"></i> Tambah Kategori</a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-tags me-2 text-primary"></i> Daftar Kategori</span>
            </div>
            <div class="card-body card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Nama Kategori</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($kategori)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada kategori menu.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($kategori as $k): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="fw-semibold text-dark"><?= $k['nama_kategori'] ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($k['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('kategori/edit/' . $k['id_kategori']) ?>" class="btn btn-sm btn-light border text-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <a href="#" class="btn btn-sm btn-light border text-danger btn-delete" data-url="<?= base_url('kategori/delete/' . $k['id_kategori']) ?>" title="Hapus"><i class="bi bi-trash"></i></a>
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
                text: "Data kategori ini akan dihapus secara permanen!",
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
