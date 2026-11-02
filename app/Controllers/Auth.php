<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/login');
    }

    public function prosesLogin()
    {
        if (! $this->validate([
            'username' => 'required',
            'password' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $user = (new UserModel())->getByUsername($this->request->getPost('username'));

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        session()->set([
            'logged_in'    => true,
            'user_id'      => $user['id'],
            'nama'         => $user['nama_lengkap'],
            'role'         => $user['role'],
            'pelanggan_id' => $user['pelanggan_id'],
        ]);

        $tujuan = session()->get('redirect_url')
            ?? ($user['role'] === 'pelanggan' ? site_url('/') : site_url('dashboard'));
        session()->remove('redirect_url');

        return redirect()->to($tujuan);
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/register');
    }

    public function prosesRegister()
    {
        if (! $this->validate([
            'nama_lengkap' => 'required|min_length[3]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'username'     => 'required|min_length[4]|is_unique[users.username]',
            'no_hp'        => 'required|numeric|min_length[10]',
            'alamat'       => 'required',
            'password'     => 'required|min_length[6]',
            'konfirmasi'   => 'required|matches[password]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $db->transStart();

        $pelangganId = (new PelangganModel())->insert([
            'namapelanggan'   => $this->request->getPost('nama_lengkap'),
            'no_hp'  => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
        ], true);

        (new UserModel())->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'role'         => 'pelanggan',
            'pelanggan_id' => $pelangganId,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Pendaftaran gagal, coba lagi.');
        }

        return redirect()->to(site_url('login'))->with('success', 'Akun berhasil dibuat. Silakan masuk.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('/'));
    }
}
