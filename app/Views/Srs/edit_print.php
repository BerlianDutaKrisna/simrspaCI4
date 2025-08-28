<?= $this->include('templates/hpa/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Print</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print Sitologi</h1>

            <!-- Form -->
            <form id="form-srs" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_srs" value="<?= $srs['id_srs'] ?>">
                <input type="hidden" name="id_pemverifikasi_srs" value="<?= $srs['id_pemverifikasi_srs'] ?? '' ?>">
                <input type="hidden" name="id_authorized_srs" value="<?= $srs['id_authorized_srs'] ?? '' ?>">
                <input type="hidden" name="id_pencetakan_srs" value="<?= $srs['id_pencetakan_srs'] ?? '' ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- Tombol Kembali -->
                <div class="mb-3">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Kembali
                    </a>
                </div>

                // Menyertakan riwayat pemeriksaan jika ada
                <?= $this->include('templates/exam/riwayat'); ?>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control summernote_print" name="print_srs" id="print_srs" rows="5">
                            <font size="5" face="verdana"><?= $srs['print_srs'] ?? '' ?></font>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('srs/update_print/' . $srs['id_srs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi_srs') ? '' : 'disabled' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-info btn-user w-100"
                            formaction="<?= base_url('srs/update_print/' . $srs['id_srs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_authorized_srs') ? '' : 'disabled' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100 mb-3"
                            formaction="<?= base_url('srs/update_print/' . $srs['id_srs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_srs') ? '' : 'disabled' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-user w-100 w-md-auto"
                            onclick="cetakPrintsrs()"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_srs') ? '' : 'disabled' ?>>
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/srs/footer_cetak'); ?>
<?= $this->include('templates/srs/cetak_print'); ?>