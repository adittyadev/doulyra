<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_kategori',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'foto',
        'status_produk',
    ];
    protected $useTimestamps = false;

    /** Daftar produk beserta nama kategori. */
    public function getWithKategori(?string $status = null)
    {
        $builder = $this->select('produk.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori');

        if ($status !== null) {
            $builder->where('produk.status_produk', $status);
        }

        return $builder->orderBy('produk.created_at', 'DESC');
    }

    /** Katalog untuk pelanggan: hanya produk tersedia. */
    public function getKatalog(?int $idKategori = null, ?string $keyword = null)
    {
        $builder = $this->getWithKategori('tersedia');

        if ($idKategori) {
            $builder->where('produk.id_kategori', $idKategori);
        }
        if ($keyword) {
            $builder->like('produk.nama_produk', $keyword);
        }

        return $builder;
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->select('produk.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori')
            ->where('produk.slug', $slug)
            ->first();
    }
}
