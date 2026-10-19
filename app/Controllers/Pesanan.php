<?php

namespace App\Controllers;

use App\Libraries\PesananService;
use App\Models\AlamatPelangganModel;
use App\Models\DetailPesananModel;
use App\Models\PembayaranModel;
use App\Models\PesananModel;
use App\Models\RiwayatStatusPesananModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pesanan extends BaseController
{
    public function index()
    {
        return view('pesanan/index', [
            'pesanan' => model(PesananModel::class)->getByUser((int) session()->get('id_user')),
        ]);
    }

    public function detail(string $kode)
    {
        $pesanan = $this->milikUser($kode);

        return view('pesanan/detail', [
            'pesanan'    => $pesanan,
            'items'      => model(DetailPesananModel::class)->getByPesanan($pesanan['id_pesanan']),
            'pembayaran' => $this->pembayaranTerakhir($pesanan['id_pesanan']),
            'riwayat'    => model(RiwayatStatusPesananModel::class)->getByPesanan($pesanan['id_pesanan']),
            'alamat'     => $pesanan['id_alamat'] ? model(AlamatPelangganModel::class)->find($pesanan['id_alamat']) : null,
        ]);
    }

    /** Unggah bukti pembayaran (QRIS / transfer). */
    public function bayar(string $kode)
    {
        $pesanan    = $this->milikUser($kode);
        $pembayaran = $this->pembayaranTerakhir($pesanan['id_pesanan']);

        if (
            $pesanan['status_pesanan'] !== 'menunggu_pembayaran'
            || ! $pembayaran
            || $pembayaran['metode_pembayaran'] === 'cash'
            || $pembayaran['status_pembayaran'] === 'berhasil'
        ) {
            return redirect()->back()->with('error', 'Bukti pembayaran tidak dapat diunggah untuk pesanan ini.');
        }

        $rules = [
            'bukti' => 'uploaded[bukti]|is_image[bukti]|mime_in[bukti,image/jpg,image/jpeg,image/png,image/webp]|max_size[bukti,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('bukti');
        $nama = $file->getRandomName();
        $file->move(FCPATH . 'uploads/bukti', $nama);

        if ($pembayaran['bukti_pembayaran']) {
            @unlink(FCPATH . 'uploads/bukti/' . $pembayaran['bukti_pembayaran']);
        }

        model(PembayaranModel::class)->update($pembayaran['id_pembayaran'], [
            'bukti_pembayaran'  => $nama,
            'tanggal_bayar'     => date('Y-m-d H:i:s'),
            'status_pembayaran' => 'menunggu',
        ]);

        model(RiwayatStatusPesananModel::class)->catat(
            $pesanan['id_pesanan'],
            'menunggu_pembayaran',
            'Bukti pembayaran diunggah',
            (int) session()->get('id_user')
        );

        return redirect()->to('/pesanan/' . $kode)->with('success', 'Bukti pembayaran terkirim, menunggu verifikasi admin.');
    }

    public function batalkan(string $kode)
    {
        $pesanan = $this->milikUser($kode);

        if ($pesanan['status_pesanan'] !== 'menunggu_pembayaran') {
            return redirect()->back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan sendiri. Hubungi admin.');
        }

        (new PesananService())->batalkan($pesanan['id_pesanan'], (int) session()->get('id_user'), 'Dibatalkan oleh pelanggan');

        return redirect()->to('/pesanan/' . $kode)->with('success', 'Pesanan dibatalkan.');
    }

    private function milikUser(string $kode): array
    {
        $pesanan = model(PesananModel::class)
            ->where(['kode_pesanan' => $kode, 'id_user' => (int) session()->get('id_user')])
            ->first();

        if (! $pesanan) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $pesanan;
    }

    private function pembayaranTerakhir(int $idPesanan): ?array
    {
        return model(PembayaranModel::class)
            ->where('id_pesanan', $idPesanan)
            ->orderBy('id_pembayaran', 'DESC')
            ->first();
    }
}
