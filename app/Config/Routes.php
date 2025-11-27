<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Tampilan awal
$routes->get('/', 'Auth::index');

// Route untuk Auth
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
});

// Route untuk Dashboard
$routes->get('dashboard', 'Dashboard::index');

// Route untuk Users
$routes->group('users', function ($routes) {
    $routes->get('index_users', 'Users::index_users');
    $routes->get('register_users', 'Users::register_users');
    $routes->post('insert', 'Users::insert');
    $routes->get('delete/(:segment)', 'Users::delete/$1');
    $routes->get('edit_user/(:segment)', 'Users::edit_users/$1');
    $routes->post('update/(:segment)', 'Users::update/$1');
    $routes->get('laporan', 'Users::laporan');
    $routes->get('filter', 'Users::filter');
});

// Route untuk Patient
$routes->group('patient', function ($routes) {
    $routes->get('index_patient', 'Patient::index_patient');
    $routes->get('register_patient', 'Patient::register_patient');
    $routes->get('register_patient/(:segment)', 'Patient::register_patient/$1');
    $routes->post('insert', 'Patient::insert');
    $routes->get('delete/(:segment)', 'Patient::delete/$1');
    $routes->get('edit_patient/(:segment)', 'Patient::edit_patient/$1');
    $routes->post('update/(:segment)', 'Patient::update/$1');
    $routes->post('modal_search', 'Patient::modal_search');
    $routes->get('laporan', 'Patient::laporan');
    $routes->get('filter', 'Patient::filter');
});

// Route untuk API
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('kunjungan/store', 'Kunjungan::store');
    $routes->put('kunjungan/update/(:segment)', 'Kunjungan::update/$1');
    $routes->delete('kunjungan/(:segment)', 'Kunjungan::delete/$1');
    $routes->get('kunjungan/getKunjunganHariIni', 'Kunjungan::getKunjunganHariIni');
    $routes->get('kunjungan/modal_search/(:segment)', 'Kunjungan::modal_search/$1');
    $routes->get('kunjungan/index', 'Kunjungan::index');
    $routes->get('kunjungan/indexAll', 'Kunjungan::indexAll');
    $routes->get('pemeriksaan/norm_pasien/(:num)', 'Pemeriksaan::showByNorm/$1');
    $routes->put('pemeriksaan/id_transaksi/(:num)', 'Pemeriksaan::updateByTransaksi/$1');
    $routes->get('pengiriman-data-simrs', 'PengirimanDataSimrs::index');
    $routes->post('pengiriman-data-simrs/kirim', 'PengirimanDataSimrs::kirim');
    $routes->post('pengiriman-data-simrs/kirim/(:num)', 'PengirimanDataSimrs::kirimById/$1');
    $routes->resource('koneksi', ['only' => ['index']]);
});

// Route untuk exam
$routes->group('exam', ['namespace' => 'App\Controllers\Exam'], function ($routes) {
    $routes->get('index', 'ExamController::index');
    $routes->get('search', 'ExamController::search');
});

// Route untuk hpa
$routes->group('hpa', ['namespace' => 'App\Controllers\Hpa'], function ($routes) {
    $routes->get('index', 'HpaController::index');
    $routes->get('index_buku_penerima', 'HpaController::index_buku_penerima');
    $routes->get('register', 'HpaController::register');
    $routes->post('insert', 'HpaController::insert');
    $routes->post('delete', 'HpaController::delete');
    $routes->get('edit/(:segment)', 'HpaController::edit/$1');
    $routes->get('edit_makroskopis/(:segment)', 'HpaController::edit_makroskopis/$1');
    $routes->get('edit_mikroskopis/(:segment)', 'HpaController::edit_mikroskopis/$1');
    $routes->get('edit_penulisan/(:segment)', 'HpaController::edit_penulisan/$1');
    $routes->get('edit_print/(:segment)', 'HpaController::edit_print/$1');
    $routes->post('update/(:segment)', 'HpaController::update/$1');
    $routes->post('update_print/(:segment)', 'HpaController::update_print/$1');
    $routes->post('update_buku_penerima', 'HpaController::update_buku_penerima');
    $routes->post('update_status', 'HpaController::update_status');
    $routes->post('update_jumlah_slide', 'HpaController::update_jumlah_slide');
    $routes->post('uploadFotoMakroskopis/(:segment)', 'HpaController::uploadFotoMakroskopis/$1');
    $routes->post('uploadFotoMikroskopis/(:segment)', 'HpaController::uploadFotoMikroskopis/$1');
    $routes->get('laporan_pemeriksaan', 'HpaController::laporan_pemeriksaan');
<<<<<<< HEAD
=======
    $routes->get('laporan_mutu', 'HpaController::laporan_mutu');
>>>>>>> dd47376b993a2f24fde3d9858cefb3149107efca
    $routes->get('laporan_kerja', 'HpaController::laporan_kerja');
    $routes->get('laporan_oprasional', 'HpaController::laporan_oprasional');
    $routes->get('laporan_BHP', 'HpaController::laporan_BHP');
    $routes->get('laporan_PUG', 'HpaController::laporan_PUG');
    $routes->get('laporan_PUB', 'HpaController::laporan_PUB');
    $routes->get('filter', 'HpaController::filter');
});

