<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Detail Transaksi</h1>
</div>

<div class="row">
    <!-- Transaction Metadata Card -->
    <div class="col-md-4 mb-4">
        <div class="card card-custom shadow-sm h-100">
            <div class="card-header-custom bg-light">
                <span class="fw-bold"><i class="bi bi-info-circle-fill text-primary me-2"></i> Informasi Transaksi</span>
            </div>
            <div class="card-body card-body-custom">
                <table class="table table-sm table-borderless align-middle mb-0" style="font-size: 0.95rem;">
                    <tr>
                        <td class="text-secondary" style="width: 140px;">No. Transaksi</td>
                        <td class="fw-bold">: #TRX-<?= sprintf('%05d', $transaksi['id_transaksi']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Tanggal / Waktu</td>
                        <td>: <?= date('d-m-Y H:i:s', strtotime($transaksi['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Kasir yang Melayani</td>
                        <td>: <?= esc($transaksi['nama_kasir']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Meja</td>
                        <td>: <?= $transaksi['id_meja'] ? 'Meja Nomor ' . $transaksi['nomor_meja'] : 'Take Away (Bawa Pulang)' ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Metode Pembayaran</td>
                        <td>: <span class="badge <?= (isset($transaksi['metode_pembayaran']) && $transaksi['metode_pembayaran'] === 'qris') ? 'bg-primary' : 'bg-secondary' ?> fw-bold text-uppercase"><?= esc($transaksi['metode_pembayaran'] ?? 'cash') ?></span></td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-secondary pt-3">Total Belanja</td>
                        <td class="fw-bold text-success fs-5 pt-3">: Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Uang Bayar</td>
                        <td class="fw-semibold text-dark">: Rp <?= number_format($transaksi['uang_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Kembalian</td>
                        <td class="fw-semibold text-dark">: Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></td>
                    </tr>
                </table>
                <div class="d-grid gap-2 mt-4 pt-3 border-top">
                    <a href="<?= base_url('struk/' . $transaksi['id_transaksi']) ?>" class="btn btn-primary-custom" target="_blank"><i class="bi bi-printer me-2"></i> Cetak Ulang Struk</a>
                    <a href="<?= base_url('riwayat') ?>" class="btn btn-secondary-custom"><i class="bi bi-arrow-left me-2"></i> Kembali ke Riwayat</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Purchased Table Card -->
    <div class="col-md-8 mb-4">
        <div class="card card-custom shadow-sm h-100">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-egg-fried text-primary me-2"></i> Item yang Dipesan</span>
            </div>
            <div class="card-body card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Menu</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center" style="width: 120px;">Jumlah</th>
                                <th class="text-end" style="width: 180px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($transaksi['detail'] as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= esc($item['nama_menu']) ?></div>
                                        <small class="text-muted">ID Menu: #MNU-<?= sprintf('%04d', $item['id_menu']) ?></small>
                                    </td>
                                    <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $item['jumlah'] ?> pcs</td>
                                    <td class="text-end fw-bold text-dark">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light fs-5 fw-bold border-top">
                                <td colspan="4" class="text-end text-dark">Total Harga:</td>
                                <td class="text-end text-success">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
