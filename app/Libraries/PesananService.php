<?php

namespace App\Libraries;

use App\Models\DetailPesananModel;
use App\Models\PesananModel;
use App\Models\RiwayatStatusPesananModel;

/**
 * Logika yang dipakai bersama oleh pelanggan dan admin.
 */
class PesananService
{
    /** Ubah status pesanan dan catat ke riwayat. */
    public function ubahStatus(int $idPesanan, string $status, ?int $oleh = null, ?string $keterangan = null): void
    {
        model(PesananModel::class)->update($idPesanan, ['status_pesanan' => $status]);
        model(RiwayatStatusPesananModel::class)->catat($idPesanan, $status, $keterangan, $oleh);
    }

    /** Batalkan pesanan: stok dikembalikan, pembayaran berhasil ditandai dikembalikan. */
    public function batalkan(int $idPesanan, ?int $oleh = null, ?string $keterangan = null): void
    {
        $db = \Config\Database::connect();
        $db->transStart();

        foreach (model(DetailPesananModel::class)->getByPesanan($idPesanan) as $item) {
            if ($item['id_produk']) {
                $db->query(
                    "UPDATE produk SET stok = stok + ?, status_produk = IF(status_produk = 'habis', 'tersedia', status_produk) WHERE id_produk = ?",
                    [$item['jumlah'], $item['id_produk']]
                );
            }
        }

        $db->table('pembayaran')
            ->where(['id_pesanan' => $idPesanan, 'status_pembayaran' => 'berhasil'])
            ->update(['status_pembayaran' => 'dikembalikan']);

        $this->ubahStatus($idPesanan, 'dibatalkan', $oleh, $keterangan);

        $db->transComplete();

        if (! $db->transStatus()) {
            throw new \RuntimeException('Gagal membatalkan pesanan.');
        }
    }
}
