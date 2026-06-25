<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\MenuModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Kategori Menu',
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('kategori/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori'
        ];
        return view('kategori/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Nama kategori harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $this->kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        session()->setFlashdata('success', 'Kategori baru berhasil ditambahkan.');
        return redirect()->to('/kategori');
    }

    public function edit($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            session()->setFlashdata('error', 'Kategori tidak ditemukan.');
            return redirect()->to('/kategori');
        }

        $data = [
            'title'    => 'Edit Kategori',
            'kategori' => $kategori
        ];
        return view('kategori/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_kategori' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Nama kategori harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);

        session()->setFlashdata('success', 'Kategori berhasil diperbarui.');
        return redirect()->to('/kategori');
    }

    public function delete($id)
    {
        $menuModel = new MenuModel();
        $count = $menuModel->where('id_kategori', $id)->countAllResults();

        if ($count > 0) {
            session()->setFlashdata('error', 'Kategori tidak dapat dihapus karena sedang digunakan oleh ' . $count . ' menu.');
            return redirect()->to('/kategori');
        }

        $this->kategoriModel->delete($id);
        session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->to('/kategori');
    }
}
