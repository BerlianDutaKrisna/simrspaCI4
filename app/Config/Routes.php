<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Route untuk Auth
$routes->get('/', 'Auth::index'); // Menampilkan form login
$routes->get('login', 'Auth::index'); // Menampilkan form login
$routes->post('auth/login', 'Auth::login'); // Menangani form POST dari Auth
$routes->get('register', 'Auth::register'); // Menampilkan form register
$routes->get('logout', 'Auth::logout');         // Menangani logout dan menghapus session

// Route untuk Dashboard
$routes->get('dashboard', 'Dashboard::index'); // Menampilkan halaman dashboard
$routes->get('patient', 'Patient::index'); // Route untuk menampilkan halaman utama
$routes->post('patient/searchPatient', 'Patient::searchPatient'); // Route untuk pencarian pasien
$routes->post('patient/create', 'Patient::create'); // Route untuk membuat pasien baru

// Route untuk Users
$routes->get('/users', 'Users::index');
$routes->post('users/insert', 'Users::insert'); // Menangani form POST dari register user

// Route untuk Patient
$routes->get('patient/index', 'Patient::index');
$routes->post('patient/searchPatient', 'Patient::searchPatient');
$routes->get('patient/register_patient', 'Patient::register_patient');
$routes->post('patient/insert', 'Patient::insert');
// Route untuk pengujian
$routes->get('/test', 'Test::index');
