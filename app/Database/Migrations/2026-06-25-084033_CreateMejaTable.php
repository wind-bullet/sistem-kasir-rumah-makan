<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMejaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_meja' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_meja' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'unique'     => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['kosong', 'terisi'],
                'default'    => 'kosong',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_meja', true);
        $this->forge->createTable('meja');
    }

    public function down()
    {
        $this->forge->dropTable('meja');
    }
}
