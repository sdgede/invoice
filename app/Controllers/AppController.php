<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InvoiceItemModel;
use App\Models\InvoiceModel;
use App\Models\PorductModel;
use App\Models\UserModel;

class AppController extends BaseController
{

    protected $productModel;
    protected $invoiceItemModel;
    protected $invoiceModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
        $this->productModel = new PorductModel();
    }

    public function index()
    {
        return view('index');
    }

    public function dashboard()
    {
        
        $totalOrder = $this->invoiceModel->countAllInvoices();
        $totalDone = $this->invoiceModel->countPaidInvoices();
        $totalmodal = $this->invoiceItemModel->getTotalBayWithQuantity();
        $totalPenjualan = $this->invoiceItemModel->getTotalPriceAllInvoices();


        return view('contents/dashboard', [
            'title' => 'Dashboard',
            'totalOrder' => $totalOrder,
            'totalDone' => $totalDone,
            'totalModal' => $totalmodal,
            'totalPenjualan' => $totalPenjualan
        ]);
    }
    public function user()
    {
        // Ambil data semua user dari model
        $userModel = new UserModel();
        $users = $userModel->findAll();
        return view('contents/user', [
            'title' => 'user',
            'users' => $users
        ]);
    }

    public function adduser()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');

        // Cek apakah username sudah ada
        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan, silakan pilih username lain.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $username,
            'password' => $this->request->getPost('password'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        // Simpan data user baru
        if ($userModel->insert($data)) {
            return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user');
        }
    }

    public function editUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        // Ambil data dari form
        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Cek apakah password diisi, jika ya tambahkan ke data
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // Update data user
        if ($userModel->update($id, $data)) {
            return redirect()->to('/user')->with('success', 'User berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui user');
        }
    }

    public function deleteUser($id)
    {
        $userModel = new UserModel();
        // Hapus user berdasarkan ID
        if ($userModel->delete($id)) {
            return redirect()->to('/user')->with('success', 'User berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus user');
        }
    }


    
}
