<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukStokModel extends Model
{
    protected $table = 'produk_stok';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'quantity', 'who_created', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Kurangi stok produk dengan transaksi aman.
     */
    public function reduceStockAdjust(int $productId, int $qty): array
    {
        $db = \Config\Database::connect();

        if ($qty > 0) {
            $db->transStart();
            // Ambil semua stok yang quantity > 0, urutkan dari yang paling awal (FIFO)
            $stokList = $db->query(
                "SELECT * FROM {$this->table} WHERE product_id = ? AND quantity > 0 ORDER BY created_at ASC FOR UPDATE",
                [$productId]
            )->getResultArray();

            if (empty($stokList)) {
                $db->transRollback();
                return [
                    'success' => false,
                    'message' => 'Stok produk tidak ditemukan.'
                ];
            }

            $totalAvailable = array_sum(array_column($stokList, 'quantity'));

            if ($totalAvailable < $qty) {
                $db->transRollback();
                return [
                    'success' => false,
                    'message' => "Total stok tersedia hanya {$totalAvailable}, butuh {$qty}."
                ];
            }

            $sisaQty = $qty;

            foreach ($stokList as $stok) {
                if ($sisaQty <= 0)
                    break;

                $kurangi = min($sisaQty, $stok['quantity']);

                $db->table($this->table)
                    ->where('id', $stok['id'])
                    ->set('quantity', "quantity - {$kurangi}", false)
                    ->update();

                $sisaQty -= $kurangi;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return [
                    'success' => false,
                    'message' => 'Gagal mengurangi stok produk.'
                ];
            }

            return ['success' => true];
        } else {
            $qty = abs($qty); // pastikan jumlah yang ditambahkan adalah positif

            $stokList = $db->query(
                "SELECT * FROM {$this->table} WHERE product_id = ?",
                [$productId]
            )->getRow();

            if (!$stokList) {
                // Jika belum ada stok sama sekali, buat stok baru
                $inserted = $this->insert([
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                if ($inserted) {
                    return ['success' => true, 'message' => 'Stok berhasil ditambahkan (data baru)'];
                } else {
                    return ['success' => false, 'message' => 'Gagal menambahkan stok baru'];
                }
            }

            // Jika stok sudah ada, tambahkan ke stok lama
            $updated = $this->update($stokList->id, [
                'quantity' => $stokList->quantity + $qty
            ]);

            if ($updated) {
                return ['success' => true, 'message' => 'Stok berhasil ditambahkan'];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan stok'];
            }
        }
    }


    public function getStockSumByProduct(int $productId): ?array
    {
        return $this->select('SUM(quantity) as quantity')
            ->where('product_id', $productId)
            ->get()
            ->getRowArray();
    }

    public function reduceStock(int $productId, int $qty): array
    {
        $db = \Config\Database::connect();

        if ($qty > 0) {
            $db->transStart();
            // Ambil semua stok yang quantity > 0, urutkan dari yang paling awal (FIFO)
            $stokList = $db->query(
                "SELECT * FROM {$this->table} WHERE product_id = ? AND quantity > 0 ORDER BY created_at ASC FOR UPDATE",
                [$productId]
            )->getResultArray();

            if (empty($stokList)) {
                $db->transRollback();
                return [
                    'success' => false,
                    'message' => 'Stok produk tidak ditemukan.'
                ];
            }

            $totalAvailable = array_sum(array_column($stokList, 'quantity'));

            if ($totalAvailable < $qty) {
                $db->transRollback();
                return [
                    'success' => false,
                    'message' => "Total stok tersedia hanya {$totalAvailable}, butuh {$qty}."
                ];
            }

            $sisaQty = $qty;

            foreach ($stokList as $stok) {
                if ($sisaQty <= 0)
                    break;

                $kurangi = min($sisaQty, $stok['quantity']);

                $db->table($this->table)
                    ->where('id', $stok['id'])
                    ->set('quantity', "quantity - {$kurangi}", false)
                    ->update();

                $sisaQty -= $kurangi;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return [
                    'success' => false,
                    'message' => 'Gagal mengurangi stok produk.'
                ];
            }

            return ['success' => true];
        } else {
            $qty = abs($qty); // pastikan jumlah yang ditambahkan adalah positif

            $stokList = $db->query(
                "SELECT * FROM {$this->table} WHERE product_id = ?",
                [$productId]
            )->getRow();

            if (!$stokList) {
                // Jika belum ada stok sama sekali, buat stok baru
                $inserted = $this->insert([
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                if ($inserted) {
                    return ['success' => true, 'message' => 'Stok berhasil ditambahkan (data baru)'];
                } else {
                    return ['success' => false, 'message' => 'Gagal menambahkan stok baru'];
                }
            }

            // Jika stok sudah ada, tambahkan ke stok lama
            $updated = $this->update($stokList->id, [
                'quantity' => $stokList->quantity + $qty
            ]);

            if ($updated) {
                return ['success' => true, 'message' => 'Stok berhasil ditambahkan'];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan stok'];
            }
        }
    }
}