// Route untuk frs
$routes->group('frs', ['namespace' => 'App\Controllers\Frs'], function ($routes) {
    $routes->get('index', 'FrsController::index');
    $routes->get('index_buku_penerima', 'FrsController::index_buku_penerima');
    $routes->get('register', 'FrsController::register');
    $routes->post('insert', 'FrsController::insert');
    $routes->post('delete', 'FrsController::delete');
    $routes->get('edit/(:segment)', 'FrsController::edit/$1');
    $routes->get('edit_makroskopis/(:segment)', 'FrsController::edit_makroskopis/$1');
    $routes->get('edit_mikroskopis/(:segment)', 'FrsController::edit_mikroskopis/$1');
    $routes->get('edit_penulisan/(:segment)', 'FrsController::edit_penulisan/$1');
    $routes->get('edit_print/(:segment)', 'FrsController::edit_print/$1');
    $routes->post('update/(:segment)', 'FrsController::update/$1');
    $routes->post('update_print/(:segment)', 'FrsController::update_print/$1');
    $routes->post('update_buku_penerima', 'FrsController::update_buku_penerima');
    $routes->post('update_status', 'FrsController::update_status');
    $routes->post('update_jumlah_slide', 'FrsController::update_jumlah_slide');
    $routes->post('uploadFotoMakroskopis/(:segment)', 'FrsController::uploadFotoMakroskopis/$1');
    $routes->post('uploadFotoMikroskopis/(:segment)', 'FrsController::uploadFotoMikroskopis/$1');
    $routes->get('laporan_pemeriksaan', 'FrsController::laporan_pemeriksaan');
    $routes->get('laporan_kerja', 'FrsController::laporan_kerja');
    $routes->get('laporan_oprasional', 'FrsController::laporan_oprasional');
    $routes->get('laporan_BHP', 'FrsController::laporan_BHP');
    $routes->get('filter', 'FrsController::filter');
});

// Route untuk srs
$routes->group('srs', ['namespace' => 'App\Controllers\Srs'], function ($routes) {
    $routes->get('index', 'SrsController::index');
    $routes->get('index_buku_penerima', 'SrsController::index_buku_penerima');
    $routes->get('register', 'SrsController::register');
    $routes->post('insert', 'SrsController::insert');
    $routes->post('delete', 'SrsController::delete');
    $routes->get('edit/(:segment)', 'SrsController::edit/$1');
    $routes->get('edit_makroskopis/(:segment)', 'SrsController::edit_makroskopis/$1');
    $routes->get('edit_mikroskopis/(:segment)', 'SrsController::edit_mikroskopis/$1');
    $routes->get('edit_penulisan/(:segment)', 'SrsController::edit_penulisan/$1');
    $routes->get('edit_print/(:segment)', 'SrsController::edit_print/$1');
    $routes->post('update/(:segment)', 'SrsController::update/$1');
    $routes->post('update_print/(:segment)', 'SrsController::update_print/$1');
    $routes->post('update_buku_penerima', 'SrsController::update_buku_penerima');
    $routes->post('update_status', 'SrsController::update_status');
    $routes->post('update_jumlah_slide', 'SrsController::update_jumlah_slide');
    $routes->post('uploadFotoMakroskopis/(:segment)', 'SrsController::uploadFotoMakroskopis/$1');
    $routes->post('uploadFotoMikroskopis/(:segment)', 'SrsController::uploadFotoMikroskopis/$1');
    $routes->get('laporan_pemeriksaan', 'SrsController::laporan_pemeriksaan');
    $routes->get('laporan_kerja', 'SrsController::laporan_kerja');
    $routes->get('laporan_oprasional', 'SrsController::laporan_oprasional');
    $routes->get('laporan_BHP', 'SrsController::laporan_BHP');
    $routes->get('filter', 'SrsController::filter');
});

