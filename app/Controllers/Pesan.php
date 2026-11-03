<?php

namespace App\Controllers;

use App\Models\MakananModel;
use App\Models\MinumanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pesan extends BaseController
{
    // Teks QRIS statis (hasil scan qris.png). Dipakai jika qris.statis di .env tidak terbaca.
    private const QRIS_STATIS = '00020101021126570011ID.DANA.WWW011893600915303338570402090333857040303UMI51440014ID.CO.QRIS.WWW0215ID10265528074260303UMI5204599553033605802ID5915Petshop Bigboss6011Kota Padang6105251216304CA1E';

    public function index($jenis, $id)
    {
        if (!session()->get('logged_in')) {
            session()->set('redirect_url', current_url());

            return redirect()->to(site_url('login'))->with('error', 'Silakan masuk dulu untuk memesan.');
        }

        helper('doulyra');

        $menu = [];
        foreach ((new MakananModel())->where('stok >', 0)->findAll() as $m) {
            $menu[] = [
                'jenis' => 'makanan',
                'id'    => $m['idmakanan'],
                'nama'  => $m['namamakanan'],
                'harga' => (int) $m['harga'],
                'stok'  => (int) $m['stok'],
                'foto'  => foto_url('makanan', $m['foto']),
            ];
        }
        foreach ((new MinumanModel())->where('stok >', 0)->findAll() as $m) {
            $menu[] = [
                'jenis' => 'minuman',
                'id'    => $m['idminuman'],
                'nama'  => $m['namaminuman'],
                'harga' => (int) $m['harga'],
                'stok'  => (int) $m['stok'],
                'foto'  => foto_url('minuman', $m['foto']),
            ];
        }

        $awal = $jenis . '|' . urldecode($id);
        if (!in_array($awal, array_map(fn($m) => $m['jenis'] . '|' . $m['id'], $menu), true)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('pesan/index', [
            'menu'       => $menu,
            'awal'       => $awal,
            'qrisStatis' => trim((string) env('qris.statis', '')) ?: self::QRIS_STATIS,
        ]);
    }

    private function wajibLogin()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') {
            return redirect()->to(site_url('login'))->with('error', 'Silakan masuk sebagai pelanggan.');
        }

        return null;
    }

    private function milik(string $id): array
    {
        $db  = db_connect();
        $trx = $db->table('transaksi')
            ->where('idtransaksi', $id)
            ->where('pelanggan_id', session()->get('pelanggan_id'))
            ->get()->getRowArray();

        if (!$trx) {
            throw PageNotFoundException::forPageNotFound();
        }

        $items = $db->table('detail_transaksi d')
            ->select("d.*, COALESCE(m.namamakanan, n.namaminuman) AS nama, COALESCE(m.foto, n.foto) AS foto, IF(d.makanan_id IS NULL, 'minuman', 'makanan') AS jenis", false)
            ->join('makanan m', 'm.idmakanan = d.makanan_id', 'left')
            ->join('minuman n', 'n.idminuman = d.minuman_id', 'left')
            ->where('d.transaksi_id', $id)
            ->get()->getResultArray();

        return [
            $trx,
            $items,
            $db->table('pembayaran')->where('transaksi_id', $id)->get()->getRowArray(),
        ];
    }

    public function simpan()
    {
        if ($r = $this->wajibLogin()) {
            return $r;
        }

        $metode = $this->request->getPost('metode');
        $items  = $this->request->getPost('items');

        if (!in_array($metode, ['cash', 'qris'], true) || !is_array($items) || !$items) {
            return redirect()->back()->with('error', 'Pesanan tidak valid.');
        }

        // QRIS wajib menyertakan bukti pembayaran (diunggah lewat modal QRIS)
        $bukti     = $this->request->getFile('bukti');
        $namaBukti = null;

        if ($metode === 'qris') {
            if (!$this->validate(['bukti' => 'uploaded[bukti]|is_image[bukti]|max_size[bukti,2048]'])) {
                return redirect()->back()->with('error', 'Unggah gambar bukti pembayaran (maksimal 2 MB).');
            }
            $namaBukti = $bukti->getRandomName();
        }

        $peta = [
            'makanan' => ['makanan', 'idmakanan', 'namamakanan'],
            'minuman' => ['minuman', 'idminuman', 'namaminuman'],
        ];

        $db    = db_connect();
        $baris = [];
        $total = 0;

        // Harga dan stok selalu diambil dari database, bukan dari kiriman browser
        foreach ($items as $it) {
            $jenis  = $it['jenis'] ?? '';
            $id     = $it['id'] ?? '';
            $jumlah = (int) ($it['jumlah'] ?? 0);

            if (!isset($peta[$jenis]) || $jumlah < 1) {
                return redirect()->back()->with('error', 'Pesanan tidak valid.');
            }

            [$tabel, $kid, $knama] = $peta[$jenis];
            $row = $db->table($tabel)->where($kid, $id)->get()->getRowArray();
            $k   = $jenis . '|' . $id;
            $jumlah += $baris[$k]['jumlah'] ?? 0;

            if (!$row || $jumlah > (int) $row['stok']) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk ' . ($row[$knama] ?? 'menu yang dipilih') . '.');
            }

            $baris[$k] = [
                'tabel'  => $tabel,
                'kid'    => $kid,
                'jenis'  => $jenis,
                'id'     => $id,
                'nama'   => $row[$knama],
                'foto'   => $row['foto'],
                'harga'  => (int) $row['harga'],
                'jumlah' => $jumlah,
            ];
        }

        foreach ($baris as $b) {
            $total += $b['harga'] * $b['jumlah'];
        }

        $kode  = date('ymdHis') . random_int(10, 99);
        $idTrx = 'TRX' . $kode;
        $galat = null;
        $ok    = false;

        try {
            $db->transStart();

            $ok = $db->table('transaksi')->insert([
                'idtransaksi'    => $idTrx,
                'tgltransaksi'   => date('Y-m-d'),
                'pelanggan_id'   => session()->get('pelanggan_id'),
                'totalbayar'     => $total,
                'status_pesanan' => 'Di Siapkan',
            ]);

            foreach ($baris as $b) {
                if (!$ok) {
                    break;
                }

                $ok = $db->table('detail_transaksi')->insert([
                    'transaksi_id' => $idTrx,
                    'makanan_id'   => $b['jenis'] === 'makanan' ? $b['id'] : null,
                    'minuman_id'   => $b['jenis'] === 'minuman' ? $b['id'] : null,
                    'jumlah'       => $b['jumlah'],
                    'harga'        => $b['harga'],
                    'subtotal'     => $b['harga'] * $b['jumlah'],
                ]);

                if ($ok) {
                    $ok = $db->table($b['tabel'])
                        ->set('stok', 'stok - ' . (int) $b['jumlah'], false)
                        ->where($b['kid'], $b['id'])
                        ->update();
                }
            }

            if ($ok) {
                $ok = $db->table('pembayaran')->insert([
                    'idpembayaran'      => 'PAY' . $kode,
                    'tglpembayaran'     => date('Y-m-d'),
                    'transaksi_id'      => $idTrx,
                    'total_bayar'       => $total,
                    'metode_pembayaran' => $metode,
                    'bukti_pembayaran'  => $namaBukti,
                    'status_pembayaran' => 'menunggu',
                ]);
            }

            if (!$ok) {
                $galat = $db->error()['message'];
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            $galat = $e->getMessage();
            $ok    = false;
        }

        if (!$ok || !$db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal simpan: ' . ($galat ?: 'penyebab tidak diketahui'));
        }

        // File bukti dipindahkan setelah data berhasil tersimpan
        if ($namaBukti) {
            $bukti->move(FCPATH . 'uploads/bukti', $namaBukti);
        }

        return redirect()->to(site_url('pesan/detail/' . $idTrx));
    }

    // Cadangan: halaman QRIS terpisah untuk pesanan QRIS yang belum punya bukti
    public function qris($id)
    {
        if ($r = $this->wajibLogin()) {
            return $r;
        }

        [$trx,, $bayar] = $this->milik($id);

        if ($bayar['metode_pembayaran'] !== 'qris' || $bayar['bukti_pembayaran']) {
            return redirect()->to(site_url('pesan/detail/' . $id));
        }

        return view('pesan/qris', ['title' => 'Pembayaran QRIS', 'trx' => $trx]);
    }

    public function bukti($id)
    {
        if ($r = $this->wajibLogin()) {
            return $r;
        }

        [,, $bayar] = $this->milik($id);

        if ($bayar['metode_pembayaran'] !== 'qris' || $bayar['bukti_pembayaran']) {
            return redirect()->to(site_url('pesan/detail/' . $id));
        }

        if (!$this->validate(['bukti' => 'uploaded[bukti]|is_image[bukti]|max_size[bukti,2048]'])) {
            return redirect()->back()->with('error', 'Unggah gambar bukti pembayaran (maksimal 2 MB).');
        }

        $file = $this->request->getFile('bukti');
        $nama = $file->getRandomName();
        $file->move(FCPATH . 'uploads/bukti', $nama);

        $db = db_connect();
        $db->transStart();
        $db->table('pembayaran')->where('transaksi_id', $id)
            ->update(['bukti_pembayaran' => $nama, 'status_pembayaran' => 'Menunggu Verifikasi']);
        $db->transComplete();

        return redirect()->to(site_url('pesan/detail/' . $id));
    }

    public function detail($id)
    {
        if ($r = $this->wajibLogin()) {
            return $r;
        }

        [$trx, $items, $bayar] = $this->milik($id);

        if ($bayar['metode_pembayaran'] === 'qris' && !$bayar['bukti_pembayaran']) {
            return redirect()->to(site_url('pesan/qris/' . $id));
        }

        helper('doulyra');

        return view('pesan/detail', ['title' => 'Detail Pesanan', 'trx' => $trx, 'items' => $items, 'bayar' => $bayar]);
    }

    public function status($id)
    {
        if ($r = $this->wajibLogin()) {
            return $r;
        }

        [$trx,, $bayar] = $this->milik($id);

        return $this->response->setJSON([
            'status' => $trx['status_pesanan'],
            'bayar'  => $bayar['status_pembayaran'],
        ]);
    }

    public function terima($id)
    {
        if ($r = $this->wajibLogin()) {
            return $r;
        }

        [$trx] = $this->milik($id);

        if ($trx['status_pesanan'] === 'Di Antar') {
            db_connect()->table('transaksi')->where('idtransaksi', $id)->update(['status_pesanan' => 'Di Terima']);
        }

        return redirect()->to(site_url('pesan/detail/' . $id));
    }
}
