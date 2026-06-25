<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen Meja</h1>
    <a href="<?= base_url('meja/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-2"></i> Tambah Meja</a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-table me-2 text-primary"></i> Daftar Meja Rumah Makan</span>
            </div>
            <div class="card-body card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Nomor Meja</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width: 250px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($meja)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada meja yang terdaftar.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($meja as $m): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="fw-bold text-dark">Meja Nomor <?= esc($m['nomor_meja']) ?></td>
                                        <td>
                                            <?php if ($m['status'] === 'kosong'): ?>
                                                <span class="badge bg-badge-kosong px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Kosong</span>
                                            <?php else: ?>
                                                <span class="badge bg-badge-terisi px-3 py-2 rounded-pill"><i class="bi bi-dash-circle me-1"></i> Terisi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d-m-Y H:i', strtotime($m['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('meja/toggle-status/' . $m['id_meja']) ?>" class="btn btn-sm btn-light border text-secondary" title="Ubah Status"><i class="bi bi-arrow-left-right"></i> Toggle Status</a>
                                            <a href="<?= base_url('meja/edit/' . $m['id_meja']) ?>" class="btn btn-sm btn-light border text-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <a href="#" class="btn btn-sm btn-light border text-danger btn-delete" data-url="<?= base_url('meja/delete/' . $m['id_meja']) ?>" title="Hapus"><i class="bi bi-trash"></i></a>
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
                text: "Data meja ini akan dihapus secara permanen!",
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
