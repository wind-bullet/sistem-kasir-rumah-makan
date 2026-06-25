<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen User</h1>
    <a href="<?= base_url('user/create') ?>" class="btn btn-primary-custom"><i class="bi bi-person-plus me-2"></i> Tambah User</a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-people me-2 text-primary"></i> Daftar Pengguna Sistem</span>
            </div>
            <div class="card-body card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Tanggal Terdaftar</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada user terdaftar.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="fw-semibold text-dark"><?= esc($u['nama']) ?></td>
                                        <td><?= esc($u['username']) ?></td>
                                        <td>
                                            <?php if ($u['role'] === 'admin'): ?>
                                                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-shield-lock me-1"></i> Admin</span>
                                            <?php else: ?>
                                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-person me-1"></i> Kasir</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d-m-Y H:i', strtotime($u['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('user/edit/' . $u['id_user']) ?>" class="btn btn-sm btn-light border text-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <?php if ($u['id_user'] != session()->get('id_user')): ?>
                                                <a href="#" class="btn btn-sm btn-light border text-danger btn-delete" data-url="<?= base_url('user/delete/' . $u['id_user']) ?>" title="Hapus"><i class="bi bi-trash"></i></a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-light border text-muted" title="Sedang Aktif" disabled><i class="bi bi-trash"></i></button>
                                            <?php endif; ?>
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
                text: "User ini akan dihapus dari database!",
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
