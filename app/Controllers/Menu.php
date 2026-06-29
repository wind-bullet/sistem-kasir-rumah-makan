<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\KategoriModel;
use App\Models\DetailTransaksiModel;

class Menu extends BaseController
{
    protected $menuModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
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

        $menu = $query->orderBy('menu.id_menu', 'DESC')->findAll();

        $data = [
            'title'      => 'Daftar Menu Makanan & Minuman',
            'menu'       => $menu,
            'kategori'   => $this->kategoriModel->findAll(),
            'keyword'    => $keyword,
            'kategoriId' => $kategoriId
        ];

        return view('menu/index', $data);
    }

    public function create()
    {
        $data = [
            'title'    => 'Tambah Menu Baru',
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('menu/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_menu'   => 'required',
            'id_kategori' => 'required|is_not_unique[kategori.id_kategori]',
            'harga'       => 'required|numeric|greater_than_equal_to[0]',
            'stok'        => 'required|numeric|greater_than_equal_to[0]',
            'gambar'      => 'permit_empty|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = null;

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(ROOTPATH . 'public/assets/uploads/menu', $namaGambar);
        }

        $this->menuModel->save([
            'nama_menu'   => $this->request->getPost('nama_menu'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'gambar'      => $namaGambar
        ]);

        session()->setFlashdata('success', 'Menu baru berhasil ditambahkan.');
        return redirect()->to('/menu');
    }

    public function edit($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            session()->setFlashdata('error', 'Menu tidak ditemukan.');
            return redirect()->to('/menu');
        }

        $data = [
            'title'    => 'Edit Menu',
            'menu'     => $menu,
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('menu/edit', $data);
    }

    public function update($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            session()->setFlashdata('error', 'Menu tidak ditemukan.');
            return redirect()->to('/menu');
        }

        $rules = [
            'nama_menu'   => 'required',
            'id_kategori' => 'required|is_not_unique[kategori.id_kategori]',
            'harga'       => 'required|numeric|greater_than_equal_to[0]',
            'stok'        => 'required|numeric|greater_than_equal_to[0]',
            'gambar'      => 'permit_empty|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $menu['gambar'];

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(ROOTPATH . 'public/assets/uploads/menu', $namaGambar);

            // Delete old image if exists
            if ($menu['gambar'] && file_exists(ROOTPATH . 'public/assets/uploads/menu/' . $menu['gambar'])) {
                unlink(ROOTPATH . 'public/assets/uploads/menu/' . $menu['gambar']);
            }
        }

        $this->menuModel->update($id, [
            'nama_menu'   => $this->request->getPost('nama_menu'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'gambar'      => $namaGambar
        ]);

        session()->setFlashdata('success', 'Menu berhasil diperbarui.');
        return redirect()->to('/menu');
    }

    public function delete($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            session()->setFlashdata('error', 'Menu tidak ditemukan.');
            return redirect()->to('/menu');
        }

        $detailModel = new DetailTransaksiModel();
        $count = $detailModel->where('id_menu', $id)->countAllResults();

        if ($count > 0) {
            session()->setFlashdata('error', 'Menu ini tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
            return redirect()->to('/menu');
        }

        // Delete image file if exists
        if ($menu['gambar'] && file_exists(ROOTPATH . 'public/assets/uploads/menu/' . $menu['gambar'])) {
            unlink(ROOTPATH . 'public/assets/uploads/menu/' . $menu['gambar']);
        }

        $this->menuModel->delete($id);
        session()->setFlashdata('success', 'Menu berhasil dihapus.');
        return redirect()->to('/menu');
    }
}
