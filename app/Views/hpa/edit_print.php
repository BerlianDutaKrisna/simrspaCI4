<?= $this->include('templates/cetak/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Print Histopatologi</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print Histopatologi</h1>

            <!-- Form -->
            <form id="form-hpa" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa['id_hpa'] ?>">
                <input type="hidden" name="id_pemverifikasi_hpa" value="<?= $hpa['id_pemverifikasi_hpa'] ?? '' ?>">
                <input type="hidden" name="id_authorized_hpa" value="<?= $hpa['id_authorized_hpa'] ?? '' ?>">
                <input type="hidden" name="id_pencetakan_hpa" value="<?= $hpa['id_pencetakan_hpa'] ?? '' ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- Tombol Kembali  -->
                <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control summernote_print" name="print_hpa" id="print_hpa" rows="5">
                            <font size="5" face="verdana"><?= $hpa['print_hpa'] ?? '' ?></font>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('hpa/update_print/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-info btn-user w-100"
                            formaction="<?= base_url('hpa/update_print/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_authorized_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100 mb-3"
                            formaction="<?= base_url('hpa/update_print/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-user w-100 w-md-auto"
                            onclick="cetakPrintHpa()"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/cetak/footer_cetak'); ?>
<?= $this->include('templates/exam/cetak_print'); ?>