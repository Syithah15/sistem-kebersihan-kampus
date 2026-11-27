<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'DashboardController::index');
$routes->get('peta', 'PetaSebaranController::index');
$routes->get('notifikasi', 'NotifikasiController::index');
$routes->get('notifikasi/baca/(:num)', 'NotifikasiController::baca/$1');

// Route Petugas (US-4)
$routes->group('petugas', static function ($routes) {
    $routes->get('dashboard', 'PetugasController::index');
    $routes->get('tugas', 'TugasController::index');
    $routes->get('tugas/(:num)', 'TugasController::detail/$1');
    $routes->get('tugas/(:num)/update', 'TugasController::formUpdate/$1');
    $routes->post('tugas/(:num)/update', 'TugasController::updateStatus/$1');
});
