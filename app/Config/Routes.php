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

// Route untuk Dashboard
$routes->get('dashboard', 'Dashboard::index'); // Menampilkan halaman dashboard

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

// Route untuk Exam
$routes->group('exam', function ($routes) {
    $routes->get('index_exam', 'Exam::index_exam'); // Menampilkan halaman index exam
    $routes->get('register_exam', 'Exam::register_exam'); // Menampilkan halaman form register exam
    $routes->post('insert', 'Exam::insert'); // Menangani form POST dari register exam
    $routes->get('edit_exam/(:segment)', 'Exam::edit_exam/$1'); // Rute untuk menampilkan form edit
    $routes->get('edit_makroskopis/(:segment)', 'Exam::edit_makroskopis/$1');
    $routes->get('edit_mikroskopis/(:segment)', 'Exam::edit_mikroskopis/$1');
    $routes->post('update/(:segment)', 'Exam::update/$1'); // Rute untuk menangani update data
    $routes->post('update_print_hpa/(:segment)', 'Exam::update_print_hpa/$1');
    $routes->get('index_buku_penerima', 'Exam::index_buku_penerima');
    $routes->post('update_buku_penerima', 'Exam::update_buku_penerima'); // Adjusted for two segments
    $routes->post('update_status_hpa', 'Exam::update_status_hpa');
    $routes->post('delete', 'Exam::delete');
    $routes->post('uploadFotoMakroskopis/(:num)', 'Exam::uploadFotoMakroskopis/$1');
    $routes->post('uploadFotoMikroskopis/(:num)', 'Exam::uploadFotoMikroskopis/$1');
    $routes->get('edit_penulisan/(:num)', 'Exam::edit_penulisan/$1');
    $routes->post('update_penulisan/(:segment)', 'Exam::update_penulisan/$1');
    $routes->get('edit_print_hpa/(:num)', 'Exam::edit_print_hpa/$1');
});

// Route untuk Fnab
$routes->group('fnab', ['namespace' => 'App\Controllers\Fnab'],function ($routes) {
    $routes->get('register_fnab', 'Fnab::register_fnab');
    $routes->post('insert', 'Fnab::insert');
    $routes->get('index_fnab', 'Fnab::index_fnab');
});

// Route untuk Penerimaan
$routes->group('penerimaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_penerimaan', 'Penerimaan::index_penerimaan'); // Menampilkan halaman penerimaan
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan'); // Proses penerimaan
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit_penerimaan', 'Penerimaan::edit_penerimaan');
    $routes->post('update_penerimaan', 'Penerimaan::update_penerimaan');
});

// Route untuk Penerimaan
$routes->group('penerimaan_fnab', ['namespace' => 'App\Controllers\Fnab\Proses'], function ($routes) {
    $routes->get('index_penerimaan', 'Penerimaan::index_penerimaan'); // Menampilkan halaman penerimaan
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan'); // Proses penerimaan
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit_penerimaan', 'Penerimaan::edit_penerimaan');
    $routes->post('update_penerimaan', 'Penerimaan::update_penerimaan');
});

// Route untuk Pengirisan
$routes->group('pengirisan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pengirisan', 'Pengirisan::index_pengirisan'); // Menampilkan halaman pengirisan
    $routes->post('proses_pengirisan', 'Pengirisan::proses_pengirisan'); // Proses pengirisan
    $routes->get('pengirisan_details', 'Pengirisan::pengirisan_details');
    $routes->post('delete', 'Pengirisan::delete');
    $routes->get('edit_pengirisan', 'Pengirisan::edit_pengirisan');
    $routes->post('update_pengirisan', 'Pengirisan::update_pengirisan');
});

// Route untuk Pemotongan
$routes->group('pemotongan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pemotongan', 'Pemotongan::index_pemotongan'); // Menampilkan halaman pemotongan
    $routes->post('proses_pemotongan', 'Pemotongan::proses_pemotongan'); // Proses pemotongan
    $routes->get('pemotongan_details', 'Pemotongan::pemotongan_details');
    $routes->post('delete', 'Pemotongan::delete');
    $routes->get('edit_pemotongan', 'Pemotongan::edit_pemotongan');
    $routes->post('update_pemotongan', 'Pemotongan::update_pemotongan');
});

