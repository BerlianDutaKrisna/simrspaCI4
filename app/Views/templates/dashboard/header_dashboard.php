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
    
    <!-- Menambahkan favicon pada tab browser menggunakan base_url() -->
    <link href="<?= base_url('img/favicon.ico') ?>" rel="shortcut icon">
    
    <!-- Menambahkan Font Awesome untuk ikon dalam tampilan -->
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    
    <!-- Menambahkan font Google Nunito untuk styling teks -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Menambahkan custom CSS untuk tema SB Admin 2 menggunakan base_url() -->
    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet">
    
    <!-- Inline styles untuk status kelas (diterima, mulai, selesai, default) -->
    <style>
        /* Status diterima, berwarna merah dan tebal */
        .diterima {
            color: red;
            font-weight: bold;
        }

        /* Status mulai, berwarna oranye dan tebal */
        .mulai {
            color: orange;
            font-weight: bold;
        }

        /* Status selesai, berwarna hijau dan tebal */
        .selesai {
            color: green;
            font-weight: bold;
        }

        /* Status default, berwarna hitam */
        .default {
            color: black;
        }
    </style>
    
    <!-- Menambahkan CSS untuk styling tabel, khusus untuk penggunaan DataTables menggunakan base_url() -->
    <link href="<?= base_url('vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">