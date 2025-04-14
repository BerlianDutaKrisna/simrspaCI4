<?= $this->include('templates/ihc/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Print ihc</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print Imunohistokimia</h1>

            <!-- Form -->
            <form id="form-ihc" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_ihc" value="<?= $ihc['id_ihc'] ?>">
                <input type="hidden" name="id_pemverifikasi_ihc" value="<?= $ihc['id_pemverifikasi_ihc'] ?? '' ?>">
                <input type="hidden" name="id_authorized_ihc" value="<?= $ihc['id_authorized_ihc'] ?? '' ?>">
                <input type="hidden" name="id_pencetakan_ihc" value="<?= $ihc['id_pencetakan_ihc'] ?? '' ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- Tombol Kembali  -->
                <a href="javascript:history.back()" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control summernote_print" name="print_ihc" id="print_ihc" rows="5">
                            <font size="5" face="verdana"><?= $ihc['print_ihc'] ?? '' ?></font>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('ihc/update_print/' . $ihc['id_ihc']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi_ihc') ? '' : 'disabled' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-info btn-user w-100"
                            formaction="<?= base_url('ihc/update_print/' . $ihc['id_ihc']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_authorized_ihc') ? '' : 'disabled' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100 mb-3"
                            formaction="<?= base_url('ihc/update_print/' . $ihc['id_ihc']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_ihc') ? '' : 'disabled' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-user w-100 w-md-auto"
                            onclick="cetakPrintihc()"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_ihc') ? '' : 'disabled' ?>>
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/ihc/footer_cetak'); ?>
<?= $this->include('templates/ihc/cetak_print'); ?>