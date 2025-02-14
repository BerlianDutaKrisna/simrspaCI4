<?= $this->include('templates/cetak/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Print HPA</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print HPA</h1>

            <!-- Form -->
            <form id="form-hpa" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa['id_hpa'] ?>">
                <input type="hidden" name="id_pemverifikasi" value="<?= $hpa['id_pemverifikasi'] ?>">
                <input type="hidden" name="id_autorized" value="<?= $hpa['id_autorized'] ?>">
                <input type="hidden" name="id_pencetakan" value="<?= $hpa['id_pencetakan'] ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- Tombol Kembali  -->
                <?php if (($_GET['redirect'] ?? '') === 'index_pencetakan'): ?>
                    <button type="submit" class="btn btn-primary mb-3"
                        formaction="<?= base_url('exam/update_print_hpa/' . $hpa['id_hpa']); ?>">
                        Kembali
                    </button>
                <?php else: ?>
                    <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>
                <?php endif; ?>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control summernote_print" name="print_hpa" id="print_hpa" rows="5">
                            <?= $hpa['print_hpa'] ?? '' ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('exam/update_print_hpa/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_autorized' || ($_GET['redirect'] ?? '') === 'index_pencetakan') ? 'disabled' : '' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-info btn-user w-100"
                            formaction="<?= base_url('exam/update_print_hpa/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi' || ($_GET['redirect'] ?? '') === 'index_pencetakan') ? 'disabled' : '' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100 mb-3"
                            formaction="<?= base_url('exam/update_print_hpa/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_index_pemverfifikasi' || ($_GET['redirect'] ?? '') === 'index_autorized') ? 'disabled' : '' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-user w-100 w-md-auto"
                            onclick="cetakPrintHpa()"
                            <?= (($_GET['redirect'] ?? '') === 'index_index_pemverfifikasi' || ($_GET['redirect'] ?? '') === 'index_autorized') ? 'disabled' : '' ?>>
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
<?= $this->include('templates/exam/cetak_print_hpa'); ?>