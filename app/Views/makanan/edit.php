<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Makanan</h3>
    </div>
    <form action="<?= base_url('makanan/update/' . $makanan['idmakanan']) ?>" method="post" enctype="multipart/form-data">
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
                <label>ID Makanan</label>
                <input type="text" class="form-control" value="<?= esc($makanan['idmakanan']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Nama Makanan</label>
                <input type="text" name="namamakanan" class="form-control" value="<?= old('namamakanan', $makanan['namamakanan']) ?>" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= old('harga', $makanan['harga']) ?>" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= old('stok', $makanan['stok']) ?>" required>
            </div>
            <div class="form-group">
                <label>Foto</label>
                <?php if ($makanan['foto']) : ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/makanan/' . $makanan['foto']) ?>" width="120" class="img-thumbnail">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" class="form-control-file" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan', $makanan['keterangan']) ?></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= base_url('makanan') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>