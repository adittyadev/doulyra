<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <!-- Kartu ringkasan -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $total['pelanggan'] ?></h3>
                        <p>Pelanggan</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-friends"></i></div>
                    <a href="<?= base_url('pelanggan') ?>" class="small-box-footer">Lihat data <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $total['makanan'] ?></h3>
                        <p>Menu Makanan</p>
                    </div>
                    <div class="icon"><i class="fas fa-hamburger"></i></div>
                    <a href="<?= base_url('makanan') ?>" class="small-box-footer">Lihat data <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $total['minuman'] ?></h3>
                        <p>Menu Minuman</p>
                    </div>
                    <div class="icon"><i class="fas fa-glass-whiskey"></i></div>
                    <a href="<?= base_url('minuman') ?>" class="small-box-footer">Lihat data <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $total['trx_hari'] ?></h3>
                        <p>Transaksi Hari Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <a href="<?= base_url('transaksi') ?>" class="small-box-footer">Lihat data <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Pendapatan & tindakan -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendapatan Hari Ini</span>
                        <span class="info-box-number"><?= rupiah($pendapatan['hari']) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-indigo"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendapatan Bulan Ini</span>
                        <span class="info-box-number"><?= rupiah($pendapatan['bulan']) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="fas fa-utensils"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Perlu Disiapkan / Diantar</span>
                        <span class="info-box-number"><?= $statusPesanan['Di Siapkan'] ?> / <?= $statusPesanan['Di Antar'] ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-maroon"><i class="fas fa-receipt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Menunggu Verifikasi</span>
                        <span class="info-box-number">
                            <a href="<?= base_url('pembayaran') ?>" class="text-dark"><?= $perluVerifikasi ?> pembayaran</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Grafik -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Penjualan 7 Hari Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="grafikPenjualan" style="min-height:250px;height:250px;max-height:250px;max-width:100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Menu terlaris -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Menu Terlaris</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Menu</th>
                                    <th class="text-right">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$terlaris): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($terlaris as $i => $t): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= esc($t['nama']) ?></td>
                                        <td class="text-right"><span class="badge badge-success"><?= (int) $t['terjual'] ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('grafikPenjualan'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($grafik['label']) ?>,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: <?= json_encode($grafik['data']) ?>,
                backgroundColor: 'rgba(60, 141, 188, .8)',
                borderRadius: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>