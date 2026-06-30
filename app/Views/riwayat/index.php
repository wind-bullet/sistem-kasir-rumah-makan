<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Riwayat Transaksi</h1>
</div>

<!-- Filter Periode Tanggal -->
<div class="card card-custom mb-4">
    <div class="card-body card-body-custom py-3">
        <form action="<?= base_url('riwayat') ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="dari" class="form-label text-secondary fw-semibold" style="font-size: 0.85rem;">Dari Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="dari" name="dari" value="<?= esc($dari) ?>">
            </div>
            <div class="col-md-4">
                <label for="sampai" class="form-label text-secondary fw-semibold" style="font-size: 0.85rem;">Sampai Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="sampai" name="sampai" value="<?= esc($sampai) ?>">
            </div>
            <div class="col-md-4 d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary-custom px-4"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="<?= base_url('riwayat') ?>" class="btn btn-secondary-custom px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- History Transactions Table -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i> Daftar Riwayat Penjualan</span>
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
                                <th>Metode</th>
                                <th>Total Harga</th>
                                <th>Uang Bayar</th>
                                <th>Kembalian</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transaksi)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Tidak ditemukan riwayat transaksi.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transaksi as $tx): ?>
                                    <tr>
                                        <td class="fw-bold">#TRX-<?= sprintf('%05d', $tx['id_transaksi']) ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($tx['tanggal'])) ?></td>
                                        <td><?= esc($tx['nama_kasir']) ?></td>
                                        <td>
                                            <?php if ($tx['id_meja']): ?>
                                                <span class="badge bg-secondary">Meja <?= $tx['nomor_meja'] ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark border">Take Away</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= (isset($tx['metode_pembayaran']) && $tx['metode_pembayaran'] === 'qris') ? 'bg-primary' : 'bg-secondary' ?> text-uppercase" style="font-size: 0.75rem;"><?= esc($tx['metode_pembayaran'] ?? 'cash') ?></span>
                                        </td>
                                        <td class="fw-bold text-success">Rp <?= number_format($tx['total_harga'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($tx['uang_bayar'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($tx['kembalian'], 0, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= base_url('riwayat/detail/' . $tx['id_transaksi']) ?>" class="btn btn-sm btn-light border text-dark" title="Lihat Detail"><i class="bi bi-eye"></i> Detail</a>
                                            <a href="<?= base_url('struk/' . $tx['id_transaksi']) ?>" class="btn btn-sm btn-light border text-primary" title="Cetak Struk" target="_blank"><i class="bi bi-printer"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                    <?= $pager->links('transaksi', 'default_full') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
