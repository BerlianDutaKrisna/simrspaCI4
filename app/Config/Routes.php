<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Tampilan awal
$routes->get('/', 'Auth::index'); // Menampilkan form login
// Route untuk Auth
$routes->get('login', 'Auth::index'); // Menampilkan form login
$routes->post('auth/login', 'Auth::login'); // Menangani form POST dari Auth
$routes->get('auth/logout', 'Auth::logout'); // Menangani logout dan menghapus session

// Route untuk Dashboard
$routes->get('dashboard', 'Dashboard::index'); // Menampilkan halaman dashboard

// Route untuk Users
$routes->get('users/index_users', 'Users::index_users'); // Menampilkan halaman index users
$routes->get('users/register_users', 'Users::register_users'); // Menampilkan halaman form register users
$routes->post('users/insert', 'Users::insert'); // Menangani form POST dari register user

// Route untuk Patient
$routes->get('patient/index_patient', 'Patient::index_patient'); // Menampilkan halaman index patient
$routes->get('patient/register_patient', 'Patient::register_patient'); // Menampilkan halaman form register patient
$routes->post('patient/insert', 'Patient::insert'); // Menangani form POST dari register patient

// Route untuk pengujian
$routes->get('/test', 'Test::index'); // Menampilkan halaman untuk pengujian
