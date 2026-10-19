<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Katalog extends BaseController
{
    public function index()
    {
        $kategori = (int) $this->request->getGet('kategori');
        $produk   = model(ProdukModel::class);

        $data = [
            'produk'   => $produk->getKatalog($kategori ?: null, $this->request->getGet('q'))->paginate(12),
            'pager'    => $produk->pager,
            'kategori' => model(KategoriModel::class)->findAll(),
        ];

        return view('katalog/index', $data);
    }

    public function detail(string $slug)
    {
        $produk = model(ProdukModel::class)->findBySlug($slug);

        if (! $produk || $produk['status_produk'] === 'nonaktif') {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('katalog/detail', ['produk' => $produk]);
    }
}
