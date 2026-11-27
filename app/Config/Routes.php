<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'AuthController::index');
$routes->post('login', 'AuthController::login');

// Admin
$routes->get('admin/dashboard', 'AdminRingkasanController::index');
$routes->get('admin/monitoring-petugas', 'AdminMonitoringController::index');
$routes->get('admin/daftar-laporan', 'AdminFigmaController::index');
$routes->get('admin/peta', 'AdminFigmaController::map');
$routes->get('admin/laporan/(:num)', 'AdminFigmaController::detail/$1');