// Route untuk ihc
$routes->group('ihc', ['namespace' => 'App\Controllers\Ihc'], function ($routes) {
    $routes->get('index', 'IhcController::index');
    $routes->get('index_buku_penerima', 'IhcController::index_buku_penerima');
    $routes->get('register', 'IhcController::register');
    $routes->post('insert', 'IhcController::insert');
    $routes->post('delete', 'IhcController::delete');
    $routes->get('edit/(:segment)', 'IhcController::edit/$1');
    $routes->get('edit_mikroskopis/(:segment)', 'IhcController::edit_mikroskopis/$1');
    $routes->get('edit_penulisan/(:segment)', 'IhcController::edit_penulisan/$1');
    $routes->get('edit_print/(:segment)', 'IhcController::edit_print/$1');
    $routes->post('update/(:segment)', 'IhcController::update/$1');
    $routes->post('update_print/(:segment)', 'IhcController::update_print/$1');
    $routes->post('update_buku_penerima', 'IhcController::update_buku_penerima');
    $routes->post('update_status', 'IhcController::update_status');
    $routes->post('uploadFotoMakroskopis/(:segment)', 'IhcController::uploadFotoMakroskopis/$1');
    $routes->post('uploadFotoMikroskopis/(:segment)', 'IhcController::uploadFotoMikroskopis/$1');
    $routes->get('laporan_pemeriksaan', 'IhcController::laporan_pemeriksaan');
    $routes->get('laporan_kerja', 'IhcController::laporan_kerja');
    $routes->get('laporan_oprasional', 'IhcController::laporan_oprasional');
    $routes->get('laporan_BHP', 'IhcController::laporan_BHP');
    $routes->get('laporan_ER', 'IhcController::laporan_ER');
    $routes->get('laporan_PR', 'IhcController::laporan_PR');
    $routes->get('laporan_HER2', 'IhcController::laporan_HER2');
    $routes->get('laporan_KI67', 'IhcController::laporan_KI67');
    $routes->get('filter', 'IhcController::filter');
});

// Route untuk Penerimaan hpa
$routes->group('penerimaan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit', 'Penerimaan::edit');
    $routes->post('update', 'Penerimaan::update');
});

// Route untuk Penerimaan frs
$routes->group('penerimaan_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit', 'Penerimaan::edit');
    $routes->post('update', 'Penerimaan::update');
});

// Route untuk Penerimaan srs
$routes->group('penerimaan_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit', 'Penerimaan::edit');
    $routes->post('update', 'Penerimaan::update');
});

// Route untuk Penerimaan ihc
$routes->group('penerimaan_ihc', ['namespace' => 'App\Controllers\Ihc\Proses'], function ($routes) {
    $routes->get('index', 'Penerimaan::index');
    $routes->post('proses_penerimaan', 'Penerimaan::proses_penerimaan');
    $routes->get('penerimaan_details', 'Penerimaan::penerimaan_details');
    $routes->get('edit', 'Penerimaan::edit');
    $routes->post('update', 'Penerimaan::update');
});

// Route untuk Pemotongan
$routes->group('pemotongan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pemotongan::index');
    $routes->post('proses_pemotongan', 'Pemotongan::proses_pemotongan');
    $routes->get('pemotongan_details', 'Pemotongan::pemotongan_details');
    $routes->post('delete', 'Pemotongan::delete');
    $routes->get('edit', 'Pemotongan::edit');
    $routes->post('update', 'Pemotongan::update');
});

// Route untuk Pemprosesan
$routes->group('pemprosesan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pemprosesan::index');
    $routes->post('proses_pemprosesan', 'Pemprosesan::proses_pemprosesan');
    $routes->get('pemprosesan_details', 'Pemprosesan::pemprosesan_details');
    $routes->post('delete', 'Pemprosesan::delete');
    $routes->get('edit', 'Pemprosesan::edit');
    $routes->post('update', 'Pemprosesan::update');
});

