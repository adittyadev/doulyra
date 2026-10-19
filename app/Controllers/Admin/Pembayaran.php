<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PesananService;
use App\Models\PembayaranModel;
use App\Models\PesananModel;
use App\Models\RiwayatStatusPesananModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pembayaran extends BaseController
{
    public function index()
    {
        $model  = model(PembayaranModel::class);
        $status = $this->request->getGet('status') ?: 'menunggu';
        $query  = $model->getForVerifikasi($status);

        if ($status === 'menunggu') {
            $query->where('pesanan.status_pesanan', 'menunggu_pembayaran');
        }

        return view('admin/pembayaran/index', [
            'pembayaran' => $query->paginate(15),
            'pager'      => $model->pager,
            'status'     => $status,
        ]);
    }

    public function verifikasi(int $id)
    {
        $model      = model(PembayaranModel::class);
        $pembayaran = $model->find($id);

        if (! $pembayaran) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate(['aksi' => 'required|in_list[setuju,tolak]'])) {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        $pesanan = model(PesananModel::class)->find($pembayaran['id_pesanan']);

        if ($pembayaran['status_pembayaran'] !== 'menunggu' || $pesanan['status_pesanan'] !== 'menunggu_pembayaran') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses atau pesanan tidak lagi menunggu pembayaran.');
        }

        $idAdmin    = (int) session()->get('id_user');
        $keterangan = $this->request->getPost('keterangan') ?: null;
        $db         = \Config\Database::connect();

        $db->transStart();

        if ($this->request->getPost('aksi') === 'setuju') {
            $model->update($id, [
                'status_pembayaran' => 'berhasil',
                'tanggal_bayar'     => $pembayaran['tanggal_bayar'] ?: date('Y-m-d H:i:s'),
                'diverifikasi_oleh' => $idAdmin,
            ]);

            (new PesananService())->ubahStatus($pesanan['id_pesanan'], 'dibayar', $idAdmin, 'Pembayaran diverifikasi');
            $pesan = 'Pembayaran diverifikasi.';
        } else {
            $model->update($id, [
                'status_pembayaran' => 'ditolak',
                'diverifikasi_oleh' => $idAdmin,
            ]);

            model(RiwayatStatusPesananModel::class)->catat(
                $pesanan['id_pesanan'],
                'menunggu_pembayaran',
                'Pembayaran ditolak' . ($keterangan ? ': ' . $keterangan : ''),
                $idAdmin
            );
            $pesan = 'Pembayaran ditolak. Pelanggan dapat mengunggah ulang bukti.';
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran.');
        }

        return redirect()->back()->with('success', $pesan);
    }
}
