<?php

namespace App\Models;

use CodeIgniter\Model;

class MakananModel extends Model
{
    protected $table            = 'makanan';
    protected $primaryKey       = 'idmakanan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'idmakanan',
        'namamakanan',
        'harga',
        'stok',
        'foto',
        'keterangan',
    ];
}
