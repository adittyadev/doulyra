<?php

namespace App\Controllers;

use App\Models\DetailKeranjangModel;
use App\Models\KeranjangModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Keranjang extends BaseController
{
    public function index()
    {
        $keranjang = $this->keranjang();
        $items     = model(DetailKeranjangModel::class)->getByKeranjang($keranjang['id_keranjang']);
        $total     = array_sum(array_column($items, 'subtotal'));

        return view('keranjang/index', compact('items', 'total'));
    }

    public function tambah()
    {
        $idProduk = (int) $this->request->getPost('id_produk');
        $jumlah   = max(1, (int) $this->request->getPost('jumlah'));
        $produk   = model(ProdukModel::class)->find($idProduk);

        if (! $produk || $produk['status_produk'] !== 'tersedia') {
            return redirect()->back()->with('error', 'Produk tidak tersedia.');
        }

        $keranjang = $this->keranjang();
        $detail    = model(DetailKeranjangModel::class);
        $ada       = $detail->where(['id_keranjang' => $keranjang['id_keranjang'], 'id_produk' => $idProduk])->first();

        if ($jumlah + ($ada['jumlah'] ?? 0) > $produk['stok']) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok (sisa ' . $produk['stok'] . ').');
        }

        $detail->tambah($keranjang['id_keranjang'], $idProduk, $jumlah);

        return redirect()->back()->with('success', $produk['nama_produk'] . ' ditambahkan ke keranjang.');
    }

    public function ubah(int $id)
    {
        $item   = $this->itemMilikUser($id);
        $jumlah = (int) $this->request->getPost('jumlah');
        $detail = model(DetailKeranjangModel::class);

        if ($jumlah < 1) {
            $detail->delete($id);

            return redirect()->to('/keranjang');
        }

        $produk = model(ProdukModel::class)->find($item['id_produk']);

        if (! $produk || $produk['status_produk'] !== 'tersedia' || $jumlah > $produk['stok']) {
            return redirect()->to('/keranjang')->with('error', 'Jumlah melebihi stok atau produk tidak tersedia.');
        }

        $detail->update($id, ['jumlah' => $jumlah]);

        return redirect()->to('/keranjang');
    }

    public function hapus(int $id)
    {
        $this->itemMilikUser($id);
        model(DetailKeranjangModel::class)->delete($id);

        return redirect()->to('/keranjang')->with('success', 'Item dihapus dari keranjang.');
    }

    private function keranjang(): array
    {
        return model(KeranjangModel::class)->getOrCreateByUser((int) session()->get('id_user'));
    }

    private function itemMilikUser(int $id): array
    {
        $item = model(DetailKeranjangModel::class)
            ->where(['id_detail_keranjang' => $id, 'id_keranjang' => $this->keranjang()['id_keranjang']])
            ->first();

        if (! $item) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $item;
    }
}
