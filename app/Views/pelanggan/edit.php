<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Pelanggan</h3>
    </div>
    <form action="<?= base_url('pelanggan/update/' . $pelanggan['idpelanggan']) ?>" method="post" enctype="multipart/form-data">
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
                <label>Nama Pelanggan</label>
                <input type="text" name="namapelanggan" class="form-control" value="<?= old('namapelanggan', $pelanggan['namapelanggan']) ?>" required>
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp', $pelanggan['no_hp']) ?>" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= old('alamat', $pelanggan['alamat']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Foto</label>
                <?php if ($pelanggan['foto']) : ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/pelanggan/' . $pelanggan['foto']) ?>" width="120" class="img-thumbnail">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" class="form-control-file" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>