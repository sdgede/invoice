<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InvoiceItemModel; // Pastikan modelnya dipanggil
use CodeIgniter\HTTP\ResponseInterface;

class InfoiceCetakController extends BaseController
{
    protected $invoiceItemModel;

    public function __construct()
    {
        $this->invoiceItemModel = new InvoiceItemModel();
    }

    public function index($code)
    {
        $session = session();

        // Cek apakah user login
        $namaUser = $session->get('name');
        if (!$namaUser) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data invoice berdasarkan code
        $invoice = $this->invoiceItemModel->getInvoiceByCode($code);
        if (!$invoice) {
            return "Invoice dengan kode $code tidak ditemukan.";
        }

        // Generate PDF
        $pdf = new \App\Libraries\Fpdf\Fpdf_lib();

        $pdf->setInvoiceNo($code);
        $pdf->AddPage();

        // Detail customer
        $pdf->invoiceDetails([
            'nama' => $invoice['customer_name'],
            'alamat' => $invoice['address'],
        ]);

        // Items
        $items = [];
        foreach ($invoice['items'] as $item) {
            $items[] = [
                'name' => $item['name'],
                'qty' => $item['quantity'],
                'price' => $item['price'],
                'disc' => $item['discount'] ?? 0,
            ];
        }

        $pdf->invoiceTable($items);

        // Footer dengan nama user login
        $pdf->footerNotes(['name' => $namaUser]);

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output('I', "$code.pdf");
        exit;
    }
    public function d($code)
    {
        $session = session();

        // Cek apakah user login
        $namaUser = $session->get('name');
        if (!$namaUser) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data invoice berdasarkan code
        $invoice = $this->invoiceItemModel->getInvoiceByCode($code);
        if (!$invoice) {
            return "Invoice dengan kode $code tidak ditemukan.";
        }

        // Generate PDF
        $pdf = new \App\Libraries\Fpdf\Fpdf_lib();

        $pdf->setInvoiceNo($code);
        $pdf->AddPage();

        // Detail customer
        $pdf->invoiceDetails([
            'nama' => $invoice['customer_name'],
            'alamat' => $invoice['address'],
        ]);

        // Items
        $items = [];
        foreach ($invoice['items'] as $item) {
            $items[] = [
                'name' => $item['name'],
                'desc' => $item['invoice_description'],
                'qty' => $item['quantity'],
                'price' => $item['price'],
                'disc' => $item['discount'] ?? 0,
            ];
        }

        $pdf->invoiceTable($items);

        // Footer dengan nama user login
        $pdf->footerNotes(['name' => $namaUser]);

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output('D', "$code.pdf");
        exit;
    }
}
