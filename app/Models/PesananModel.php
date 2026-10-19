<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'pesanan';
    protected $primaryKey       = 'id_pesanan';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'kode_pesanan',
        'id_user',
        'id_alamat',
        'tanggal_pesanan',
        'total_harga',
        'biaya_pengiriman',
        'diskon',
        'grand_total',
        'metode_pengiriman',
        'catatan',
        'status_pesanan',
    ];
    protected $useTimestamps = false;

    public function generateKode(): string
    {
        return 'INV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }

    public function getByUser(int $idUser): array
    {
        return $this->where('id_user', $idUser)
            ->orderBy('tanggal_pesanan', 'DESC')
            ->findAll();
    }

    /** Semua pesanan beserta nama pelanggan (untuk admin). */
    public function getWithUser(?string $status = null)
    {
        $builder = $this->select('pesanan.*, users.nama AS nama_pelanggan')
            ->join('users', 'users.id_user = pesanan.id_user');

        if ($status !== null) {
            $builder->where('pesanan.status_pesanan', $status);
        }

        return $builder->orderBy('pesanan.tanggal_pesanan', 'DESC');
    }

    /** Laporan penjualan per hari (pesanan yang tidak dibatalkan / belum dibayar). */
    public function laporanHarian(?string $dari = null, ?string $sampai = null): array
    {
        $builder = $this->select('DATE(tanggal_pesanan) AS tanggal, COUNT(*) AS jumlah_pesanan, SUM(grand_total) AS total_nilai_pesanan')
            ->whereIn('status_pesanan', ['dibayar', 'diproses', 'siap_diambil', 'dikirim', 'selesai']);

        if ($dari) {
            $builder->where('DATE(tanggal_pesanan) >=', $dari);
        }
        if ($sampai) {
            $builder->where('DATE(tanggal_pesanan) <=', $sampai);
        }

        return $builder->groupBy('DATE(tanggal_pesanan)')
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }
}
