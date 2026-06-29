<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Kasir Rumah Makan' ?> - RM LEZAT JAYA</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3><i class="bi bi-shop me-2"></i>LEZAT JAYA</h3>
        </div>

        <?php
            $role = session()->get('role');
            $current_uri = service('uri')->getSegment(1);
        ?>

        <ul class="list-unstyled components">
            <li class="<?= ($current_uri == 'dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url('dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </li>
            
            <li class="<?= ($current_uri == 'transaksi') ? 'active' : '' ?>">
                <a href="<?= base_url('transaksi') ?>"><i class="bi bi-cart3"></i> Transaksi Baru</a>
            </li>

            <li class="<?= ($current_uri == 'riwayat' || $current_uri == 'struk') ? 'active' : '' ?>">
                <a href="<?= base_url('riwayat') ?>"><i class="bi bi-journal-text"></i> Riwayat Transaksi</a>
            </li>

            <?php if ($role === 'admin'): ?>
                <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 15px;">
                <div class="px-4 py-1 text-uppercase text-secondary fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Master Data</div>
                
                <li class="<?= ($current_uri == 'menu') ? 'active' : '' ?>">
                    <a href="<?= base_url('menu') ?>"><i class="bi bi-egg-fried"></i> Menu Makanan</a>
                </li>
                
                <li class="<?= ($current_uri == 'kategori') ? 'active' : '' ?>">
                    <a href="<?= base_url('kategori') ?>"><i class="bi bi-tags"></i> Kategori Menu</a>
                </li>
                
                <li class="<?= ($current_uri == 'meja') ? 'active' : '' ?>">
                    <a href="<?= base_url('meja') ?>"><i class="bi bi-table"></i> Manajemen Meja</a>
                </li>
                
                <li class="<?= ($current_uri == 'qrcode') ? 'active' : '' ?>">
                    <a href="<?= base_url('qrcode') ?>"><i class="bi bi-qr-code"></i> QR Code Meja</a>
                </li>
                
                <li class="<?= ($current_uri == 'laporan') ? 'active' : '' ?>">
                    <a href="<?= base_url('laporan') ?>"><i class="bi bi-graph-up-arrow"></i> Laporan Penjualan</a>
                </li>
                
                <li class="<?= ($current_uri == 'user') ? 'active' : '' ?>">
                    <a href="<?= base_url('user') ?>"><i class="bi bi-people"></i> Manajemen User</a>
                </li>
            <?php endif; ?>
            
            <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 15px;">
            <li>
                <a href="<?= base_url('auth/logout') ?>" class="text-danger-custom" style="color: #ff7676;"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary border-0">
                    <i class="bi bi-list fs-4"></i>
                </button>
                
                <div class="ms-3 d-none d-md-block">
                    <span class="text-secondary fw-medium"><?= date('l, d F Y') ?></span>
                </div>

                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="me-2 text-end">
                                <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem; line-height: 1.2;"><?= session()->get('nama') ?></div>
                                <span class="badge bg-<?= session()->get('role') === 'admin' ? 'danger' : 'success' ?>" style="font-size: 0.7rem;"><?= ucfirst(session()->get('role')) ?></span>
                            </div>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary-custom fw-bold" style="width: 40px; height: 40px; border: 2px solid var(--primary-color);">
                                <?= strtoupper(substr(session()->get('nama'), 0, 1)) ?>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown" style="border-radius: 10px;">
                            <li><a class="dropdown-menu-item dropdown-item py-2" href="<?= base_url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="container-fluid p-4">
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show card-custom border-0 border-start border-success border-4 py-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                        <div>
                            <strong>Sukses!</strong> <?= session()->getFlashdata('success') ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show card-custom border-0 border-start border-danger border-4 py-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                        <div>
                            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!-- SweetAlert2 JS -->
<script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>

<script>
    // Toggle Sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
