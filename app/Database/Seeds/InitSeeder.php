<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {
        // 1. Insert Kategori
        $kategoriData = [
            ['nama_kategori' => 'Makanan', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Minuman', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['nama_kategori' => 'Dessert', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('kategori')->insertBatch($kategoriData);

        // Get inserted kategori IDs (assuming MySQL AUTO_INCREMENT starts at 1)
        // 1 = Makanan, 2 = Minuman, 3 = Dessert

        // 2. Insert Users
        $usersData = [
            [
                'nama'       => 'Administrator',
                'username'   => 'admin',
                'password'   => password_hash('admin123', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama'       => 'Kasir Utama',
                'username'   => 'kasir',
                'password'   => password_hash('kasir123', PASSWORD_BCRYPT),
                'role'       => 'kasir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('users')->insertBatch($usersData);

        // 3. Insert Meja (Nomor 1-10)
        $mejaData = [];
        for ($i = 1; $i <= 10; $i++) {
            $mejaData[] = [
                'nomor_meja' => $i,
                'status'     => 'kosong',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        $this->db->table('meja')->insertBatch($mejaData);

        // 4. Insert Menu
        $menuData = [
            [
                'id_kategori' => 1,
                'nama_menu'   => 'Nasi Goreng Spesial',
                'harga'       => 25000,
                'stok'        => 20,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'id_kategori' => 1,
                'nama_menu'   => 'Mie Goreng Seafood',
                'harga'       => 28000,
                'stok'        => 15,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'id_kategori' => 1,
                'nama_menu'   => 'Ayam Bakar Taliwang',
                'harga'       => 35000,
                'stok'        => 10,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'id_kategori' => 2,
                'nama_menu'   => 'Es Teh Manis',
                'harga'       => 5000,
                'stok'        => 50,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'id_kategori' => 2,
                'nama_menu'   => 'Jus Alpukat',
                'harga'       => 12000,
                'stok'        => 15,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'id_kategori' => 3,
                'nama_menu'   => 'Banana Split',
                'harga'       => 20000,
                'stok'        => 8,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'id_kategori' => 3,
                'nama_menu'   => 'Es Campur',
                'harga'       => 15000,
                'stok'        => 12,
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
        ];
        $this->db->table('menu')->insertBatch($menuData);
    }
}
