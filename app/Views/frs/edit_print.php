<?= $this->include('templates/hpa/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Print</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print Fine Needle Aspiration Biopsy</h1>

            <!-- Form -->
            <form id="form-frs" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?>">
                <input type="hidden" name="id_pemverifikasi_frs" value="<?= $frs['id_pemverifikasi_frs'] ?? '' ?>">
                <input type="hidden" name="id_authorized_frs" value="<?= $frs['id_authorized_frs'] ?? '' ?>">
                <input type="hidden" name="id_pencetakan_frs" value="<?= $frs['id_pencetakan_frs'] ?? '' ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- Tombol Kembali -->
                <div class="mb-3">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Kembali
                    </a>
                </div>

                <?= $this->include('templates/exam/riwayat'); ?>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control summernote_print" name="print_frs" id="print_frs" rows="5">
                            <font size="5" face="verdana"><?= $frs['print_frs'] ?? '' ?></font>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('frs/update_print/' . $frs['id_frs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-info btn-user w-100"
                            formaction="<?= base_url('frs/update_print/' . $frs['id_frs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_authorized_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100 mb-3"
                            formaction="<?= base_url('frs/update_print/' . $frs['id_frs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-user w-100 w-md-auto"
                            onclick="cetakPrintfrs()"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_cetak'); ?>
<?= $this->include('templates/frs/cetak_print'); ?>