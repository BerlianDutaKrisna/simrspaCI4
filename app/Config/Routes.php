<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Tampilan awal
$routes->get('/', 'Auth::index'); // Menampilkan form login

// Route untuk Auth
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::index'); // Menampilkan form login
    $routes->post('login', 'Auth::login'); // Menangani form POST dari Auth
    $routes->get('logout', 'Auth::logout'); // Menangani logout dan menghapus session
});

$routes->get('dashboard', 'Dashboard::index'); // Rute untuk dashboard


// Route untuk Users
$routes->group('users', function ($routes) {
    $routes->get('index_users', 'Users::index_users'); // Menampilkan halaman index users
    $routes->get('register_users', 'Users::register_users'); // Menampilkan halaman form register users
    $routes->post('insert', 'Users::insert'); // Menangani form POST dari register user
    $routes->get('delete/(:segment)', 'Users::delete/$1'); // Menghapus user
    $routes->get('edit_user/(:segment)', 'Users::edit_users/$1'); // Rute untuk menampilkan form edit
    $routes->post('update/(:segment)', 'Users::update/$1'); // Rute untuk menangani update data
});

// Route untuk Patient
$routes->group('patient', function ($routes) {
    $routes->get('index_patient', 'Patient::index_patient'); // Menampilkan halaman index patient
    $routes->get('register_patient', 'Patient::register_patient'); // Menampilkan halaman form register patient
    $routes->post('insert', 'Patient::insert'); // Menangani form POST dari register patient
    $routes->get('delete/(:segment)', 'Patient::delete/$1'); // Menghapus patient
    $routes->get('edit_patient/(:segment)', 'Patient::edit_patient/$1'); // Rute untuk menampilkan form edit
    $routes->post('update/(:segment)', 'Patient::update/$1'); // Rute untuk menangani update data
    $routes->post('modal_search', 'Patient::modal_search'); // Pencarian dengan NoRM
});

// Route untuk hpa
$routes->group('hpa', ['namespace' => 'App\Controllers\Hpa'], function ($routes) {
    $routes->get('index', 'HpaController::index');
    $routes->get('register', 'HpaController::register');
    $routes->post('insert', 'HpaController::insert');
    $routes->post('delete', 'HpaController::delete');
    $routes->get('edit/(:segment)', 'HpaController::edit/$1');
    $routes->get('edit_makroskopis/(:segment)/(:segment)', 'HpaController::edit_makroskopis/$1/$2');
    $routes->get('edit_mikroskopis/(:segment)/(:segment)/(:segment)/(:segment)', 'HpaController::edit_mikroskopis/$1/$2/$3/$4');
    $routes->get('edit_penulisan/(:segment)/(:segment)/(:segment)', 'HpaController::edit_penulisan/$1/$2/$3');
    $routes->get('edit_print_hpa/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)', 'HpaController::edit_print_hpa/$1/$2/$3/$4/$5/$6');
    $routes->post('update/(:segment)', 'HpaController::update/$1');
    $routes->post('update_print_hpa/(:segment)', 'HpaController::update_print_hpa/$1');
    $routes->get('index_buku_penerima', 'HpaController::index_buku_penerima');
    $routes->post('update_buku_penerima', 'HpaController::update_buku_penerima');
    $routes->post('update_status_hpa', 'HpaController::update_status_hpa');
    $routes->post('uploadFotoMakroskopis/(:segment)', 'HpaController::uploadFotoMakroskopis/$1');
    $routes->post('uploadFotoMikroskopis/(:segment)', 'HpaController::uploadFotoMikroskopis/$1');
});

// Route untuk frs
$routes->group('frs', ['namespace' => 'App\Controllers\Frs'], function ($routes) {
    $routes->get('register', 'FrsController::register');
    $routes->post('insert', 'FrsController::insert');
    $routes->get('index', 'FrsController::index');
});

// Route untuk srs
$routes->group('srs', ['namespace' => 'App\Controllers\Srs'], function ($routes) {
    $routes->get('register', 'SrsController::register');
    $routes->post('insert', 'SrsController::insert');
    $routes->get('index', 'SrsController::index');
});

