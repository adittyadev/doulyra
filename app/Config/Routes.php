<?php

// Salin isi di bawah ini ke app/Config/Routes.php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ===== Publik =====
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Dashboard::index');

$routes->get('pelanggan', 'Pelanggan::index');
$routes->get('pelanggan/create', 'Pelanggan::create');
$routes->post('pelanggan/store', 'Pelanggan::store');
$routes->get('pelanggan/edit/(:segment)', 'Pelanggan::edit/$1');
$routes->post('pelanggan/update/(:segment)', 'Pelanggan::update/$1');
$routes->get('pelanggan/delete/(:segment)', 'Pelanggan::delete/$1');
$routes->post('pelanggan/store-cepat', 'Pelanggan::storeCepat');

// ===== Makanan =====
$routes->get('makanan', 'Makanan::index');
$routes->get('makanan/create', 'Makanan::create');
$routes->post('makanan/store', 'Makanan::store');
$routes->get('makanan/edit/(:segment)', 'Makanan::edit/$1');
$routes->post('makanan/update/(:segment)', 'Makanan::update/$1');
$routes->get('makanan/delete/(:segment)', 'Makanan::delete/$1');

// ===== Minuman =====
$routes->get('minuman', 'Minuman::index');
$routes->get('minuman/create', 'Minuman::create');
$routes->post('minuman/store', 'Minuman::store');
$routes->get('minuman/edit/(:segment)', 'Minuman::edit/$1');
$routes->post('minuman/update/(:segment)', 'Minuman::update/$1');
$routes->get('minuman/delete/(:segment)', 'Minuman::delete/$1');

$routes->get('transaksi', 'Transaksi::index');
$routes->get('transaksi/create', 'Transaksi::create');
$routes->post('transaksi/store', 'Transaksi::store');
$routes->get('transaksi/edit/(:segment)', 'Transaksi::edit/$1');
$routes->post('transaksi/update/(:segment)', 'Transaksi::update/$1');
$routes->get('transaksi/delete/(:segment)', 'Transaksi::delete/$1');
$routes->post('transaksi/ubah-status/(:segment)', 'Transaksi::ubahStatus/$1');

$routes->post('pesan/simpan', 'Pesan::simpan');
$routes->get('pesan/qris/(:segment)', 'Pesan::qris/$1');
$routes->post('pesan/bukti/(:segment)', 'Pesan::bukti/$1');
$routes->get('pesan/detail/(:segment)', 'Pesan::detail/$1');
$routes->get('pesan/status/(:segment)', 'Pesan::status/$1');
$routes->post('pesan/terima/(:segment)', 'Pesan::terima/$1');
$routes->get('pesan/(:segment)/(:segment)', 'Pesan::index/$1/$2');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::prosesLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::prosesRegister');
$routes->get('logout', 'Auth::logout');

$routes->get('chat/ambil', 'Chat::ambil');
$routes->post('chat/kirim', 'Chat::kirim');

$routes->get('chat/admin/daftar', 'Chat::adminDaftar');
$routes->get('chat/admin/(:num)', 'Chat::adminAmbil/$1');
$routes->post('chat/admin/(:num)/kirim', 'Chat::adminKirim/$1');
$routes->get('chat/pesan-masuk', 'Chat::adminHalaman');

$routes->get('transaksi/faktur/(:segment)', 'Transaksi::faktur/$1');
