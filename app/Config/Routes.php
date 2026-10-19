<?php

// Salin isi di bawah ini ke app/Config/Routes.php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ===== Publik =====
$routes->get('/', 'Katalog::index');
$routes->get('produk/(:segment)', 'Katalog::detail/$1');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::prosesLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::prosesRegister');
$routes->get('logout', 'Auth::logout');

// ===== Pelanggan =====
$routes->group('', ['filter' => 'role:pelanggan'], static function ($routes) {
    $routes->get('keranjang', 'Keranjang::index');
    $routes->post('keranjang/tambah', 'Keranjang::tambah');
    $routes->post('keranjang/ubah/(:num)', 'Keranjang::ubah/$1');
    $routes->post('keranjang/hapus/(:num)', 'Keranjang::hapus/$1');

    $routes->get('checkout', 'Checkout::index');
    $routes->post('checkout', 'Checkout::proses');

    $routes->get('pesanan', 'Pesanan::index');
    $routes->get('pesanan/(:segment)', 'Pesanan::detail/$1');
    $routes->post('pesanan/(:segment)/bayar', 'Pesanan::bayar/$1');
    $routes->post('pesanan/(:segment)/batalkan', 'Pesanan::batalkan/$1');

    $routes->get('alamat', 'Alamat::index');
    $routes->post('alamat', 'Alamat::simpan');
    $routes->post('alamat/utama/(:num)', 'Alamat::utama/$1');
    $routes->post('alamat/hapus/(:num)', 'Alamat::hapus/$1');
});

// ===== Admin =====
$routes->group('admin', ['filter' => 'role:admin', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('kategori', 'Kategori::index');
    $routes->post('kategori/simpan', 'Kategori::simpan');
    $routes->post('kategori/ubah/(:num)', 'Kategori::ubah/$1');
    $routes->post('kategori/hapus/(:num)', 'Kategori::hapus/$1');

    $routes->get('produk', 'Produk::index');
    $routes->get('produk/baru', 'Produk::baru');
    $routes->post('produk/simpan', 'Produk::simpan');
    $routes->get('produk/(:num)/edit', 'Produk::edit/$1');
    $routes->post('produk/(:num)/ubah', 'Produk::ubah/$1');
    $routes->post('produk/(:num)/hapus', 'Produk::hapus/$1');

    $routes->get('pesanan', 'Pesanan::index');
    $routes->get('pesanan/(:num)', 'Pesanan::detail/$1');
    $routes->post('pesanan/(:num)/status', 'Pesanan::ubahStatus/$1');
    $routes->post('pesanan/(:num)/batalkan', 'Pesanan::batalkan/$1');

    $routes->get('pembayaran', 'Pembayaran::index');
    $routes->post('pembayaran/(:num)/verifikasi', 'Pembayaran::verifikasi/$1');

    $routes->get('user', 'User::index');
    $routes->post('user/simpan', 'User::simpan');
    $routes->post('user/(:num)/status', 'User::ubahStatus/$1');
});

// ===== Pimpinan (admin juga boleh melihat laporan) =====
$routes->group('pimpinan', ['filter' => 'role:pimpinan,admin', 'namespace' => 'App\Controllers\Pimpinan'], static function ($routes) {
    $routes->get('laporan', 'Laporan::index');
});
