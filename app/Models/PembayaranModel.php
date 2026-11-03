<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'idpembayaran';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'idpembayaran',
        'tglpembayaran',
        'transaksi_id',
        'total_bayar',
        'bayar',
        'kembalian',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    // Pembayaran + data transaksi dan pelanggan
    public function getDetail($idpembayaran = null)
    {
        $builder = $this->select('pembayaran.*, transaksi.tgltransaksi, transaksi.status_pesanan, pelanggan.namapelanggan')
            ->join('transaksi', 'transaksi.idtransaksi = pembayaran.transaksi_id', 'left')
            ->join('pelanggan', 'pelanggan.idpelanggan = transaksi.pelanggan_id', 'left');

        if ($idpembayaran !== null) {
            return $builder->where('pembayaran.idpembayaran', $idpembayaran)->first();
        }

        return $builder->orderBy('pembayaran.tglpembayaran', 'DESC')->findAll();
    }

    public function enumValues(string $field): array
    {
        $row = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE ?", [$field])->getRowArray();

        if (! $row || ! preg_match("/^enum\((.*)\)$/i", $row['Type'], $m)) {
            return [];
        }

        return array_map(fn($v) => trim($v, "'"), explode(',', $m[1]));
    }
}
