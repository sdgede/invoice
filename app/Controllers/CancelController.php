<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PorductModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use Config\Database;

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
        $db = \Config\Database::connect();
        $invoiceModel = new \App\Models\InvoiceModel();

        $invoice = $invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $db->transStart();

        $invoiceModel->restoreItem($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memulihkan pesanan. Silakan coba lagi.');
        }

        return redirect()->to('/invoice')
            ->with('success', 'Pesanan berhasil dipulihkan.');
    }
}
