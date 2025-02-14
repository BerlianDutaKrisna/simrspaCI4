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
$routes->get('users/edit_user/(:segment)', 'Users::edit_users/$1');  // Rute untuk menampilkan form edit
$routes->post('users/update/(:segment)', 'Users::update/$1');  // Rute untuk menangani update data

// Route untuk Patient
$routes->get('patient/index_patient', 'Patient::index_patient'); // Menampilkan halaman index patient
$routes->get('patient/register_patient', 'Patient::register_patient'); // Menampilkan halaman form register patient
$routes->post('patient/insert', 'Patient::insert'); // Menangani form POST dari register patient
$routes->get('patient/delete/(:segment)', 'Patient::delete/$1');  // Menghapus patient
$routes->get('patient/edit_patient/(:segment)', 'Patient::edit_patient/$1');  // Rute untuk menampilkan form edit
$routes->post('patient/update/(:segment)', 'Patient::update/$1');  // Rute untuk menangani update data
$routes->post('patient/modal_search', 'Patient::modal_search'); // Pencarian dengan NoRM

// Route untuk Exam
$routes->get('exam/index_exam', 'Exam::index_exam'); // Menampilkan halaman index exam
$routes->get('exam/register_exam', 'Exam::register_exam'); // Menampilkan halaman form register exam
$routes->post('exam/insert', 'Exam::insert'); // Menangani form POST dari register exam
$routes->get('exam/edit_exam/(:segment)', 'Exam::edit_exam/$1');  // Rute untuk menampilkan form edit
$routes->get('exam/edit_makroskopis/(:segment)', 'Exam::edit_makroskopis/$1');
$routes->get('exam/edit_mikroskopis/(:segment)', 'Exam::edit_mikroskopis/$1');
$routes->post('exam/update/(:segment)', 'Exam::update/$1');  // Rute untuk menangani update data
$routes->post('exam/update_print_hpa/(:segment)', 'Exam::update_print_hpa/$1');
$routes->get('exam/index_buku_penerima', 'Exam::index_buku_penerima');
$routes->post('exam/update_buku_penerima', 'Exam::update_buku_penerima');  // Adjusted for two segments
$routes->post('exam/update_status_hpa', 'Exam::update_status_hpa');
$routes->post('exam/delete', 'Exam::delete');
$routes->post('exam/uploadFotoMakroskopis/(:num)', 'Exam::uploadFotoMakroskopis/$1');
$routes->post('exam/uploadFotoMikroskopis/(:num)', 'Exam::uploadFotoMikroskopis/$1');
$routes->get('exam/edit_penulisan/(:num)', 'Exam::edit_penulisan/$1');
$routes->post('exam/update_penulisan/(:segment)', 'Exam::update_penulisan/$1');
$routes->get('exam/edit_print_hpa/(:num)', 'Exam::edit_print_hpa/$1');

// Route untuk Penerimaan
$routes->get('penerimaan/index_penerimaan', 'Proses\Penerimaan::index_penerimaan'); // Menampilkan halaman penerimaan
$routes->group('penerimaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form penerimaan yang menerima POST request
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
});
$routes->get('penerimaan/penerimaan_details', 'Proses\Penerimaan::penerimaan_details');
$routes->get('penerimaan/edit_penerimaan', 'Proses\Penerimaan::edit_penerimaan');
$routes->post('penerimaan/update_penerimaan', 'Proses\Penerimaan::update_penerimaan');

// Route untuk Pengirisan
$routes->get('pengirisan/index_pengirisan', 'Proses\Pengirisan::index_pengirisan'); // Menampilkan halaman pengirisan
$routes->group('pengirisan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pengirisan yang menerima POST request
    $routes->post('proses_pengirisan', 'Pengirisan::proses_pengirisan');
});
$routes->get('pengirisan/pengirisan_details', 'Proses\Pengirisan::pengirisan_details');
$routes->post('pengirisan/delete', 'Proses\Pengirisan::delete');
$routes->get('pengirisan/edit_pengirisan', 'Proses\Pengirisan::edit_pengirisan');
$routes->post('pengirisan/update_pengirisan', 'Proses\Pengirisan::update_pengirisan');

