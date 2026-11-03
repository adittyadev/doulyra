<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .trx-title {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 1rem;
    }

    .trx-title i {
        color: #f5a900;
        margin-right: .4rem;
    }

    /* Kartu menu di modal */
    .product-box {
        height: 100%;
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: all .15s ease;
        display: flex;
        flex-direction: column;
    }

    .product-box.active {
        border-color: #f5a900;
        box-shadow: 0 0 0 3px rgba(245, 169, 0, .2);
    }

    .product-box.habis {
        opacity: .5;
    }

    .product-img {
        height: 110px;
        background: #f4f6f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ced4da;
        font-size: 2.5rem;
    }

    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info {
        padding: .6rem .8rem;
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        font-size: .9rem;
        line-height: 1.25;
        margin-bottom: .2rem;
    }

    .product-price {
        color: #d97706;
        font-weight: 700;
    }

    .product-stock {
        font-size: .75rem;
        color: #6c757d;
    }

    .product-action {
        padding: 0 .8rem .8rem;
    }

    /* Jumlah */
    .qty {
        display: inline-flex;
        align-items: center;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .qty button {
        border: 0;
        background: #f4f6f9;
        width: 34px;
        height: 34px;
        font-size: 1rem;
    }

    .qty button:hover:not(:disabled) {
        background: #e9ecef;
    }

    .qty input,
    .qty span {
        width: 48px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        border: 0;
        border-left: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        -moz-appearance: textfield;
    }

    .qty input::-webkit-outer-spin-button,
    .qty input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Ringkasan */
    .summary {
        position: sticky;
        top: 70px;
    }

    .summary .line {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: .4rem 0;
        font-size: .9rem;
    }

    .summary .line span:first-child {
        color: #6c757d;
    }

    .summary .line span:last-child {
        text-align: right;
        font-weight: 500;
        white-space: nowrap;
    }

    .summary .total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed #ced4da;
        margin-top: .5rem;
        padding-top: .9rem;
    }

    .summary .total strong {
        font-size: 1.4rem;
        color: #d97706;
    }

    .btn-order {
        background: #f5a900;
        border-color: #f5a900;
        color: #212529;
        font-weight: 600;
        padding: .7rem;
        border-radius: 8px;
    }

    .btn-order:hover {
        background: #dd9700;
        border-color: #dd9700;
        color: #212529;
    }

    .btn-order:disabled {
        background: #e9ecef;
        border-color: #e9ecef;
        color: #adb5bd;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 600;
    }

    .nav-tabs .nav-link.active {
        color: #d97706;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    /* Modal pelanggan */
    .pelanggan-foto {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: #f4f6f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ced4da;
    }

    .btn-pilih {
        background: #f5a900;
        border-color: #f5a900;
        color: #212529;
        font-weight: 600;
    }

    .btn-pilih:hover {
        background: #dd9700;
        border-color: #dd9700;
        color: #212529;
    }

    /* Modal QRIS */
    #qrisKode img,
    #qrisKode canvas {
        display: block;
    }
</style>

<?php
$oldPelanggan  = (string) old('pelanggan_id');
$namaPelanggan = '';
foreach ($pelanggan as $p) {
    if ((string) $p['idpelanggan'] === $oldPelanggan) {
        $namaPelanggan = $p['namapelanggan'];
    }
}

// Konfigurasi dua tab menu (nama field sesuai tabel makanan & minuman)
$menu = [
    'makanan' => ['label' => 'Makanan', 'icon' => 'fa-hamburger',     'data' => $makanan, 'id' => 'idmakanan', 'nama' => 'namamakanan', 'old' => (array) old('makanan')],
    'minuman' => ['label' => 'Minuman', 'icon' => 'fa-glass-whiskey', 'data' => $minuman, 'id' => 'idminuman', 'nama' => 'namaminuman', 'old' => (array) old('minuman')],
];
?>

