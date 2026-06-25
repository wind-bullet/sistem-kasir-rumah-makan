<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id_transaksi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'id_meja', 'total_harga', 'uang_bayar', 'kembalian', 'tanggal'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTransaksiHariIni()
    {
        $today = date('Y-m-d');
        return $this->select('transaksi.*, users.nama as nama_kasir, meja.nomor_meja')
                    ->join('users', 'users.id_user = transaksi.id_user')
                    ->join('meja', 'meja.id_meja = transaksi.id_meja', 'left')
                    ->where('DATE(transaksi.tanggal)', $today)
                    ->orderBy('transaksi.id_transaksi', 'DESC')
                    ->findAll();
    }

    public function getTotalPendapatanHariIni()
    {
        $today = date('Y-m-d');
        $result = $this->selectSum('total_harga')
                       ->where('DATE(tanggal)', $today)
                       ->first();
        return $result ? ($result['total_harga'] ?? 0) : 0;
    }

    public function getTransaksiByPeriode($dari, $sampai)
    {
        return $this->select('transaksi.*, users.nama as nama_kasir, meja.nomor_meja')
                    ->join('users', 'users.id_user = transaksi.id_user')
                    ->join('meja', 'meja.id_meja = transaksi.id_meja', 'left')
                    ->where('DATE(transaksi.tanggal) >=', $dari)
                    ->where('DATE(transaksi.tanggal) <=', $sampai)
                    ->orderBy('transaksi.tanggal', 'ASC')
                    ->findAll();
    }

    public function getTransaksiWithDetail($id)
    {
        $transaksi = $this->select('transaksi.*, users.nama as nama_kasir, meja.nomor_meja')
                          ->join('users', 'users.id_user = transaksi.id_user')
                          ->join('meja', 'meja.id_meja = transaksi.id_meja', 'left')
                          ->where(['transaksi.id_transaksi' => $id])
                          ->first();

        if ($transaksi) {
            $db = \Config\Database::connect();
            $transaksi['detail'] = $db->table('detail_transaksi')
                                     ->where('id_transaksi', $id)
                                     ->get()
                                     ->getResultArray();
        }

        return $transaksi;
    }
}
