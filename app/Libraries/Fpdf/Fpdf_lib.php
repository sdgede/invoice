<?php

namespace App\Libraries\Fpdf;

require_once(APPPATH . 'Libraries/Fpdf/fpdf.php');

class Fpdf_lib extends \FPDF
{
    protected $invoiceNo = 'INV-000000';

    public function setInvoiceNo(string $no)
    {
        $this->invoiceNo = $no;
    }

    public function Header()
    {
        $logoPath = FCPATH . 'img/logo.jpg';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 10, 35);
        }

        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(9, 25);
        $this->Cell(100, 5, 'Jl. Kebo Iwa Utara No:28', 0, 1);
        $this->SetXY(9, 30);
        $this->Cell(100, 5, 'BR. Liligundi - Denpasar', 0, 1);

        // Invoice Title
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(160, 10);
        $this->Cell(40, 10, 'INVOICE', 0, 2, 'R');

        // Invoice No
        $this->SetFont('Arial', '', 10);
        $this->SetX(160);
        $this->SetTextColor(0, 102, 204);
        $this->Cell(40, 5, 'NO: ' . $this->invoiceNo, 0, 1, 'R');

        $this->SetTextColor(0);
        $this->Ln(10);
    }

    public function invoiceDetails($pelanggan)
    {
        $this->SetFont('Arial', '', 10);

        // Nama dan alamat di kiri
        $startY = $this->GetY();
        $this->SetXY(10, $startY);
        $this->Cell(20, 6, 'Kepada Yth', 0, 0);
        $this->Cell(3, 6, ':', 0, 0);

        $nama   = $pelanggan['nama'] ?? '';
        $alamat = $pelanggan['alamat'] ?? '';

        $this->Cell(50, 6, $nama, 0, 1);

        // Alamat di bawah nama, rata kiri
        $this->Cell(20, 6, 'Alamat', 0, 0);
        $this->Cell(3, 6, ':', 0, 0);
        $this->MultiCell(70, 6, $alamat, 0, 1);

        // Tanggal di kanan, sejajar dengan "Kepada Yth"
        $this->SetXY(150, $startY);
        $this->Cell(18, 6, 'Tanggal :', 0, 0);
        $this->Cell(40, 6, date('j F Y'), 0, 1);

        $this->Ln(15);
    }

    public function invoiceTable($items)
    {
        $w = ['10', '60', '20', '30', '30', '30'];

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(230, 230, 230);
        $this->Cell($w[0], 8, 'NO', 1, 0, 'C', true);
        $this->Cell($w[1], 8, 'Nama', 1, 0, 'C', true);
        $this->Cell($w[2], 8, 'Qty', 1, 0, 'C', true);
        $this->Cell($w[3], 8, 'Price', 1, 0, 'C', true);
        $this->Cell($w[4], 8, 'Disc', 1, 0, 'C', true);
        $this->Cell($w[5], 8, 'Total', 1, 1, 'C', true);

        $this->SetFont('Arial', '', 10);
        $no = 1;
        $grandTotal = 0;

        if (!empty($items)) {
            foreach ($items as $item) {
                $name  = $item['name'] ?? '';
                $desc  = $item['desc'] ?? '';
                $qty   = (int)($item['qty'] ?? 0);
                $price = (float)($item['price'] ?? 0);
                $disc  =  (float)$item['disc'] ?? 0;

                // Hitung diskon
                $total = ($price * $qty) - $disc;
                $grandTotal += $total;

                $this->Cell($w[0], 8, $no++, 1, 0, 'C');
                $this->Cell($w[1], 8, $name, 1);
                $this->Cell($w[2], 8, $qty, 1, 0, 'C');
                $this->Cell($w[3], 8, $this->formatRupiah($price), 1, 0, 'R');
                $this->Cell($w[4], 8, $this->formatRupiah($disc), 1, 0, 'R');
                $this->Cell($w[5], 8, $this->formatRupiah($total), 1, 1, 'R');
            }
        } else {
            $this->Cell(180, 8, 'Tidak ada item.', 1, 1, 'C');
        }

        // Grand Total
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(150, 8, 'Grand Total', 1, 0, 'R');
        $this->Cell(30, 8, $this->formatRupiah($grandTotal), 1, 1, 'R');
    }

    public function footerNotes($approvedBy)
    {
        $rek = getenv('NoRek') ?: '0000000000';

        $this->Ln(10);
        $this->SetFont('Arial', '', 9);
        $this->Cell(90, 5, 'Approved by', 0, 0);
        $this->Cell(90, 5, 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan', 0, 1, 'R');

        $this->Cell(90, 5, '', 0, 0);
        $this->Cell(90, 5, 'Pembayaran dapat ditransfer melalui bank Mandiri', 0, 1, 'R');

        $this->Cell(90, 5, '', 0, 0);
        $this->Cell(90, 5, "No. Rek: $rek", 0, 1, 'R');

        $approvedName = $approvedBy['name'] ?? '-';
        $this->Cell(90, 5, $approvedName, 0, 0);
        $this->Cell(90, 5, 'Bila transfer bank: TULIS nomor nota di berita', 0, 1, 'R');
    }

    protected function formatRupiah($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
}
