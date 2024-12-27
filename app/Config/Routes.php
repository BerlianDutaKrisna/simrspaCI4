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
$routes->get('users/delete/(:segment)', 'Users::delete/$1');  // Menghapus user
$routes->get('/users/edit_user/(:segment)', 'Users::edit_users/$1');  // Rute untuk menampilkan form edit
$routes->post('/users/update/(:segment)', 'Users::update/$1');  // Rute untuk menangani update data

// Route untuk Patient
$routes->get('patient/index_patient', 'Patient::index_patient'); // Menampilkan halaman index patient
$routes->get('patient/register_patient', 'Patient::register_patient'); // Menampilkan halaman form register patient
$routes->post('patient/insert', 'Patient::insert'); // Menangani form POST dari register patient
$routes->get('patient/delete/(:segment)', 'Patient::delete/$1');  // Menghapus patient
$routes->get('/patient/edit_patient/(:segment)', 'Patient::edit_patient/$1');  // Rute untuk menampilkan form edit
$routes->post('/patient/update/(:segment)', 'Patient::update/$1');  // Rute untuk menangani update data
$routes->post('patient/modal_search', 'Patient::modal_search'); // Pencarian dengan NoRM

// Route untuk Exam
$routes->get('exam/register_exam', 'Exam::register_exam'); // Menampilkan halaman form register exam
$routes->post('exam/insert', 'Exam::insert'); // Menangani form POST dari register exam


// Route untuk Penerimaan
$routes->get('penerimaan/index_penerimaan', 'Proses\Penerimaan::index_penerimaan'); // Menampilkan halaman penerimaan
$routes->group('penerimaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form penerimaan yang menerima POST request
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
});

// Route untuk Pengirisan
$routes->get('pengirisan/index_pengirisan', 'Proses\Pengirisan::index_pengirisan'); // Menampilkan halaman pengirisan
$routes->group('pengirisan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pengirisan yang menerima POST request
    $routes->post('proses_pengirisan', 'Pengirisan::proses_pengirisan');
});

// Route untuk Pemotongan
$routes->get('pemotongan/index_pemotongan', 'Proses\Pemotongan::index_pemotongan'); // Menampilkan halaman pemotongan
$routes->group('pemotongan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemotongan yang menerima POST request
    $routes->post('proses_pemotongan', 'Pemotongan::proses_pemotongan');
});
// Route untuk Pemprosesan
$routes->get('pemprosesan/index_pemprosesan', 'Proses\Pemprosesan::index_pemprosesan'); // Menampilkan halaman pemprosesan
$routes->group('pemprosesan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemprosesan yang menerima POST request
    $routes->post('proses_pemprosesan', 'Pemprosesan::proses_pemprosesan');
});
// Route untuk Penanaman
$routes->get('penanaman/index_penanaman', 'Proses\Penanaman::index_penanaman'); // Menampilkan halaman penanaman
$routes->group('penanaman', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form penanaman yang menerima POST request
    $routes->post('proses_penanaman', 'Penanaman::proses_penanaman');
});
// Route untuk Pemotongan Tipis
$routes->get('pemotongan_tipis/index_pemotongan_tipis', 'Proses\PemotonganTipis::index_pemotongan_tipis'); // Menampilkan halaman pemotongan tipis
$routes->group('pemotongan_tipis', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemotongan tipis yang menerima POST request
    $routes->post('proses_pemotongan_tipis', 'PemotonganTipis::proses_pemotongan_tipis');
});
// Route untuk Pewarnaan
$routes->get('pewarnaan/index_pewarnaan', 'Proses\Pewarnaan::index_pewarnaan'); // Menampilkan halaman pewarnaan
$routes->group('pewarnaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pewarnaan yang menerima POST request
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan');
});
// Route untuk Pembacaan
$routes->get('pembacaan/index_pembacaan', 'Proses\Pembacaan::index_pembacaan'); // Menampilkan halaman pembacaan
$routes->group('pembacaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pembacaan yang menerima POST request
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan');
});
// Route untuk Penulisan
$routes->get('penulisan/index_penulisan', 'Proses\Penulisan::index_penulisan'); // Menampilkan halaman penulisan
$routes->group('penulisan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form penulisan yang menerima POST request
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan');
});
// Route untuk Pemverifikasi
$routes->get('pemverifikasi/index_pemverifikasi', 'Proses\Pemverifikasi::index_pemverifikasi'); // Menampilkan halaman pemverifikasi
$routes->group('pemverifikasi', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemverifikasi yang menerima POST request
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi');
});
// Route untuk Pencetakan
$routes->get('pencetakan/index_pencetakan', 'Proses\Pencetakan::index_pencetakan'); // Menampilkan halaman pencetakan
$routes->group('pencetakan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pencetakan yang menerima POST request
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan');
});








// Route untuk pengujian
$routes->get('/test', 'Test::index'); // Menampilkan halaman untuk pengujian
