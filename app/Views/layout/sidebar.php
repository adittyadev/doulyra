<?php
$role  = session()->get('role');
$uri   = uri_string();
$aktif = static fn(string $awalan): string => strpos($uri, $awalan) === 0 ? 'active' : '';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Logo -->
    <a href="<?= base_url(['admin' => 'admin', 'pimpinan' => 'pimpinan/laporan'][$role] ?? '/') ?>" class="brand-link bg-warning">
        <img src="<?= base_url('assets/img/logo.jpg') ?>" class="brand-image img-circle elevation-3">
        <span class="d-block text-center">Toko Makanan</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <?php if ($role === 'admin'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('admin') ?>" class="nav-link <?= $uri === 'admin' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">DATA MASTER</li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/kategori') ?>" class="nav-link <?= $aktif('admin/kategori') ?>">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/produk') ?>" class="nav-link <?= $aktif('admin/produk') ?>">
                            <i class="nav-icon fas fa-hamburger"></i>
                            <p>Produk</p>
                        </a>
                    </li>

                    <li class="nav-header">TRANSAKSI</li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/pesanan') ?>" class="nav-link <?= $aktif('admin/pesanan') ?>">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Pesanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/pembayaran') ?>" class="nav-link <?= $aktif('admin/pembayaran') ?>">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Pembayaran</p>
                        </a>
                    </li>

                    <li class="nav-header">PENGGUNA</li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/user') ?>" class="nav-link <?= $aktif('admin/user') ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data User</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($role === 'admin' || $role === 'pimpinan'): ?>
                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item">
                        <a href="<?= base_url('pimpinan/laporan') ?>" class="nav-link <?= $aktif('pimpinan/laporan') ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($role === 'pelanggan' || ! $role): ?>
                    <li class="nav-header">MENU</li>
                    <li class="nav-item">
                        <a href="<?= base_url('/') ?>" class="nav-link <?= ($uri === '' || strpos($uri, 'produk/') === 0) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-utensils"></i>
                            <p>Katalog</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($role === 'pelanggan'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('keranjang') ?>" class="nav-link <?= $aktif('keranjang') ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Keranjang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('pesanan') ?>" class="nav-link <?= $aktif('pesanan') ?>">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Pesanan Saya</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('alamat') ?>" class="nav-link <?= $aktif('alamat') ?>">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>Alamat Saya</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (! $role): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('login') ?>" class="nav-link <?= $aktif('login') ?>">
                            <i class="nav-icon fas fa-sign-in-alt"></i>
                            <p>Login</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('register') ?>" class="nav-link <?= $aktif('register') ?>">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>Daftar</p>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mt-3">
                        <a href="<?= base_url('logout') ?>" class="nav-link text-danger btnLogout">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</aside>