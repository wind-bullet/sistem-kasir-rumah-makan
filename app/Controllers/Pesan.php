<?php

namespace App\Controllers;

use App\Models\MejaModel;
use App\Models\MenuModel;
use App\Models\KategoriModel;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\UserModel;

class Pesan extends BaseController
{
    protected $mejaModel;
    protected $menuModel;
    protected $kategoriModel;
    protected $transaksiModel;
    protected $detailTransaksiModel;
    protected $userModel;

    public function __construct()
    {
        $this->mejaModel = new MejaModel();
        $this->menuModel = new MenuModel();
        $this->kategoriModel = new KategoriModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->userModel = new UserModel();
    }

    public function index($nomor_meja)
    {
        // Find meja by nomor_meja
        $meja = $this->mejaModel->where('nomor_meja', $nomor_meja)->first();
        if (!$meja) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Meja tidak ditemukan.");
        }

        // Get all menu items with category details
        $menu = $this->menuModel->select('menu.*, kategori.nama_kategori')
                                ->join('kategori', 'kategori.id_kategori = menu.id_kategori')
                                ->orderBy('menu.nama_menu', 'ASC')
                                ->findAll();

        $kategori = $this->kategoriModel->findAll();

        $data = [
            'title'       => 'Pesan Makanan - Meja ' . $nomor_meja,
            'is_takeaway' => false,
            'meja'        => $meja,
            'menu'        => $menu,
            'kategori'    => $kategori
        ];

