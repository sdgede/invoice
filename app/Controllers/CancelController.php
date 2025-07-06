<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PorductModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use Config\Database;
use App\Models\ProdukStokModel;

class CancelController extends BaseController
{
    protected $db;
    protected $productModel;
    protected $invoiceItemModel;
    protected $invoiceModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
        $this->productModel = new PorductModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $searchInvoice = $this->request->getPost('searchInvoice');
        $searchStatus = '0';

        $invoiceGrouped = $this->invoiceItemModel->getGroupedInvoices($searchInvoice, $searchStatus);
        $invoiceTotals = $this->invoiceItemModel->findAll();
        $product = $this->productModel->findAll();

        return view('contents/cancelInvoice', [
            'title' => 'Invoice Yang Dibatalkan',
            'invoices' => $invoiceGrouped,
            'totalInvoice' => $invoiceTotals,
            'products' => $product,
        ]);
    }

    public function restoreInvoice($id)
{
    $db           = \Config\Database::connect();
    $invoiceModel = new \App\Models\InvoiceModel();
    $itemModel    = new InvoiceItemModel();
    $stokModel    = new ProdukStokModel();

    // 1. Cek invoice
    $invoice = $invoiceModel->find($id);
    if (!$invoice || $invoice['status'] != '4') {
        return redirect()->back()
            ->with('error', 'Invoice tidak ditemukan / bukan status CANCELED.');
    }

    // 2. Ambil semua item invoice
    $items = $itemModel->where('invoice_id', $id)->findAll();
    if (!$items) {
        return redirect()->back()
            ->with('error', 'Invoice kosong—tidak ada item.');
    }

    // 3. Mulai transaksi
    $db->transStart();

    // 4. Kurangi stok per item
    foreach ($items as $item) {
        $res = $stokModel->reduceStock($item['product_id'], $item['quantity']); // qty POSITIF = kurangi
        if (!$res['success']) {
            $db->transRollback();
            return redirect()->back()
                ->with('error', 'Stok tidak cukup: '.$res['message']);
        }
    }

    // 5. Ubah status invoice kembali ke “Menunggu Pembayaran” (1)
    $invoiceModel->restoreItem($id);

    // 6. Commit
    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'DB Error—transaksi rollback.');
    }

    return redirect()->to('/invoice')->with('success', 'Invoice dipulihkan & stok terpotong ✅');
}
}
