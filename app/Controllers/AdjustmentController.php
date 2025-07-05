<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DataException;
use App\Models\AdjustmentModel;
use App\Models\PorductModel;
use App\Models\ProdukStokModel;
use Throwable;

class AdjustmentController extends BaseController
{
    protected AdjustmentModel $adjustmentModel;

    protected PorductModel    $productModel;

    protected ProdukStokModel $produkStokModel;

    public function __construct()
    {
        $this->adjustmentModel = new AdjustmentModel();
        $this->productModel    = new PorductModel();
        $this->produkStokModel = new ProdukStokModel();
    }

    public function index()
    {
        $data = [
            'products'     => $this->productModel->findAll(),      
            'adjustments'  => $this->adjustmentModel->listVisible(), 
            'title'        => 'Adjustment',
        ];
        return view('contents/adjustment', $data);
    }

    public function cencelAjudtment(){
        $data = [
            'title'        => 'Cancel Adjustment',
            
            'products'     => $this->productModel->findAll(), 
            'adjustments'  => $this->adjustmentModel->listInvisible(), 
        ];
         return view('contents/cencel-ajusment', $data);
    }


    public function create()
    {
        /* 1. VALIDASI */
        $rules = [
            'product'  => 'required|is_natural_no_zero',
            'quantity' => 'required|is_natural_no_zero',
            'adjust'   => 'required|in_list[1,2,3]',          // tiga alasan
            'description' => 'permit_empty|max_length[255]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', $this->validator->listErrors());
        }

        /* 2. INPUT */
        $productId  = (int)$this->request->getPost('product');
        $qty        = (int)$this->request->getPost('quantity');     // selalu POSITIF
        $reasonCode = (int)$this->request->getPost('adjust');       // 1 / 2 / 3
        $desc       = $this->request->getPost('description');

        /* 3. KURANGI STOK */
        $result = $this->produkStokModel->reduceStockAdjust($productId, $qty);
        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        /* 4. LOG ADJUSTMENT */
        $this->adjustmentModel->insert([
            'type'        => $reasonCode,
            'product_id'  => $productId,
            'quantity'    => $qty,
            'description' => $desc,
            'who_created' => session()->get('user_id'),
        ]);

        return redirect()->to(site_url('adjustment'))
            ->with('success', 'Stok berhasil dikurangi.');
    }

    public function update(int $id)
    {

        $old = $this->adjustmentModel->find($id);
        if (!$old) return redirect()->back()->with('error', 'Data not found');

        $oldProd = (int)$old['product_id'];
        $oldQty  = (int)$old['quantity'];

        $newProd = (int)$this->request->getPost('product');
        $newQty  = (int)$this->request->getPost('quantity');   // always +

        $reason  = (int)$this->request->getPost('adjust');
        $desc    = $this->request->getPost('description');

        $db = db_connect();
        $db->transStart();

        try {
            /* ---------- Revisi stok ---------- */
            if ($oldProd === $newProd) {
                // hanya selisih qty
                $delta = $newQty - $oldQty;
                if ($delta !== 0) {
                    $res = $this->produkStokModel->reduceStock($newProd, $delta);
                    if (!$res['success']) throw new \RuntimeException($res['message']);
                }
            } else {
                // kembalikan stok lama
                $res1 = $this->produkStokModel->reduceStock($oldProd, -$oldQty);
                // kurangi stok produk baru
                $res2 = $this->produkStokModel->reduceStock($newProd, $newQty);
                if (!$res1['success']) throw new \RuntimeException($res1['message']);
                if (!$res2['success']) throw new \RuntimeException($res2['message']);
            }

            /* ---------- Update baris ---------- */
            $this->adjustmentModel->update($id, [
                'product_id'  => $newProd,
                'quantity'    => $newQty,
                'type'        => $reason,
                'description' => $desc,
                'who_updated' => session()->get('user_id'),
            ]);

            $db->transComplete();
            return redirect()->to(site_url('adjustment'))->with('success', 'Adjustment updated');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function cancel(int $id)
    {
        $this->validate(['reason' => 'required|max_length[255]']);
        $adj = $this->adjustmentModel->find($id);
        if (!$adj || $adj['status'] === 'canceled') {
            return redirect()->back()->with('error', 'Data tidak ditemukan / sudah canceled');
        }


        $ok = $this->produkStokModel->reduceStock($adj['product_id'], -$adj['quantity']);
        if (!$ok['success']) return redirect()->back()->with('error', $ok['message']);


        $this->adjustmentModel->update($id, [
            'status'      => 'canceled',
            'canceled_at' => date('Y-m-d H:i:s'),
            'description' => $this->request->getPost('reason'),
            'who_updated' => session()->get('user_id'),
        ]);

        return redirect()->back()->with('success', 'Adjustment dibatalkan & stok dipulihkan.');
    }


    public function restore($id)
    {
        $adjustment = $this->adjustmentModel->find($id);
        if (!$adjustment || $adjustment['status'] !== 'canceled') {
            return redirect()->back()->with('error', 'Data tidak valid untuk di-restore.');
        }

        $cancelTime = strtotime($adjustment['canceled_at']);
        if ($cancelTime < strtotime('-3 days')) {
            return redirect()->back()->with('error', 'Data sudah kedaluwarsa untuk dipulihkan.');
        }

        // kurangi lagi stok
        $this->productModel->decreaseStock($adjustment['product_id'], $adjustment['quantity']);

        // update status
        $this->adjustmentModel->update($id, [
            'status' => 'active',
            'description' => '[RESTORED] ' . $adjustment['description']
        ]);

        return redirect()->back()->with('success', 'Berhasil di-restore.');
    }

    
}
