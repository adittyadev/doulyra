<?php

namespace App\Controllers\Pimpinan;

use App\Controllers\BaseController;
use App\Models\PesananModel;

class Laporan extends BaseController
{
    public function index()
    {
        $dari   = $this->request->getGet('dari') ?: date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?: date('Y-m-d');

        $laporan = model(PesananModel::class)->laporanHarian($dari, $sampai);

        return view('pimpinan/laporan', [
            'laporan'       => $laporan,
            'dari'          => $dari,
            'sampai'        => $sampai,
            'total_pesanan' => array_sum(array_column($laporan, 'jumlah_pesanan')),
            'total_nilai'   => array_sum(array_column($laporan, 'total_nilai_pesanan')),
        ]);
    }
}
