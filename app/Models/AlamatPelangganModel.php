<?php

namespace App\Models;

use CodeIgniter\Model;

class AlamatPelangganModel extends Model
{
    protected $table            = 'alamat_pelanggan';
    protected $primaryKey       = 'id_alamat';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_user',
        'nama_penerima',
        'no_hp',
        'alamat_lengkap',
        'kecamatan',
        'kota',
        'kode_pos',
        'is_utama',
    ];
    protected $useTimestamps = false;

    public function getByUser(int $idUser): array
    {
        return $this->where('id_user', $idUser)
            ->orderBy('is_utama', 'DESC')
            ->findAll();
    }

    /** Jadikan satu alamat sebagai alamat utama, yang lain dinonaktifkan. */
    public function setUtama(int $idUser, int $idAlamat): void
    {
        $this->where('id_user', $idUser)->set('is_utama', 0)->update();
        $this->update($idAlamat, ['is_utama' => 1]);
    }
}
