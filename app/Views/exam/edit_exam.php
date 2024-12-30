<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Traker Histopatologi</title>
    <link href="<?= base_url('img/favicon.ico') ?>" rel="shortcut icon">
    
    <!-- Menambahkan Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Menambahkan font Google Nunito -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700" rel="stylesheet">

    <!-- Menambahkan custom CSS untuk tema SB Admin 2 -->
    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet">

    <!-- Menambahkan CSS untuk Summernote -->
    <link href="<?= base_url('css/summernote-bs4.min.css') ?>" rel="stylesheet">

    <!-- Menambahkan Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>">
</head>

<body id="page-top">

<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Hpa</h6>
    </div>
    <div class="card-body">
        <h1>Edit Data Hpa</h1>
        <!-- Tombol Kembali ke halaman sebelumnya -->
        <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

        <form action="<?= base_url('hpa/update/' . $hpa['id_hpa']); ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <!-- Form Input Data -->
            <div class="form-group">
                <label for="summernote">Makroskopis Hpa</label>
                <textarea id="summernote" name="content"><?= $hpa['makroskopis_hpa']; ?></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>

<!-- Footer -->
<footer class="sticky-footer bg-danger text-white">
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-6 text-center text-md-left">
                <p class="mb-0">© 2025 SIMRSPA - Developed by <strong>Berlian Duta Krisna S.Tr.Kes</strong></p>
            </div>
            <div class="col-md-6 text-center text-md-right">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="https://www.linkedin.com/in/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.instagram.com/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="mailto:berliandutakrisna@gmail.com" class="text-white">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap core JavaScript -->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Summernote JS -->
<script src="<?= base_url('js/summernote-bs4.min.js') ?>"></script>

<!-- Custom scripts for all pages -->
<script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>

<!-- Initialize Summernote -->
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300, // Ukuran editor
            placeholder: 'Diterima ...',
            tabsize: 2
        });
    });
</script>
</body>
</html>
