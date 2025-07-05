<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PorductModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use Config\Database;

class InvoiceController extends BaseController
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
        $searchStatus = $this->request->getPost('searchStatus');

        $invoiceGrouped = $this->invoiceItemModel->getGroupedInvoices($searchInvoice, $searchStatus);
        $invoiceTotals = $this->invoiceItemModel->findAll();
        $product = $this->productModel->findAll();

        return view('contents/invoice', [
            'title' => 'Invoice',
            'invoices' => $invoiceGrouped,
            'totalInvoice' => $invoiceTotals,
            'products' => $product,
        ]);
    }

    public function simpanInvoice()
    {
        $db = \Config\Database::connect();
        $invoiceModel = new \App\Models\InvoiceModel();
        $itemModel = new \App\Models\InvoiceItemModel();
        $stokModel = new \App\Models\ProdukStokModel();

        // -------------- 1. Siapkan data invoice ---------------
        $lastCode = $invoiceModel->orderBy('id', 'DESC')->first()['code_invoice'] ?? null;
        $newNumber = ((int) substr($lastCode ?? 'WBT-00000', 4)) + 1;
        $newCode = 'WBT-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $invoice_id = $this->request->getPost('invoice_id');
        $aksi = $this->request->getPost('aksi');
        $diskon = preg_replace('/[^0-9]/', '', $this->request->getPost('diskon'));

        $invoiceData = [
            'customer_name' => $this->request->getPost('customer_name'),
            'address' => $this->request->getPost('address'),
            'discount' => $diskon,
            'status' => $this->request->getPost('status'),
            'invoice_description' => $this->request->getPost('description'),
        ];

        $produkIds = $this->request->getPost('produk_id');   // array
        $quantities = $this->request->getPost('quantity');    // array

        // -------------- 2. Mulai TRANSAKSI BESAR ---------------
        $db->transStart();

        // Simpan invoice
        if (!$invoice_id && $aksi == 'tambah') {
            $invoiceData = array_merge($invoiceData, [
                'code_invoice' => $newCode,
                'who_created' => session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $invoiceResult = $invoiceModel->insertInvoice($invoiceData);
        } else {
            $invoiceData = array_merge($invoiceData, [
                'updated_who' => session()->get('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $invoiceResult = $invoiceModel->updateInvoice($invoice_id, $invoiceData);
        }

        $getInvoiceItem = $this->db->query("SELECT * FROM invoice_items WHERE invoice_id = '$invoice_id'");

        // Loop item
        if (!$produkIds || count($produkIds) == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon Tambahkan Produk, Produk Tidak Boleh Kosong.');
        }

        $check_dupe = count($produkIds) !== count(array_unique($produkIds));

        if ($check_dupe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Barang Tidak Boleh Sama.');
        }

        $totalInputRowProduk = count($produkIds);
        $checkWillChange = [];
        $listDeleteProduk = [];
        $totalEProdukSukses = [];
        foreach ($produkIds as $i => $prodId) {
            $qty = (int) $quantities[$i];

            if (!$invoice_id && $aksi == 'tambah') {
                // a) Kurangi stok
                $stokRes = $stokModel->reduceStock($prodId, $qty);
                if (!$stokRes['success']) {
                    $db->transRollback();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', message: $stokRes['message']);
                }

                // b) Simpan item
                $itemModel->addItem($invoiceResult, $prodId, $qty, session()->get('user_id'));
            } else {
                if ($getInvoiceItem->getNumRows() == count($produkIds)) {
                    foreach ($getInvoiceItem->getResult() as $i2) {
                        if ($prodId == $i2->product_id) {
                            if ($qty != $i2->quantity) {
                                $itemModel->setStock($i2->id, $qty);
                                $stokRes = $stokModel->reduceStock($prodId, $qty - $i2->quantity);
                                if (!$stokRes['success']) {
                                    $db->transRollback();
                                    return redirect()->back()
                                        ->withInput()
                                        ->with('error', message: $stokRes['message']);
                                }
                            }
                        }
                        $totalInputRowProduk -= 1;
                    }
                    // foreach ($getInvoiceItem->getResult() as $value) {
                    //     $ubah = true;
                    //     if ($value->product_id == $prodId) {
                    //         $ubah = false;
                    //         array_push($totalEProdukSukses, [$value->invoice_id, $prodId, $ubah]);
                    //     }
                    //     if ($ubah === true) {
                    //         echo 'ProdukID1: ' . $value->product_id . ' ProdukID: ' . $prodId . '<br>';
                    //         // $stokModel->reduceStock($value->product_id, $qty);
                    //         // $itemModel->removeProduct2($invoice_id, $value->product_id);
                    //     }
                    // }
                } else {
                    if ($getInvoiceItem->getNumRows() < count($produkIds)) {
                        foreach ($getInvoiceItem->getResult() as $i2) {
                            $ubah = true;
                            if ($prodId == $i2->product_id) {
                                $ubah = false;
                            }
                            array_push($checkWillChange, [$i2->invoice_id, $prodId, $qty, $ubah]);
                        }
                    } else {
                        foreach ($getInvoiceItem->getResult() as $i2) {
                            $delete = true;
                            if ($prodId == $i2->product_id) {
                                $delete = false;
                            }
                            array_push($listDeleteProduk, [$i2->id, $i2->product_id, $i2->quantity, $delete]);
                        }
                    }
                }
            }
        }

        // dd($checkWillChange);
        foreach ($checkWillChange as $value) {
            if ($value[3] === true) {
                $itemModel->addItem($value[0], $value[1], $value[2], session()->get('user_id'));
                $stokModel->reduceStock($value[1], $value[2]);
            }
        }

        // dd($listDeleteProduk);
        foreach ($listDeleteProduk as $value) {
            if ($value[3] === true) {
                $quantity = -$value[2];
                $stokModel->reduceStock($value[1], $quantity);
                $itemModel->removeProduct($value[0]);
            }
        }

        // echo $totalInputRowProduk;
        // dd($totalEProdukSukses);
        // if ($totalInputRowProduk != 0) {
        //     foreach ($getInvoiceItem->getResult() as $i2) {
        //         foreach ($totalEProdukSukses as $value) {
        //             if ($value[1] === true) {

        //             }
        //         }
        //     }
        // }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan invoice. Coba lagi.');
        }

        return redirect()->to('/invoice')
            ->with('success', 'Invoice berhasil disimpan ✅');
    }

    public function print()
    {

        $pdf = new \App\Libraries\Fpdf\Fpdf_lib();
        $pdf->AddPage();

        $pdf->invoiceDetails(
            [
                'nama' => 'Gede',
                'alamat' => 'Jl. Kebo Iwa Utara No:28, BR. Liligundi - Denpasar'
            ]
        );
        $pdf->invoiceTable([
            ['desc' => 'Tumbler Design', 'qty' => 37, 'price' => 44000],
            ['desc' => 'Tumbler Polos', 'qty' => 5, 'price' => 30000],
            ['desc' => 'Tumbler Design', 'qty' => 37, 'price' => 44000],
        ]);
        $pdf->footerNotes(
            ['name' => 'Gede'],
        );


        if (ob_get_length()) {
            ob_end_clean();
        }
        $pdf->Output('I', 'invoice.pdf');
        exit;
    }

    public function printBeforeDelete()
    {


        // PDF Logic
        $pdf = new \App\Libraries\Fpdf\Fpdf_lib();
        $pdf->AddPage();

        $pdf->invoiceDetails(
            ['nama' => 'Gede'],
            ['alamat' => 'Jl. Kebo Iwa Utara No:28, BR. Liligundi - Denpasar']
        );
        $pdf->invoiceTable([
            ['desc' => 'Tumbler Design', 'qty' => 37, 'price' => 44000],
            ['desc' => 'Tumbler Polos', 'qty' => 5, 'price' => 30000],
        ]);
        $pdf->footerNotes(
            ['name' => 'Gede'],
        );

        if (ob_get_length())
            ob_end_clean();
        $pdf->Output('D', 'invoice.pdf');
        exit;
    }

    public function cancelInvoice($id)
    {
        $db = \Config\Database::connect();
        $invoiceModel = new \App\Models\InvoiceModel();

        $invoice = $invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->back()
                ->with('error', 'Invoice tidak ditemukan.');
        }

        $db->transStart();

        $invoiceModel->cancelItem($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membatalkan invoice. Silakan coba lagi.');
        }

        return redirect()->to('/cancelled')
            ->with('success', 'Invoice berhasil dibatalkan.');
    }

    public function settleInvoice($id)
    {
        $db = \Config\Database::connect();
        $invoiceModel = new \App\Models\InvoiceModel();

        $invoice = $invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $db->transStart();

        $invoiceModel->settleItem($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyelesaikan pesanan. Silakan coba lagi.');
        }

        return redirect()->to('/invoice')
            ->with('success', 'Pesanan berhasil diselunaskan.');
    }
}
