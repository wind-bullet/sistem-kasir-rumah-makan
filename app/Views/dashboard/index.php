<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard</h1>
    <a href="<?= base_url('transaksi') ?>" class="btn btn-primary-custom"><i class="bi bi-cart-plus me-2"></i> Transaksi Baru</a>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Total Menu Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card stat-card-1">
            <div class="stat-card-value"><?= $total_menu ?></div>
            <div class="stat-card-label">Total Menu</div>
            <div class="stat-card-icon">
                <i class="bi bi-egg-fried"></i>
            </div>
        </div>
    </div>

    <!-- Transaksi Hari Ini Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card stat-card-2">
            <div class="stat-card-value"><?= $transaksi_hari_ini ?></div>
            <div class="stat-card-label">Transaksi Hari Ini</div>
            <div class="stat-card-icon">
                <i class="bi bi-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Pendapatan Hari Ini Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card stat-card-3">
            <div class="stat-card-value">Rp <?= number_format($pendapatan_hari_ini, 0, ',', '.') ?></div>
            <div class="stat-card-label">Pendapatan Hari Ini</div>
            <div class="stat-card-icon">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>

    <!-- Meja Kosong Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card stat-card-4">
            <div class="stat-card-value"><?= $meja_kosong ?> / <?= $total_meja ?></div>
            <div class="stat-card-label">Meja Kosong / Total</div>
            <div class="stat-card-icon">
                <i class="bi bi-table"></i>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row mt-2">
    <!-- Recent Transactions Table -->
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> 5 Transaksi Terakhir</span>
                <a href="<?= base_url('riwayat') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Tanggal & Waktu</th>
                                <th>Kasir</th>
                                <th>Meja</th>
                                <th>Total Harga</th>
                                <th>Uang Bayar</th>
                                <th>Kembalian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_transaksi)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi hari ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_transaksi as $tx): ?>
                                    <tr>
                                        <td class="fw-bold">#TRX-<?= sprintf('%05d', $tx['id_transaksi']) ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($tx['tanggal'])) ?></td>
                                        <td><?= esc($tx['nama_kasir'] ?? 'Pelanggan') ?></td>
                                        <td>
                                            <?php if ($tx['id_meja']): ?>
                                                <span class="badge bg-secondary">Meja <?= $tx['nomor_meja'] ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">Take Away</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-success">Rp <?= number_format($tx['total_harga'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($tx['uang_bayar'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($tx['kembalian'], 0, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= base_url('riwayat/detail/' . $tx['id_transaksi']) ?>" class="btn btn-sm btn-light border" title="Detail"><i class="bi bi-eye"></i></a>
                                            <a href="<?= base_url('struk/' . $tx['id_transaksi']) ?>" class="btn btn-sm btn-light border text-primary" title="Cetak Struk" target="_blank"><i class="bi bi-printer"></i></a>
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
