<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\TransaksiModel;
use App\Models\MejaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $menuModel = new MenuModel();
        $transaksiModel = new TransaksiModel();
        $mejaModel = new MejaModel();

        $data = [
            'title' => 'Dashboard',
            'total_menu' => $menuModel->countAll(),
            'transaksi_hari_ini' => $transaksiModel->where('DATE(tanggal)', date('Y-m-d'))->countAllResults(),
            'pendapatan_hari_ini' => $transaksiModel->getTotalPendapatanHariIni(),
            'meja_kosong' => $mejaModel->where('status', 'kosong')->countAllResults(),
            'total_meja' => $mejaModel->countAll(),
        ];

        // Fetch last 5 transactions
        $data['recent_transaksi'] = $transaksiModel->select('transaksi.*, users.nama as nama_kasir, meja.nomor_meja')
                                                    ->join('users', 'users.id_user = transaksi.id_user', 'left')
                                                    ->join('meja', 'meja.id_meja = transaksi.id_meja', 'left')
                                                    ->orderBy('transaksi.id_transaksi', 'DESC')
                                                    ->limit(5)
                                                    ->find();

        return view('dashboard/index', $data);
    }
}
