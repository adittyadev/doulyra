<?php

namespace App\Models;

use CodeIgniter\Model;

class MinumanModel extends Model
{
    protected $table            = 'minuman';
    protected $primaryKey       = 'idminuman';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'idminuman',
        'namaminuman',
        'harga',
        'stok',
        'foto',
        'keterangan',
    ];
}
