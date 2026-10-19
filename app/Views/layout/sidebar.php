<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Logo -->
    <a href="<?= base_url('/') ?>" class="brand-link bg-warning">
        <img src="<?= base_url('assets/img/logo.jpg') ?>" class="brand-image img-circle elevation-3">
        <span class="d-block text-center">Petshop Bigboss</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <?php if (session()->get('role') == 'karyawan' || session()->get('role') == 'pimpinan'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (url_is('dashboard')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (session()->get('role') == 'karyawan'): ?>
                    <li class="nav-header">DATA MASTER</li>

                    <li class="nav-item">
                        <a href="<?= base_url('pelanggan') ?>" class="nav-link <?= (url_is('pelanggan')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Pelanggan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('produkadmin') ?>" class="nav-link <?= (url_is('produkadmin')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Produk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('layanan') ?>" class="nav-link <?= (url_is('layanan')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-spa"></i>
                            <p>Layanan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('barang-masuk') ?>" class="nav-link <?= (url_is('barang-masuk')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Barang Masuk</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (session()->get('role') == 'karyawan'): ?>
                    <li class="nav-header">TRANSAKSI</li>

                    <li class="nav-item">
                        <a href="<?= base_url('transaksi') ?>" class="nav-link <?= (url_is('transaksi')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                Transaksi Penjualan
                                <span id="badge-transaksi" class="right badge badge-danger" style="display:none;"></span>
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('grooming') ?>" class="nav-link <?= (url_is('grooming')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cut"></i>
                            <p>
                                Layanan Grooming
                                <span id="badge-grooming" class="right badge badge-danger" style="display:none;"></span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (session()->get('role') == 'karyawan' || session()->get('role') == 'pimpinan'): ?>
                    <li class="nav-header">LAPORAN</li>

                    <li class="nav-item">
                        <a href="<?= base_url('laporan/penjualan') ?>" class="nav-link <?= (url_is('laporan/penjualan')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('laporan/grooming') ?>" class="nav-link <?= (url_is('laporan/grooming')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Grooming</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('laporan/pelanggan') ?>" class="nav-link <?= (url_is('laporan/pelanggan')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Pelanggan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('laporan/barang-masuk') ?>" class="nav-link <?= (url_is('laporan/barang-masuk')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Barang Masuk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('laporan/stok') ?>" class="nav-link <?= (url_is('laporan/stok')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Stok</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (session()->get('role') == 'pelanggan'): ?>
                    <li class="nav-header">MENU PELANGGAN</li>

                    <li class="nav-item">
                        <a href="<?= base_url('pelanggan/dashboard') ?>" class="nav-link <?= (url_is('pelanggan/dashboard')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('status-grooming') ?>" class="nav-link <?= (url_is('status-grooming')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-paw"></i>
                            <p>Status Grooming</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('transaksi/riwayat') ?>"
                            class="nav-link <?= (url_is('transaksi/riwayat')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Riwayat Transaksi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('pelanggan/profil') ?>"
                            class="nav-link <?= (url_is('pelanggan/profil')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item mt-3">
                    <a href="<?= base_url('logout') ?>" class="nav-link text-danger btnLogout">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>