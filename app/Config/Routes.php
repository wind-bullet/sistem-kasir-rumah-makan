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
$routes->group('kategori', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Kategori::index');
    $routes->get('create', 'Kategori::create');
    $routes->post('store', 'Kategori::store');
    $routes->get('edit/(:num)', 'Kategori::edit/$1');
    $routes->post('update/(:num)', 'Kategori::update/$1');
    $routes->get('delete/(:num)', 'Kategori::delete/$1');
});

// Menu (auth + admin)
$routes->group('menu', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Menu::index');
    $routes->get('create', 'Menu::create');
    $routes->post('store', 'Menu::store');
    $routes->get('edit/(:num)', 'Menu::edit/$1');
    $routes->post('update/(:num)', 'Menu::update/$1');
    $routes->get('delete/(:num)', 'Menu::delete/$1');
});

// Meja (auth + admin)
$routes->group('meja', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Meja::index');
    $routes->get('create', 'Meja::create');
    $routes->post('store', 'Meja::store');
    $routes->get('edit/(:num)', 'Meja::edit/$1');
    $routes->post('update/(:num)', 'Meja::update/$1');
    $routes->get('delete/(:num)', 'Meja::delete/$1');
    $routes->get('toggle-status/(:num)', 'Meja::toggleStatus/$1');
});

// User (auth + admin)
$routes->group('user', ['filter' => 'auth:admin'], function($routes) {
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
$routes->group('laporan', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Laporan::index');
    $routes->post('generate', 'Laporan::generate');
    $routes->post('export-pdf', 'Laporan::exportPdf');
});
