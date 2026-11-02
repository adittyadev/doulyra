<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    // Status pembayaran yang dihitung sebagai pendapatan (sesuaikan dengan data Anda)
    private const STATUS_LUNAS = 'lunas';
    private const STATUS_VERIF = 'Menunggu Verifikasi';
    private const BATAS_STOK   = 5;

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'karyawan') {
            return redirect()->to(site_url('login'))->with('error', 'Akses khusus karyawan.');
        }

        helper('doulyra');

        $db    = db_connect();
        $hari  = date('Y-m-d');
        $bulan = date('Y-m');

        // Kartu ringkasan
        $total = [
            'pelanggan' => $db->table('pelanggan')->countAllResults(),
            'makanan'   => $db->table('makanan')->countAllResults(),
            'minuman'   => $db->table('minuman')->countAllResults(),
            'trx_hari'  => $db->table('transaksi')->where('tgltransaksi', $hari)->countAllResults(),
        ];

        $pendapatan = [
            'hari'  => $this->pendapatan($db, "p.tglpembayaran = '{$hari}'"),
            'bulan' => $this->pendapatan($db, "DATE_FORMAT(p.tglpembayaran, '%Y-%m') = '{$bulan}'"),
        ];

        // Perlu tindakan
        $perluVerifikasi = $db->table('pembayaran')->where('status_pembayaran', self::STATUS_VERIF)->countAllResults();

        $statusPesanan = ['Di Siapkan' => 0, 'Di Antar' => 0, 'Di Terima' => 0];
        $rows = $db->table('transaksi')
            ->select('status_pesanan, COUNT(*) AS jml', false)
            ->groupBy('status_pesanan')
            ->get()->getResultArray();
        foreach ($rows as $r) {
            $statusPesanan[$r['status_pesanan']] = (int) $r['jml'];
        }

        // Grafik penjualan 7 hari terakhir
        $awal  = date('Y-m-d', strtotime('-6 days'));
        $hasil = $db->table('transaksi')
            ->select('tgltransaksi, SUM(totalbayar) AS total', false)
            ->where('tgltransaksi >=', $awal)
            ->groupBy('tgltransaksi')
            ->get()->getResultArray();
        $peta = array_column($hasil, 'total', 'tgltransaksi');

        $grafik = ['label' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $tgl               = date('Y-m-d', strtotime("-{$i} days"));
            $grafik['label'][] = date('d M', strtotime($tgl));
            $grafik['data'][]  = (int) ($peta[$tgl] ?? 0);
        }

        // Transaksi terbaru
        $terbaru = $db->table('transaksi t')
            ->select('t.*, p.namapelanggan, b.metode_pembayaran, b.status_pembayaran')
            ->join('pelanggan p', 'p.idpelanggan = t.pelanggan_id', 'left')
            ->join('pembayaran b', 'b.transaksi_id = t.idtransaksi', 'left')
            ->orderBy('t.idtransaksi', 'DESC')
            ->limit(8)
            ->get()->getResultArray();

        // Menu terlaris
        $terlaris = $db->table('detail_transaksi d')
            ->select('COALESCE(m.namamakanan, n.namaminuman) AS nama, SUM(d.jumlah) AS terjual', false)
            ->join('makanan m', 'm.idmakanan = d.makanan_id', 'left')
            ->join('minuman n', 'n.idminuman = d.minuman_id', 'left')
            ->groupBy('nama')
            ->orderBy('terjual', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Stok menipis (makanan + minuman)
        $stok = [];
        foreach ($db->table('makanan')->select("namamakanan AS nama, stok, 'Makanan' AS jenis", false)->where('stok <=', self::BATAS_STOK)->get()->getResultArray() as $r) {
            $stok[] = $r;
        }
        foreach ($db->table('minuman')->select("namaminuman AS nama, stok, 'Minuman' AS jenis", false)->where('stok <=', self::BATAS_STOK)->get()->getResultArray() as $r) {
            $stok[] = $r;
        }
        usort($stok, static fn($a, $b) => $a['stok'] <=> $b['stok']);

        return view('dashboard/index', [
            'title'           => 'Dashboard',
            'total'           => $total,
            'pendapatan'      => $pendapatan,
            'perluVerifikasi' => $perluVerifikasi,
            'statusPesanan'   => $statusPesanan,
            'grafik'          => $grafik,
            'terbaru'         => $terbaru,
            'terlaris'        => $terlaris,
            'stok'            => array_slice($stok, 0, 8),
            'batasStok'       => self::BATAS_STOK,
        ]);
    }

    private function pendapatan($db, string $kondisi): int
    {
        $row = $db->table('pembayaran p')
            ->select('COALESCE(SUM(p.total_bayar), 0) AS total', false)
            ->where('p.status_pembayaran', self::STATUS_LUNAS)
            ->where($kondisi, null, false)
            ->get()->getRowArray();

        return (int) ($row['total'] ?? 0);
    }
}