// Route untuk ihc
$routes->group('ihc', ['namespace' => 'App\Controllers\Ihc'], function ($routes) {
    $routes->get('register', 'IhcController::register');
    $routes->post('insert', 'IhcController::insert');
    $routes->get('index', 'IhcController::index');
});

// Route untuk Penerimaan hpa
$routes->group('penerimaan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit_penerimaan', 'Penerimaan::edit_penerimaan');
    $routes->post('update_penerimaan', 'Penerimaan::update_penerimaan');
});

// Route untuk Penerimaan frs
$routes->group('penerimaan_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit_penerimaan', 'Penerimaan::edit_penerimaan');
    $routes->post('update_penerimaan', 'Penerimaan::update_penerimaan');
});

// Route untuk Penerimaan srs
$routes->group('penerimaan_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit_penerimaan', 'Penerimaan::edit_penerimaan');
    $routes->post('update_penerimaan', 'Penerimaan::update_penerimaan');
});

// Route untuk Penerimaan ihc
$routes->group('penerimaan_ihc', ['namespace' => 'App\Controllers\Ihc\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit_penerimaan', 'Penerimaan::edit_penerimaan');
    $routes->post('update_penerimaan', 'Penerimaan::update_penerimaan');
});

// Route untuk Pengirisan
$routes->group('pengirisan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pengirisan::index'); 
    $routes->post('proses_pengirisan', 'Pengirisan::proses_pengirisan');
    $routes->get('pengirisan_details', 'Pengirisan::pengirisan_details');
    $routes->post('delete', 'Pengirisan::delete');
    $routes->get('edit_pengirisan', 'Pengirisan::edit_pengirisan');
    $routes->post('update_pengirisan', 'Pengirisan::update_pengirisan');
});

// Route untuk Pemotongan
$routes->group('pemotongan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pemotongan::index'); // Menampilkan halaman pemotongan
    $routes->post('proses_pemotongan', 'Pemotongan::proses_pemotongan'); // Proses pemotongan
    $routes->get('pemotongan_details', 'Pemotongan::pemotongan_details');
    $routes->post('delete', 'Pemotongan::delete');
    $routes->get('edit_pemotongan', 'Pemotongan::edit_pemotongan');
    $routes->post('update_pemotongan', 'Pemotongan::update_pemotongan');
});

// Route untuk Pemprosesan
$routes->group('pemprosesan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pemprosesan::index'); // Menampilkan halaman pemprosesan
    $routes->post('proses_pemprosesan', 'Pemprosesan::proses_pemprosesan'); // Proses pemprosesan
    $routes->get('pemprosesan_details', 'Pemprosesan::pemprosesan_details');
    $routes->post('delete', 'Pemprosesan::delete');
    $routes->get('edit_pemprosesan', 'Pemprosesan::edit_pemprosesan');
    $routes->post('update_pemprosesan', 'Pemprosesan::update_pemprosesan');
});

// Route untuk Penanaman
$routes->group('penanaman_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Penanaman::index'); // Menampilkan halaman penanaman
    $routes->post('proses_penanaman', 'Penanaman::proses_penanaman'); // Proses penanaman
    $routes->get('penanaman_details', 'Penanaman::penanaman_details');
    $routes->post('delete', 'Penanaman::delete');
    $routes->get('edit_penanaman', 'Penanaman::edit_penanaman');
    $routes->post('update_penanaman', 'Penanaman::update_penanaman');
});

// Route untuk Pemotongan Tipis
$routes->group('pemotongan_tipis_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'PemotonganTipis::index'); // Menampilkan halaman pemotongan tipis
    $routes->post('proses_pemotongan_tipis', 'PemotonganTipis::proses_pemotongan_tipis'); // Proses pemotongan tipis
    $routes->get('pemotongan_tipis_details', 'PemotonganTipis::pemotongan_tipis_details');
    $routes->post('delete', 'PemotonganTipis::delete');
    $routes->get('edit_pemotongan_tipis', 'PemotonganTipis::edit_pemotongan_tipis');
    $routes->post('update_pemotongan_tipis', 'PemotonganTipis::update_pemotongan_tipis');
});

