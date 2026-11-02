<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Makanan</h3>
        <a href="<?= base_url('makanan/create') ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Tambah Makanan
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nama Makanan</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Keterangan</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($makanan as $i => $row) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($row['idmakanan']) ?></td>
                        <td>
                            <?php if ($row['foto']) : ?>
                                <img src="<?= base_url('uploads/makanan/' . $row['foto']) ?>" width="60" class="img-thumbnail">
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['namamakanan']) ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= esc($row['stok']) ?></td>
                        <td><?= esc($row['keterangan']) ?></td>
                        <td>
                            <a href="<?= base_url('makanan/edit/' . $row['idmakanan']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="<?= base_url('makanan/delete/' . $row['idmakanan']) ?>" class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi dari controller (simpan / edit / hapus)
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: <?= json_encode(session()->getFlashdata('success')) ?>,
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: <?= json_encode(session()->getFlashdata('error')) ?>
        });
    <?php endif; ?>

    // Konfirmasi hapus
    document.querySelectorAll('.btn-hapus').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Hapus data?',
                text: 'Data yang dihapus tidak bisa dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>