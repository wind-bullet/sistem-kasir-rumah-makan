<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');

// Auth
$routes->get('/auth', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');

// Dashboard (auth)
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Kategori (auth + admin)
$routes->group('kategori', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'Kategori::index');
    $routes->get('create', 'Kategori::create');
    $routes->post('store', 'Kategori::store');
    $routes->get('edit/(:num)', 'Kategori::edit/$1');
    $routes->post('update/(:num)', 'Kategori::update/$1');
    $routes->get('delete/(:num)', 'Kategori::delete/$1');
});

// Menu (auth + admin)
$routes->group('menu', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'Menu::index');
    $routes->get('create', 'Menu::create');
    $routes->post('store', 'Menu::store');
    $routes->get('edit/(:num)', 'Menu::edit/$1');
    $routes->post('update/(:num)', 'Menu::update/$1');
    $routes->get('delete/(:num)', 'Menu::delete/$1');
});

// Meja (auth + admin)
$routes->group('meja', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'Meja::index');
    $routes->get('create', 'Meja::create');
    $routes->post('store', 'Meja::store');
    $routes->get('edit/(:num)', 'Meja::edit/$1');
    $routes->post('update/(:num)', 'Meja::update/$1');
    $routes->get('delete/(:num)', 'Meja::delete/$1');
    $routes->get('toggle-status/(:num)', 'Meja::toggleStatus/$1');
});

// User (auth + admin)
$routes->group('user', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'User::index');
    $routes->get('create', 'User::create');
    $routes->post('store', 'User::store');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->get('delete/(:num)', 'User::delete/$1');
});

// Transaksi (auth, admin & kasir)
$routes->group('transaksi', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Transaksi::index');
    $routes->get('get-menu', 'Transaksi::getMenu');
    $routes->post('store', 'Transaksi::store');
});

// Struk (auth)
$routes->get('/struk/(:num)', 'Struk::index/$1', ['filter' => 'auth']);

// Riwayat (auth)
$routes->group('riwayat', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Riwayat::index');
    $routes->get('detail/(:num)', 'Riwayat::detail/$1');
});

// Laporan (auth + admin)
$routes->group('laporan', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'Laporan::index');
    $routes->post('generate', 'Laporan::generate');
    $routes->post('export-pdf', 'Laporan::exportPdf');
});

// Pemesanan via QR (publik, tanpa login)
$routes->get('/pesan/sukses', 'Pesan::sukses');
$routes->get('/pesan/takeaway', 'Pesan::takeaway');
$routes->post('/pesan/submit-takeaway', 'Pesan::submitTakeaway');
$routes->get('/pesan/(:num)', 'Pesan::index/$1');
$routes->post('/pesan/submit', 'Pesan::submit');

// QR Code Management (auth + admin)
$routes->group('qrcode', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'QRCode::index');
    $routes->get('generate', 'QRCode::generate');
    $routes->get('generate/(:num)', 'QRCode::generateSingle/$1');
    $routes->get('generate-takeaway', 'QRCode::generateTakeaway');
});


