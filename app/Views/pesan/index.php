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
            --primary-hover: #e04f2a;
            --bg-light: #f8f9fa;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            padding-bottom: 90px; /* Space for sticky cart */
        }
        .navbar-brand-custom {
            font-weight: 700;
            color: var(--primary-color) !important;
            letter-spacing: 0.5px;
        }
        .category-tab {
            background-color: white;
            border: 1px solid #eee;
            color: #666;
            border-radius: 50px;
            padding: 8px 20px;
            white-space: nowrap;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .category-tab.active, .category-tab:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(255, 94, 54, 0.2);
        }
        .menu-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .menu-img-container {
            position: relative;
            height: 160px;
            background-color: #f1f1f1;
        }
        .menu-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .menu-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            color: #333;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
        }
        .sticky-cart-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 -5px 25px rgba(0,0,0,0.05);
            z-index: 1030;
            padding: 15px 0;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            background-color: #f1f3f5;
            border-radius: 8px;
            padding: 2px;
        }
        .quantity-btn {
            border: none;
            background: none;
            color: var(--primary-color);
            padding: 4px 10px;
            font-weight: bold;
            font-size: 1.2rem;
            line-height: 1;
        }
        .quantity-val {
            min-width: 25px;
            text-align: center;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <span class="navbar-brand navbar-brand-custom fs-4"><i class="bi bi-shop me-2"></i>RM LEZAT JAYA</span>
            <?php if (isset($is_takeaway) && $is_takeaway): ?>
                <span class="badge bg-light text-danger border px-3 py-2 rounded-pill fs-6 fw-semibold">
                    <i class="bi bi-bag-check-fill me-2 text-danger"></i>Take Away (Bawa Pulang)
                </span>
            <?php else: ?>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6 fw-semibold">
                    <i class="bi bi-table me-2 text-primary"></i>Meja <?= esc($meja['nomor_meja']) ?>
                </span>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Content Container -->
    <div class="container py-4">

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Banner -->
        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
            <h4 class="fw-bold mb-1">Selamat Datang di RM Lezat Jaya!</h4>
            <?php if (isset($is_takeaway) && $is_takeaway): ?>
                <p class="text-secondary mb-0">Silakan pilih makanan & minuman untuk dibawa pulang (Take Away).</p>
            <?php else: ?>
                <p class="text-secondary mb-0">Silakan pilih makanan & minuman langsung dari layar ponsel Anda.</p>
            <?php endif; ?>
        </div>

        <!-- Categories Slider -->
        <div class="d-flex gap-2 overflow-x-auto pb-3 mb-4" style="scrollbar-width: none;">
            <a href="#" class="category-tab active" data-category="all">Semua Menu</a>
            <?php foreach ($kategori as $k): ?>
                <a href="#" class="category-tab" data-category="<?= $k['id_kategori'] ?>"><?= esc($k['nama_kategori']) ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Menu Grid -->
        <div class="row" id="menu-container">
            <?php if (empty($menu)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-egg-fried text-muted fs-1 mb-3"></i>
                    <h5 class="text-secondary">Mohon maaf, semua menu sedang habis.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($menu as $m): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 menu-item-col" data-category="<?= $m['id_kategori'] ?>">
                        <div class="card menu-card h-100">
                            <div class="menu-img-container">
                                <?php if ($m['gambar'] && file_exists(ROOTPATH . 'public/assets/uploads/menu/' . $m['gambar'])): ?>
                                    <img src="<?= base_url('assets/uploads/menu/' . $m['gambar']) ?>" alt="<?= esc($m['nama_menu']) ?>" class="menu-img">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="menu-badge"><?= esc($m['nama_kategori']) ?></span>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;"><?= esc($m['nama_menu']) ?></h5>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="fw-bold text-success" style="font-size: 1.1rem;">Rp <?= number_format($m['harga'], 0, ',', '.') ?></span>
                                    
                                    <!-- Add button / Quantity Control -->
                                    <div class="menu-actions" id="actions-<?= $m['id_menu'] ?>">
                                        <button class="btn btn-primary-custom btn-sm px-3" onclick="addToCart(<?= $m['id_menu'] ?>, '<?= esc($m['nama_menu']) ?>', <?= $m['harga'] ?>)">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Sticky Cart Bar -->
    <div class="sticky-cart-bar d-none" id="cart-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold text-secondary" style="font-size: 0.85rem;"><span id="cart-qty-total">0</span> Item Terpilih</div>
                <div class="fw-bold text-dark fs-5" id="cart-price-total">Rp 0</div>
            </div>
            <div>
                <button class="btn btn-primary-custom px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#cartModal">
                    Lihat Keranjang <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="cartModalLabel"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Cart List -->
                    <div id="cart-list" class="mb-4">
                        <!-- Items will be injected here -->
                    </div>

                    <!-- Summary -->
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Subtotal</span>
                            <span class="fw-semibold text-dark" id="summary-subtotal">Rp 0</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-dark">Total Pembayaran</span>
                            <span class="fw-bold text-success fs-5" id="summary-total">Rp 0</span>
                        </div>
                    </div>

                    <!-- Submission Form -->
                    <form action="<?= (isset($is_takeaway) && $is_takeaway) ? base_url('pesan/submit-takeaway') : base_url('pesan/submit') ?>" method="POST" onsubmit="return submitOrder()">
                        <?= csrf_field() ?>
                        <?php if (isset($is_takeaway) && $is_takeaway): ?>
                            <!-- Takeaway Mode -->
                        <?php else: ?>
                            <input type="hidden" name="id_meja" value="<?= $meja['id_meja'] ?>">
                            <input type="hidden" name="nomor_meja" value="<?= $meja['nomor_meja'] ?>">
                        <?php endif; ?>
                        <input type="hidden" id="cart-input" name="cart">

                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark d-block"><i class="bi bi-credit-card me-2"></i>Metode Pembayaran</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-cash" value="cash" checked onchange="togglePaymentInstructions()">
                                    <label class="btn btn-outline-secondary w-100 py-3 rounded-3 fw-semibold d-flex flex-column align-items-center" for="pay-cash">
                                        <i class="bi bi-cash-coin fs-3 mb-1"></i>
                                        Cash / Tunai
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-qris" value="qris" onchange="togglePaymentInstructions()">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-semibold d-flex flex-column align-items-center" for="pay-qris">
                                        <i class="bi bi-qr-code fs-3 mb-1"></i>
                                        QRIS / Digital
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions / QRIS Image -->
                        <div id="payment-instructions" class="alert alert-info border-0 rounded-3 mb-4 py-3 shadow-sm text-center">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <span id="instruction-text">Silakan lakukan pembayaran langsung di meja kasir setelah Anda selesai makan.</span>
                        </div>

                        <div id="qris-payment-container" class="d-none text-center border p-3 rounded-3 bg-white mb-4 shadow-sm">
                            <span class="fw-bold text-dark d-block mb-2">Scan QRIS RM LEZAT JAYA</span>
                            <?php if (file_exists(ROOTPATH . 'public/assets/images/qris_payment.png')): ?>
                                <img src="<?= base_url('assets/images/qris_payment.png') ?>" alt="QRIS Payment" class="img-fluid rounded mb-2" style="max-width: 220px;">
                            <?php else: ?>
                                <div class="bg-light p-4 rounded d-flex flex-column align-items-center justify-content-center text-muted" style="height: 180px;">
                                    <i class="bi bi-qr-code-scan fs-1 mb-2"></i>
                                    <span class="fw-semibold">QRIS Aktif Belum Diupload</span>
                                    <small class="text-secondary mt-1">Gunakan cash atau hubungi kasir</small>
                                </div>
                            <?php endif; ?>
                            <div class="small text-muted mt-2">
                                Harap simpan screenshot bukti transfer untuk ditunjukkan saat pembayaran dikonfirmasi.
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom py-3 fw-bold fs-6">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pesanan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Client Side Cart Logic -->
    <script>
        let cart = [];

        // Category Filter
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Toggle active class
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const cat = this.getAttribute('data-category');
                document.querySelectorAll('.menu-item-col').forEach(item => {
                    if (cat === 'all' || item.getAttribute('data-category') === cat) {
                        item.classList.remove('d-none');
                    } else {
                        item.classList.add('d-none');
                    }
                });
            });
        });

        // Add Item to Cart
        function addToCart(id, name, price) {
            const existing = cart.find(i => i.id_menu === id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id_menu: id, nama_menu: name, harga: price, qty: 1 });
            }
            updateUI();
        }

        // Remove/Decrease quantity
        function changeQty(id, delta) {
            const item = cart.find(i => i.id_menu === id);
            if (item) {
                item.qty += delta;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id_menu !== id);
                }
            }
            updateUI();
        }

        // Update Layout & Cart Bar UI
        function updateUI() {
            const cartBar = document.getElementById('cart-bar');
            
            if (cart.length > 0) {
                cartBar.classList.remove('d-none');
            } else {
                cartBar.classList.add('d-none');
            }

            let totalQty = 0;
            let totalPrice = 0;

            // Render all action buttons properly based on cart state
            renderAllActionButtons();

            cart.forEach(item => {
                totalQty += item.qty;
                totalPrice += item.harga * item.qty;
            });

            document.getElementById('cart-qty-total').innerText = totalQty;
            document.getElementById('cart-price-total').innerText = formatRupiah(totalPrice);
            
            // Modal summaries
            document.getElementById('summary-subtotal').innerText = formatRupiah(totalPrice);
            document.getElementById('summary-total').innerText = formatRupiah(totalPrice);

            // Populate Modal Cart List
            const cartListDiv = document.getElementById('cart-list');
            if (cart.length === 0) {
                cartListDiv.innerHTML = '<p class="text-center text-muted my-3">Keranjang kosong</p>';
            } else {
                let html = '';
                cart.forEach(item => {
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">${item.nama_menu}</h6>
                                <small class="text-success">${formatRupiah(item.harga)}</small>
                            </div>
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="changeQty(${item.id_menu}, -1)">-</button>
                                <span class="quantity-val">${item.qty}</span>
                                <button class="quantity-btn" onclick="changeQty(${item.id_menu}, 1)">+</button>
                            </div>
                        </div>
                    `;
                });
                cartListDiv.innerHTML = html;
            }
        }

        // Render Action Buttons for all Grid items based on current cart
        function renderAllActionButtons() {
            // Find all elements starting with actions-
            const divs = document.querySelectorAll('[id^="actions-"]');
            divs.forEach(div => {
                const id = parseInt(div.id.split('-')[1]);
                const item = cart.find(i => i.id_menu === id);
                
                // Get menu details from original source or attributes
                if (item) {
                    div.innerHTML = `
                        <div class="quantity-control">
                            <button class="quantity-btn" onclick="changeQty(${id}, -1)">-</button>
                            <span class="quantity-val">${item.qty}</span>
                            <button class="quantity-btn" onclick="changeQty(${id}, 1)">+</button>
                        </div>
                    `;
                } else {
                    // Revert to original Add button
                    // We can extract name, price from card
                    const card = div.closest('.menu-card');
                    const name = card.querySelector('h5').innerText;
                    const priceText = card.querySelector('.text-success').innerText;
                    const price = parseInt(priceText.replace(/[^0-9]/g, ''));

                    div.innerHTML = `
                        <button class="btn btn-primary-custom btn-sm px-3" onclick="addToCart(${id}, '${escapeQuote(name)}', ${price})">
                            <i class="bi bi-plus-lg me-1"></i> Tambah
                        </button>
                    `;
                }
            });
        }

        function escapeQuote(str) {
            return str.replace(/'/g, "\\'");
        }

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function submitOrder() {
            if (cart.length === 0) {
                alert('Keranjang belanja kosong!');
                return false;
            }
            // Put JSON cart string into hidden input
            document.getElementById('cart-input').value = JSON.stringify(cart);
            return true;
        }

        function togglePaymentInstructions() {
            const payQris = document.getElementById('pay-qris').checked;
            const instructions = document.getElementById('payment-instructions');
            const qrisContainer = document.getElementById('qris-payment-container');
            const text = document.getElementById('instruction-text');
            const isTakeaway = <?= (isset($is_takeaway) && $is_takeaway) ? 'true' : 'false' ?>;
            
            if (payQris) {
                if (qrisContainer) qrisContainer.classList.remove('d-none');
                text.innerText = 'Silakan scan QRIS di atas untuk membayar, lalu kirim pesanan. Tunjukkan bukti transfer ke kasir.';
                instructions.className = 'alert alert-primary border-0 rounded-3 mb-4 py-3 shadow-sm text-center';
            } else {
                if (qrisContainer) qrisContainer.classList.add('d-none');
                if (isTakeaway) {
                    text.innerText = 'Silakan lakukan pembayaran langsung di meja kasir untuk mengambil pesanan Anda.';
                } else {
                    text.innerText = 'Silakan lakukan pembayaran langsung di meja kasir setelah Anda selesai makan.';
                }
                instructions.className = 'alert alert-info border-0 rounded-3 mb-4 py-3 shadow-sm text-center';
            }
        }

        // Call initially on DOM load
        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentInstructions();
        });
    </script>
</body>
</html>
