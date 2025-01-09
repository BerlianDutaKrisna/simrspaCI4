<?= $this->include('templates/cetak/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Hpa</h6>
        </div>
        <div class="card-body">
            <h1>Print Hpa</h1>
            <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <textarea class="form-control summernote_hpa" name="print_hpa" id="print_hpa"><?= $hpa['print_hpa'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn btn-success btn-user w-100">Cetak</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/cetak/footer_cetak'); ?>