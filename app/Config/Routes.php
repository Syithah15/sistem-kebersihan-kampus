<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'DashboardController::index');
$routes->get('peta', 'PetaSebaranController::index');
$routes->get('notifikasi', 'NotifikasiController::index');
$routes->get('notifikasi/baca/(:num)', 'NotifikasiController::baca/$1');