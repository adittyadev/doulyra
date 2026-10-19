<?php
$fotoNavbar = base_url('assets/img/logo.jpg');
$namaNavbar = 'Guest';

if (session()->get('role') === 'pelanggan') {

    $db = \Config\Database::connect();

    $pelangganNavbar = $db->table('pelanggan')
        ->select('pelanggan.foto, users.username')
        ->join('users', 'users.pelanggan_id = pelanggan.idpelanggan', 'left')
        ->where('pelanggan.idpelanggan', session()->get('pelanggan_id'))
        ->get()
        ->getRowArray();

    if ($pelangganNavbar) {

        if (!empty($pelangganNavbar['foto'])) {
            $fotoNavbar = base_url('uploads/foto-profil/' . $pelangganNavbar['foto']);
        }

        $namaNavbar = $pelangganNavbar['username'] ?? 'Pelanggan';
    }
} elseif (in_array(session()->get('role'), ['karyawan', 'pimpinan'])) {

    $db = \Config\Database::connect();

    $userNavbar = $db->table('users')
        ->select('username')
        ->where('id', session()->get('id'))
        ->get()
        ->getRowArray();

    if ($userNavbar) {
        $namaNavbar = $userNavbar['username'];
    }
}
?>

<nav class="main-header navbar navbar-expand navbar-dark bg-warning">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link text-white" data-toggle="dropdown" href="#">
                <div class="d-flex align-items-center">
                    <img src="<?= esc($fotoNavbar) ?>" class="img-circle elevation-2 mr-2"
                        alt="User Image" width="28" height="28" style="object-fit:cover;">
                    <span class="d-none d-md-inline text-black">Hallo, <?= esc($namaNavbar) ?></span>
                </div>
            </a>
        </li>

    </ul>
</nav>

<style>
    .dropdown-item:hover {
        background-color: black;
    }
</style>