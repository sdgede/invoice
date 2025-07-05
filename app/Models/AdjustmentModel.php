<?php

namespace App\Models;

use CodeIgniter\Model;

class AdjustmentModel extends Model
{
    protected $table            = 'adjustments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['type', 'product_id', 'quantity', 'description', 'who_created', 'who_updated', 'status'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $returnType       = 'array';

    /**
     * Mengurangi stok produk
     * @param int $id
     * @param int $jumlah
     * @return bool
     */
    public function kurangiStok(int $id, int $jumlah): bool
    {
        return $this->db->table('produk_stok')
            ->where('id', $id)
            ->set('quantity', "quantity - $jumlah", false) 
            ->update();
    }

    /**
     * Menambah stok produk
     * @param int $id
     * @param int $jumlah
     * @return bool
     */
    public function tambahStok(int $id, int $jumlah): bool
    {
        return $this->db->table('produk_stok')
            ->where('id', $id)
            ->set('quantity', "quantity + $jumlah", false)
            ->update();
    }

    public function getAdjustmentsWithProducts()
{
    return $this->db->table($this->table)
        ->select('adjustments.*, products.name AS product_name')
        ->join('products', 'adjustments.product_id = products.id', 'left')
        ->orderBy('adjustments.created_at', 'DESC')   // biar rapi
        ->get()
        ->getResultArray();
}


public function listVisible(): array
{
    return $this->db->table('adjustments a')
        ->select('a.*, p.name AS product_name')
        ->join('products p', 'p.id = a.product_id', 'left')
        ->groupStart()
            ->where('a.status', 'active')
            ->orGroupStart()
                ->where('a.status', 'canceled')
                ->where('a.canceled_at >=', date('Y-m-d H:i:s', strtotime('-3 days')))
            ->groupEnd()
        ->groupEnd()
        ->orderBy('a.created_at', 'DESC')
        ->get()->getResultArray();
}

public function listInvisible(): array
{
    return $this->db->table('adjustments a')
        ->select('a.*, p.name AS product_name')
        ->join('products p', 'p.id = a.product_id', 'left')
        ->groupStart()
            ->where('a.status', 'canceled')
            ->orGroupStart()
                ->where('a.status', 'canceled')
                ->where('a.canceled_at >=', date('Y-m-d H:i:s', strtotime('-3 days')))
            ->groupEnd()
        ->groupEnd()
        ->orderBy('a.created_at', 'DESC')
        ->get()->getResultArray();
}

public function increaseStock($productId, $qty)
{
    return $this->db->table('products')
        ->where('id', $productId)
        ->increment('stock', $qty);
}

public function decreaseStock($productId, $qty)
{
    return $this->db->table('products')
        ->where('id', $productId)
        ->decrement('stock', $qty);
}
}
