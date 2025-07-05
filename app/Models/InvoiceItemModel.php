<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table = 'invoice_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'invoice_id',
        'product_id',
        'quantity',
        'who_created',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';

    // ✅ Ambil semua invoice items dengan join
    public function getAllItems()
    {
        return $this->db->table($this->table)
            ->select('*')
            ->join('invoices', 'invoice_items.invoice_id = invoices.id', 'left')
            ->join('products', 'invoice_items.product_id = products.id', 'left')
            ->orderBy('invoice_items.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ✅ Hitung total harga setelah diskon untuk 1 invoice
    public function getTotalByInvoice($invoiceId)
    {
        $builder = $this->db->table('invoice_items t');
        $builder->select(
            't.invoice_id,
             i.code_invoice,
             i.customer_name,
             SUM(t.quantity * p.price) AS total_harga,
             i.discount,
             (SUM(t.quantity * p.price) - i.discount) AS total_setelah_diskon'
        );
        $builder->join('invoices i', 'i.id = t.invoice_id');
        $builder->join('products p', 'p.id = t.product_id');
        $builder->where('t.invoice_id', $invoiceId);
        $builder->groupBy('t.invoice_id, i.code_invoice, i.customer_name, i.discount');

        return $builder->get()->getRowArray(); // array, biar konsisten
    }

    public function getGroupedInvoices($searchInvoice, $searchStatus)
    {
        $builder = $this->db->table($this->table)
            ->select('
            invoices.id AS invoice_id,
            invoices.code_invoice,
            invoices.customer_name,
            invoices.address,
            invoices.status,
            invoices.discount,
            invoices.who_created,
            invoices.created_at,
            invoices.updated_at,
            invoices.updated_who,
            invoice_items.quantity,
            invoice_items.id AS item_id,
            invoice_items.product_id AS product_id,
            invoices.description AS invoice_description,
            products.name,
            products.price,
            users.name AS user_name
        ')
            ->join('invoices', 'invoice_items.invoice_id = invoices.id', 'left')
            ->join('products', 'invoice_items.product_id = products.id', 'left')
            ->join('users', 'users.id = IFNULL(invoices.updated_who, invoices.who_created)', 'left');

        if (!empty($searchInvoice)) {
            $builder->groupStart()
                ->like('invoices.code_invoice', $searchInvoice, 'both')
                ->orLike('invoices.customer_name', $searchInvoice, 'both')
                ->orLike('products.name', $searchInvoice, 'both')
                ->groupEnd();
        }

        if (!empty($searchStatus)) {
            $builder->where('invoices.status', $searchStatus);
        }

        $results = $builder->orderBy('invoice_items.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Grouping manual
        $grouped = [];

        foreach ($results as $row) {
            $code = $row['code_invoice'];

            if (!isset($grouped[$code])) {
                $grouped[$code] = [
                    'invoice_id' => $row['invoice_id'],
                    'customer_name' => $row['customer_name'],
                    'address' => $row['address'],
                    'status' => $row['status'],
                    'discount' => $row['discount'],
                    'who_created' => $row['who_created'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'updated_who' => $row['updated_who'],
                    'user_name' => $row['user_name'],
                    'invoice_description' => $row['invoice_description'],
                    'items' => [],
                ];
            }

            $grouped[$code]['items'][] = [
                'item_id' => $row['item_id'],
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'discount' => $row['discount'],
            ];
        }

        return $grouped;
    }

    public function addItem(int $invoiceId, int $productId, int $qty, int $userId)
    {
        return $this->insert([
            'invoice_id' => $invoiceId,
            'product_id' => $productId,
            'quantity' => $qty,
            'who_created' => $userId,
        ]);
    }

    // public function setItem(int $id, int $productId, int $qty)
    // {
    //     return $this->update($id,[
    //         'product_id' => $productId,
    //         'quantity' => $qty,
    //     ]);
    // }

    public function setStock($item_id, $quantity)
    {
        $this->update($item_id, ['quantity' => $quantity]);
    }

    public function removeProduct($item_id) {
        $this->delete($item_id);
    }

    public function removeProduct2($invoice_id, $product_id) {
        $this->db->query("DELETE FROM invoice_item WHERE invoice_id = '$invoice_id' AND product_id = '$product_id'");
    }


    public function getTotalPriceAllInvoices(): float
    {
        return $this->db->table('invoice_items')
            ->select('SUM(invoice_items.quantity * products.price) AS total_price')
            ->join('products', 'invoice_items.product_id = products.id')
            ->join('invoices', 'invoice_items.invoice_id = invoices.id')
            ->where('invoices.status', '3')
            ->get()
            ->getRowArray()['total_price'] ?? 0.0;
    }

    public function getTotalBayWithQuantity(): float
    {
        return $this->db->table('invoice_items')
            ->select('SUM(invoice_items.quantity * products.bay) AS total_bay')
            ->join('products', 'invoice_items.product_id = products.id')
            ->get()
            ->getRowArray()['total_bay'] ?? 0.0;
    }

    // InvoiceItemModel.php

    public function getInvoiceByCode($code)
    {
        $results = $this->db->table($this->table)
            ->select('
            invoices.id AS invoice_id,
            invoices.code_invoice,
            invoices.customer_name,
            invoices.address,
            invoices.status,
            invoices.discount,
            invoices.description AS invoice_description,
            invoice_items.quantity,
            invoice_items.id AS item_id,
            invoice_items.product_id AS product_id,
            products.name,
            products.price
        ')
            ->join('invoices', 'invoice_items.invoice_id = invoices.id')
            ->join('products', 'invoice_items.product_id = products.id', 'left')
            ->where('invoices.code_invoice', $code)
            ->get()
            ->getResultArray();

        if (empty($results)) {
            return null;
        }

        // Group single invoice
        $invoice = [
            'invoice_id' => $results[0]['invoice_id'],
            'customer_name' => $results[0]['customer_name'],
            'address' => $results[0]['address'],
            'status' => $results[0]['status'],
            'discount' => $results[0]['discount'],
            'invoice_description' => $results[0]['invoice_description'],
            'items' => [],
        ];

        foreach ($results as $row) {
            $invoice['items'][] = [
                'item_id' => $row['item_id'],
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'discount' => $row['discount'],
                'invoice_description' => $row['invoice_description'],

            ];
        }

        return $invoice;
    }
}
