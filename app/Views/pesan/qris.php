<?= $this->extend('pesan/layout') ?>

<?= $this->section('content') ?>
<div class="box">
    <div>
        <h1>Bayar dengan QRIS</h1>
        <p class="muted">Kode pesanan: <?= esc($trx['idtransaksi']) ?></p>
    </div>

    <?php if (session('error')): ?>
        <div class="alert"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <div class="qr">
        <?php if (is_file(FCPATH . 'assets/img/qris.jpg')): ?>
            <img src="<?= base_url('assets/img/qris.jpg') ?>" alt="QRIS Doulyra">
        <?php else: ?>
            <div class="qr--kosong">Letakkan gambar QRIS di<br>public/assets/img/qris.jpg</div>
        <?php endif; ?>
    </div>

    <div class="total">
        <span>Total yang harus dibayar</span>
        <strong><?= rupiah($trx['total']) ?></strong>
    </div>

    <form action="<?= site_url('pesan/bukti/' . $trx['idtransaksi']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <p class="muted" style="margin-bottom:10px">Scan QRIS di atas, bayar sesuai total, lalu unggah bukti pembayaran.</p>
        <input type="file" name="bukti" accept="image/*" required>
        <button type="submit" class="btn" style="margin-top:14px">Kirim bukti pembayaran</button>
    </form>
</div>
<?= $this->endSection() ?>