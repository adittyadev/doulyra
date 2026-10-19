<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
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
        $pesanan = model(PesananModel::class);
        $status  = $this->request->getGet('status') ?: null;

        return view('admin/pesanan/index', [
            'pesanan' => $pesanan->getWithUser($status)->paginate(15),
            'pager'   => $pesanan->pager,
            'status'  => $status,
        ]);
    }

    public function detail(int $id)
    {
        $pesanan = $this->cari($id);

        return view('admin/pesanan/detail', [
            'pesanan'    => $pesanan,
            'items'      => model(DetailPesananModel::class)->getByPesanan($id),
            'pembayaran' => model(PembayaranModel::class)->getByPesanan($id),
            'riwayat'    => model(RiwayatStatusPesananModel::class)->getByPesanan($id),
            'alamat'     => $pesanan['id_alamat'] ? model(AlamatPelangganModel::class)->find($pesanan['id_alamat']) : null,
        ]);
    }

    public function ubahStatus(int $id)
    {
        $pesanan = $this->cari($id);

        if (! $this->validate(['status_pesanan' => 'required|in_list[diproses,siap_diambil,dikirim,selesai]'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $status = $this->request->getPost('status_pesanan');

        if (! in_array($pesanan['status_pesanan'], ['dibayar', 'diproses', 'siap_diambil', 'dikirim'], true)) {
            return redirect()->back()->with('error', 'Status pesanan ini tidak dapat diubah.');
        }

        if (($status === 'siap_diambil' && $pesanan['metode_pengiriman'] !== 'ambil_sendiri')
            || ($status === 'dikirim' && $pesanan['metode_pengiriman'] !== 'diantar')
        ) {
            return redirect()->back()->with('error', 'Status tidak sesuai metode pengiriman pesanan.');
        }

        (new PesananService())->ubahStatus($id, $status, (int) session()->get('id_user'), $this->request->getPost('keterangan') ?: null);

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    public function batalkan(int $id)
    {
        $pesanan = $this->cari($id);

        if (in_array($pesanan['status_pesanan'], ['selesai', 'dibatalkan'], true)) {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        (new PesananService())->batalkan($id, (int) session()->get('id_user'), $this->request->getPost('keterangan') ?: 'Dibatalkan oleh admin');

        return redirect()->back()->with('success', 'Pesanan dibatalkan.');
    }

    private function cari(int $id): array
    {
        $pesanan = model(PesananModel::class)
            ->select('pesanan.*, users.nama AS nama_pelanggan, users.no_hp AS hp_pelanggan')
            ->join('users', 'users.id_user = pesanan.id_user')
            ->find($id);

        if (! $pesanan) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $pesanan;
    }
}
