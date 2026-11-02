<?php
$urut = ['Di Siapkan', 'Di Antar', 'Di Terima'];
$ikon = ['🍳', '🛵', '✅'];
$idx  = array_search($trx['status_pesanan'], $urut, true);
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .stepper {
        display: flex;
        gap: .5rem;
    }

    .stepper .step {
        flex: 1;
        text-align: center;
        padding: .75rem .5rem;
        border-radius: .5rem;
        background: #f1f3f5;
        color: #868e96;
        font-weight: 600;
        transition: all .2s;
    }

    .stepper .step i {
        display: block;
        font-style: normal;
        font-size: 1.5rem;
        margin-bottom: .25rem;
    }

    .stepper .step.done {
        background: #d3f9d8;
        color: #2b8a3e;
    }

    .stepper .step.active {
        background: #4c6ef5;
        color: #fff;
    }

    .thumb {
        width: 48px;
        height: 48px;
        border-radius: .4rem;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        font-size: 1.5rem;
    }

    .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Pesanan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= site_url('/#menu') ?>">Menu</a></li>
                    <li class="breadcrumb-item active">Detail Pesanan</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <!-- Status pesanan -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= esc($trx['idtransaksi']) ?> &middot; <?= date('d M Y', strtotime($trx['tgltransaksi'])) ?>
                </h3>
            </div>
            <div class="card-body">
                <div class="stepper" id="stepper">
                    <?php foreach ($urut as $i => $s): ?>
                        <div class="step <?= $idx !== false && $i < $idx ? 'done' : '' ?> <?= $idx === $i ? 'active' : '' ?>">
                            <i><?= $ikon[$i] ?></i><?= esc($s) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Tabel item -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Item Pesanan</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="width:70px">Foto</th>
                            <th>Menu</th>
                            <th>Jenis</th>
                            <th class="text-right">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $n => $d): ?>
                            <?php $f = foto_url($d['jenis'], $d['foto']); ?>
                            <tr>
                                <td><?= $n + 1 ?></td>
                                <td>
                                    <div class="thumb">
                                        <?php if ($f): ?><img src="<?= esc($f) ?>" alt=""><?php else: ?><?= $d['jenis'] === 'makanan' ? '🍽️' : '🥤' ?><?php endif; ?>
                                    </div>
                                </td>
                                <td class="align-middle"><b><?= esc($d['nama']) ?></b></td>
                                <td class="align-middle"><?= ucfirst(esc($d['jenis'])) ?></td>
                                <td class="align-middle text-right"><?= rupiah($d['harga']) ?></td>
                                <td class="align-middle text-center"><?= (int) $d['jumlah'] ?></td>
                                <td class="align-middle text-right"><?= rupiah($d['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total</th>
                            <th class="text-right"><?= rupiah($trx['totalbayar']) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Tabel pembayaran -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pembayaran</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <th style="width:220px">Metode pembayaran</th>
                            <td><?= strtoupper(esc($bayar['metode_pembayaran'])) ?></td>
                        </tr>
                        <tr>
                            <th>Status pembayaran</th>
                            <td><span class="badge badge-info" id="bayar"><?= esc($bayar['status_pembayaran']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Total bayar</th>
                            <td><b><?= rupiah($trx['totalbayar']) ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <form action="<?= site_url('pesan/terima/' . $trx['idtransaksi']) ?>" method="post" id="terima" class="d-inline" <?= $trx['status_pesanan'] === 'Di Antar' ? '' : 'hidden' ?>>
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success">Pesanan sudah saya terima</button>
                </form>
                <a href="<?= site_url('/#menu') ?>" class="btn btn-outline-secondary">Pesan lagi</a>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    const urut = <?= json_encode($urut) ?>;
    const steps = document.querySelectorAll('.step');
    const bayar = document.getElementById('bayar');
    const terima = document.getElementById('terima');
    let timer;

    async function cek() {
        try {
            const r = await fetch('<?= site_url('pesan/status/' . $trx['idtransaksi']) ?>', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!r.ok) return;
            const d = await r.json();
            const idx = urut.indexOf(d.status);

            steps.forEach((el, i) => {
                el.classList.toggle('done', idx !== -1 && i < idx);
                el.classList.toggle('active', i === idx);
            });
            bayar.textContent = d.bayar;
            terima.hidden = d.status !== 'Di Antar';

            if (d.status === 'Di Terima') clearInterval(timer);
        } catch (e) {}
    }

    timer = setInterval(cek, 5000);
</script>
<?= $this->endSection() ?>