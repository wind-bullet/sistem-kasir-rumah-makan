<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Riwayat extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $dari = $this->request->getGet('dari');
        $sampai = $this->request->getGet('sampai');

        $query = $this->transaksiModel->select('transaksi.*, users.nama as nama_kasir, meja.nomor_meja')
                                     ->join('users', 'users.id_user = transaksi.id_user')
                                     ->join('meja', 'meja.id_meja = transaksi.id_meja', 'left');

        if (!empty($dari)) {
            $query->where('DATE(transaksi.tanggal) >=', $dari);
        }
        if (!empty($sampai)) {
            $query->where('DATE(transaksi.tanggal) <=', $sampai);
        }

        $data = [
            'title'     => 'Riwayat Transaksi',
            'transaksi' => $query->orderBy('transaksi.id_transaksi', 'DESC')->paginate(10, 'transaksi'),
            'pager'     => $this->transaksiModel->pager,
            'dari'      => $dari,
            'sampai'    => $sampai
        ];

        return view('riwayat/index', $data);
    }

    public function detail($id)
    {
        $transaksi = $this->transaksiModel->getTransaksiWithDetail($id);

        if (!$transaksi) {
            session()->setFlashdata('error', 'Transaksi tidak ditemukan.');
            return redirect()->to('/riwayat');
        }

        $data = [
            'title'     => 'Detail Transaksi #' . sprintf('%05d', $id),
            'transaksi' => $transaksi
        ];

        return view('riwayat/detail', $data);
    }
}
