<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faktur <?= esc($transaksi['idtransaksi']) ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #212529;
            background: #f4f6f9;
            padding: 24px 12px
        }

        .faktur {
            max-width: 780px;
            margin: 0 auto;
            background: #fff;
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .1)
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #f5a900;
            padding-bottom: 14px;
            margin-bottom: 18px
        }

        .head h1 {
            font-size: 1.6rem;
            letter-spacing: 1px
        }

        .head .toko {
            font-size: 1.2rem;
            font-weight: 700;
            color: #d97706
        }

        .meta {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 18px
        }

        .meta table td {
            padding: 2px 10px 2px 0;
            vertical-align: top
        }

        .meta td:first-child {
            color: #6c757d
        }

        table.items {
            width: 100%;
            border-collapse: collapse
        }

        table.items th {
            background: #f8f9fa;
            text-align: left;
            padding: 8px 10px;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6
        }

        table.items td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee
        }

        .r {
            text-align: right !important
        }

        .c {
            text-align: center !important
        }

        .ringkas {
            margin-top: 14px;
            margin-left: auto;
            width: 300px
        }

        .ringkas .baris {
            display: flex;
            justify-content: space-between;
            padding: 4px 0
        }

        .ringkas .baris span:first-child {
            color: #6c757d
        }

        .ringkas .grand {
            border-top: 2px dashed #ced4da;
            margin-top: 6px;
            padding-top: 10px;
            font-size: 1.2rem;
            font-weight: 700
        }

        .ringkas .grand span:first-child {
            color: #212529
        }

        .ringkas .grand span:last-child {
            color: #d97706
        }

        .foot {
            text-align: center;
            color: #6c757d;
            margin-top: 28px;
            padding-top: 14px;
            border-top: 1px solid #eee
        }

        .aksi {
            max-width: 780px;
            margin: 16px auto 0;
            display: flex;
            gap: 8px;
            justify-content: center
        }

        .aksi button,
        .aksi a {
            border: 0;
            border-radius: 6px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none
        }

        .btn-cetak {
            background: #f5a900;
            color: #212529
        }

        .btn-tutup {
            background: #e9ecef;
            color: #212529
        }

        @media print {
            body {
                background: #fff;
                padding: 0
            }

            .faktur {
                box-shadow: none;
                border-radius: 0;
                max-width: none;
                padding: 0
            }

            .aksi {
                display: none
            }
        }
    </style>
</head>

<body>
    <?php
    $rp = fn($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');

    $metode = '-';
    if ($bayar) {
        $metode = strtolower($bayar['metode_pembayaran']) === 'cash' ? 'Cash' : strtoupper($bayar['metode_pembayaran']);
    }
    ?>

    <div class="faktur">
        <div class="head">
            <div>
                <div class="toko">Doulyra</div>
                <div style="color:#6c757d">Faktur Penjualan</div>
            </div>
            <h1>FAKTUR</h1>
        </div>

        <div class="meta">
            <table>
                <tr>
                    <td>No. Faktur</td>
                    <td><b><?= esc($transaksi['idtransaksi']) ?></b></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td><?= date('d-m-Y', strtotime($transaksi['tgltransaksi'])) ?></td>
                </tr>
                <tr>
                    <td>Status Pesanan</td>
                    <td><?= esc($transaksi['status_pesanan']) ?></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>Pelanggan</td>
                    <td><b><?= esc($pelanggan['namapelanggan'] ?? '-') ?></b></td>
                </tr>
                <tr>
                    <td>No HP</td>
                    <td><?= esc($pelanggan['no_hp'] ?? '-') ?: '-' ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td><?= esc($pelanggan['alamat'] ?? '-') ?: '-' ?></td>
                </tr>
            </table>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="40" class="c">No</th>
                    <th>Menu</th>
                    <th class="r">Harga</th>
                    <th class="c">Jumlah</th>
                    <th class="r">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $it) : ?>
                    <tr>
                        <td class="c"><?= $i + 1 ?></td>
                        <td><?= esc($it['nama']) ?> <small style="color:#6c757d">(<?= esc($it['jenis']) ?>)</small></td>
                        <td class="r"><?= $rp($it['harga']) ?></td>
                        <td class="c"><?= (int) $it['jumlah'] ?></td>
                        <td class="r"><?= $rp($it['subtotal']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="ringkas">
            <div class="baris grand"><span>Total</span><span><?= $rp($transaksi['totalbayar']) ?></span></div>
            <div class="baris"><span>Metode Pembayaran</span><span><?= esc($metode) ?></span></div>
            <?php if ($bayar) : ?>
                <?php if (strtolower($bayar['metode_pembayaran']) === 'cash') : ?>
                    <div class="baris"><span>Dibayar</span><span><?= $rp($bayar['bayar']) ?></span></div>
                    <div class="baris"><span>Kembalian</span><span><?= $rp($bayar['kembalian']) ?></span></div>
                <?php endif; ?>
                <div class="baris"><span>Status Pembayaran</span><span><?= esc(ucfirst($bayar['status_pembayaran'])) ?></span></div>
            <?php endif; ?>
        </div>

        <div class="foot">Terima kasih telah berbelanja di Doulyra</div>
    </div>

    <div class="aksi">
        <button type="button" class="btn-cetak" onclick="window.print()">Cetak Faktur</button>
        <a href="<?= base_url('transaksi') ?>" class="btn-tutup">Kembali</a>
    </div>
</body>

</html>