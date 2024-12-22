<div class="card shadow mb-4">
    <div class="card-header py-4">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Pemeriksaan</h6>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-8 col-sm-12 mb-3">
                <form action="<?= base_url('/patient/search_patient') ?>" method="POST">
                    <div class="input-group">
                        <input type="text" name="norm_pasien" id="SearchPatient" class="form-control" 
                                placeholder="Masukkan Norm pasien" autocomplete="off" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-sm"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
