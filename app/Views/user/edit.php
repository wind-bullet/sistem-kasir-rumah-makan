<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Edit User</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Form Edit Pengguna</span>
            </div>
            <div class="card-body card-body-custom">
                <form action="<?= base_url('user/update/' . $user['id_user']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label text-secondary fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control <?= (validation_errors() && isset(validation_errors()['nama'])) ? 'is-invalid' : '' ?>" id="nama" name="nama" placeholder="Masukkan nama lengkap" value="<?= old('nama', $user['nama']) ?>" required>
                            <?php if (validation_errors() && isset(validation_errors()['nama'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['nama'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label text-secondary fw-semibold">Username</label>
                            <input type="text" class="form-control <?= (validation_errors() && isset(validation_errors()['username'])) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Masukkan username login" value="<?= old('username', $user['username']) ?>" required>
                            <?php if (validation_errors() && isset(validation_errors()['username'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['username'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-secondary fw-semibold">Password Baru (Opsional)</label>
                            <input type="password" class="form-control <?= (validation_errors() && isset(validation_errors()['password'])) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <div class="form-text">Isi kolom ini hanya jika Anda ingin mengganti password user.</div>
                            <?php if (validation_errors() && isset(validation_errors()['password'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['password'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label text-secondary fw-semibold">Hak Akses / Role</label>
                            <select class="form-select <?= (validation_errors() && isset(validation_errors()['role'])) ? 'is-invalid' : '' ?>" id="role" name="role" required <?= $user['id_user'] == session()->get('id_user') ? 'disabled' : '' ?>>
                                <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="kasir" <?= old('role', $user['role']) === 'kasir' ? 'selected' : '' ?>>Kasir</option>
                            </select>
                            <?php if ($user['id_user'] == session()->get('id_user')): ?>
                                <input type="hidden" name="role" value="<?= $user['role'] ?>">
                                <div class="form-text text-warning"><i class="bi bi-exclamation-triangle"></i> Anda tidak bisa mengubah role akun sendiri yang sedang aktif.</div>
                            <?php endif; ?>
                            <?php if (validation_errors() && isset(validation_errors()['role'])): ?>
                                <div class="invalid-feedback"><?= validation_errors()['role'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('user') ?>" class="btn btn-secondary-custom">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
