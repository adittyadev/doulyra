<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pelanggan</h3>
        <a href="<?= base_url('pelanggan/create') ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Foto</th>
                    <th>Nama Pelanggan</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pelanggan as $i => $row) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if ($row['foto']) : ?>
                                <img src="<?= base_url('uploads/pelanggan/' . $row['foto']) ?>" width="60" class="img-thumbnail">
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['namapelanggan']) ?></td>
                        <td><?= esc($row['no_hp']) ?></td>
                        <td><?= esc($row['alamat']) ?></td>
                        <td>
                            <a href="<?= base_url('pelanggan/edit/' . $row['idpelanggan']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="<?= base_url('pelanggan/delete/' . $row['idpelanggan']) ?>" class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hapus baris ini jika layout/main sudah memuat SweetAlert2 -->
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