// Route untuk Pemotongan
$routes->get('pemotongan/index_pemotongan', 'Proses\Pemotongan::index_pemotongan'); // Menampilkan halaman pemotongan
$routes->group('pemotongan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemotongan yang menerima POST request
    $routes->post('proses_pemotongan', 'Pemotongan::proses_pemotongan');
});
$routes->get('pemotongan/pemotongan_details', 'Proses\Pemotongan::pemotongan_details');
$routes->post('pemotongan/delete', 'Proses\Pemotongan::delete');
$routes->get('pemotongan/edit_pemotongan', 'Proses\Pemotongan::edit_pemotongan');
$routes->post('pemotongan/update_pemotongan', 'Proses\Pemotongan::update_pemotongan');

// Route untuk Pemprosesan
$routes->get('pemprosesan/index_pemprosesan', 'Proses\Pemprosesan::index_pemprosesan'); // Menampilkan halaman pemprosesan
$routes->group('pemprosesan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemprosesan yang menerima POST request
    $routes->post('proses_pemprosesan', 'Pemprosesan::proses_pemprosesan');
});
$routes->get('pemprosesan/pemprosesan_details', 'Proses\Pemprosesan::pemprosesan_details');
$routes->post('pemprosesan/delete', 'Proses\Pemprosesan::delete');
$routes->get('pemprosesan/edit_pemprosesan', 'Proses\Pemprosesan::edit_pemprosesan');
$routes->post('pemprosesan/update_pemprosesan', 'Proses\Pemprosesan::update_pemprosesan');

// Route untuk Penanaman
$routes->get('penanaman/index_penanaman', 'Proses\Penanaman::index_penanaman'); // Menampilkan halaman penanaman
$routes->group('penanaman', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form penanaman yang menerima POST request
    $routes->post('proses_penanaman', 'Penanaman::proses_penanaman');
});
$routes->get('penanaman/penanaman_details', 'Proses\Penanaman::penanaman_details');
$routes->post('penanaman/delete', 'Proses\Penanaman::delete');
$routes->get('penanaman/edit_penanaman', 'Proses\Penanaman::edit_penanaman');
$routes->post('penanaman/update_penanaman', 'Proses\Penanaman::update_penanaman');

// Route untuk Pemotongan Tipis
$routes->get('pemotongan_tipis/index_pemotongan_tipis', 'Proses\PemotonganTipis::index_pemotongan_tipis'); // Menampilkan halaman pemotongan tipis
$routes->group('pemotongan_tipis', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemotongan tipis yang menerima POST request
    $routes->post('proses_pemotongan_tipis', 'PemotonganTipis::proses_pemotongan_tipis');
});
$routes->get('pemotongan_tipis/pemotongan_tipis_details', 'Proses\PemotonganTipis::pemotongan_tipis_details');
$routes->post('pemotongan_tipis/delete', 'Proses\PemotonganTipis::delete');
$routes->get('pemotongan_tipis/edit_pemotongan_tipis', 'Proses\PemotonganTipis::edit_pemotongan_tipis');
$routes->post('pemotongan_tipis/update_pemotongan_tipis', 'Proses\PemotonganTipis::update_pemotongan_tipis');

// Route untuk Pewarnaan
$routes->get('pewarnaan/index_pewarnaan', 'Proses\Pewarnaan::index_pewarnaan'); // Menampilkan halaman pewarnaan
$routes->group('pewarnaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pewarnaan yang menerima POST request
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan');
});
$routes->get('pewarnaan/pewarnaan_details', 'Proses\Pewarnaan::pewarnaan_details');
$routes->post('pewarnaan/delete', 'Proses\Pewarnaan::delete');
$routes->get('pewarnaan/edit_pewarnaan', 'Proses\Pewarnaan::edit_pewarnaan');
$routes->post('pewarnaan/update_pewarnaan', 'Proses\Pewarnaan::update_pewarnaan');

