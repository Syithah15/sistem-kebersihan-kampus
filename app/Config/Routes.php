<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Route utama dan autentikasi
$routes->get('/', 'Home::index');
$routes->post('login', 'AuthController::login');

// Route kelompok
$routes->get('dashboard', 'DashboardController::index');
$routes->get('peta', 'PetaSebaranController::index');
$routes->get('notifikasi', 'NotifikasiController::index');
$routes->get('notifikasi/baca/(:num)', 'NotifikasiController::baca/$1');

// Route Admin US-5
$routes->get('admin/dashboard', 'AdminRingkasanController::index');
$routes->get('admin/monitoring-petugas', 'AdminMonitoringController::index');
$routes->get('admin/daftar-laporan', 'AdminFigmaController::index');
$routes->get('admin/peta', 'AdminFigmaController::map');
$routes->get('admin/laporan/(:num)', 'AdminFigmaController::detail/$1');

// Route Petugas US-4
$routes->group('petugas', static function ($routes) {
    $routes->get('dashboard', 'PetugasController::index');
    $routes->get('tugas', 'TugasController::index');
    $routes->get('tugas/(:num)', 'TugasController::detail/$1');
    $routes->get('tugas/(:num)/update', 'TugasController::formUpdate/$1');
    $routes->post('tugas/(:num)/update', 'TugasController::updateStatus/$1');
});