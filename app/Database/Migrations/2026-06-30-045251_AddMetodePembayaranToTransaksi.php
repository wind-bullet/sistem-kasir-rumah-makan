<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetodePembayaranToTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaksi', [
            'metode_pembayaran' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'qris'],
                'default'    => 'cash',
                'after'      => 'kembalian'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaksi', 'metode_pembayaran');
    }
}

