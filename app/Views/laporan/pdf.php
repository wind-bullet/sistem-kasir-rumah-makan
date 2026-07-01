<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - RM LEZAT JAYA</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px double #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 20pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 9pt;
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .report-meta {
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .report-meta table {
            width: 100%;
        }
        .report-meta td {
            padding: 3px 0;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10pt;
        }
        .table-data th, .table-data td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        .table-data th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .table-data tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
        .summary-section {
            margin-bottom: 30px;
            background-color: #fcfcfc;
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 5px;
            font-size: 10pt;
        }
        .summary-row {
            display: block;
            margin-bottom: 5px;
        }
        .signature-section {
            margin-top: 50px;
            text-align: right;
            font-size: 10pt;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 70px;
        }
    </style>
</head>
<body>

    <!-- Store Header -->
    <div class="header">
        <h1>RM LEZAT JAYA</h1>
        <p>Jl. Raya Kenangan No. 88, Jakarta | Telp: 021-12345678</p>
    </div>

    <!-- Report Title -->
    <div class="report-title">
        Laporan Penjualan
    </div>

    <!-- Metadata info -->
    <div class="report-meta">
        <table>
            <tr>
                <td style="width: 15%;">Tipe Periode</td>
                <td style="width: 35%;">: <strong><?= ucfirst($tipe) ?></strong></td>
                <td style="width: 15%;">Tanggal Cetak</td>
                <td style="width: 35%;">: <?= date('d-m-Y H:i:s') ?></td>
            </tr>
            <tr>
                <td>Tanggal Acuan</td>
                <td>: <?= date('d-m-Y', strtotime($tanggal)) ?></td>
                <td>Rentang Laporan</td>
                <td>: <?= date('d-m-Y', strtotime($dari)) ?> s/d <?= date('d-m-Y', strtotime($sampai)) ?></td>
            </tr>
        </table>
    </div>

    <!-- Summary Box -->
    <div class="summary-section">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>Total Transaksi</td>
                <td class="fw-bold">: <?= count($transaksi) ?> kali</td>
            </tr>
            <tr>
                <td>Total Pendapatan</td>
                <td class="fw-bold text-success">: Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">No. Transaksi</th>
                <th style="width: 25%;">Tanggal & Waktu</th>
                <th style="width: 20%;">Kasir</th>
                <th style="width: 15%;">Meja</th>
                <th style="width: 15%;" class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transaksi)): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px 0; color: #777;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($transaksi as $tx): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-bold">#TRX-<?= sprintf('%05d', $tx['id_transaksi']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($tx['tanggal'])) ?></td>
                        <td><?= esc($tx['nama_kasir'] ?? 'Pelanggan') ?></td>
                        <td class="text-center">
                            <?= $tx['id_meja'] ? 'Meja ' . $tx['nomor_meja'] : 'Take Away' ?>
                        </td>
                        <td class="text-right fw-bold">Rp <?= number_format($tx['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- Total Row -->
                <tr style="background-color: #f5f5f5; font-size: 11pt;">
                    <td colspan="5" class="text-right fw-bold" style="padding: 10px;">GRAND TOTAL:</td>
                    <td class="text-right fw-bold" style="padding: 10px;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Jakarta, <?= date('d F Y') ?></p>
            <p>Mengetahui,</p>
            <p class="fw-bold" style="margin-top: 10px;">Administrator</p>
            <div class="signature-space"></div>
            <hr style="border: 0.5px solid #333; width: 150px; margin: 0 auto 5px auto;">
            <p style="margin: 0; font-size: 9pt;">RM LEZAT JAYA</p>
        </div>
    </div>

</body>
</html>
