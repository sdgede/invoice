<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        return view('LoginPage'); // Pastikan view ini tersedia di app/Views/
    }

    public function loginProcess()
    {
        $validation = \Config\Services::validation();

        // Validasi input
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/login')->withInput()->with('error', 'Username dan password harus diisi.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil user dari database
        $userModel = model(\App\Models\UserModel::class);
        $user = $userModel->where('username', $username)->first();

        // --- Pengecekan password plaintext ---
        if ($user && $password === $user['password']) {

            // (Opsional) Cek apakah user aktif
            if (isset($user['is_active']) && !$user['is_active']) {
                return redirect()->to('/login')->withInput()->with('error', 'Akun Anda tidak aktif.');
            }

            // Set session login
            session()->set([
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'name'      => $user['name'], // <- tambahkan ini!
                'logged_in' => true
            ]);


            return redirect()->to('/');
        } else {
            return redirect()->to('/login')->withInput()->with('error', 'Username atau password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Anda telah logout.');
    }
}
