<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PesananModel;
use App\Models\ProdukModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $pesanan = model(PesananModel::class);

        return view('admin/dashboard', [
            'pesanan_hari_ini'   => $pesanan->where('DATE(tanggal_pesanan)', date('Y-m-d'))->countAllResults(),
            'perlu_diproses'     => $pesanan->whereIn('status_pesanan', ['dibayar', 'diproses'])->countAllResults(),
            'perlu_verifikasi'   => model(PembayaranModel::class)
                ->where('status_pembayaran', 'menunggu')
                ->where('bukti_pembayaran IS NOT NULL', null, false)
                ->countAllResults(),
            'produk_habis'       => model(ProdukModel::class)->where('status_produk', 'habis')->countAllResults(),
        ]);
    }
}
