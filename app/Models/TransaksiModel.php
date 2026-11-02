<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'idtransaksi';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'idtransaksi',
        'tgltransaksi',
        'pelanggan_id',
        'makanan_id',
        'minuman_id',
        'jumlah',
        'totalbayar',
        'status_pesanan',
    ];

    public function getDetail()
    {
        return $this->select("transaksi.*, pelanggan.namapelanggan,
            GROUP_CONCAT(CONCAT(COALESCE(makanan.namamakanan, minuman.namaminuman), ' x', detail_transaksi.jumlah)
                ORDER BY detail_transaksi.iddetail SEPARATOR '||') AS menu")
            ->join('pelanggan', 'pelanggan.idpelanggan = transaksi.pelanggan_id', 'left')
            ->join('detail_transaksi', 'detail_transaksi.transaksi_id = transaksi.idtransaksi', 'left')
            ->join('makanan', 'makanan.idmakanan = detail_transaksi.makanan_id', 'left')
            ->join('minuman', 'minuman.idminuman = detail_transaksi.minuman_id', 'left')
            ->groupBy('transaksi.idtransaksi, pelanggan.namapelanggan')
            ->orderBy('transaksi.idtransaksi', 'DESC')
            ->findAll();
    }
}