// Route untuk Pewarnaan
$routes->group('pewarnaan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pewarnaan::index'); // Menampilkan halaman pewarnaan
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan'); // Proses pewarnaan
    $routes->get('pewarnaan_details', 'Pewarnaan::pewarnaan_details');
    $routes->post('delete', 'Pewarnaan::delete');
    $routes->get('edit_pewarnaan', 'Pewarnaan::edit_pewarnaan');
    $routes->post('update_pewarnaan', 'Pewarnaan::update_pewarnaan');
});

// Route untuk Pembacaan
$routes->group('pembacaan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pembacaan::index'); // Menampilkan halaman pembacaan
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan'); // Proses pembacaan
    $routes->get('pembacaan_details', 'Pembacaan::pembacaan_details');
    $routes->post('delete', 'Pembacaan::delete');
    $routes->get('edit_pembacaan', 'Pembacaan::edit_pembacaan');
    $routes->post('update_pembacaan', 'Pembacaan::update_pembacaan');
});

// Route untuk Penulisan
$routes->group('penulisan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Penulisan::index'); // Menampilkan halaman penulisan
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan'); // Proses penulisan
    $routes->get('penulisan_details', 'Penulisan::penulisan_details');
    $routes->post('delete', 'Penulisan::delete');
    $routes->get('edit_penulisan', 'Penulisan::edit_penulisan');
    $routes->post('update_penulisan', 'Penulisan::update_penulisan');
});

// Route untuk Pemverifikasi
$routes->group('pemverifikasi_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pemverifikasi::index'); // Menampilkan halaman pemverifikasi
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi'); // Proses pemverifikasi
    $routes->get('pemverifikasi_details', 'Pemverifikasi::pemverifikasi_details');
    $routes->post('delete', 'Pemverifikasi::delete');
    $routes->get('edit_pemverifikasi', 'Pemverifikasi::edit_pemverifikasi');
    $routes->post('update_pemverifikasi', 'Pemverifikasi::update_pemverifikasi');
});

// Route untuk Authorized
$routes->group('authorized_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Authorized::index');
    $routes->post('proses_authorized', 'Authorized::proses_authorized'); 
    $routes->get('authorized_details', 'Authorized::authorized_details');
    $routes->post('delete', 'Authorized::delete');
    $routes->get('edit_authorized', 'Authorized::edit_authorized');
    $routes->post('update_authorized', 'Authorized::update_authorized');
});

// Route untuk Pencetakan
$routes->group('pencetakan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pencetakan::index'); // Menampilkan halaman pencetakan
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan'); // Proses pencetakan
    $routes->get('pencetakan_details', 'Pencetakan::pencetakan_details');
    $routes->post('delete', 'Pencetakan::delete');
    $routes->get('edit_pencetakan', 'Pencetakan::edit_pencetakan');
    $routes->post('update_pencetakan', 'Pencetakan::update_pencetakan');
});

// Router untuk Mutu
$routes->group('mutu', function ($routes) {
    $routes->get('mutu_details', 'Mutu::mutu_details');
    $routes->get('edit_mutu', 'Mutu::edit_mutu');
    $routes->post('update_mutu', 'Mutu::update_mutu');
});

// Router untuk Cetak
$routes->group('cetak', function ($routes) {
    $routes->get('form_hpa', 'Cetak::cetak_form_hpa');
    $routes->get('cetak_proses/(:num)', 'Cetak::cetak_proses/$1');
    $routes->get('autorized/(:num)', 'Cetak::autorized/$1');
    $routes->get('cetak_hpa/(:num)', 'Cetak::cetak_hpa/$1');
});

// Laporan
$routes->get('laporan_jumlah_pasien', 'Laporan\Laporan::index_laporan');
