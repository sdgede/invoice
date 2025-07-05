<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => ('root'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert ke tabel users
        $this->db->table('users')->insert($data);
    }
}
