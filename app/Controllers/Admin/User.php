<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class User extends BaseController
{
    public function index()
    {
        $model = model(UserModel::class);

        return view('admin/user/index', [
            'users' => $model->orderBy('created_at', 'DESC')->paginate(15),
            'pager' => $model->pager,
        ]);
    }

    /** Membuat akun admin / pimpinan. Pelanggan mendaftar sendiri lewat /register. */
    public function simpan()
    {
        $rules = [
            'nama'     => 'required|max_length[100]',
            'username' => 'required|alpha_dash|min_length[4]|max_length[50]|is_unique[users.username]',
            'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'no_hp'    => 'permit_empty|numeric|max_length[20]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,pimpinan]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        model(UserModel::class)->insert([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'no_hp'    => $this->request->getPost('no_hp') ?: null,
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
            'status'   => 'aktif',
        ]);

        return redirect()->to('/admin/user')->with('success', 'Akun ditambahkan.');
    }

    public function ubahStatus(int $id)
    {
        $user = model(UserModel::class)->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($id === (int) session()->get('id_user')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        model(UserModel::class)->update($id, ['status' => $user['status'] === 'aktif' ? 'nonaktif' : 'aktif']);

        return redirect()->back()->with('success', 'Status akun diperbarui.');
    }
}
