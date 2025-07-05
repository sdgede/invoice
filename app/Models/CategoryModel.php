<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'created_at', 'updated_at', 'who_created'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Validation
    protected $validationRules = [
        'name' => 'required',
        'who_created' => 'required|integer',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama kategori wajib diisi.',
        ],
        'who_created' => [
            'required' => 'ID pembuat wajib diisi.',
            'integer' => 'ID pembuat harus berupa angka.',
        ],
    ];
}
