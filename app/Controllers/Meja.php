<?php

namespace App\Controllers;

use App\Models\MejaModel;

class Meja extends BaseController
{
    protected $mejaModel;

    public function __construct()
    {
        $this->mejaModel = new MejaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Meja',
            'meja'  => $this->mejaModel->orderBy('nomor_meja', 'ASC')->findAll()
        ];
        return view('meja/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Meja'
        ];
        return view('meja/create', $data);
    }

    public function store()
    {
        $rules = [
            'nomor_meja' => [
                'rules'  => 'required|numeric|is_unique[meja.nomor_meja]',
                'errors' => [
                    'required'  => 'Nomor meja harus diisi.',
                    'numeric'   => 'Nomor meja harus berupa angka.',
                    'is_unique' => 'Nomor meja sudah digunakan.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $this->mejaModel->save([
            'nomor_meja' => $this->request->getPost('nomor_meja'),
            'status'     => 'kosong'
        ]);

        session()->setFlashdata('success', 'Meja baru berhasil ditambahkan.');
        return redirect()->to('/meja');
    }

    public function edit($id)
    {
        $meja = $this->mejaModel->find($id);
        if (!$meja) {
            session()->setFlashdata('error', 'Meja tidak ditemukan.');
            return redirect()->to('/meja');
        }

        $data = [
            'title' => 'Edit Meja',
            'meja'  => $meja
        ];
        return view('meja/edit', $data);
    }

    public function update($id)
    {
        $meja = $this->mejaModel->find($id);
        if (!$meja) {
            session()->setFlashdata('error', 'Meja tidak ditemukan.');
            return redirect()->to('/meja');
        }

        $rules = [
            'nomor_meja' => [
                'rules'  => "required|numeric|is_unique[meja.nomor_meja,id_meja,{$id}]",
                'errors' => [
                    'required'  => 'Nomor meja harus diisi.',
                    'numeric'   => 'Nomor meja harus berupa angka.',
                    'is_unique' => 'Nomor meja sudah digunakan.'
                ]
            ],
            'status' => 'required|in_list[kosong,terisi]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $this->mejaModel->update($id, [
            'nomor_meja' => $this->request->getPost('nomor_meja'),
            'status'     => $this->request->getPost('status')
        ]);

        session()->setFlashdata('success', 'Meja berhasil diperbarui.');
        return redirect()->to('/meja');
    }

    public function delete($id)
    {
        $meja = $this->mejaModel->find($id);
        if (!$meja) {
            session()->setFlashdata('error', 'Meja tidak ditemukan.');
            return redirect()->to('/meja');
        }

        if ($meja['status'] === 'terisi') {
            session()->setFlashdata('error', 'Meja tidak dapat dihapus karena sedang terisi pelanggan.');
            return redirect()->to('/meja');
        }

        $this->mejaModel->delete($id);
        session()->setFlashdata('success', 'Meja berhasil dihapus.');
        return redirect()->to('/meja');
    }

    public function toggleStatus($id)
    {
        $meja = $this->mejaModel->find($id);
        if (!$meja) {
            session()->setFlashdata('error', 'Meja tidak ditemukan.');
            return redirect()->to('/meja');
        }

        $statusBaru = ($meja['status'] === 'kosong') ? 'terisi' : 'kosong';
        $this->mejaModel->update($id, ['status' => $statusBaru]);

        session()->setFlashdata('success', "Status Meja {$meja['nomor_meja']} berhasil diubah menjadi {$statusBaru}.");
        return redirect()->to('/meja');
    }
}
