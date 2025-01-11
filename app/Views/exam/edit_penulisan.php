<?= $this->include('templates/cetak/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Penulisan</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Penulisan</h1>
            <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form action="<?= base_url('exam/update/' . $hpa[0]['id_hpa']) ?>" method="POST">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa[0]['id_hpa'] ?>">
                <input type="hidden" name="kode_hpa" value="<?= $hpa[0]['kode_hpa'] ?>">

                <!-- Kolom print -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="print_hpa"></label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote_hpa" name="print_hpa" id="print_hpa" rows="5">
                            <table width="800pt" height="80">
                                <tbody>
                                    <tr>
                                        <td style="border: none;" width="200pt">
                                            <font size="5" face="verdana"><b>LOKASI</b></font>
                                        </td>
                                        <td style="border: none;" width="10pt">
                                            <font size="5" face="verdana"><b>:</b><br></font>
                                        </td>
                                        <td style="border: none;" width="590pt">
                                            <font size="5" face="verdana"><b><?= $hpa[0]['lokasi_spesimen'] ?? '' ?><br></b></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;" width="200pt">
                                            <font size="5" face="verdana"><b>DIAGNOSA KLINIK</b></font>
                                        </td>
                                        <td style="border: none;" width="10pt">
                                            <font size="5" face="verdana"><b>:</b><br></font>
                                        </td>
                                        <td style="border: none;" width="590pt">
                                            <font size="5" face="verdana"><b><?= $hpa[0]['diagnosa_klinik'] ?? '' ?><br></b></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;" width="200pt">
                                            <font size="5" face="verdana"><b>ICD</b></font>
                                        </td>
                                        <td style="border: none;" width="10pt">
                                            <font size="5" face="verdana"><b>:</b></font>
                                        </td>
                                        <td style="border: none;" width="590pt">
                                            <font size="5" face="verdana"><br></font>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <font size="5" face="verdana"><b>LAPORAN PEMERIKSAAN:<br></b></font>
                            <p><b style="font-family: verdana; font-size: x-large;">MAKROSKOPIK :</b></p>
                            <font size="5" face="verdana"><?= $hpa[0]['makroskopis_hpa'] ?? '' ?></font>
                            <div>
                                <font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><?= $hpa[0]['mikroskopis_hpa'] ?? '' ?></font>
                            </div>
                            <br>
                            <b>KESIMPULAN :</b> <?= $hpa[0]['lokasi_spesimen'] ?? '' ?>, <?= $hpa[0]['diagnosa_klinik'] ?? '' ?>:
                            <br><font size="5" face="verdana"><b><?= $hpa[0]['hasil_hpa'] ?? '' ?></b></font>
                        </textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12 text-center">
                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-success btn-user w-100">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/cetak/footer_cetak'); ?>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/summernote/summernote.js') ?>"></script>
<script>
    $(document).ready(function() {
        var height = $(window).height() - 200; // Sesuaikan dengan ukuran yang diinginkan

        $('.summernote_hpa').summernote({
            placeholder: '',
            tabsize: 2,
            height: height, // Menyesuaikan tinggi dengan lebar layar
            enterTag: 'p',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'fontname']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['color', ['color']],
                ['view', ['codeview', 'help']]
            ],
        });
    });
</script>