// Route untuk Pembacaan
$routes->get('pembacaan/index_pembacaan', 'Proses\Pembacaan::index_pembacaan'); // Menampilkan halaman pembacaan
$routes->group('pembacaan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pembacaan yang menerima POST request
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan');
});
$routes->get('pembacaan/pembacaan_details', 'Proses\Pembacaan::pembacaan_details');
$routes->post('pembacaan/delete', 'Proses\Pembacaan::delete');
$routes->get('pembacaan/edit_pembacaan', 'Proses\Pembacaan::edit_pembacaan');
$routes->post('pembacaan/update_pembacaan', 'Proses\Pembacaan::update_pembacaan');

// Route untuk Penulisan
$routes->get('penulisan/index_penulisan', 'Proses\Penulisan::index_penulisan'); // Menampilkan halaman penulisan
$routes->group('penulisan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form penulisan yang menerima POST request
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan');
});
$routes->get('penulisan/penulisan_details', 'Proses\Penulisan::penulisan_details');
$routes->post('penulisan/delete', 'Proses\Penulisan::delete');
$routes->get('penulisan/edit_penulisan', 'Proses\Penulisan::edit_penulisan');
$routes->post('penulisan/update_penulisan', 'Proses\Penulisan::update_penulisan');

// Route untuk Pemverifikasi
$routes->get('pemverifikasi/index_pemverifikasi', 'Proses\Pemverifikasi::index_pemverifikasi'); // Menampilkan halaman pemverifikasi
$routes->group('pemverifikasi', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pemverifikasi yang menerima POST request
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi');
});
$routes->get('pemverifikasi/pemverifikasi_details', 'Proses\Pemverifikasi::pemverifikasi_details');
$routes->post('pemverifikasi/delete', 'Proses\Pemverifikasi::delete');
$routes->get('pemverifikasi/edit_pemverifikasi', 'Proses\Pemverifikasi::edit_pemverifikasi');
$routes->post('pemverifikasi/update_pemverifikasi', 'Proses\Pemverifikasi::update_pemverifikasi');

// Route untuk Autorized
$routes->get('autorized/index_autorized', 'Proses\Autorized::index_autorized'); // Menampilkan halaman autorized
$routes->group('autorized', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form autorized yang menerima POST request
    $routes->post('proses_autorized', 'Autorized::proses_autorized');
});
$routes->get('autorized/autorized_details', 'Proses\Autorized::autorized_details');
$routes->post('autorized/delete', 'Proses\Autorized::delete');
$routes->get('autorized/edit_autorized', 'Proses\Autorized::edit_autorized');
$routes->post('autorized/update_autorized', 'Proses\Autorized::update_autorized');


// Route untuk Pencetakan
$routes->get('pencetakan/index_pencetakan', 'Proses\Pencetakan::index_pencetakan'); // Menampilkan halaman pencetakan
$routes->group('pencetakan', ['namespace' => 'App\Controllers\Proses'], function ($routes) {
    // Definisikan rute untuk form pencetakan yang menerima POST request
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan');
});
$routes->get('pencetakan/pencetakan_details', 'Proses\Pencetakan::pencetakan_details');
$routes->post('pencetakan/delete', 'Proses\Pencetakan::delete');
$routes->get('pencetakan/edit_pencetakan', 'Proses\Pencetakan::edit_pencetakan');
$routes->post('pencetakan/update_pencetakan', 'Proses\Pencetakan::update_pencetakan');

// Router untuk Mutu
$routes->get('mutu/mutu_details', 'Mutu::mutu_details');
$routes->get('mutu/edit_mutu', 'Mutu::edit_mutu');
$routes->post('mutu/update_mutu', 'Mutu::update_mutu');

// Router untuk Cetak
$routes->get('cetak/form_hpa', 'Cetak::cetak_form_hpa');
$routes->get('cetak/cetak_proses/(:num)', 'Cetak::cetak_proses/$1');
$routes->get('cetak/autorized/(:num)', 'Cetak::autorized/$1');
$routes->get('cetak/cetak_hpa/(:num)', 'Cetak::cetak_hpa/$1');

// Laporan
$routes->get('laporan_jumlah_pasien', 'Laporan\Laporan::index_laporan');

