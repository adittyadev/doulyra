<?php

namespace App\Controllers;

use App\Models\AlamatPelangganModel;
use App\Models\DetailKeranjangModel;
use App\Models\DetailPesananModel;
use App\Models\KeranjangModel;
use App\Models\PembayaranModel;
use App\Models\PesananModel;
use App\Models\RiwayatStatusPesananModel;

class Checkout extends BaseController
{
    // Ubah sesuai kebijakan ongkir toko
    private const BIAYA_ANTAR = 0;

    public function index()
    {
        $idUser    = (int) session()->get('id_user');
        $keranjang = model(KeranjangModel::class)->getOrCreateByUser($idUser);
        $items     = model(DetailKeranjangModel::class)->getByKeranjang($keranjang['id_keranjang']);

        if (! $items) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout/index', [
            'items'       => $items,
            'total'       => array_sum(array_column($items, 'subtotal')),
            'biaya_antar' => self::BIAYA_ANTAR,
            'alamat'      => model(AlamatPelangganModel::class)->getByUser($idUser),
        ]);
    }

    public function proses()
    {
        $rules = [
            'metode_pengiriman' => 'required|in_list[ambil_sendiri,diantar]',
            'metode_pembayaran' => 'required|in_list[cash,qris,transfer_bank]',
            'catatan'           => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idUser    = (int) session()->get('id_user');
        $keranjang = model(KeranjangModel::class)->getOrCreateByUser($idUser);
        $items     = model(DetailKeranjangModel::class)->getByKeranjang($keranjang['id_keranjang']);

        if (! $items) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang masih kosong.');
        }

        $metodeKirim = $this->request->getPost('metode_pengiriman');
        $idAlamat    = null;

        if ($metodeKirim === 'diantar') {
            $alamat = model(AlamatPelangganModel::class)
                ->where(['id_alamat' => (int) $this->request->getPost('id_alamat'), 'id_user' => $idUser])
                ->first();

            if (! $alamat) {
                return redirect()->back()->withInput()->with('error', 'Pilih alamat pengiriman.');
            }

            $idAlamat = $alamat['id_alamat'];
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $baris = [];
            $total = 0;

            foreach ($items as $item) {
                // Kunci baris produk agar stok tidak diambil dua pesanan sekaligus
                $produk = $db->query(
                    'SELECT harga, stok, status_produk FROM produk WHERE id_produk = ? FOR UPDATE',
                    [$item['id_produk']]
                )->getRowArray();

                if (! $produk || $produk['status_produk'] !== 'tersedia' || $produk['stok'] < $item['jumlah']) {
                    throw new \RuntimeException('Stok "' . $item['nama_produk'] . '" tidak mencukupi. Silakan ubah jumlah di keranjang.');
                }

                $subtotal = (float) $produk['harga'] * $item['jumlah'];
                $total   += $subtotal;
                $baris[]  = [
                    'id_produk'    => $item['id_produk'],
                    'nama_produk'  => $item['nama_produk'],
                    'harga_satuan' => $produk['harga'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $subtotal,
                ];
            }

            $biaya      = $metodeKirim === 'diantar' ? self::BIAYA_ANTAR : 0;
            $grandTotal = $total + $biaya;
            $pesanan    = model(PesananModel::class);
            $kode       = $pesanan->generateKode();

            $idPesanan = $pesanan->insert([
                'kode_pesanan'      => $kode,
                'id_user'           => $idUser,
                'id_alamat'         => $idAlamat,
                'total_harga'       => $total,
                'biaya_pengiriman'  => $biaya,
                'diskon'            => 0,
                'grand_total'       => $grandTotal,
                'metode_pengiriman' => $metodeKirim,
                'catatan'           => $this->request->getPost('catatan') ?: null,
                'status_pesanan'    => 'menunggu_pembayaran',
            ], true);

            $baris = array_map(fn($b) => $b + ['id_pesanan' => $idPesanan], $baris);
            model(DetailPesananModel::class)->insertBatch($baris);

            foreach ($items as $item) {
                // Pada MySQL, assignment dievaluasi berurutan sehingga status memakai stok terbaru
                $db->query(
                    "UPDATE produk SET stok = stok - ?, status_produk = IF(stok <= 0, 'habis', status_produk) WHERE id_produk = ?",
                    [$item['jumlah'], $item['id_produk']]
                );
            }

            model(PembayaranModel::class)->insert([
                'id_pesanan'        => $idPesanan,
                'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
                'jumlah_bayar'      => $grandTotal,
                'status_pembayaran' => 'menunggu',
            ]);

            model(RiwayatStatusPesananModel::class)->catat($idPesanan, 'menunggu_pembayaran', 'Pesanan dibuat', $idUser);
            model(DetailKeranjangModel::class)->kosongkan($keranjang['id_keranjang']);

            $db->transCommit();
        } catch (\RuntimeException $e) {
            $db->transRollback();

            return redirect()->to('/keranjang')->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Checkout gagal: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Checkout gagal, silakan coba lagi.');
        }

        return redirect()->to('/pesanan/' . $kode)->with('success', 'Pesanan berhasil dibuat.');
    }
}
