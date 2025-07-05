<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Category extends BaseController
{
    // Tampilkan semua kategori
    public function index()
    {
        $categoryModel = new CategoryModel();
        $data = $categoryModel->findAll();

        return view('contents/category', [
            'title' => 'Kategori',
            'categories' => $data,
            'message' => empty($data) ? 'Tidak ada kategori yang ditemukan.' : null,
        ]);
    }

    // Tambah kategori baru
    public function addcategory()
    {
        $newCategory = trim($this->request->getPost('name'));
        $userId = session()->get('user_id') ?? 1; // default dev ID

        $data = [
            'name' => $newCategory,
            'who_created' => $userId,
        ];

        $categoryModel = new CategoryModel();

        if (!$categoryModel->validate($data)) {
            $errors = $categoryModel->errors();
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        $categoryModel->insert($data);
        return redirect()->to('/category')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Update kategori
    public function updatecategory($id)
    {
        $categoryModel = new CategoryModel();
        $existing = $categoryModel->find($id);

        if (!$existing) {
            return redirect()->to('/category')->with('error', 'Kategori tidak ditemukan.');
        }

        $updatedName = trim($this->request->getPost('name'));

        $data = [
            'id' => $id,
            'name' => $updatedName,
            'update_at' =>  date('Y-m-d H:i:s'),
            'who_created' => $existing['who_created'], // tetap pakai yang lama
        ];

        if (!$categoryModel->validate($data)) {
            $errors = $categoryModel->errors();
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        $categoryModel->save($data);
        return redirect()->to('/category')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Hapus kategori
    public function deletecategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/category')->with('error', 'Kategori tidak ditemukan.');
        }

        $categoryModel->delete($id);
        return redirect()->to('/category')->with('success', 'Kategori berhasil dihapus!');
    }
}
