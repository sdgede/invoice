<?php

namespace App\Models;

use CodeIgniter\Model;

class PorductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'category_id',
        'description',
        'price',
        'bay',  // harga beli
        'who_created',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil semua produk lengkap dengan nama kategori
    public function getAll()
    {
        $builder = $this->db->table($this->table);
        $builder->select('products.*, categories.name as category_name');
        $builder->join('categories', 'products.category_id = categories.id', 'left');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
