<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    .pos-menu-img-container {
        position: relative;
        overflow: hidden;
    }
    .pos-menu-badge-out {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        font-size: 0.8rem;
        border-radius: 50px;
        font-weight: 600;
    }
    .pos-menu-badge-crit {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        background-color: #ffc107;
        color: #212529;
        padding: 5px 10px;
        font-size: 0.8rem;
        border-radius: 50px;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Transaksi Penjualan</h1>
</div>

<div class="row">
    <!-- Left Column: Menu Items -->
    <div class="col-lg-7 col-md-12 mb-4">
        <!-- Search & Filter Bar -->
        <div class="card card-custom mb-3">
            <div class="card-body card-body-custom py-2">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-secondary py-1"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0 form-control-sm py-1" id="search-input" placeholder="Cari menu...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select form-select-sm py-1" id="category-filter">
                            <option value="">-- Semua Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="row row-cols-2 row-cols-md-3 g-3" id="menu-grid">
            <!-- Menus will load dynamically here -->
            <div class="col-12 text-center py-5 text-muted" id="menu-loading">
                <div class="spinner-border text-primary-custom" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Memuat daftar menu...</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Cart Panel -->
    <div class="col-lg-5 col-md-12">
        <div class="card card-custom pos-cart-panel shadow">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-cart3 me-2 text-primary"></i> Keranjang Pesanan</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3" onclick="clearCart()"><i class="bi bi-trash"></i> Bersihkan</button>
            </div>
            
            <div class="card-body card-body-custom">
                <!-- Select Table / Meja -->
                <div class="mb-3">
                    <label for="id_meja" class="form-label text-secondary fw-semibold">Pilih Meja (Opsional)</label>
                    <select class="form-select" id="id_meja">
                        <option value="">-- Take Away (Bawa Pulang) --</option>
                        <?php foreach ($meja as $m): ?>
                            <option value="<?= $m['id_meja'] ?>">Meja Nomor <?= $m['nomor_meja'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Payment Method / Metode Pembayaran -->
                <div class="mb-3">
                    <label for="metode_pembayaran" class="form-label text-secondary fw-semibold">Metode Pembayaran</label>
                    <select class="form-select" id="metode_pembayaran" onchange="toggleCashierPaymentMethod()">
                        <option value="cash">Cash / Tunai</option>
                        <option value="qris">QRIS / Digital</option>
                    </select>
                </div>

                <!-- Table Cart Items -->
                <div class="table-responsive cart-items-list border rounded p-2 mb-3" style="min-height: 180px;">
                    <table class="table table-sm table-borderless align-middle mb-0" id="cart-table">
                        <thead>
                            <tr class="border-bottom text-muted" style="font-size: 0.85rem;">
                                <th>Menu</th>
                                <th style="width: 110px;" class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody">
                            <!-- Cart items will render here -->
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted" id="cart-empty-placeholder">
                                    <i class="bi bi-basket3 text-secondary display-6 d-block mb-2"></i>
                                    Keranjang belanja kosong
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total Summary -->
                <div class="bg-light p-3 rounded mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary fw-semibold">Total Item:</span>
                        <span class="fw-bold" id="total-qty">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-0 border-top pt-2">
                        <span class="fs-5 text-dark fw-bold">Total Harga:</span>
                        <span class="fs-4 text-success fw-bold" id="total-price">Rp 0</span>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="mb-3">
                    <label for="uang_bayar" class="form-label text-secondary fw-semibold">Uang Bayar</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0 fw-semibold text-secondary">Rp</span>
                        <input type="number" class="form-control bg-white border-start-0 fw-bold text-success" id="uang_bayar" min="0" placeholder="0" oninput="calculateChange()">
                    </div>
                </div>

                <!-- Change Summary -->
                <div class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded">
                    <span class="fw-bold text-secondary">Kembalian:</span>
                    <span class="fs-4 fw-bold text-dark" id="change-value">Rp 0</span>
                </div>

                <!-- Submit Button -->
                <button type="button" class="btn btn-primary-custom w-100 py-3 fs-5" onclick="submitTransaction()"><i class="bi bi-credit-card me-2"></i> Proses Pembayaran</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let menuData = [];
    let cart = [];
    const csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';

    // Document Ready
    document.addEventListener("DOMContentLoaded", function() {
        loadMenus();

        // Search & Filter listeners
        document.getElementById('search-input').addEventListener('input', filterAndRenderMenus);
        document.getElementById('category-filter').addEventListener('change', filterAndRenderMenus);
    });

    // Fetch menus via AJAX
    function loadMenus() {
        fetch('<?= base_url("transaksi/get-menu") ?>')
            .then(response => response.json())
            .then(data => {
                menuData = data;
                filterAndRenderMenus();
            })
            .catch(error => {
                console.error('Error fetching menus:', error);
                document.getElementById('menu-grid').innerHTML = `
                    <div class="col-12 text-center py-5 text-danger">
                        <i class="bi bi-exclamation-triangle-fill display-5"></i>
                        <p class="mt-2">Gagal memuat menu. Silakan segarkan halaman.</p>
                    </div>`;
            });
    }

    // Filter and Render Menu Cards
    function filterAndRenderMenus() {
        const keyword = document.getElementById('search-input').value.toLowerCase();
        const categoryId = document.getElementById('category-filter').value;
        const grid = document.getElementById('menu-grid');
        grid.innerHTML = '';

        const filtered = menuData.filter(item => {
            const matchesKeyword = item.nama_menu.toLowerCase().includes(keyword);
            const matchesCategory = !categoryId || item.id_kategori == categoryId;
            return matchesKeyword && matchesCategory;
        });

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-search display-6"></i>
                    <p class="mt-2">Tidak ada menu yang sesuai.</p>
                </div>`;
            return;
        }

        filtered.forEach(item => {
            const formattedPrice = new Intl.NumberFormat('id-ID').format(item.harga);

            const imgHtml = item.gambar 
                ? `<img src="<?= base_url('assets/uploads/menu/') ?>/${item.gambar}" class="pos-menu-img" alt="${item.nama_menu}">`
                : `<div class="pos-menu-placeholder"><i class="bi bi-egg-fried"></i></div>`;

            const cardStyle = '';

            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
                <div class="pos-menu-card shadow-sm h-100" style="${cardStyle}" onclick="addToCart(${item.id_menu}, '${item.nama_menu.replace(/'/g, "\\'")}', ${item.harga})">
                    <div class="pos-menu-img-container">
                        ${imgHtml}
                    </div>
                    <div class="p-3">
                        <div class="fw-bold text-dark text-truncate" title="${item.nama_menu}">${item.nama_menu}</div>
                        <small class="text-secondary d-block mb-2" style="font-size: 0.8rem;">${item.nama_kategori}</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success" style="font-size: 0.95rem;">Rp ${formattedPrice}</span>
                        </div>
                    </div>
                </div>`;
            grid.appendChild(col);
        });
    }

    // Add Item to Cart
    function addToCart(id, nama, harga) {
        const existing = cart.find(item => item.id_menu === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                id_menu: id,
                nama_menu: nama,
                harga: harga,
                qty: 1
            });
        }

        renderCart();
    }

    // Adjust Quantity of Cart Item
    function updateQty(id, delta) {
        const item = cart.find(item => item.id_menu === id);
        if (item) {
            const newQty = item.qty + delta;
            if (newQty <= 0) {
                removeFromCart(id);
            } else {
                item.qty = newQty;
                renderCart();
            }
        }
    }

    // Remove Item from Cart
    function removeFromCart(id) {
        cart = cart.filter(item => item.id_menu !== id);
        renderCart();
    }

    // Clear entire cart
    function clearCart() {
        if (cart.length === 0) return;

        Swal.fire({
            title: 'Bersihkan Keranjang?',
            text: "Semua item di keranjang akan dihapus.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, bersihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                renderCart();
            }
        });
    }

    // Render Cart HTML
    function renderCart() {
        const tbody = document.getElementById('cart-tbody');
        const emptyPlaceholder = document.getElementById('cart-empty-placeholder');
        
        // Remove all except empty placeholder
        const items = tbody.querySelectorAll('.cart-row-item');
        items.forEach(el => el.remove());

        if (cart.length === 0) {
            emptyPlaceholder.style.display = '';
            document.getElementById('total-qty').innerText = '0';
            document.getElementById('total-price').innerText = 'Rp 0';
            document.getElementById('uang_bayar').value = '';
            document.getElementById('change-value').innerText = 'Rp 0';
            document.getElementById('change-value').className = 'fs-4 fw-bold text-dark';
            return;
        }

        emptyPlaceholder.style.display = 'none';

        let totalQty = 0;
        let totalPrice = 0;

        cart.forEach(item => {
            totalQty += item.qty;
            const subtotal = item.harga * item.qty;
            totalPrice += subtotal;

            const tr = document.createElement('tr');
            tr.className = 'cart-row-item border-bottom';
            tr.innerHTML = `
                <td>
                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">${item.nama_menu}</div>
                    <small class="text-muted">@ Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</small>
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm justify-content-center">
                        <button type="button" class="btn btn-outline-secondary btn-xs px-2" onclick="updateQty(${item.id_menu}, -1)"><i class="bi bi-dash"></i></button>
                        <span class="input-group-text bg-white px-3 fw-bold">${item.qty}</span>
                        <button type="button" class="btn btn-outline-secondary btn-xs px-2" onclick="updateQty(${item.id_menu}, 1)"><i class="bi bi-plus"></i></button>
                    </div>
                </td>
                <td class="text-end fw-bold text-dark" style="font-size: 0.9rem;">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeFromCart(${item.id_menu})"><i class="bi bi-x"></i></button>
                </td>`;
            tbody.appendChild(tr);
        });

        document.getElementById('total-qty').innerText = totalQty;
        document.getElementById('total-price').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        
        // Update read-only QRIS field value dynamically
        const method = document.getElementById('metode_pembayaran').value;
        if (method === 'qris') {
            document.getElementById('uang_bayar').value = totalPrice;
        }

        calculateChange();
    }

    // Compute change based on payment and total
    function calculateChange() {
        const totalPrice = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        const uangBayar = parseInt(document.getElementById('uang_bayar').value) || 0;
        const changeValue = document.getElementById('change-value');

        if (totalPrice === 0) {
            changeValue.innerText = 'Rp 0';
            changeValue.className = 'fs-4 fw-bold text-dark';
            return;
        }

        const change = uangBayar - totalPrice;
        changeValue.innerText = `Rp ${new Intl.NumberFormat('id-ID').format(Math.max(0, change))}`;

        if (change >= 0) {
            changeValue.className = 'fs-4 fw-bold text-success';
        } else {
            changeValue.className = 'fs-4 fw-bold text-danger';
        }
    }

    // Submit Transaction via Fetch AJAX
    function submitTransaction() {
        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Transaksi Gagal',
                text: 'Keranjang belanja Anda masih kosong!',
                confirmButtonColor: '#ff5e36'
            });
            return;
        }

        const totalPrice = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        const uangBayar = parseInt(document.getElementById('uang_bayar').value) || 0;
        const idMeja = document.getElementById('id_meja').value;
        const metodePembayaran = document.getElementById('metode_pembayaran').value;

        if (metodePembayaran === 'cash' && uangBayar < totalPrice) {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Kurang',
                text: 'Jumlah uang bayar lebih kecil daripada total harga belanja.',
                confirmButtonColor: '#ff5e36'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pembayaran?',
            text: `Proses transaksi dengan total: Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)} (${metodePembayaran.toUpperCase()})`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Bayar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Prepare form data
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('id_meja', idMeja);
                formData.append('uang_bayar', metodePembayaran === 'qris' ? totalPrice : uangBayar);
                formData.append('metode_pembayaran', metodePembayaran);

                cart.forEach((item, index) => {
                    formData.append(`cart[${index}][id_menu]`, item.id_menu);
                    formData.append(`cart[${index}][qty]`, item.qty);
                });

                // Post via fetch
                fetch('<?= base_url("transaksi/store") ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'Cetak Struk'
                        }).then(() => {
                            // Redirect to Print Struk
                            window.open(`<?= base_url("struk/") ?>/${data.id_transaksi}`, '_blank');
                            // Reload page or reset cart
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sistem Error',
                            text: data.message,
                            confirmButtonColor: '#ff5e36'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error submitting transaction:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Koneksi',
                        text: 'Terjadi kegagalan koneksi ke server.',
                        confirmButtonColor: '#ff5e36'
                    });
                });
            }
        });
    }
    function toggleCashierPaymentMethod() {
        const method = document.getElementById('metode_pembayaran').value;
        const uangBayarInput = document.getElementById('uang_bayar');
        const totalPrice = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);

        if (method === 'qris') {
            uangBayarInput.value = totalPrice;
            uangBayarInput.setAttribute('readonly', true);
        } else {
            uangBayarInput.removeAttribute('readonly');
            uangBayarInput.value = '';
        }
        calculateChange();
    }
</script>
<?= $this->endSection() ?>
