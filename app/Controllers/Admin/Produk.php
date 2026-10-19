<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DetailKeranjangModel;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Produk extends BaseController
{
    protected $helpers = ['url'];

    public function index()
    {
        $produk = model(ProdukModel::class);

        return view('admin/produk/index', [
            'produk' => $produk->getWithKategori()->paginate(15),
            'pager'  => $produk->pager,
        ]);
    }

    public function baru()
    {
        return view('admin/produk/form', ['produk' => null, 'kategori' => model(KategoriModel::class)->findAll()]);
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        model(ProdukModel::class)->insert($this->dataForm(null));

        return redirect()->to('/admin/produk')->with('success', 'Produk ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('admin/produk/form', ['produk' => $this->cari($id), 'kategori' => model(KategoriModel::class)->findAll()]);
    }

    public function ubah(int $id)
    {
        $lama = $this->cari($id);

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        model(ProdukModel::class)->update($id, $this->dataForm($lama));

        return redirect()->to('/admin/produk')->with('success', 'Produk diperbarui.');
    }

    public function hapus(int $id)
    {
        $produk = $this->cari($id);

        try {
            // detail_keranjang memakai ON DELETE RESTRICT, jadi dibersihkan lebih dulu
            model(DetailKeranjangModel::class)->where('id_produk', $id)->delete();
            model(ProdukModel::class)->delete($id);
        } catch (\Throwable $e) {
            log_message('error', 'Hapus produk gagal: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Produk tidak dapat dihapus. Ubah statusnya menjadi nonaktif.');
        }

        if ($produk['foto']) {
            @unlink(FCPATH . 'uploads/produk/' . $produk['foto']);
        }

        return redirect()->to('/admin/produk')->with('success', 'Produk dihapus.');
    }

    private function rules(): array
    {
        return [
            'id_kategori'   => 'required|is_not_unique[kategori.id_kategori]',
            'nama_produk'   => 'required|max_length[150]',
            'harga'         => 'required|numeric|greater_than_equal_to[0]',
            'stok'          => 'required|is_natural',
            'status_produk' => 'required|in_list[tersedia,habis,nonaktif]',
            'foto'          => 'is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ];
    }

    private function dataForm(?array $lama): array
    {
        $nama   = $this->request->getPost('nama_produk');
        $stok   = (int) $this->request->getPost('stok');
        $status = $this->request->getPost('status_produk');

        if ($stok === 0 && $status === 'tersedia') {
            $status = 'habis';
        }

        $data = [
            'id_kategori'   => (int) $this->request->getPost('id_kategori'),
            'nama_produk'   => $nama,
            'slug'          => $lama ? $lama['slug'] : $this->buatSlug($nama), // slug tetap agar URL tidak berubah
            'deskripsi'     => $this->request->getPost('deskripsi') ?: null,
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $stok,
            'status_produk' => $status,
        ];

        $file = $this->request->getFile('foto');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move(FCPATH . 'uploads/produk', $namaFile);

            if (! empty($lama['foto'])) {
                @unlink(FCPATH . 'uploads/produk/' . $lama['foto']);
            }

            $data['foto'] = $namaFile;
        }

        return $data;
    }

    private function buatSlug(string $nama): string
    {
        $dasar = url_title($nama, '-', true);
        $slug  = $dasar;
        $i     = 2;

        while (model(ProdukModel::class)->where('slug', $slug)->first()) {
            $slug = $dasar . '-' . $i++;
        }

        return $slug;
    }

    private function cari(int $id): array
    {
        $produk = model(ProdukModel::class)->find($id);

        if (! $produk) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $produk;
    }
}
