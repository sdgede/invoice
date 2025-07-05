<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PorductModel;
use App\Models\ProdukStokModel;
use App\Models\CategoryModel;

class ProductC extends BaseController
{
    protected $productModel;
    protected $produkStokModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new PorductModel();
        $this->produkStokModel = new ProdukStokModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $q = $this->request->getGet('q');

        if ($q) {
            // Cari produk by nama, join kategori di model
            $products = $this->productModel
                ->like('name', $q)
                ->getAll(); // pakai method getAll agar join kategori jalan
        } else {
            $products = $this->productModel->getAll();
        }

        // Tambahkan stok total tiap produk
        foreach ($products as &$p) {
            $stok = $this->produkStokModel->getStockSumByProduct($p['id']);
            $p['quantity'] = $stok ? $stok['quantity'] : 0;
        }
        
        // Ambil kategori utk dropdown modal tambah produk
        $categories = $this->categoryModel->findAll();
        $stoks = $this->produkStokModel->findAll();

        return view('contents/product', [
            'products' => $products,
            'categories' => $categories,
            'stoks' => $stoks,
        ]);
    }

    // Tambah produk baru
    public function addProduct()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'bay' => $this->request->getPost('bay'),
            'price' => $this->request->getPost('price'),
        ];

        if ($this->productModel->insert($data)) {
            session()->setFlashdata('success', 'Produk berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan produk.');
        }

        return redirect()->to(site_url('product'));
    }

    // Tambah stok produk
    public function addStock()
    {

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity' => $this->request->getPost('quantity'),
            'created_at' => date('Y-m-d H:i:s'),
            'who_created' => session()->get('user_id'), // Asumsi ada user_id di session
        ];

        if ($this->produkStokModel->insert($data)) {
            session()->setFlashdata('success', 'Stok berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan stok.');
        }

        return redirect()->to(site_url('product'));
    }



    // Update produk
    public function updateProduct($id)
    {
        $product = new PorductModel();
        $exit = $product->find($id);
        if (!$exit) {
            return redirect()->to('/product')->with('error', 'Kategori tidak ditemukan.');
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'bay' => $this->request->getPost('bay'),
            'price' => $this->request->getPost('price'),
            'update_at' => date('Y-m-d H:i:s'),
            'who_created' => session()->get('user_id'),
        ];

        if ($product->update($id, $data)) {
            session()->setFlashdata('success', 'Produk berhasil diupdate.');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate produk.');
        }

        return redirect()->to(site_url('product'));
    }

   public function updateStok($productId)
{
    $requestedQty = (int) $this->request->getPost('quantity');
    $sisaQty = $requestedQty;

    // Ambil stok (FIFO)
    $stokList = $this->produkStokModel
        ->where('product_id', $productId)
        ->where('quantity >', 0)
        ->orderBy('id', 'ASC')
        ->findAll();

    $db = \Config\Database::connect();
    $db->transStart();

    foreach ($stokList as $stok) {
        if ($sisaQty <= 0) break;

        if ($stok['quantity'] >= $sisaQty) {
            $newQty = $stok['quantity'] - $sisaQty;
            if ($newQty != $stok['quantity']) {
                $this->produkStokModel->update($stok['id'], ['quantity' => $newQty]);
            }
            $sisaQty = 0;
        } else {
            if ($stok['quantity'] > 0) {
                $this->produkStokModel->update($stok['id'], ['quantity' => 0]);
            }
            $sisaQty -= $stok['quantity'];
        }
    }

    if ($sisaQty > 0) {
        // Rollback manual kalau stok kurang
        $db->transRollback();
        return redirect()->back()->with('error', 'Stok tidak mencukupi!');
    }

    $db->transComplete();
    return redirect()->back()->with('success', 'Stok berhasil dikurangi!');
}



    // Hapus produk
    public function deleteProduct($id = null)
    {
        if (!$id) {
            return redirect()->to(site_url('product'));
        }

        if ($this->productModel->delete($id)) {
            session()->setFlashdata('success', 'Produk berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus produk.');
        }

        return redirect()->to(site_url('product'));
    }
}