// Route untuk Penanaman
$routes->group('penanaman_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Penanaman::index');
    $routes->post('proses_penanaman', 'Penanaman::proses_penanaman');
    $routes->get('penanaman_details', 'Penanaman::penanaman_details');
    $routes->post('delete', 'Penanaman::delete');
    $routes->get('edit', 'Penanaman::edit');
    $routes->post('update', 'Penanaman::update');
});

// Route untuk Pemotongan Tipis
$routes->group('pemotongan_tipis_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'PemotonganTipis::index');
    $routes->post('proses_pemotongan_tipis', 'PemotonganTipis::proses_pemotongan_tipis');
    $routes->get('pemotongan_tipis_details', 'PemotonganTipis::pemotongan_tipis_details');
    $routes->post('delete', 'PemotonganTipis::delete');
    $routes->get('edit', 'PemotonganTipis::edit');
    $routes->post('update', 'PemotonganTipis::update');
});

// Route untuk Pewarnaan hpa
$routes->group('pewarnaan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pewarnaan::index');
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan');
    $routes->get('pewarnaan_details', 'Pewarnaan::pewarnaan_details');
    $routes->post('delete', 'Pewarnaan::delete');
    $routes->get('edit', 'Pewarnaan::edit');
    $routes->post('update', 'Pewarnaan::update');
});

// Route untuk Pewarnaan frs
$routes->group('pewarnaan_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Pewarnaan::index');
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan');
    $routes->get('pewarnaan_details', 'Pewarnaan::pewarnaan_details');
    $routes->post('delete', 'Pewarnaan::delete');
    $routes->get('edit', 'Pewarnaan::edit');
    $routes->post('update', 'Pewarnaan::update');
});

// Route untuk Pewarnaan srs
$routes->group('pewarnaan_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Pewarnaan::index');
    $routes->post('proses_pewarnaan', 'Pewarnaan::proses_pewarnaan');
    $routes->get('pewarnaan_details', 'Pewarnaan::pewarnaan_details');
    $routes->post('delete', 'Pewarnaan::delete');
    $routes->get('edit', 'Pewarnaan::edit');
    $routes->post('update', 'Pewarnaan::update');
});

// Route untuk Pembacaan hpa
$routes->group('pembacaan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pembacaan::index');
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan');
    $routes->get('pembacaan_details', 'Pembacaan::pembacaan_details');
    $routes->post('delete', 'Pembacaan::delete');
    $routes->get('edit', 'Pembacaan::edit');
    $routes->post('update', 'Pembacaan::update');
});

// Route untuk Pembacaan frs
$routes->group('pembacaan_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Pembacaan::index');
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan');
    $routes->get('pembacaan_details', 'Pembacaan::pembacaan_details');
    $routes->post('delete', 'Pembacaan::delete');
    $routes->get('edit', 'Pembacaan::edit');
    $routes->post('update', 'Pembacaan::update');
});

// Route untuk Pembacaan srs
$routes->group('pembacaan_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Pembacaan::index');
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan');
    $routes->get('pembacaan_details', 'Pembacaan::pembacaan_details');
    $routes->post('delete', 'Pembacaan::delete');
    $routes->get('edit', 'Pembacaan::edit');
    $routes->post('update', 'Pembacaan::update');
});

// Route untuk Pembacaan ihc
$routes->group('pembacaan_ihc', ['namespace' => 'App\Controllers\Ihc\Proses'], function ($routes) {
    $routes->get('index', 'Pembacaan::index');
    $routes->post('proses_pembacaan', 'Pembacaan::proses_pembacaan');
    $routes->get('pembacaan_details', 'Pembacaan::pembacaan_details');
    $routes->post('delete', 'Pembacaan::delete');
    $routes->get('edit', 'Pembacaan::edit');
    $routes->post('update', 'Pembacaan::update');
});

// Route untuk Penulisan hpa
$routes->group('penulisan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Penulisan::index');
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan');
    $routes->get('penulisan_details', 'Penulisan::penulisan_details');
    $routes->post('delete', 'Penulisan::delete');
    $routes->get('edit', 'Penulisan::edit');
    $routes->post('update', 'Penulisan::update');
});

