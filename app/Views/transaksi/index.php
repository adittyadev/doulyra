<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$badge = [
    'Di Siapkan' => 'warning',
    'Di Antar'   => 'info',
    'Di Terima'  => 'success',
];
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Transaksi</h3>
        <a href="<?= base_url('transaksi/create') ?>" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Tambah Transaksi
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Menu</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transaksi as $i => $row) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($row['idtransaksi']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgltransaksi'])) ?></td>
                        <td><?= esc($row['namapelanggan']) ?></td>
                        <td>
                            <?php if ($row['menu']) : ?>
                                <?php foreach (explode('||', $row['menu']) as $m) : ?>
                                    <div><?= esc($m) ?></div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>Rp <?= number_format($row['totalbayar'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge badge-<?= $badge[$row['status_pesanan']] ?? 'secondary' ?>">
                                <?= esc($row['status_pesanan']) ?>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm btn-status" title="Ganti status"
                                data-id="<?= esc($row['idtransaksi']) ?>" data-status="<?= esc($row['status_pesanan']) ?>">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <a href="<?= base_url('transaksi/delete/' . $row['idtransaksi']) ?>" class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Form tersembunyi untuk ganti status -->
<form id="formStatus" method="post" action="">
    <?= csrf_field() ?>
    <input type="hidden" name="status_pesanan" id="statusBaru">
</form>

<!-- Hapus baris ini jika layout/main sudah memuat SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

    // Ganti status pesanan
    document.querySelectorAll('.btn-status').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;

            Swal.fire({
                title: 'Ganti status pesanan',
                text: id,
                input: 'select',
                inputOptions: {
                    'Di Siapkan': 'Di Siapkan',
                    'Di Antar': 'Di Antar',
                    'Di Terima': 'Di Terima'
                },
                inputValue: this.dataset.status,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('formStatus');
                    form.action = '<?= base_url('transaksi/ubah-status') ?>/' + encodeURIComponent(id);
                    document.getElementById('statusBaru').value = result.value;
                    form.submit();
                }
            });
        });
    });

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