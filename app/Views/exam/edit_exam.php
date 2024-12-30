<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Traker Histopatologi</title>
    <link rel="shortcut icon" href="<?= base_url('img/favicon.ico') ?>">

    <!-- CDN jQuery -->
    <script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>

    <!-- CDN Font Awesome (Versi 5.15.4) -->
    <link href="<?= base_url('assets/fontawesome/css/all.min.css') ?>" rel="stylesheet">

    <!-- CDN Summernote CSS (Versi terbaru) -->
    <link href="<?= base_url('assets/summernote/summernote.css') ?>" rel="stylesheet">

    <!-- CDN Bootstrap 5 CSS -->
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet">

    <style>
        .note-editor .dropdown-toggle::after {
            all: unset;
        }

        .note-editor .note-dropdown-menu {
            box-sizing: content-box;
        }

        .note-editor .note-modal-footer {
            box-sizing: content-box;
        }
    </style>

</head>

<body id="page-top">
    <?= $this->include('templates/dashboard/navbar_dashboard'); ?>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Hpa</h6>
            </div>
            <div class="card-header py-2">
                <div class="row align-items-center">
                    <!-- Kolom Judul -->
                    <div class="col-12 col-sm-11">
                        <h6 class="m-2 font-weight-bold text-primary">Edit data HPA</h6>
                    </div>

                    <!-- Kolom Tombol Cetak -->
                    <div class="col-12 col-sm-1 text-center text-sm-right">
                        <a href="#" target="_blank" class="btn btn-primary btn-user btn-block">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h1>Edit Data Hpa</h1>
                <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

                <form action="#" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode HPA</label>
                        <div class="col-sm-4">
                            <input type="text" name="kode_hpa" value="<?= $hpa['kode_hpa'] ?? '' ?>" class="form-control">
                        </div>

                        <label class="col-sm-2 col-form-label">Diagnosa</label>
                        <div class="col-sm-4">
                            <input type="text" name="diagnosa_klinik" value="<?= $hpa['diagnosa_klinik'] ?? '' ?>" class="form-control form-control-user">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pasien</label>
                        <div class="col-sm-4">
                            <input type="text" name="nama_pasien" value="<?= $hpa['nama_pasien'] ?? '' ?>" class="form-control form-control-user">
                        </div>

                        <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                        <div class="col-sm-4">
                            <input type="text" name="dokter_pengirim" value="<?= $hpa['dokter_pengirim'] ?? '' ?>" class="form-control form-control-user">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Norm Pasien</label>
                        <div class="col-sm-4">
                            <input type="text" name="norm_pasien" value="<?= $hpa['norm_pasien'] ?? '' ?>" class="form-control form-control-user">
                        </div>

                        <label class="col-sm-2 col-form-label">Unit Asal</label>
                        <div class="col-sm-4">
                            <input type="text" name="unit_asal" value="<?= $hpa['unit_asal'] ?? '' ?>" class="form-control form-control-user">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tindakan Spesimen</label>
                        <div class="col-sm-4">
                            <input type="text" name="tindakan_spesimen" value="<?= $hpa['tindakan_spesimen'] ?? '' ?>" class="form-control form-control-user">
                        </div>

                        <label class="col-sm-2 col-form-label">Tanggal Permintaan</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggal_permintaan" value="<?= $hpa['tanggal_permintaan'] ?? '' ?>" class="form-control form-control-user">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Lokasi Spesimen</label>
                        <div class="col-sm-4">
                            <input type="text" name="lokasi_spesimen" value="<?= $hpa['lokasi_spesimen'] ?? '' ?>" class="form-control form-control-user">
                        </div>

                        <label class="col-sm-2 col-form-label">Tanggal Hasil</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggal_hasil" value="<?= $hpa['tanggal_hasil'] ?? '' ?>" class="form-control form-control-user">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Foto Makroskopis</label>
                        <div class="col-sm-6">
                            <img src="" width="200" alt="">
                            <input type="file" name="foto_makroskopis_hpa" class="form-control form-control-user mt-2">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Makroskopis</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="makroskopis_hpa"><?= $hpa['makroskopis_hpa'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Foto Mikroskopis</label>
                        <div class="col-sm-6">
                            <img src="" width="200" alt="">
                            <input type="file" name="foto_mikroskopis_hpa" class="form-control form-control-user mt-2">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Mikroskopis</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="mikroskopis_hpa"><?= $hpa['mikroskopis_hpa'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Hasil HPA</label>
                        <div class="col-sm-4">
                            <input type="text" name="hasil_hpa" value="<?= $hpa['hasil_hpa'] ?? '' ?>" class="form-control form-control-user">
                        </div>

                        <label class="col-sm-2 col-form-label" for="jumlah_slide">Jumlah Slide</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="jumlah_slide" name="jumlah_slide" onchange="handleJumlahSlideChange(this)">
                                <option value="0" selected>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <input type="text" class="form-control mt-2 d-none" id="jumlah_slide_custom" name="jumlah_slide_custom" placeholder="Masukkan Jumlah Slide Lainnya">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <a href="#" class="btn btn-success btn-user btn-block">Simpan</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?= $this->include('templates/notifikasi') ?>

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

    <!-- CDN Bootstrap 5 JS (bundle, menyertakan Popper.js) -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- CDN Summernote JS (Versi terbaru) -->
    <script src="<?= base_url('assets/summernote/summernote.js') ?>"></script>
    <!-- Inisialisasi Summernote -->
    <script>
        function handleJumlahSlideChange(selectElement) {
            const customInput = document.getElementById('jumlah_slide_custom');
            if (selectElement.value === 'lainnya') {
                customInput.classList.remove('d-none');
            } else {
                customInput.classList.add('d-none');
                customInput.value = '';
            }
        }

        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline']], // tombol gaya teks
                    ['font', ['fontsize', 'fontname']], // font dan ukuran font
                    ['para', ['ul', 'ol', 'paragraph']], // format paragraf
                    ['color', ['color']], // pilihan warna
                    ['view', ['codeview', 'help']] // menampilkan kode HTML dan bantuan
                ]
            });
        });
    </script>
</body>

</html>