// Route untuk Penulisan frs
$routes->group('penulisan_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Penulisan::index');
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan');
    $routes->get('penulisan_details', 'Penulisan::penulisan_details');
    $routes->post('delete', 'Penulisan::delete');
    $routes->get('edit', 'Penulisan::edit');
    $routes->post('update', 'Penulisan::update');
});

// Route untuk Penulisan srs
$routes->group('penulisan_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Penulisan::index');
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan');
    $routes->get('penulisan_details', 'Penulisan::penulisan_details');
    $routes->post('delete', 'Penulisan::delete');
    $routes->get('edit', 'Penulisan::edit');
    $routes->post('update', 'Penulisan::update');
});

// Route untuk Penulisan ihc
$routes->group('penulisan_ihc', ['namespace' => 'App\Controllers\Ihc\Proses'], function ($routes) {
    $routes->get('index', 'Penulisan::index');
    $routes->post('proses_penulisan', 'Penulisan::proses_penulisan');
    $routes->get('penulisan_details', 'Penulisan::penulisan_details');
    $routes->post('delete', 'Penulisan::delete');
    $routes->get('edit', 'Penulisan::edit');
    $routes->post('update', 'Penulisan::update');
});

// Route untuk Pemverifikasi hpa
$routes->group('pemverifikasi_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pemverifikasi::index');
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi');
    $routes->get('pemverifikasi_details', 'Pemverifikasi::pemverifikasi_details');
    $routes->post('delete', 'Pemverifikasi::delete');
    $routes->get('edit', 'Pemverifikasi::edit');
    $routes->post('update', 'Pemverifikasi::update');
});

// Route untuk Pemverifikasi frs
$routes->group('pemverifikasi_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Pemverifikasi::index');
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi');
    $routes->get('pemverifikasi_details', 'Pemverifikasi::pemverifikasi_details');
    $routes->post('delete', 'Pemverifikasi::delete');
    $routes->get('edit', 'Pemverifikasi::edit');
    $routes->post('update', 'Pemverifikasi::update');
});

// Route untuk Pemverifikasi srs
$routes->group('pemverifikasi_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Pemverifikasi::index');
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi');
    $routes->get('pemverifikasi_details', 'Pemverifikasi::pemverifikasi_details');
    $routes->post('delete', 'Pemverifikasi::delete');
    $routes->get('edit', 'Pemverifikasi::edit');
    $routes->post('update', 'Pemverifikasi::update');
});

// Route untuk Pemverifikasi ihc
$routes->group('pemverifikasi_ihc', ['namespace' => 'App\Controllers\Ihc\Proses'], function ($routes) {
    $routes->get('index', 'Pemverifikasi::index');
    $routes->post('proses_pemverifikasi', 'Pemverifikasi::proses_pemverifikasi');
    $routes->get('pemverifikasi_details', 'Pemverifikasi::pemverifikasi_details');
    $routes->post('delete', 'Pemverifikasi::delete');
    $routes->get('edit', 'Pemverifikasi::edit');
    $routes->post('update', 'Pemverifikasi::update');
});

// Route untuk Authorized hpa
$routes->group('authorized_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Authorized::index');
    $routes->post('proses_authorized', 'Authorized::proses_authorized');
    $routes->get('authorized_details', 'Authorized::authorized_details');
    $routes->post('delete', 'Authorized::delete');
    $routes->get('edit', 'Authorized::edit');
    $routes->post('update', 'Authorized::update');
});

// Route untuk Authorized frs
$routes->group('authorized_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Authorized::index');
    $routes->post('proses_authorized', 'Authorized::proses_authorized');
    $routes->get('authorized_details', 'Authorized::authorized_details');
    $routes->post('delete', 'Authorized::delete');
    $routes->get('edit', 'Authorized::edit');
    $routes->post('update', 'Authorized::update');
});

// Route untuk Authorized srs
$routes->group('authorized_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Authorized::index');
    $routes->post('proses_authorized', 'Authorized::proses_authorized');
    $routes->get('authorized_details', 'Authorized::authorized_details');
    $routes->post('delete', 'Authorized::delete');
    $routes->get('edit', 'Authorized::edit');
    $routes->post('update', 'Authorized::update');
});

