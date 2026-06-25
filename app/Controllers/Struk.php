<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Struk extends BaseController
{
    public function index($id_transaksi)
    {
        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->getTransaksiWithDetail($id_transaksi);

        if (!$transaksi) {
            session()->setFlashdata('error', 'Transaksi tidak ditemukan.');
            return redirect()->to('/dashboard');
        }

        $data = [
            'title'     => 'Cetak Struk',
            'transaksi' => $transaksi
        ];

        return view('struk/index', $data);
    }
}
