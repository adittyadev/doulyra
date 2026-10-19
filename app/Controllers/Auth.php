<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to($this->beranda(session()->get('role')));
        }

        return view('auth/login');
    }

    public function prosesLogin()
    {
        if (! $this->validate(['login' => 'required', 'password' => 'required'])) {
            return redirect()->back()->withInput()->with('error', 'Username/email dan password wajib diisi.');
        }

        $user = model(UserModel::class)->findByLogin($this->request->getPost('login'));

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username/email atau password salah, atau akun nonaktif.');
        }

        session()->regenerate();
        session()->set([
            'logged_in' => true,
            'id_user'   => (int) $user['id_user'],
            'nama'      => $user['nama'],
            'role'      => $user['role'],
        ]);

        return redirect()->to($this->beranda($user['role']));
    }

    public function register()
    {
        return view('auth/register');
    }

    public function prosesRegister()
    {
        $rules = [
            'nama'             => 'required|max_length[100]',
            'username'         => 'required|alpha_dash|min_length[4]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'no_hp'            => 'permit_empty|numeric|max_length[20]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        model(UserModel::class)->insert([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'no_hp'    => $this->request->getPost('no_hp') ?: null,
            'password' => $this->request->getPost('password'), // di-hash oleh UserModel
            'role'     => 'pelanggan',
            'status'   => 'aktif',
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    private function beranda(string $role): string
    {
        return ['admin' => '/admin', 'pimpinan' => '/pimpinan/laporan'][$role] ?? '/';
    }
}