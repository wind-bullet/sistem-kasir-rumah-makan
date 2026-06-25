<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table            = 'detail_transaksi';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_transaksi', 'id_menu', 'nama_menu', 'harga', 'jumlah', 'subtotal'];

    // Dates
    protected $useTimestamps = false; // migration has created_at but not updated_at, we can handle it manually or set useTimestamps to false and let the db default or manually write created_at
}
