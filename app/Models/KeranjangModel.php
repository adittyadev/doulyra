<?php

namespace App\Models;

use CodeIgniter\Model;

class KeranjangModel extends Model
{
    protected $table            = 'keranjang';
    protected $primaryKey       = 'id_keranjang';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_user'];
    protected $useTimestamps    = false;

    /** Ambil keranjang milik user; buat baru jika belum ada. */
    public function getOrCreateByUser(int $idUser): array
    {
        $keranjang = $this->where('id_user', $idUser)->first();

        if (! $keranjang) {
            $id        = $this->insert(['id_user' => $idUser], true);
            $keranjang = $this->find($id);
        }

        return $keranjang;
    }
}
