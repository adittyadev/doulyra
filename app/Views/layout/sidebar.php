<?php
$uri   = uri_string();
$aktif = static fn(string $awalan): string => strpos($uri, $awalan) === 0 ? 'active' : '';
$role  = session()->get('role');

// Susunan menu per role: [header => [[label, url, ikon, awalan aktif], ...]]
$menu = [
    'karyawan' => [
        'DASHBOARD' => [
            ['Dashboard', 'dashboard', 'fas fa-tachometer-alt', 'dashboard'],
        ],
        'DATA MASTER' => [
            ['Pelanggan', 'pelanggan', 'fas fa-user-friends', 'pelanggan'],
            ['Makanan', 'makanan', 'fas fa-hamburger', 'makanan'],
            ['Minuman', 'minuman', 'fas fa-glass-whiskey', 'minuman'],
        ],
        'DATA TRANSAKSI' => [
            ['Transaksi', 'transaksi', 'fas fa-shopping-cart', 'transaksi'],
            ['Pembayaran', 'pembayaran', 'fas fa-money-bill-wave', 'pembayaran'],
        ],
        'LAYANAN' => [
            ['Pesan Masuk', 'chat/pesan-masuk', 'fas fa-comments', 'chat'],
        ],
        'LAPORAN' => [
            ['Laporan Transaksi', 'laporan/transaksi', 'fas fa-file-alt', 'laporan'],
        ],
    ],
    'pelanggan' => [
        'PESANAN' => [
            ['Riwayat Pesanan', 'pesan/riwayat', 'fas fa-receipt', 'pesan'],
        ],
    ],
    'pimpinan' => [
        'LAPORAN' => [
            ['Laporan Transaksi', 'laporan/transaksi', 'fas fa-file-alt', 'laporan'],
        ],
    ],
];

$menuRole = $menu[$role] ?? [];
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Logo -->
    <a href="<?= base_url('/') ?>" class="brand-link bg-indigo">
        <img src="<?= base_url('assets/img/logo.jpg') ?>" class="brand-image img-circle elevation-3">
        <span class="d-block text-center">Doulyra</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <?php foreach ($menuRole as $header => $items): ?>
                    <li class="nav-header"><?= esc($header) ?></li>
                    <?php foreach ($items as [$label, $url, $ikon, $awalan]): ?>
                        <li class="nav-item">
                            <a href="<?= base_url($url) ?>" class="nav-link <?= $aktif($awalan) ?>">
                                <i class="nav-icon <?= $ikon ?>"></i>
                                <p>
                                    <?= esc($label) ?>
                                    <?php if ($url === 'chat/pesan-masuk'): ?>
                                        <span class="badge badge-danger right" id="sbChatBadge" style="display:none">0</span>
                                    <?php endif; ?>
                                </p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>

            </ul>
        </nav>
    </div>
</aside>

<?php if ($role === 'karyawan'): ?>
    <script>
        // Badge jumlah pesan pelanggan yang belum dibaca (diperbarui tiap 10 detik)
        (function() {
            const badge = document.getElementById('sbChatBadge');
            if (!badge) return;
            async function cek() {
                try {
                    const r = await fetch('<?= site_url('chat/admin/daftar') ?>');
                    if (!r.ok) return;
                    const rows = await r.json();
                    const total = rows.reduce((a, p) => a + (+p.belum_dibaca || 0), 0);
                    badge.textContent = total;
                    badge.style.display = total > 0 ? '' : 'none';
                } catch (e) {}
            }
            cek();
            setInterval(cek, 10000);
        })();
    </script>
<?php endif; ?>