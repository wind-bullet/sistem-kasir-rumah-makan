<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menu';
    protected $primaryKey       = 'id_menu';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_kategori', 'nama_menu', 'harga', 'stok', 'gambar'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getMenuWithKategori($id = false)
    {
        if ($id === false) {
            return $this->select('menu.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id_kategori = menu.id_kategori')
                        ->findAll();
        }

        return $this->select('menu.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id_kategori = menu.id_kategori')
                    ->where(['menu.id_menu' => $id])
                    ->first();
    }

    public function kurangiStok($id_menu, $jumlah)
    {
        $menu = $this->find($id_menu);
        if ($menu) {
            $stokBaru = max(0, $menu['stok'] - $jumlah);
            return $this->update($id_menu, ['stok' => $stokBaru]);
        }
        return false;
    }

    public function cariMenu($keyword)
    {
        return $this->select('menu.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id_kategori = menu.id_kategori')
                    ->like('menu.nama_menu', $keyword)
                    ->findAll();
    }

    public function getByKategori($id_kategori)
    {
        return $this->select('menu.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id_kategori = menu.id_kategori')
                    ->where(['menu.id_kategori' => $id_kategori])
                    ->findAll();
    }
}
