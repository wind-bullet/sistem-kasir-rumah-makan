<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - RM LEZAT JAYA</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff5e36;
            --bg-light: #f8f9fa;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 100%;
            background: white;
            padding: 40px 30px;
        }
        .success-icon {
            font-size: 5rem;
            color: #2ec4b6;
            line-height: 1;
            margin-bottom: 20px;
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 30px;
            transition: all 0.2s ease;
        }
        .btn-primary-custom:hover {
            background-color: #e04f2a;
            border-color: #e04f2a;
            color: white;
        }
        .item-row {
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
        }
        .item-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center p-3">
        <div class="success-card text-center shadow-lg">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            
            <h3 class="fw-bold text-dark mb-2">Pesanan Dikirim!</h3>
            <p class="text-secondary mb-4">Pesanan Anda telah diterima sistem dan sedang diproses oleh dapur kami.</p>

            <!-- Order Info Summary -->
            <div class="bg-light p-3 rounded-4 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">No. Transaksi</span>
                    <span class="fw-bold text-dark">#TX-<?= sprintf('%05d', $order['id_transaksi']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Tipe Pesanan / Meja</span>
                    <span class="fw-bold text-dark"><?= ($order['nomor_meja'] === 'Take Away' || empty($order['nomor_meja'])) ? 'Take Away (Bawa Pulang)' : 'Dine In - Meja ' . esc($order['nomor_meja']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Metode Pembayaran</span>
                    <span class="badge <?= (isset($order['metode_pembayaran']) && $order['metode_pembayaran'] === 'qris') ? 'bg-primary' : 'bg-secondary' ?> fw-bold text-uppercase"><?= esc($order['metode_pembayaran'] ?? 'cash') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">Waktu</span>
                    <span class="fw-bold text-dark"><?= date('H:i') ?> WIB</span>
                </div>
                
                <h6 class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">Detail Pesanan:</h6>
                <div class="mb-3">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="d-flex justify-content-between align-items-center item-row">
                            <span class="text-dark"><?= esc($item['nama_menu']) ?> <strong class="text-secondary">x<?= $item['jumlah'] ?></strong></span>
                            <span class="fw-semibold text-dark">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">Total</span>
                    <span class="fw-bold text-success fs-5">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="d-grid gap-2">
                <?php if ($order['nomor_meja'] === 'Take Away' || empty($order['nomor_meja'])): ?>
                    <a href="<?= base_url('pesan/takeaway') ?>" class="btn btn-primary-custom">
                        <i class="bi bi-plus-lg me-2"></i> Pesan Menu Tambahan
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('pesan/' . $order['nomor_meja']) ?>" class="btn btn-primary-custom">
                        <i class="bi bi-plus-lg me-2"></i> Pesan Menu Tambahan
                    </a>
                <?php endif; ?>
                <div class="text-muted mt-3" style="font-size: 0.85rem;">
                    <?php if (isset($order['metode_pembayaran']) && $order['metode_pembayaran'] === 'qris'): ?>
                        Silakan tunjukkan bukti pembayaran QRIS Anda ke kasir saat mengambil pesanan. Terima kasih!
                    <?php else: ?>
                        <?php if ($order['nomor_meja'] === 'Take Away' || empty($order['nomor_meja'])): ?>
                            Silakan lakukan pembayaran langsung di meja kasir untuk mengambil pesanan Anda. Terima kasih!
                        <?php else: ?>
                            Silakan bayar di kasir setelah Anda selesai bersantap. Terima kasih!
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
