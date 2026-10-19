<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id_detail_pesanan';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_pesanan',
        'id_produk',
        'nama_produk',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    // Tabel ini tidak punya created_at / updated_at
    protected $useTimestamps = false;

    public function getByPesanan(int $idPesanan): array
    {
        return $this->where('id_pesanan', $idPesanan)->findAll();
    }
}
