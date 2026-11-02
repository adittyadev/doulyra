<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Transaksi</h3>
    </div>
    <form action="<?= base_url('transaksi/update/' . $transaksi['idtransaksi']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>ID Transaksi</label>
                <input type="text" class="form-control" value="<?= esc($transaksi['idtransaksi']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tgltransaksi" class="form-control" value="<?= old('tgltransaksi', $transaksi['tgltransaksi']) ?>" required>
            </div>
            <div class="form-group">
                <label>Pelanggan</label>
                <select name="pelanggan_id" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach ($pelanggan as $p) : ?>
                        <option value="<?= $p['idpelanggan'] ?>" <?= old('pelanggan_id', $transaksi['pelanggan_id']) == $p['idpelanggan'] ? 'selected' : '' ?>>
                            <?= esc($p['namapelanggan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Makanan</label>
                <select name="makanan_id" class="form-control">
                    <option value="">-- Tidak ada --</option>
                    <?php foreach ($makanan as $m) : ?>
                        <option value="<?= esc($m['idmakanan']) ?>" <?= old('makanan_id', $transaksi['makanan_id']) == $m['idmakanan'] ? 'selected' : '' ?>>
                            <?= esc($m['namamakanan']) ?> - Rp <?= number_format($m['harga'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Minuman</label>
                <select name="minuman_id" class="form-control">
                    <option value="">-- Tidak ada --</option>
                    <?php foreach ($minuman as $m) : ?>
                        <option value="<?= esc($m['idminuman']) ?>" <?= old('minuman_id', $transaksi['minuman_id']) == $m['idminuman'] ? 'selected' : '' ?>>
                            <?= esc($m['namaminuman']) ?> - Rp <?= number_format($m['harga'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Pilih minimal satu makanan atau minuman</small>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" min="1" value="<?= old('jumlah', $transaksi['jumlah']) ?>" required>
                <small class="text-muted">Total bayar dihitung ulang otomatis saat disimpan</small>
            </div>
            <div class="form-group">
                <label>Status Pesanan</label>
                <select name="status_pesanan" class="form-control" required>
                    <?php foreach (['Di Siapkan', 'Di Antar', 'Di Terima'] as $s) : ?>
                        <option value="<?= $s ?>" <?= old('status_pesanan', $transaksi['status_pesanan']) == $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= base_url('transaksi') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>