// Route untuk Pemprosesan
$routes->group('pemprosesan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pemprosesan', 'Pemprosesan::index_pemprosesan'); // Menampilkan halaman pemprosesan
    $routes->post('proses_pemprosesan', 'Pemprosesan::proses_pemprosesan'); // Proses pemprosesan
    $routes->get('pemprosesan_details', 'Pemprosesan::pemprosesan_details');
    $routes->post('delete', 'Pemprosesan::delete');
    $routes->get('edit_pemprosesan', 'Pemprosesan::edit_pemprosesan');
    $routes->post('update_pemprosesan', 'Pemprosesan::update_pemprosesan');
});

// Route untuk Penanaman
$routes->group('penanaman', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_penanaman', 'Penanaman::index_penanaman'); // Menampilkan halaman penanaman
    $routes->post('proses_penanaman', 'Penanaman::proses_penanaman'); // Proses penanaman
    $routes->get('penanaman_details', 'Penanaman::penanaman_details');
    $routes->post('delete', 'Penanaman::delete');
    $routes->get('edit_penanaman', 'Penanaman::edit_penanaman');
    $routes->post('update_penanaman', 'Penanaman::update_penanaman');
});

// Route untuk Pemotongan Tipis
$routes->group('pemotongan_tipis', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pemotongan_tipis', 'PemotonganTipis::index_pemotongan_tipis'); // Menampilkan halaman pemotongan tipis
    $routes->post('proses_pemotongan_tipis', 'PemotonganTipis::proses_pemotongan_tipis'); // Proses pemotongan tipis
    $routes->get('pemotongan_tipis_details', 'PemotonganTipis::pemotongan_tipis_details');
    $routes->post('delete', 'PemotonganTipis::delete');
    $routes->get('edit_pemotongan_tipis', 'PemotonganTipis::edit_pemotongan_tipis');
    $routes->post('update_pemotongan_tipis', 'PemotonganTipis::update_pemotongan_tipis');
});

// Route untuk Pewarnaan
$routes->group('pewarnaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pewarnaan', 'Pewarnaan::index_pewarnaan'); // Menampilkan halaman pewarnaan
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan'); // Proses pewarnaan
    $routes->get('pewarnaan_details', 'Pewarnaan::pewarnaan_details');
    $routes->post('delete', 'Pewarnaan::delete');
    $routes->get('edit_pewarnaan', 'Pewarnaan::edit_pewarnaan');
    $routes->post('update_pewarnaan', 'Pewarnaan::update_pewarnaan');
});

// Route untuk Pembacaan
$routes->group('pembacaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pembacaan', 'Pembacaan::index_pembacaan'); // Menampilkan halaman pembacaan
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan'); // Proses pembacaan
    $routes->get('pembacaan_details', 'Pembacaan::pembacaan_details');
    $routes->post('delete', 'Pembacaan::delete');
    $routes->get('edit_pembacaan', 'Pembacaan::edit_pembacaan');
    $routes->post('update_pembacaan', 'Pembacaan::update_pembacaan');
});

// Route untuk Penulisan
$routes->group('penulisan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_penulisan', 'Penulisan::index_penulisan'); // Menampilkan halaman penulisan
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan'); // Proses penulisan
    $routes->get('penulisan_details', 'Penulisan::penulisan_details');
    $routes->post('delete', 'Penulisan::delete');
    $routes->get('edit_penulisan', 'Penulisan::edit_penulisan');
    $routes->post('update_penulisan', 'Penulisan::update_penulisan');
});

// Route untuk Pemverifikasi
$routes->group('pemverifikasi', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pemverifikasi', 'Pemverifikasi::index_pemverifikasi'); // Menampilkan halaman pemverifikasi
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi'); // Proses pemverifikasi
    $routes->get('pemverifikasi_details', 'Pemverifikasi::pemverifikasi_details');
    $routes->post('delete', 'Pemverifikasi::delete');
    $routes->get('edit_pemverifikasi', 'Pemverifikasi::edit_pemverifikasi');
    $routes->post('update_pemverifikasi', 'Pemverifikasi::update_pemverifikasi');
});

// Route untuk Autorized
$routes->group('autorized', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_autorized', 'Autorized::index_autorized'); // Menampilkan halaman autorized
    $routes->post('proses_autorized', 'Autorized::proses_autorized'); // Proses autorized
    $routes->get('autorized_details', 'Autorized::autorized_details');
    $routes->post('delete', 'Autorized::delete');
    $routes->get('edit_autorized', 'Autorized::edit_autorized');
    $routes->post('update_autorized', 'Autorized::update_autorized');
});

// Route untuk Pencetakan
$routes->group('pencetakan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    $routes->get('index_pencetakan', 'Pencetakan::index_pencetakan'); // Menampilkan halaman pencetakan
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
