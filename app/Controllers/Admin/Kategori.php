<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Kategori extends BaseController
{
    public function index()
    {
        return view('admin/kategori/index', ['kategori' => model(KategoriModel::class)->findAll()]);
    }

    public function simpan()
    {
        if (! $this->validate(['nama_kategori' => 'required|max_length[100]|is_unique[kategori.nama_kategori]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        model(KategoriModel::class)->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
        ]);

        return redirect()->to('/admin/kategori')->with('success', 'Kategori ditambahkan.');
    }

    public function ubah(int $id)
    {
        $this->cari($id);

        $rules = ['nama_kategori' => "required|max_length[100]|is_unique[kategori.nama_kategori,id_kategori,{$id}]"];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        model(KategoriModel::class)->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
        ]);

        return redirect()->to('/admin/kategori')->with('success', 'Kategori diperbarui.');
    }

    public function hapus(int $id)
    {
        $this->cari($id);

        if (model(ProdukModel::class)->where('id_kategori', $id)->countAllResults() > 0) {
            return redirect()->back()->with('error', 'Kategori masih dipakai oleh produk.');
        }

        model(KategoriModel::class)->delete($id);

        return redirect()->to('/admin/kategori')->with('success', 'Kategori dihapus.');
    }

    private function cari(int $id): array
    {
        $kategori = model(KategoriModel::class)->find($id);

        if (! $kategori) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $kategori;
    }
}