<form action="<?= base_url('transaksi/store') ?>" method="post">
    <?= csrf_field() ?>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- ============ KIRI ============ -->
        <div class="col-lg-8">

            <!-- Informasi pesanan -->
            <div class="card">
                <div class="card-body">
                    <div class="trx-title"><i class="fas fa-user"></i>Informasi Pesanan</div>
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label>Pelanggan</label>
                            <input type="hidden" name="pelanggan_id" id="pelangganId" value="<?= esc($oldPelanggan) ?>">
                            <div class="input-group">
                                <input type="text" id="pelangganNama" class="form-control" placeholder="Belum ada pelanggan dipilih"
                                    value="<?= esc($namaPelanggan) ?>" readonly style="cursor:pointer;background:#fff"
                                    data-toggle="modal" data-target="#modalPelanggan">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalPelanggan">
                                        <i class="fas fa-search"></i> Pilih
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 form-group">
                            <label>Tanggal Transaksi</label>
                            <input type="date" name="tgltransaksi" class="form-control" value="<?= old('tgltransaksi', date('Y-m-d')) ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu yang dipilih -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="trx-title mb-0"><i class="fas fa-utensils"></i>Menu Pesanan</div>
                        <button type="button" class="btn btn-warning font-weight-bold" data-toggle="modal" data-target="#modalMenu">
                            <i class="fas fa-plus"></i> Pilih Menu
                        </button>
                    </div>

                    <div id="kosongMenu" class="text-center text-muted py-4">
                        <i class="fas fa-shopping-basket fa-2x mb-2"></i><br>
                        Belum ada menu dipilih
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 d-none" id="tabelMenu">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="daftarMenu"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============ KANAN: ringkasan ============ -->
        <div class="col-lg-4">
            <div class="card summary">
                <div class="card-body">
                    <div class="trx-title"><i class="fas fa-receipt"></i>Ringkasan Pesanan</div>

                    <div class="form-group">
                        <label>Status Pesanan</label>
                        <select name="status_pesanan" class="form-control" required>
                            <?php foreach (['Di Siapkan', 'Di Antar', 'Di Terima'] as $s) : ?>
                                <option value="<?= $s ?>" <?= old('status_pesanan') == $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode" class="form-control" required>
                            <option value="">-- Pilih metode --</option>
                            <?php foreach ($metode as $m) : ?>
                                <option value="<?= esc($m) ?>" <?= old('metode_pembayaran') == $m ? 'selected' : '' ?>>
                                    <?= strtolower($m) === 'cash' ? 'Cash' : strtoupper($m) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Khusus cash -->
                    <div id="boxCash" class="d-none">
                        <div class="form-group">
                            <label>Uang Dibayar</label>
                            <input type="number" name="bayar" id="inputBayar" class="form-control" min="0" value="<?= esc(old('bayar')) ?>">
                        </div>
                        <div class="line d-flex justify-content-between mb-2">
                            <span class="text-muted">Kembalian</span>
                            <strong id="sumKembalian">Rp 0</strong>
                        </div>
                    </div>

                    <!-- Khusus QRIS: status konfirmasi + tombol buka modal -->
                    <div id="boxQris" class="d-none">
                        <input type="hidden" name="konfirmasi_qris" id="konfirmasiQris" value="0">
                        <div class="border rounded p-2 mb-3 d-flex justify-content-between align-items-center small">
                            <span id="teksQris" class="text-muted">Pembayaran QRIS belum dikonfirmasi</span>
                            <button type="button" class="btn btn-sm btn-outline-warning ml-2" data-toggle="modal" data-target="#modalQris">
                                <i class="fas fa-qrcode"></i> QRIS
                            </button>
                        </div>
                    </div>

                    <hr>
                    <div id="sumItems"></div>
                    <div class="total">
                        <span>Total Bayar</span>
                        <strong id="sumTotal">Rp 0</strong>
                    </div>

                    <div id="hint" class="small text-muted mt-3">
                        <i class="fas fa-info-circle"></i> <span id="hintText"></span>
                    </div>

                    <button type="submit" id="btnSubmit" class="btn btn-order btn-block mt-3" disabled>
                        <i class="fas fa-check-circle"></i> Buat Transaksi
                    </button>
                    <a href="<?= base_url('transaksi') ?>" class="btn btn-light btn-block">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL: pilih menu (input jumlah ikut terkirim bersama form) ============ -->
    <div class="modal fade" id="modalMenu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header pb-0 border-bottom-0 d-block">
                    <div class="d-flex justify-content-between">
                        <h5 class="modal-title"><i class="fas fa-utensils mr-1"></i> Pilih Menu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span>&times;</span></button>
                    </div>

                    <ul class="nav nav-tabs mt-3" role="tablist">
                        <?php $first = true;
                        foreach ($menu as $jenis => $cfg) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $first ? 'active' : '' ?>" data-toggle="tab" href="#tab-<?= $jenis ?>" role="tab">
                                    <i class="fas <?= $cfg['icon'] ?>"></i> <?= $cfg['label'] ?>
                                    <span class="badge badge-warning d-none" id="badge-<?= $jenis ?>">0</span>
                                </a>
                            </li>
                        <?php $first = false;
                        endforeach; ?>
                    </ul>
                </div>

                <div class="modal-body">
                    <div class="tab-content">
                        <?php $first = true;
                        foreach ($menu as $jenis => $cfg) : ?>
                            <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="tab-<?= $jenis ?>" role="tabpanel">
                                <div class="row">
                                    <?php foreach ($cfg['data'] as $m) :
                                        $id    = $m[$cfg['id']];
                                        $stok  = (int) $m['stok'];
                                        $habis = $stok <= 0;
                                        $qty   = $habis ? 0 : min($stok, (int) ($cfg['old'][$id] ?? 0));
                                    ?>
                                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                                            <div class="product-box <?= $habis ? 'habis' : '' ?>">
                                                <div class="product-img">
                                                    <?php if ($m['foto']) : ?>
                                                        <img src="<?= base_url('uploads/' . $jenis . '/' . $m['foto']) ?>" alt="<?= esc($m[$cfg['nama']]) ?>">
                                                    <?php else : ?>
                                                        <i class="fas <?= $cfg['icon'] ?>"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="product-info">
                                                    <div class="product-name"><?= esc($m[$cfg['nama']]) ?></div>
                                                    <div class="product-price">Rp <?= number_format($m['harga'], 0, ',', '.') ?></div>
                                                    <div class="product-stock">
                                                        <?= $habis ? '<span class="text-danger">Habis</span>' : 'Stok: ' . $stok ?>
                                                    </div>
                                                </div>
                                                <div class="product-action">
                                                    <div class="qty">
                                                        <button type="button" class="menu-btn" data-act="minus" <?= $habis ? 'disabled' : '' ?>>&minus;</button>
                                                        <input type="number" class="menu-qty" name="<?= $jenis ?>[<?= esc($id) ?>]"
                                                            min="0" max="<?= $stok ?>" value="<?= $qty ?>"
                                                            data-jenis="<?= $jenis ?>" data-nama="<?= esc($m[$cfg['nama']]) ?>"
                                                            data-harga="<?= (int) $m['harga'] ?>" data-stok="<?= $stok ?>"
                                                            <?= $habis ? 'disabled' : '' ?>>
                                                        <button type="button" class="menu-btn" data-act="plus" <?= $habis ? 'disabled' : '' ?>>+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php if (empty($cfg['data'])) : ?>
                                        <div class="col-12 text-center text-muted py-4">Belum ada data <?= strtolower($cfg['label']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php $first = false;
                        endforeach; ?>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="mr-auto"><span id="modalInfo" class="text-muted">Belum ada menu dipilih</span></div>
                    <button type="button" class="btn btn-pilih" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- ============ MODAL: pilih pelanggan ============ -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user mr-1"></i> Pilih Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Tambah pelanggan baru (cukup nama) -->
                <div class="border rounded p-3 mb-3 bg-light">
                    <label class="small font-weight-bold mb-1"><i class="fas fa-user-plus"></i> Pelanggan baru</label>
                    <div class="input-group">
                        <input type="text" id="namaBaru" class="form-control" placeholder="Ketik nama pelanggan baru" maxlength="100">
                        <div class="input-group-append">
                            <button type="button" id="btnSimpanBaru" class="btn btn-warning font-weight-bold">Simpan &amp; Pilih</button>
                        </div>
                    </div>
                    <small id="pesanBaru" class="text-danger"></small>
                </div>

                <input type="text" id="cariPelanggan" class="form-control mb-3" placeholder="Cari nama, no HP, atau alamat...">

                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="60">Foto</th>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th width="90"></th>
                        </tr>
                    </thead>
                    <tbody id="listPelanggan">
                        <?php foreach ($pelanggan as $p) : ?>
                            <tr>
                                <td>
                                    <?php if ($p['foto']) : ?>
                                        <img src="<?= base_url('uploads/pelanggan/' . $p['foto']) ?>" class="pelanggan-foto" alt="">
                                    <?php else : ?>
                                        <span class="pelanggan-foto"><i class="fas fa-user"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($p['namapelanggan']) ?></td>
                                <td><?= esc($p['no_hp']) ?></td>
                                <td><?= esc($p['alamat']) ?></td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-sm btn-pilih" data-dismiss="modal"
                                        data-id="<?= $p['idpelanggan'] ?>" data-nama="<?= esc($p['namapelanggan']) ?>">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div id="kosongPelanggan" class="text-center text-muted py-4 <?= $pelanggan ? 'd-none' : '' ?>">
                    Pelanggan tidak ditemukan.
                    <a href="<?= base_url('pelanggan/create') ?>">Tambah pelanggan baru</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL: QRIS + konfirmasi pembayaran ============ -->
<div class="modal fade" id="modalQris" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-qrcode mr-1"></i> Pembayaran QRIS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <div class="text-muted small">Total bayar</div>
                <div class="h3 font-weight-bold mb-3" style="color:#d97706" id="totalQris">Rp 0</div>

                <?php if ($qrisStatis) : ?>
                    <div id="qrisKode" class="d-inline-block border rounded p-2 bg-white"></div>
                <?php else : ?>
                    <img src="<?= base_url('uploads/qris.png') ?>" alt="QRIS" class="img-fluid border rounded" style="max-width:280px">
                    <div class="small text-warning mt-2">QRIS statis belum diatur, nominal harus diketik manual oleh pelanggan.</div>
                <?php endif; ?>

                <p class="small text-muted mt-3 mb-0">
                    Minta pelanggan memindai QR ini. Setelah pembayaran diterima, klik <b>Konfirmasi Pembayaran</b>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning font-weight-bold" id="btnKonfirmasiQris">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    (function() {
        var pelangganId = document.getElementById('pelangganId');
        var pelangganNama = document.getElementById('pelangganNama');
        var inputs = Array.prototype.slice.call(document.querySelectorAll('.menu-qty'));
        var JENIS = {
            makanan: 'Makanan',
            minuman: 'Minuman'
        };
        var metode = document.getElementById('metode');
        var inputBayar = document.getElementById('inputBayar');
        var boxCash = document.getElementById('boxCash');
        var boxQris = document.getElementById('boxQris');
        var konfirmasi = document.getElementById('konfirmasiQris');
        var teksQris = document.getElementById('teksQris');
        var btnKonfirmasi = document.getElementById('btnKonfirmasiQris');
        var totalSekarang = 0;

        function rp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function el(tag, cls, text) {
            var e = document.createElement(tag);
            if (cls) e.className = cls;
            if (text !== undefined) e.textContent = text;
            return e;
        }

        // ===== QRIS dinamis: nominal otomatis terisi =====
        var QRIS_STATIS = <?= json_encode($qrisStatis) ?>;
        var qrisObj = null;

        function crc16(s) {
            var c = 0xFFFF;
            for (var i = 0; i < s.length; i++) {
                c ^= s.charCodeAt(i) << 8;
                for (var j = 0; j < 8; j++) c = (c & 0x8000) ? ((c << 1) ^ 0x1021) : (c << 1);
                c &= 0xFFFF;
            }
            return c.toString(16).toUpperCase().padStart(4, '0');
        }

        // Ubah QRIS statis menjadi dinamis dengan nominal tertentu
        function qrisDinamis(statis, nominal) {
            var tlv = [];
            for (var i = 0; i < statis.length;) {
                var len = parseInt(statis.substr(i + 2, 2), 10);
                tlv.push([statis.substr(i, 2), statis.substr(i + 4, len)]);
                i += 4 + len;
            }

            var data = tlv
                .filter(function(t) {
                    return t[0] !== '54' && t[0] !== '63'; // buang nominal & CRC lama
                })
                .map(function(t) {
                    return t[0] === '01' ? [t[0], '12'] : t; // 11 = statis, 12 = dinamis
                });
            data.push(['54', String(Math.round(nominal))]);
            data.sort(function(a, b) {
                return a[0].localeCompare(b[0]);
            });

            var p = data.map(function(t) {
                return t[0] + String(t[1].length).padStart(2, '0') + t[1];
            }).join('') + '6304';
            return p + crc16(p);
        }

        function buatQris(nominal) {
            var box = document.getElementById('qrisKode');
            if (!QRIS_STATIS || !box || nominal <= 0) return;

            var teks = qrisDinamis(QRIS_STATIS, nominal);
            if (qrisObj) {
                qrisObj.clear();
                qrisObj.makeCode(teks);
            } else {
                qrisObj = new QRCode(box, {
                    text: teks,
                    width: 240,
                    height: 240,
                    correctLevel: QRCode.CorrectLevel.M
                });
            }
        }

        // Status konfirmasi pembayaran QRIS
        function setKonfirmasi(ok) {
            konfirmasi.value = ok ? '1' : '0';
            konfirmasi.dataset.nominal = ok ? totalSekarang : '';
            teksQris.textContent = ok ?
                '\u2713 Pembayaran QRIS dikonfirmasi (' + rp(totalSekarang) + ')' :
                'Pembayaran QRIS belum dikonfirmasi';
            teksQris.className = ok ? 'text-success font-weight-bold' : 'text-muted';
        }

        function setQty(input, q) {
            var stok = parseInt(input.dataset.stok, 10) || 0;
            input.value = Math.max(0, Math.min(stok, q || 0));
            hitung();
        }

        function qtyOf(input) {
            return parseInt(input.value, 10) || 0;
        }

        // Baris tabel "Menu Pesanan" (kiri)
        function barisMenu(input) {
            var q = qtyOf(input);
            var harga = parseInt(input.dataset.harga, 10) || 0;
            var tr = el('tr');

            var tdNama = el('td');
            tdNama.appendChild(el('div', 'font-weight-bold', input.dataset.nama));
            tdNama.appendChild(el('small', 'text-muted', JENIS[input.dataset.jenis]));

            var tdHarga = el('td', 'text-right', rp(harga));

            var tdQty = el('td', 'text-center');
            var box = el('div', 'qty');
            var minus = el('button', '', '\u2212');
            var plus = el('button', '', '+');
            minus.type = plus.type = 'button';
            minus.addEventListener('click', function() {
                setQty(input, qtyOf(input) - 1);
            });
            plus.addEventListener('click', function() {
                setQty(input, qtyOf(input) + 1);
            });
            box.appendChild(minus);
            box.appendChild(el('span', '', q));
            box.appendChild(plus);
            tdQty.appendChild(box);

            var tdSub = el('td', 'text-right font-weight-bold', rp(harga * q));

            var tdHapus = el('td', 'text-right');
            var hapus = el('button', 'btn btn-sm btn-outline-danger');
            hapus.type = 'button';
            hapus.innerHTML = '<i class="fas fa-trash"></i>';
            hapus.addEventListener('click', function() {
                setQty(input, 0);
            });
            tdHapus.appendChild(hapus);

            [tdNama, tdHarga, tdQty, tdSub, tdHapus].forEach(function(td) {
                tr.appendChild(td);
            });
            return tr;
        }

        function hitung() {
            var total = 0,
                jumlahItem = 0;
            var count = {
                makanan: 0,
                minuman: 0
            };
            var daftar = document.getElementById('daftarMenu');
            var sum = document.getElementById('sumItems');
            daftar.innerHTML = '';
            sum.innerHTML = '';

            inputs.forEach(function(input) {
                var q = qtyOf(input);
                input.closest('.product-box').classList.toggle('active', q > 0);
                if (q <= 0) return;

                var harga = parseInt(input.dataset.harga, 10) || 0;
                total += harga * q;
                jumlahItem += q;
                count[input.dataset.jenis] += q;

                daftar.appendChild(barisMenu(input));

                var line = el('div', 'line');
                line.appendChild(el('span', '', input.dataset.nama + ' \u00d7 ' + q));
                line.appendChild(el('span', '', rp(harga * q)));
                sum.appendChild(line);
            });

            if (jumlahItem === 0) sum.appendChild(el('div', 'text-muted small py-2', 'Belum ada menu dipilih'));

            Object.keys(count).forEach(function(j) {
                var b = document.getElementById('badge-' + j);
                b.textContent = count[j];
                b.classList.toggle('d-none', count[j] === 0);
            });

            var adaPelanggan = pelangganId.value !== '';
            var ada = jumlahItem > 0;

            document.getElementById('tabelMenu').classList.toggle('d-none', !ada);
            document.getElementById('kosongMenu').classList.toggle('d-none', ada);
            document.getElementById('modalInfo').textContent = ada ?
                jumlahItem + ' item dipilih \u2022 ' + rp(total) : 'Belum ada menu dipilih';
            document.getElementById('sumTotal').textContent = rp(total);

            // QRIS: nominal ikut total; konfirmasi dibatalkan jika total berubah
            totalSekarang = total;
            document.getElementById('totalQris').textContent = rp(total);
            btnKonfirmasi.disabled = total <= 0;
            buatQris(total);
            if (konfirmasi.value === '1' && parseInt(konfirmasi.dataset.nominal, 10) !== total) setKonfirmasi(false);

            var cash = metode.value.toLowerCase() === 'cash';
            var qris = metode.value.toLowerCase() === 'qris';
            var bayar = parseInt(inputBayar.value, 10) || 0;
            var kurang = cash && bayar < total;
            var belumKonfirmasi = qris && konfirmasi.value !== '1';

            boxCash.classList.toggle('d-none', !cash);
            boxQris.classList.toggle('d-none', !qris);
            document.getElementById('sumKembalian').textContent = rp(Math.max(0, bayar - total));

            var siap = ada && adaPelanggan && metode.value !== '' && !kurang && !belumKonfirmasi;

            document.getElementById('btnSubmit').disabled = !siap;
            document.getElementById('hint').classList.toggle('d-none', siap);
            document.getElementById('hintText').textContent = !adaPelanggan ? 'Pilih pelanggan terlebih dahulu' :
                !ada ? 'Pilih minimal satu makanan atau minuman' :
                metode.value === '' ? 'Pilih metode pembayaran' :
                belumKonfirmasi ? 'Konfirmasi pembayaran QRIS terlebih dahulu' :
                'Uang yang dibayar kurang dari total';
        }

        document.getElementById('modalMenu').addEventListener('click', function(e) {
            var btn = e.target.closest('.menu-btn');
            if (!btn) return;
            var input = btn.parentNode.querySelector('.menu-qty');
            setQty(input, qtyOf(input) + (btn.dataset.act === 'plus' ? 1 : -1));
        });
        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                setQty(input, qtyOf(input));
            });
        });

        // Pilih pelanggan dari modal (modal ditutup otomatis oleh data-dismiss)
        function pasangPilih(btn) {
            btn.addEventListener('click', function() {
                pelangganId.value = this.dataset.id;
                pelangganNama.value = this.dataset.nama;
                hitung();
            });
        }
        document.querySelectorAll('.btn-pilih[data-id]').forEach(pasangPilih);

        // Tambah baris pelanggan baru ke daftar di modal
        function tambahBaris(id, nama) {
            var tr = document.createElement('tr');

            var tdFoto = document.createElement('td');
            tdFoto.innerHTML = '<span class="pelanggan-foto"><i class="fas fa-user"></i></span>';

            var tdNama = document.createElement('td');
            tdNama.textContent = nama;

            var tdHp = document.createElement('td');
            tdHp.textContent = '-';

            var tdAlamat = document.createElement('td');
            tdAlamat.textContent = '-';

            var tdAksi = document.createElement('td');
            tdAksi.className = 'text-right';

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-pilih';
            btn.setAttribute('data-dismiss', 'modal');
            btn.dataset.id = id;
            btn.dataset.nama = nama;
            btn.textContent = 'Pilih';
            pasangPilih(btn);
            tdAksi.appendChild(btn);

            [tdFoto, tdNama, tdHp, tdAlamat, tdAksi].forEach(function(td) {
                tr.appendChild(td);
            });
            document.getElementById('listPelanggan').prepend(tr);
            document.getElementById('kosongPelanggan').classList.add('d-none');
        }

        // Simpan pelanggan baru (nama saja) lalu langsung dipilih
        var csrfName = '<?= csrf_token() ?>';
        var csrfInput = document.querySelector('input[name="' + csrfName + '"]');
        var namaBaru = document.getElementById('namaBaru');
        var btnBaru = document.getElementById('btnSimpanBaru');
        var pesanBaru = document.getElementById('pesanBaru');

        function simpanBaru() {
            var nama = namaBaru.value.trim();
            pesanBaru.textContent = '';

            if (nama === '') {
                pesanBaru.textContent = 'Nama pelanggan wajib diisi';
                namaBaru.focus();
                return;
            }

            var fd = new FormData();
            fd.append('namapelanggan', nama);
            if (csrfInput) fd.append(csrfName, csrfInput.value);

            btnBaru.disabled = true;

            fetch('<?= base_url('pelanggan/store-cepat') ?>', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(r) {
                    return r.json().then(function(d) {
                        return {
                            ok: r.ok,
                            d: d
                        };
                    });
                })
                .then(function(res) {
                    if (res.d.token && csrfInput) csrfInput.value = res.d.token;

                    if (!res.ok || !res.d.status) {
                        pesanBaru.textContent = res.d.message || 'Gagal menyimpan pelanggan';
                        return;
                    }

                    tambahBaris(res.d.id, res.d.nama);
                    pelangganId.value = res.d.id;
                    pelangganNama.value = res.d.nama;
                    namaBaru.value = '';
                    hitung();
                    document.querySelector('#modalPelanggan .close').click();
                })
                .catch(function() {
                    pesanBaru.textContent = 'Terjadi kesalahan, coba lagi';
                })
                .then(function() {
                    btnBaru.disabled = false;
                });
        }

        btnBaru.addEventListener('click', simpanBaru);
        namaBaru.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                simpanBaru();
            }
        });

        // Cari pelanggan di modal
        document.getElementById('cariPelanggan').addEventListener('input', function() {
            var kata = this.value.toLowerCase();
            var tampil = 0;

            document.querySelectorAll('#listPelanggan tr').forEach(function(tr) {
                var cocok = tr.textContent.toLowerCase().indexOf(kata) !== -1;
                tr.style.display = cocok ? '' : 'none';
                if (cocok) tampil++;
            });

            document.getElementById('kosongPelanggan').classList.toggle('d-none', tampil > 0);
        });

        // Ganti metode: QRIS langsung membuka modal QRIS (jika sudah ada menu)
        metode.addEventListener('change', function() {
            if (metode.value.toLowerCase() !== 'qris') {
                setKonfirmasi(false);
            } else if (totalSekarang > 0) {
                $('#modalQris').modal('show');
            }
            hitung();
        });
        inputBayar.addEventListener('input', hitung);

        // Tombol "Konfirmasi Pembayaran" di modal QRIS
        btnKonfirmasi.addEventListener('click', function() {
            setKonfirmasi(true);
            $('#modalQris').modal('hide');
            hitung();
        });

        hitung();
    })();
</script>
<?= $this->endSection() ?>