// Route untuk Authorized ihc
$routes->group('authorized_ihc', ['namespace' => 'App\Controllers\ihc\Proses'], function ($routes) {
    $routes->get('index', 'Authorized::index');
    $routes->post('proses_authorized', 'Authorized::proses_authorized');
    $routes->get('authorized_details', 'Authorized::authorized_details');
    $routes->post('delete', 'Authorized::delete');
    $routes->get('edit', 'Authorized::edit');
    $routes->post('update', 'Authorized::update');
});

// Route untuk Pencetakan hpa
$routes->group('pencetakan_hpa', ['namespace' => 'App\Controllers\Hpa\Proses'], function ($routes) {
    $routes->get('index', 'Pencetakan::index');
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan');
    $routes->get('pencetakan_details', 'Pencetakan::pencetakan_details');
    $routes->post('delete', 'Pencetakan::delete');
    $routes->get('edit', 'Pencetakan::edit');
    $routes->post('update', 'Pencetakan::update');
});

// Route untuk Pencetakan frs
$routes->group('pencetakan_frs', ['namespace' => 'App\Controllers\Frs\Proses'], function ($routes) {
    $routes->get('index', 'Pencetakan::index');
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan');
    $routes->get('pencetakan_details', 'Pencetakan::pencetakan_details');
    $routes->post('delete', 'Pencetakan::delete');
    $routes->get('edit', 'Pencetakan::edit');
    $routes->post('update', 'Pencetakan::update');
});

// Route untuk Pencetakan srs
$routes->group('pencetakan_srs', ['namespace' => 'App\Controllers\Srs\Proses'], function ($routes) {
    $routes->get('index', 'Pencetakan::index');
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan');
    $routes->get('pencetakan_details', 'Pencetakan::pencetakan_details');
    $routes->post('delete', 'Pencetakan::delete');
    $routes->get('edit', 'Pencetakan::edit');
    $routes->post('update', 'Pencetakan::update');
});

// Route untuk Pencetakan ihc
$routes->group('pencetakan_ihc', ['namespace' => 'App\Controllers\Ihc\Proses'], function ($routes) {
    $routes->get('index', 'Pencetakan::index');
    $routes->post('proses_pencetakan', 'Pencetakan::proses_pencetakan');
    $routes->get('pencetakan_details', 'Pencetakan::pencetakan_details');
    $routes->post('delete', 'Pencetakan::delete');
    $routes->get('edit', 'Pencetakan::edit');
    $routes->post('update', 'Pencetakan::update');
});

// Router untuk Mutu Hpa
$routes->group('mutu_hpa', ['namespace' => 'App\Controllers\Hpa'], function ($routes) {
    $routes->get('index', 'mutu::index');
    $routes->post('proses_mutu', 'mutu::proses_mutu');
    $routes->get('mutu_details', 'mutu::mutu_details');
    $routes->get('edit', 'mutu::edit');
    $routes->post('update', 'mutu::update');
});

// Router untuk Mutu Frs
$routes->group('mutu_frs', ['namespace' => 'App\Controllers\Frs'], function ($routes) {
    $routes->get('index', 'mutu::index');
    $routes->post('proses_mutu', 'mutu::proses_mutu');
    $routes->get('mutu_details', 'mutu::mutu_details');
    $routes->get('edit', 'mutu::edit');
    $routes->post('update', 'mutu::update');
});

// Router untuk Mutu Srs
$routes->group('mutu_srs', ['namespace' => 'App\Controllers\Srs'], function ($routes) {
    $routes->get('index', 'mutu::index');
    $routes->post('proses_mutu', 'mutu::proses_mutu');
    $routes->get('mutu_details', 'mutu::mutu_details');
    $routes->get('edit', 'mutu::edit');
    $routes->post('update', 'mutu::update');
});

// Router untuk Mutu Ihc
$routes->group('mutu_ihc', ['namespace' => 'App\Controllers\Ihc'], function ($routes) {
    $routes->get('index', 'mutu::index');
    $routes->post('proses_mutu', 'mutu::proses_mutu');
    $routes->get('mutu_details', 'mutu::mutu_details');
    $routes->get('edit', 'mutu::edit');
    $routes->post('update', 'mutu::update');
});