        return view('pesan/index', $data);
    }

    public function takeaway()
    {
        // Get all menu items with category details
        $menu = $this->menuModel->select('menu.*, kategori.nama_kategori')
                                ->join('kategori', 'kategori.id_kategori = menu.id_kategori')
                                ->orderBy('menu.nama_menu', 'ASC')
                                ->findAll();

        $kategori = $this->kategoriModel->findAll();

        $data = [
            'title'       => 'Pesan Makanan - Take Away',
            'is_takeaway' => true,
            'meja'        => null,
            'menu'        => $menu,
            'kategori'    => $kategori
        ];

        return view('pesan/index', $data);
    }

    public function submit()
    {
        $idMeja = $this->request->getPost('id_meja');
        $nomorMeja = $this->request->getPost('nomor_meja');
        $cartData = $this->request->getPost('cart'); // Expecting format: [{"id_menu": 1, "qty": 2}, ...]
        $metodePembayaran = $this->request->getPost('metode_pembayaran') ?? 'cash';
        
        $cart = json_decode($cartData, true);

        if (empty($cart)) {
            session()->setFlashdata('error', 'Keranjang belanja Anda masih kosong.');
            return redirect()->to('/pesan/' . $nomorMeja);
        }

        // Fetch the first user to associate with the transaction (required by database schema)
        $firstUser = $this->userModel->orderBy('id_user', 'ASC')->first();
        $idUser = $firstUser ? $firstUser['id_user'] : 1;

        $totalHarga = 0;
        $itemsToSave = [];

        foreach ($cart as $item) {
            $menuId = (int)$item['id_menu'];
            $qty = (int)$item['qty'];

            if ($qty <= 0) {
                continue;
            }

            $menu = $this->menuModel->find($menuId);
            if (!$menu) {
                session()->setFlashdata('error', 'Menu dengan ID ' . $menuId . ' tidak ditemukan.');
                return redirect()->to('/pesan/' . $nomorMeja);
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

        if (empty($itemsToSave)) {
            session()->setFlashdata('error', 'Tidak ada item pesanan yang valid.');
            return redirect()->to('/pesan/' . $nomorMeja);
        }

        // Database transaction block
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert into transaksi
        $this->transaksiModel->insert([
            'id_user'           => $idUser,
            'id_meja'           => $idMeja,
            'total_harga'       => $totalHarga,
            'uang_bayar'        => $totalHarga, // Customer ordering: set cash paid equal to total
            'kembalian'         => 0,
            'metode_pembayaran' => $metodePembayaran,
            'tanggal'           => date('Y-m-d H:i:s')
        ]);

        $idTransaksi = $this->transaksiModel->getInsertID();

        // 2. Insert detail_transaksi & reduce stock
        foreach ($itemsToSave as $item) {
            $item['id_transaksi'] = $idTransaksi;
            $item['created_at'] = date('Y-m-d H:i:s');
            $this->detailTransaksiModel->insert($item);
        }

        // 3. Update table status to 'terisi'
        $this->mejaModel->update($idMeja, ['status' => 'terisi']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Gagal memproses pesanan Anda. Silakan coba lagi.');
            return redirect()->to('/pesan/' . $nomorMeja);
        }

        // Save order details in session to display in success page
        session()->setFlashdata('success_order', [
            'id_transaksi'      => $idTransaksi,
            'nomor_meja'        => $nomorMeja,
            'total_harga'       => $totalHarga,
            'metode_pembayaran' => $metodePembayaran,
            'items'             => $itemsToSave
        ]);

        return redirect()->to('/pesan/sukses');
    }

    public function submitTakeaway()
    {
        $cartData = $this->request->getPost('cart'); // Expecting format: [{"id_menu": 1, "qty": 2}, ...]
        $metodePembayaran = $this->request->getPost('metode_pembayaran') ?? 'cash';
        
        $cart = json_decode($cartData, true);

        if (empty($cart)) {
            session()->setFlashdata('error', 'Keranjang belanja Anda masih kosong.');
            return redirect()->to('/pesan/takeaway');
        }

        // Fetch the first user to associate with the transaction
        $firstUser = $this->userModel->orderBy('id_user', 'ASC')->first();
        $idUser = $firstUser ? $firstUser['id_user'] : 1;

        $totalHarga = 0;
        $itemsToSave = [];

        foreach ($cart as $item) {
            $menuId = (int)$item['id_menu'];
            $qty = (int)$item['qty'];

            if ($qty <= 0) {
                continue;
            }

            $menu = $this->menuModel->find($menuId);
            if (!$menu) {
                session()->setFlashdata('error', 'Menu dengan ID ' . $menuId . ' tidak ditemukan.');
                return redirect()->to('/pesan/takeaway');
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

        if (empty($itemsToSave)) {
            session()->setFlashdata('error', 'Tidak ada item pesanan yang valid.');
            return redirect()->to('/pesan/takeaway');
        }

        // Database transaction block
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert into transaksi
        $this->transaksiModel->insert([
            'id_user'           => $idUser,
            'id_meja'           => null, // Set to null for takeaway
            'total_harga'       => $totalHarga,
            'uang_bayar'        => $totalHarga,
            'kembalian'         => 0,
            'metode_pembayaran' => $metodePembayaran,
            'tanggal'           => date('Y-m-d H:i:s')
        ]);

        $idTransaksi = $this->transaksiModel->getInsertID();

        // 2. Insert detail_transaksi
        foreach ($itemsToSave as $item) {
            $item['id_transaksi'] = $idTransaksi;
            $item['created_at'] = date('Y-m-d H:i:s');
            $this->detailTransaksiModel->insert($item);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Gagal memproses pesanan Anda. Silakan coba lagi.');
            return redirect()->to('/pesan/takeaway');
        }

        // Save order details in session to display in success page
        session()->setFlashdata('success_order', [
            'id_transaksi'      => $idTransaksi,
            'nomor_meja'        => 'Take Away',
            'total_harga'       => $totalHarga,
            'metode_pembayaran' => $metodePembayaran,
            'items'             => $itemsToSave
        ]);

        return redirect()->to('/pesan/sukses');
    }

    public function sukses()
    {
        $orderInfo = session()->getFlashdata('success_order');
        if (!$orderInfo) {
            return redirect()->to('/');
        }

        $data = [
            'title'     => 'Pesanan Sukses!',
            'order'     => $orderInfo
        ];

        return view('pesan/sukses', $data);
    }
}

