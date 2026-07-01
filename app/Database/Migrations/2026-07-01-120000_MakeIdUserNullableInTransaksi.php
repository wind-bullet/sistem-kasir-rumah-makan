<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeIdUserNullableInTransaksi extends Migration
{
    public function up()
    {
        // First drop foreign key temporarily if needed, but in MySQL modify column can be done directly if types match.
        // Let's modify the column to be nullable.
        $this->db->query("ALTER TABLE transaksi MODIFY id_user INT(11) UNSIGNED NULL");
    }

    public function down()
    {
        // Restore id_user to NOT NULL.
        $this->db->query("ALTER TABLE transaksi MODIFY id_user INT(11) UNSIGNED NOT NULL");
    }
}
