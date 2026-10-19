<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id_pembayaran';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_pesanan',
        'metode_pembayaran',
        'jumlah_bayar',
        'bukti_pembayaran',
        'tanggal_bayar',
        'status_pembayaran',
        'diverifikasi_oleh',
    ];
    protected $useTimestamps = false;

    public function getByPesanan(int $idPesanan): array
    {
        return $this->where('id_pesanan', $idPesanan)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /** Pembayaran beserta kode pesanan dan nama pelanggan (untuk verifikasi admin). */
    public function getForVerifikasi(?string $status = null)
    {
        $builder = $this->select('pembayaran.*, pesanan.kode_pesanan, pesanan.grand_total, users.nama AS nama_pelanggan')
            ->join('pesanan', 'pesanan.id_pesanan = pembayaran.id_pesanan')
            ->join('users', 'users.id_user = pesanan.id_user');

        if ($status !== null) {
            $builder->where('pembayaran.status_pembayaran', $status);
        }

        return $builder->orderBy('pembayaran.created_at', 'DESC');
    }
}
