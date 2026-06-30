<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen QR Code Meja</h1>
    <div>
        <a href="<?= base_url('meja') ?>" class="btn btn-secondary-custom"><i class="bi bi-table me-2"></i> Manajemen Meja</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <span class="fw-bold"><i class="bi bi-gear-fill me-2 text-primary"></i> Pengaturan QR Code</span>
            </div>
            <div class="card-body card-body-custom">
                <form action="<?= base_url('qrcode/generate') ?>" method="GET" class="row align-items-end g-3">
                    <div class="col-md-7">
                        <label for="ip" class="form-label fw-semibold text-secondary">
                            IP Address / Host Local Server (Opsional)
                        </label>
                        <input type="text" class="form-control" id="ip" name="ip" placeholder="Contoh: 192.168.1.100:8080" value="<?= esc($ip) ?>">
                        <div class="form-text">
                            Biarkan kosong untuk menggunakan Base URL default sistem (<?= base_url() ?>). Isi jika Anda ingin customer mengakses menggunakan IP lokal komputer ini melalui jaringan Wi-Fi/HP.
                        </div>
                    </div>
                    <div class="col-md-5 d-grid">
                        <button type="submit" class="btn btn-primary-custom py-2">
                            <i class="bi bi-qr-code-scan me-2"></i> Generate Ulang Semua QR Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 col-sm-6">
        <div class="card card-custom border-primary h-100 text-center shadow-sm">
            <div class="card-header-custom bg-primary text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-bag-check-fill me-2"></i> QR Code Take Away</span>
                <span class="badge bg-light text-primary rounded-pill" style="font-size: 0.75rem;">Bawa Pulang</span>
            </div>
            <div class="card-body card-body-custom d-flex flex-column align-items-center justify-content-center py-4">
                <?php 
                    $takeawayFile = 'QR_images/qr_takeaway.png';
                    $takeawayExists = file_exists(ROOTPATH . 'public/' . $takeawayFile);
                ?>
                <?php if ($takeawayExists): ?>
                    <div class="bg-white p-3 rounded shadow-sm border mb-3" style="max-width: 200px;">
                        <img src="<?= base_url($takeawayFile) ?>?t=<?= time() ?>" alt="QR Take Away" class="img-fluid">
                    </div>
                    <div class="text-muted mb-3" style="font-size: 0.85rem; word-break: break-all;">
                        <strong>Target URL:</strong><br>
                        <span class="text-primary">
                            <?php 
                                if (!empty($ip)) {
                                    $cleanIp = rtrim($ip, '/');
                                    if (!str_starts_with($cleanIp, 'http://') && !str_starts_with($cleanIp, 'https://')) {
                                        $cleanIp = 'http://' . $cleanIp;
                                    }
                                    echo esc($cleanIp . '/pesan/takeaway');
                                } else {
                                    echo site_url('pesan/takeaway');
                                }
                            ?>
                        </span>
                    </div>
                    <div class="mt-auto w-100">
                        <a href="<?= base_url($takeawayFile) ?>" download="QR_TakeAway.png" class="btn btn-outline-primary w-100 mb-2 btn-sm">
                            <i class="bi bi-download me-2"></i> Unduh Gambar
                        </a>
                        <a href="<?= base_url('qrcode/generate-takeaway' . (!empty($ip) ? '?ip=' . urlencode($ip) : '')) ?>" class="btn btn-outline-secondary w-100 btn-sm">
                            <i class="bi bi-arrow-clockwise me-2"></i> Generate Ulang
                        </a>
                    </div>
                <?php else: ?>
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center border mb-3" style="width: 180px; height: 180px;">
                        <i class="bi bi-qr-code text-muted" style="font-size: 3rem;"></i>
                        <span class="text-muted mt-2 fw-medium" style="font-size: 0.8rem;">QR Take Away Belum Dibuat</span>
                    </div>
                    <div class="mt-auto w-100">
                        <a href="<?= base_url('qrcode/generate-takeaway' . (!empty($ip) ? '?ip=' . urlencode($ip) : '')) ?>" class="btn btn-primary-custom w-100 btn-sm">
                            <i class="bi bi-gear-wide-connected me-2"></i> Generate QR Take Away
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-table me-2"></i> QR Code Berdasarkan Meja</h5>

