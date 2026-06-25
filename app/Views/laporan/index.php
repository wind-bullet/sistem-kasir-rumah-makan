<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Laporan Penjualan</h1>
</div>

<!-- Laporan Filter Card -->
<div class="card card-custom mb-4">
    <div class="card-header-custom">
        <span class="fw-bold"><i class="bi bi-funnel-fill text-primary me-2"></i> Filter Laporan</span>
    </div>
    <div class="card-body card-body-custom">
        <form id="laporan-form" action="<?= base_url('laporan/generate') ?>" method="POST" class="row g-3 align-items-end">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <label for="tipe" class="form-label text-secondary fw-semibold">Tipe Periode</label>
                <select class="form-select" id="tipe" name="tipe" required>
                    <option value="harian" <?= $tipe === 'harian' ? 'selected' : '' ?>>Harian (1 Hari)</option>
                    <option value="mingguan" <?= $tipe === 'mingguan' ? 'selected' : '' ?>>Mingguan (7 Hari Terakhir)</option>
                    <option value="bulanan" <?= $tipe === 'bulanan' ? 'selected' : '' ?>>Bulanan (Satu Bulan Penuh)</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <label for="tanggal" class="form-label text-secondary fw-semibold">Pilih Tanggal Acuan</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= esc($tanggal) ?>" required>
            </div>

            <div class="col-md-4 d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary-custom px-4" id="btn-tampilkan"><i class="bi bi-display me-2"></i> Tampilkan</button>
                <button type="button" class="btn btn-danger px-4" id="btn-export-pdf" style="border-radius: 10px;"><i class="bi bi-file-earmark-pdf me-2"></i> Export PDF</button>
            </div>
        </form>
    </div>
</div>

<!-- Results Area -->
<?php if ($transaksi !== null): ?>
    <!-- Summaries -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="stat-card stat-card-3 py-3" style="height: auto;">
                <div class="stat-card-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                <div class="stat-card-label">Total Pendapatan</div>
                <div class="stat-card-icon"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="stat-card stat-card-2 py-3" style="height: auto;">
                <div class="stat-card-value"><?= count($transaksi) ?> Transaksi</div>
                <div class="stat-card-label">Jumlah Transaksi</div>
                <div class="stat-card-icon"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Menu Items -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header-custom">
                    <span class="fw-bold"><i class="bi bi-star-fill text-warning me-2"></i> Menu Terlaris</span>
                </div>
                <div class="card-body card-body-custom p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>Nama Menu</th>
                                    <th class="text-center" style="width: 110px;">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($top_menu)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">Belum ada menu terjual.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($top_menu as $menu): ?>
                                        <tr>
                                            <td class="fw-semibold text-dark"><?= esc($menu['nama_menu']) ?></td>
                                            <td class="text-center"><span class="badge bg-success px-2 py-1"><?= $menu['total_terjual'] ?> porsi</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header-custom">
                    <span class="fw-bold"><i class="bi bi-list-task text-primary me-2"></i> Rincian Penjualan (<?= date('d-m-Y', strtotime($dari)) ?> s/d <?= date('d-m-Y', strtotime($sampai)) ?>)</span>
                </div>
                <div class="card-body card-body-custom p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Kasir</th>
                                    <th>Meja</th>
                                    <th class="text-end">Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transaksi)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada transaksi pada periode ini.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transaksi as $tx): ?>
                                        <tr>
                                            <td class="fw-bold">#TRX-<?= sprintf('%05d', $tx['id_transaksi']) ?></td>
                                            <td><?= date('d-m-Y H:i', strtotime($tx['tanggal'])) ?></td>
                                            <td><?= esc($tx['nama_kasir']) ?></td>
                                            <td>
                                                <?= $tx['id_meja'] ? 'Meja ' . $tx['nomor_meja'] : '<span class="text-muted">Take Away</span>' ?>
                                            </td>
                                            <td class="text-end fw-bold text-success">Rp <?= number_format($tx['total_harga'], 0, ',', '.') ?></td>
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
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Handle switching action for PDF export
    document.getElementById('btn-export-pdf').addEventListener('click', function() {
        const form = document.getElementById('laporan-form');
        
        // Temporarily change form attributes for PDF export
        const originalAction = form.action;
        form.action = '<?= base_url("laporan/export-pdf") ?>';
        form.target = '_blank';
        
        // Submit the form
        form.submit();
        
        // Restore original form attributes
        form.action = originalAction;
        form.target = '';
    });
</script>
<?= $this->endSection() ?>
