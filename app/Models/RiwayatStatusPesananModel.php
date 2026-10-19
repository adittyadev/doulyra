<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatStatusPesananModel extends Model
{
    protected $table            = 'riwayat_status_pesanan';
    protected $primaryKey       = 'id_riwayat';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_pesanan', 'status', 'keterangan', 'diubah_oleh'];

    // Hanya ada created_at (diisi MySQL), tidak ada updated_at
    protected $useTimestamps = false;

    public function catat(int $idPesanan, string $status, ?string $keterangan = null, ?int $diubahOleh = null): bool
    {
        return (bool) $this->insert([
            'id_pesanan'  => $idPesanan,
            'status'      => $status,
            'keterangan'  => $keterangan,
            'diubah_oleh' => $diubahOleh,
        ]);
    }

    public function getByPesanan(int $idPesanan): array
    {
        return $this->select('riwayat_status_pesanan.*, users.nama AS nama_pengubah')
            ->join('users', 'users.id_user = riwayat_status_pesanan.diubah_oleh', 'left')
            ->where('riwayat_status_pesanan.id_pesanan', $idPesanan)
            ->orderBy('riwayat_status_pesanan.created_at', 'ASC')
            ->findAll();
    }
}
