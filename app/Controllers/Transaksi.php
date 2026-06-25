<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\MejaModel;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;

class Transaksi extends BaseController
{
    protected $menuModel;
    protected $mejaModel;
    protected $transaksiModel;
    protected $detailTransaksiModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->mejaModel = new MejaModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Transaksi Baru',
            'meja'     => $this->mejaModel->where('status', 'kosong')->orderBy('nomor_meja', 'ASC')->findAll(),
            'kategori' => (new \App\Models\KategoriModel())->findAll()
        ];
        return view('transaksi/index', $data);
    }

    public function getMenu()
    {
        $keyword = $this->request->getGet('keyword');
        $kategoriId = $this->request->getGet('kategori');

        $query = $this->menuModel->select('menu.*, kategori.nama_kategori')
                                 ->join('kategori', 'kategori.id_kategori = menu.id_kategori');

        if (!empty($keyword)) {
            $query->like('menu.nama_menu', $keyword);
        }

        if (!empty($kategoriId)) {
            $query->where('menu.id_kategori', $kategoriId);
        }

        $menu = $query->orderBy('menu.nama_menu', 'ASC')->findAll();

        return $this->response->setJSON($menu);
    }

    public function store()
    {
        // AJAX POST handler
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Direct access not allowed.']);
        }

        $idMeja = $this->request->getPost('id_meja');
        $uangBayar = (int)$this->request->getPost('uang_bayar');
        $cart = $this->request->getPost('cart'); // Array of items: [{id_menu, qty}]

        if (empty($cart) || !is_array($cart)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Keranjang belanja masih kosong.'
            ]);
        }

        // Calculate total and validate stock first
        $totalHarga = 0;
        $itemsToSave = [];

        foreach ($cart as $item) {
            $menuId = (int)$item['id_menu'];
            $qty = (int)$item['qty'];

            if ($qty <= 0) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Jumlah pesanan tidak valid.'
                ]);
            }

            $menu = $this->menuModel->find($menuId);
            if (!$menu) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Menu tidak ditemukan.'
                ]);
            }

            if ($menu['stok'] < $qty) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "Stok untuk menu '{$menu['nama_menu']}' tidak mencukupi (Tersedia: {$menu['stok']})."
                ]);
            }

            $subtotal = $menu['harga'] * $qty;
            $totalHarga += $subtotal;

            $itemsToSave[] = [
                'id_menu'   => $menuId,
                'nama_menu' => $menu['nama_menu'],
                'harga'     => $menu['harga'],
                'jumlah'    => $qty,
                'subtotal'  => $subtotal
            ];
        }

        if ($uangBayar < $totalHarga) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Uang bayar tidak mencukupi total transaksi.'
            ]);
        }

        $kembalian = $uangBayar - $totalHarga;
        $idUser = session()->get('id_user');

        // Database transaction block
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert into transaksi
        $this->transaksiModel->insert([
            'id_user'     => $idUser,
            'id_meja'     => !empty($idMeja) ? $idMeja : null,
            'total_harga' => $totalHarga,
            'uang_bayar'  => $uangBayar,
            'kembalian'   => $kembalian,
            'tanggal'     => date('Y-m-d H:i:s')
        ]);

        $idTransaksi = $this->transaksiModel->getInsertID();

        // 2. Insert detail_transaksi & reduce stock
        foreach ($itemsToSave as $item) {
            $item['id_transaksi'] = $idTransaksi;
            $item['created_at'] = date('Y-m-d H:i:s');
            $this->detailTransaksiModel->insert($item);

            // Reduce stock
            $this->menuModel->kurangiStok($item['id_menu'], $item['jumlah']);
        }

        // 3. Update table status if selected
        if (!empty($idMeja)) {
            $this->mejaModel->update($idMeja, ['status' => 'terisi']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan transaksi.'
            ]);
        }

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Transaksi berhasil disimpan.',
            'id_transaksi' => $idTransaksi
        ]);
    }
}
