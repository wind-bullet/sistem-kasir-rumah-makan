<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #TRX-<?= sprintf('%05d', $transaksi['id_transaksi']) ?> - RM LEZAT JAYA</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier Prime', monospace;
            color: #000;
            background-color: #f1f1f1;
            margin: 0;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .actions {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            font-family: 'Outfit', sans-serif;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 0.9rem;
        }

        .btn-print {
            background-color: #ff5e36;
            color: white;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .receipt-container {
            background-color: #fff;
            width: 80mm;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-radius: 3px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .brand-address {
            font-size: 0.8rem;
            margin: 0;
            color: #333;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .meta-info {
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            margin: 10px 0;
        }

        .items-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .totals-section {
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 1rem;
        }

        .footer-msg {
            font-size: 0.85rem;
            margin-top: 20px;
            font-weight: bold;
        }

        /* Print Media CSS */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
                margin: 0;
            }
            .actions {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none;
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="actions">
    <button class="btn btn-print" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Struk</button>
    <a href="<?= base_url('transaksi') ?>" class="btn btn-back">Kembali</a>
</div>

<div class="receipt-container">
    <!-- Header -->
    <div class="text-center">
        <h1 class="brand-name">RM LEZAT JAYA</h1>
        <p class="brand-address">Jl. Raya Kenangan No. 88, Jakarta</p>
        <p class="brand-address">Telp: 021-12345678</p>
    </div>

    <div class="divider"></div>

    <!-- Metadata -->
    <div class="meta-info">
        <div class="meta-row">
            <span>No. Transaksi:</span>
            <span>#TRX-<?= sprintf('%05d', $transaksi['id_transaksi']) ?></span>
        </div>
        <div class="meta-row">
            <span>Tanggal:</span>
            <span><?= date('d/m/Y H:i:s', strtotime($transaksi['tanggal'])) ?></span>
        </div>
        <div class="meta-row">
            <span>Kasir:</span>
            <span><?= esc($transaksi['nama_kasir']) ?></span>
        </div>
        <div class="meta-row">
            <span>Meja:</span>
            <span><?= $transaksi['id_meja'] ? 'Meja ' . $transaksi['nomor_meja'] : 'Take Away' ?></span>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Order Items -->
    <table class="items-table">
        <tbody>
            <?php foreach ($transaksi['detail'] as $item): ?>
                <tr>
                    <td colspan="2">
                        <strong><?= esc($item['nama_menu']) ?></strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;">
                        <?= $item['jumlah'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                    </td>
                    <td class="text-right">
                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Totals -->
    <div class="totals-section">
        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
        </div>
        <div class="total-row">
            <span>METODE:</span>
            <span style="text-transform: uppercase;"><?= esc($transaksi['metode_pembayaran'] ?? 'cash') ?></span>
        </div>
        <div class="total-row">
            <span>BAYAR:</span>
            <span>Rp <?= number_format($transaksi['uang_bayar'], 0, ',', '.') ?></span>
        </div>
        <div class="total-row">
            <span>KEMBALIAN:</span>
            <span>Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></span>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Footer Message -->
    <div class="text-center footer-msg">
        <p>Terima Kasih Atas Kunjungan Anda!</p>
        <p>Silakan Datang Kembali</p>
    </div>
</div>

<script>
    // Automatically trigger print dialog
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });
</script>
</body>
</html>
