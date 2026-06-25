<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use Dompdf\Dompdf;

class Laporan extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Laporan Penjualan',
            'tipe'      => 'harian',
            'tanggal'   => date('Y-m-d'),
            'transaksi' => null
        ];
        return view('laporan/index', $data);
    }

    public function generate()
    {
        $tipe = $this->request->getPost('tipe') ?? 'harian';
        $tanggal = $this->request->getPost('tanggal') ?? date('Y-m-d');

        $range = $this->getDateRange($tipe, $tanggal);
        $dari = $range['dari'];
        $sampai = $range['sampai'];

        $transaksi = $this->transaksiModel->getTransaksiByPeriode($dari, $sampai);

        // Calculate summaries
        $totalPendapatan = 0;
        foreach ($transaksi as $tx) {
            $totalPendapatan += $tx['total_harga'];
        }

        // Optional: Get top selling menu in this period
        $db = \Config\Database::connect();
        $topMenu = $db->table('detail_transaksi')
                      ->select('nama_menu, SUM(jumlah) as total_terjual')
                      ->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi')
                      ->where('DATE(transaksi.tanggal) >=', $dari)
                      ->where('DATE(transaksi.tanggal) <=', $sampai)
                      ->groupBy('detail_transaksi.id_menu')
                      ->orderBy('total_terjual', 'DESC')
                      ->limit(5)
                      ->get()
                      ->getResultArray();

        $data = [
            'title'            => 'Laporan Penjualan',
            'tipe'             => $tipe,
            'tanggal'          => $tanggal,
            'dari'             => $dari,
            'sampai'           => $sampai,
            'transaksi'        => $transaksi,
            'total_pendapatan' => $totalPendapatan,
            'top_menu'         => $topMenu
        ];

        return view('laporan/index', $data);
    }

    public function exportPdf()
    {
        $tipe = $this->request->getPost('tipe') ?? 'harian';
        $tanggal = $this->request->getPost('tanggal') ?? date('Y-m-d');

        $range = $this->getDateRange($tipe, $tanggal);
        $dari = $range['dari'];
        $sampai = $range['sampai'];

        $transaksi = $this->transaksiModel->getTransaksiByPeriode($dari, $sampai);

        $totalPendapatan = 0;
        foreach ($transaksi as $tx) {
            $totalPendapatan += $tx['total_harga'];
        }

        $data = [
            'tipe'             => $tipe,
            'tanggal'          => $tanggal,
            'dari'             => $dari,
            'sampai'           => $sampai,
            'transaksi'        => $transaksi,
            'total_pendapatan' => $totalPendapatan
        ];

        $html = view('laporan/pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'Laporan_Penjualan_' . $tipe . '_' . $tanggal . '.pdf';
        $dompdf->stream($filename, ['Attachment' => false]);
        exit;
    }

    private function getDateRange($tipe, $tanggal)
    {
        $dari = $tanggal;
        $sampai = $tanggal;

        if ($tipe === 'mingguan') {
            // 7 days ending on the selected date
            $dari = date('Y-m-d', strtotime('-6 days', strtotime($tanggal)));
            $sampai = $tanggal;
        } elseif ($tipe === 'bulanan') {
            // Whole month of selected date
            $dari = date('Y-m-01', strtotime($tanggal));
            $sampai = date('Y-m-t', strtotime($tanggal));
        }

        return ['dari' => $dari, 'sampai' => $sampai];
    }
}
