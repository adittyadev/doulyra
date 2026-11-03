<?php

namespace App\Controllers;

use App\Models\DetailTransaksiModel;
use App\Models\MakananModel;
use App\Models\MinumanModel;
use App\Models\PelangganModel;
use App\Models\PembayaranModel;
use App\Models\TransaksiModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $detailModel;
    protected $pelangganModel;
    protected $makananModel;
    protected $minumanModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->transaksiModel  = new TransaksiModel();
        $this->detailModel     = new DetailTransaksiModel();
        $this->pelangganModel  = new PelangganModel();
        $this->makananModel    = new MakananModel();
        $this->minumanModel    = new MinumanModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    public function index()
    {
        $data['transaksi'] = $this->transaksiModel->getDetail();

        return view('transaksi/index', $data);
    }

    public function create()
    {
        return view('transaksi/create', $this->dataPilihan() + [
            'qrisStatis' => config('Qris')->statis,
        ]);
    }

    public function store()
    {
        if (! $this->validate($this->rulesStore())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->kumpulItem();

        if (! $items) {
            return redirect()->back()->withInput()->with('errors', ['Pilih minimal satu makanan atau minuman']);
        }

        $total  = (int) array_sum(array_column($items, 'subtotal'));
        $metode = $this->request->getPost('metode_pembayaran');

        // Hitung bayar, kembalian, dan status pembayaran sesuai metode
        if (strtolower($metode) === 'cash') {
            $bayar = (int) $this->request->getPost('bayar');

            if ($bayar < $total) {
                return redirect()->back()->withInput()->with('errors', ['Uang yang dibayar kurang dari total']);
            }

            $kembalian = $bayar - $total;
            $status    = 'lunas';      // sesuaikan dengan enum status_pembayaran
        } elseif (strtolower($metode) === 'qris') {
            if ($this->request->getPost('konfirmasi_qris') !== '1') {
                return redirect()->back()->withInput()->with('errors', ['Konfirmasi pembayaran QRIS terlebih dahulu']);
            }

            $bayar     = $total;
            $kembalian = 0;
            $status    = 'lunas';      // sesuaikan dengan enum status_pembayaran
        } else {
            $bayar     = $total;
            $kembalian = 0;
            $status    = 'menunggu';
        }

        $id = $this->generateId();

        $db = db_connect();
        $db->transStart();

        $this->transaksiModel->insert([
            'idtransaksi'    => $id,
            'tgltransaksi'   => $this->request->getPost('tgltransaksi'),
            'pelanggan_id'   => $this->request->getPost('pelanggan_id'),
            'totalbayar'     => $total,
            'status_pesanan' => $this->request->getPost('status_pesanan'),
        ]);
        $this->simpanDetail($id, $items);

        $this->pembayaranModel->insert([
            'idpembayaran'      => $this->generateIdPembayaran(),
            'tglpembayaran'     => date('Y-m-d'),
            'transaksi_id'      => $id,
            'total_bayar'       => $total,
            'bayar'             => $bayar,
            'kembalian'         => $kembalian,
            'metode_pembayaran' => $metode,
            'status_pembayaran' => $status,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('errors', ['Transaksi gagal disimpan']);
        }

        return redirect()->to('/transaksi')->with('success', 'Data transaksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $transaksi = $this->transaksiModel->find($id);

        if (! $transaksi) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }

        return view('transaksi/edit', [
            'transaksi' => $transaksi,
            'jumlah'    => $this->jumlahTersimpan($id),
        ] + $this->dataPilihan());
    }

    public function update($id)
    {
        if (! $this->transaksiModel->find($id)) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->kumpulItem();

        if (! $items) {
            return redirect()->back()->withInput()->with('errors', ['Pilih minimal satu makanan atau minuman']);
        }

        $db = db_connect();
        $db->transStart();

        $this->transaksiModel->update($id, [
            'tgltransaksi'   => $this->request->getPost('tgltransaksi'),
            'pelanggan_id'   => $this->request->getPost('pelanggan_id'),
            'totalbayar'     => array_sum(array_column($items, 'subtotal')),
            'status_pesanan' => $this->request->getPost('status_pesanan'),
        ]);
        $this->detailModel->where('transaksi_id', $id)->delete();
        $this->simpanDetail($id, $items);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('errors', ['Transaksi gagal diubah']);
        }

        return redirect()->to('/transaksi')->with('success', 'Data transaksi berhasil diubah');
    }

    public function delete($id)
    {
        if (! $this->transaksiModel->find($id)) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }

        // detail_transaksi ikut terhapus (ON DELETE CASCADE)
        try {
            $this->transaksiModel->delete($id);
        } catch (DatabaseException $e) {
            return redirect()->to('/transaksi')->with('error', 'Transaksi tidak bisa dihapus karena sudah memiliki data pembayaran');
        }

        return redirect()->to('/transaksi')->with('success', 'Data transaksi berhasil dihapus');
    }

    // Aturan dasar (dipakai store dan update)
    private function rules(): array
    {
        return [
            'tgltransaksi'   => 'required|valid_date[Y-m-d]',
            'pelanggan_id'   => 'required|is_not_unique[pelanggan.idpelanggan]',
            'status_pesanan' => 'required|in_list[Di Siapkan,Di Antar,Di Terima]',
        ];
    }

    // Aturan khusus tambah transaksi: ditambah metode pembayaran
    private function rulesStore(): array
    {
        $metode = implode(',', $this->pembayaranModel->enumValues('metode_pembayaran'));

        return $this->rules() + [
            'metode_pembayaran' => 'required|in_list[' . $metode . ']',
        ];
    }

    // Data untuk form create dan edit
    private function dataPilihan(): array
    {
        return [
            'pelanggan' => $this->pelangganModel->findAll(),
            'makanan'   => $this->makananModel->findAll(),
            'minuman'   => $this->minumanModel->findAll(),
            'metode'    => $this->pembayaranModel->enumValues('metode_pembayaran'),
        ];
    }

    private function kumpulItem(): array
    {
        $sumber = [
            'makanan' => $this->makananModel,
            'minuman' => $this->minumanModel,
        ];
        $items = [];

        foreach ($sumber as $field => $model) {
            foreach ((array) $this->request->getPost($field) as $id => $qty) {
                $qty = (int) $qty;
                $row = $qty > 0 ? $model->find((string) $id) : null;

                if (! $row) {
                    continue;
                }

                $items[] = [
                    'makanan_id' => $field === 'makanan' ? (string) $id : null,
                    'minuman_id' => $field === 'minuman' ? (string) $id : null,
                    'jumlah'     => $qty,
                    'harga'      => $row['harga'],
                    'subtotal'   => (int) ($row['harga'] * $qty),
                ];
            }
        }

        return $items;
    }

    private function simpanDetail(string $transaksiId, array $items): void
    {
        foreach ($items as &$item) {
            $item['transaksi_id'] = $transaksiId;
        }

        $this->detailModel->insertBatch($items);
    }

    // Jumlah tersimpan per item untuk form edit: ['makanan' => [id => jumlah], 'minuman' => [id => jumlah]]
    private function jumlahTersimpan(string $transaksiId): array
    {
        $hasil = ['makanan' => [], 'minuman' => []];

        foreach ($this->detailModel->where('transaksi_id', $transaksiId)->findAll() as $d) {
            if ($d['makanan_id']) {
                $hasil['makanan'][$d['makanan_id']] = (int) $d['jumlah'];
            } else {
                $hasil['minuman'][$d['minuman_id']] = (int) $d['jumlah'];
            }
        }

        return $hasil;
    }

    private function generateId(): string
    {
        $prefix = 'TRX' . date('Ymd');
        $last   = $this->transaksiModel->like('idtransaksi', $prefix, 'after')->selectMax('idtransaksi')->first()['idtransaksi'] ?? null;
        $next   = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    private function generateIdPembayaran(): string
    {
        $prefix = 'PAY' . date('Ymd');
        $last   = $this->pembayaranModel->like('idpembayaran', $prefix, 'after')->selectMax('idpembayaran')->first()['idpembayaran'] ?? null;
        $next   = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    // Faktur transaksi (halaman siap cetak)
    public function faktur($id)
    {
        $transaksi = $this->transaksiModel->find($id);

        if (! $transaksi) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }

        $items = db_connect()->table('detail_transaksi d')
            ->select("d.*, COALESCE(m.namamakanan, n.namaminuman) AS nama, IF(d.makanan_id IS NULL, 'Minuman', 'Makanan') AS jenis", false)
            ->join('makanan m', 'm.idmakanan = d.makanan_id', 'left')
            ->join('minuman n', 'n.idminuman = d.minuman_id', 'left')
            ->where('d.transaksi_id', $id)
            ->get()->getResultArray();

        return view('transaksi/faktur', [
            'transaksi' => $transaksi,
            'pelanggan' => $this->pelangganModel->find($transaksi['pelanggan_id']),
            'items'     => $items,
            'bayar'     => $this->pembayaranModel->where('transaksi_id', $id)->first(),
        ]);
    }

    public function ubahStatus($id)
    {
        if (! $this->transaksiModel->find($id)) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak ditemukan');
        }

        if (! $this->validate(['status_pesanan' => 'required|in_list[Di Siapkan,Di Antar,Di Terima]'])) {
            return redirect()->to('/transaksi')->with('error', 'Status tidak valid');
        }

        $this->transaksiModel->update($id, ['status_pesanan' => $this->request->getPost('status_pesanan')]);

        return redirect()->to('/transaksi')->with('success', 'Status pesanan berhasil diubah');
    }
}