<div class="row">
    <?php if (empty($meja)): ?>
        <div class="col-12">
            <div class="card card-custom py-5 text-center">
                <div class="card-body card-body-custom">
                    <i class="bi bi-exclamation-circle text-muted fs-1 mb-3"></i>
                    <h5 class="text-secondary fw-semibold">Belum Ada Meja Terdaftar</h5>
                    <p class="text-muted">Silakan tambahkan meja terlebih dahulu di menu Manajemen Meja.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($meja as $m): ?>
            <?php 
                $nomorMeja = $m['nomor_meja'];
                $qrFile = 'QR_images/qr_meja_' . $nomorMeja . '.png';
                $qrExists = file_exists(ROOTPATH . 'public/' . $qrFile);
            ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card card-custom h-100 text-center">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Meja Nomor <?= esc($nomorMeja) ?></span>
                        <?php if ($m['status'] === 'kosong'): ?>
                            <span class="badge bg-badge-kosong rounded-pill text-success" style="font-size: 0.75rem;"><i class="bi bi-check-circle me-1"></i> Kosong</span>
                        <?php else: ?>
                            <span class="badge bg-badge-terisi rounded-pill text-danger" style="font-size: 0.75rem;"><i class="bi bi-dash-circle me-1"></i> Terisi</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body card-body-custom d-flex flex-column align-items-center justify-content-center py-4">
                        <?php if ($qrExists): ?>
                            <div class="bg-white p-3 rounded shadow-sm border mb-3" style="max-width: 200px;">
                                <img src="<?= base_url($qrFile) ?>?t=<?= time() ?>" alt="QR Meja <?= esc($nomorMeja) ?>" class="img-fluid">
                            </div>
                            <div class="text-muted mb-3" style="font-size: 0.85rem; word-break: break-all;">
                                <strong>Target URL:</strong><br>
                                <span class="text-primary">
                                    <?php 
                                        if (!empty($ip)) {
                                            $cleanIp = rtrim($ip, '/');
                                            if (!str_starts_with($cleanIp, 'http://') && !str_starts_with($cleanIp, 'https://')) {
                                                $cleanIp = 'http://' . $cleanIp;
                                            }
                                            echo esc($cleanIp . '/pesan/' . $nomorMeja);
                                        } else {
                                            echo site_url('pesan/' . $nomorMeja);
                                        }
                                    ?>
                                </span>
                            </div>
                            <div class="mt-auto w-100">
                                <a href="<?= base_url($qrFile) ?>" download="QR_Meja_<?= esc($nomorMeja) ?>.png" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                    <i class="bi bi-download me-2"></i> Unduh Gambar
                                </a>
                                <a href="<?= base_url('qrcode/generate/' . esc($nomorMeja) . (!empty($ip) ? '?ip=' . urlencode($ip) : '')) ?>" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="bi bi-arrow-clockwise me-2"></i> Generate Ulang
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center border mb-3" style="width: 180px; height: 180px;">
                                <i class="bi bi-qr-code text-muted" style="font-size: 3rem;"></i>
                                <span class="text-muted mt-2 fw-medium" style="font-size: 0.8rem;">QR Belum Dibuat</span>
                            </div>
                            <div class="mt-auto w-100">
                                <a href="<?= base_url('qrcode/generate/' . esc($nomorMeja) . (!empty($ip) ? '?ip=' . urlencode($ip) : '')) ?>" class="btn btn-primary-custom btn-sm w-100">
                                    <i class="bi bi-gear-wide-connected me-2"></i> Generate QR
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

