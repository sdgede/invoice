<?php
namespace App\Models;
use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'code_invoice',
        'customer_name',
        'address',
        'discount',
        'status',
        'description',
        'who_created',
        'created_at',
        'updated_at',
        'updated_who',
    ];

    protected $useTimestamps = true;

    // Optional: Format data output ke array
    protected $returnType = 'array';

    // hitung semua invoice berdasarkan code
    public function countAllInvoices(): int
    {
        return $this->select('code_invoice')
            ->where('code_invoice IS NOT NULL')
            ->countAllResults();
    }

    public function insertInvoice($data)
    {
        $this->insert($data);
        return $this->insertID();
    }

    public function updateInvoice($id_invoice, $data)
    {
        return $this->update($id_invoice, $data);
    }

    public function cancelItem($id_invoice)
    {
        return $this->update($id_invoice, [
            "status" => "4",
            "updated_at" => date('Y-m-d H:i:s'),
            "update_who" => session()->get('user_id')
        ]);
    }

    public function settleItem($id_invoice)
    {
        return $this->update($id_invoice, [
            "status" => "3",
            "updated_at" => date('Y-m-d H:i:s'),
            "update_who" => session()->get('user_id')
        ]);
    }

    public function restoreItem($id_invoice)
    {
        return $this->update($id_invoice, [
            "status" => "1",
            "updated_at" => date('Y-m-d H:i:s'),
            "update_who" => session()->get('user_id')
        ]);
    }


    // hitumg semua invoice berdasarkan status lunas
    public function countPaidInvoices(): int
    {
        return $this->where('status', '3')->countAllResults();
    }
}
