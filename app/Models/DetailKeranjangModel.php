<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailKeranjangModel extends Model
{
    protected $table            = 'detail_keranjang';
    protected $primaryKey       = 'id_detail_keranjang';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_keranjang', 'id_produk', 'jumlah'];
    protected $useTimestamps    = false;

    /** Isi keranjang beserta data produk dan subtotal. */
    public function getByKeranjang(int $idKeranjang): array
    {
        return $this->select('detail_keranjang.*, produk.nama_produk, produk.slug, produk.harga, produk.stok, produk.foto, produk.status_produk, (produk.harga * detail_keranjang.jumlah) AS subtotal')
            ->join('produk', 'produk.id_produk = detail_keranjang.id_produk')
            ->where('detail_keranjang.id_keranjang', $idKeranjang)
            ->findAll();
    }

    /** Tambah produk; jika sudah ada, jumlah ditambahkan. */
    public function tambah(int $idKeranjang, int $idProduk, int $jumlah = 1): bool
    {
        $item = $this->where(['id_keranjang' => $idKeranjang, 'id_produk' => $idProduk])->first();

        if ($item) {
            return $this->update($item['id_detail_keranjang'], ['jumlah' => $item['jumlah'] + $jumlah]);
        }

        return (bool) $this->insert([
            'id_keranjang' => $idKeranjang,
            'id_produk'    => $idProduk,
            'jumlah'       => $jumlah,
        ]);
    }

    public function kosongkan(int $idKeranjang): bool
    {
        return (bool) $this->where('id_keranjang', $idKeranjang)->delete();
    }
}
