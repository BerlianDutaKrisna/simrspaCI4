<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Menentukan karakter encoding untuk halaman -->
    <meta charset="utf-8">
    <!-- Memastikan halaman dapat ditampilkan dengan baik di semua browser, termasuk versi lama -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Membuat halaman responsif di perangkat mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Meta tag untuk deskripsi halaman (digunakan oleh search engine dan media sosial) -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Meta refresh, jika diaktifkan akan me-refresh halaman setiap 5 detik (komentar: jika diperlukan) -->
    <!-- <meta http-equiv="refresh" content="5"> -->
    <!-- Judul halaman yang muncul di tab browser -->
    <title>Traker Histopatologi</title>
    <!-- Menambahkan icon traker pada tab browser menggunakan base_url() -->
    <link href="<?= base_url('img/favicon.ico') ?>" rel="shortcut icon">
    <!-- Menambahkan Font Awesome untuk ikon dalam tampilan -->
    <link href="<?= base_url('assets/fontawesome/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <!-- Menambahkan custom CSS untuk tema SB Admin 2 menggunakan base_url() -->
    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet">
    <!-- Menambahkan CSS untuk styling tabel, khusus untuk penggunaan DataTables menggunakan base_url() -->
    <link href="<?= base_url('assets/datatables/css/buttons.dataTables.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/datatables/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    
    <script src="<?= base_url('assets/chart.js/Chart.min.js') ?>"></script> <!-- Menambahkan plugin Chart.js untuk grafik -->
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">