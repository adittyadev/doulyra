<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table         = 'detail_transaksi';
    protected $primaryKey    = 'iddetail';
    protected $returnType    = 'array';
    protected $allowedFields = ['transaksi_id', 'makanan_id', 'minuman_id', 'jumlah', 'harga', 'subtotal'];
}
