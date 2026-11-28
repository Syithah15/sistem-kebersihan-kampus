<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// HALAMAN LOGIN
$routes->get('/', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->post('/logout', 'AuthController::logout');

// DASHBOARD
$routes->get('/pelapor/dashboard', 'DashboardController::pelapor', [
    'filter' => 'role:pelapor'
]);

$routes->get('/petugas/dashboard', 'DashboardController::petugas', [
    'filter' => 'role:petugas'
]);

$routes->get('/admin/dashboard', 'DashboardController::admin', [
    'filter' => 'role:admin'
]);

// FITUR LAPORAN PELAPOR
$routes->get('/pelapor/laporan/buat', 'LaporanController::create', [
    'filter' => 'role:pelapor'
]);

$routes->post('/pelapor/laporan/simpan', 'LaporanController::store', [
    'filter' => 'role:pelapor'
]);

$routes->post('/pelapor/laporan/sinkronisasi', 'SinkronisasiController::simpan', [
    'filter' => 'role:pelapor'
]);

// FITUR PETUGAS
$routes->get('/petugas/laporan/detail/(:num)', 'PetugasController::detail/$1', [
    'filter' => 'role:petugas'
]);

$routes->get('/petugas/foto/(:segment)', 'PetugasController::foto/$1', [
    'filter' => 'role:petugas'
]);

$routes->post('/petugas/laporan/ambil/(:num)', 'PetugasController::ambilTugas/$1', [
    'filter' => 'role:petugas'
]);

$routes->post('/petugas/tugas/mulai/(:num)', 'TugasController::mulai/$1', [
    'filter' => 'role:petugas'
]);

$routes->get('/petugas/tugas/selesai/(:num)', 'TugasController::formSelesai/$1', [
    'filter' => 'role:petugas'
]);

$routes->post('/petugas/tugas/selesai/simpan/(:num)', 'TugasController::simpanSelesai/$1', [
    'filter' => 'role:petugas'
]);

// DETAIL LAPORAN PELAPOR
$routes->get('/pelapor/laporan/detail/(:num)', 'PelaporController::detail/$1', [
    'filter' => 'role:pelapor'
]);

$routes->get('/pelapor/laporan/foto/(:num)', 'PelaporController::fotoLaporan/$1', [
    'filter' => 'role:pelapor'
]);

$routes->get('/pelapor/laporan/foto-tugas/(:num)/(:segment)', 'PelaporController::fotoTugas/$1/$2', [
    'filter' => 'role:pelapor'
]);

// NOTIFIKASI
$routes->get('/notifikasi', 'NotifikasiController::index', [
    'filter' => 'role:pelapor,petugas,admin'
]);

$routes->post('/notifikasi/baca/(:num)', 'NotifikasiController::baca/$1', [
    'filter' => 'role:pelapor,petugas,admin'
]);

// FITUR ADMIN
$routes->get('/admin/laporan', 'AdminController::laporan', [
    'filter' => 'role:admin'
]);

$routes->get('/admin/laporan/detail/(:num)', 'AdminController::detail/$1', [
    'filter' => 'role:admin'
]);

$routes->get('/admin/laporan/foto/(:segment)', 'PetugasController::foto/$1', [
    'filter' => 'role:admin'
]);

$routes->post('/admin/laporan/batalkan/(:num)', 'AdminController::batalkan/$1', [
    'filter' => 'role:admin'
]);

$routes->get('/admin/peta-laporan', 'AdminController::peta', [
    'filter' => 'role:admin'
]);


// === [START: MODUL 5 - REINALDI (ADMIN: RINGKASAN, PETA & PETUGAS)] ===
$routes->get('/admin/ringkasan', 'AdminRingkasanController::index', [
    'filter' => 'role:admin'
]);

$routes->get('/admin/dashboard-ringkasan', 'AdminRingkasanController::index', [
    'filter' => 'role:admin'
]);

$routes->get('/admin/peta-sebaran', 'PetaSebaranController::index', [
    'filter' => 'role:admin'
]);

$routes->get('/admin/monitoring-petugas', 'MonitoringPetugasController::index', [
    'filter' => 'role:admin'
]);
// === [END: MODUL 5] ===
