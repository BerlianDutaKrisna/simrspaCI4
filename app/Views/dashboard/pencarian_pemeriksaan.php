<div class="card shadow mb-4">
    <!-- Card Header: Judul untuk card -->
    <div class="card-header py-4">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Pemeriksaan</h6>
    </div>

    <!-- Card Body: Konten utama dari card -->
    <div class="card-body">
        <!-- Row 1: Pencarian Pasien SIMRS (lebih luas) -->
        <div class="row mb-3">
            <!-- Kolom pertama: Pencarian Pasien SIMRS (lebih lebar) -->
            <div class="col-md-8 col-sm-12 mb-3">
                <h5 class="mb-2">Pencarian Pasien <strong class="text-primary">SIMRS</strong></h5>
                <div class="input-group">
                    <input type="text" id="norm_simrs" name="norm_simrs" class="form-control" placeholder="Masukkan Norm Pasien SIMRS">
                    <div class="input-group-append">
                        <button type="button" id="searchButtonSimrs" class="btn btn-primary">
                            <i class="fas fa-search fa-sm"></i> Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kolom kedua: Pencarian Manual (lebih sempit) -->
            <div class="col-md-4 col-sm-12 mb-3">
                <h5 class="mb-2">Pencarian Manual</h5>
                <div class="input-group">
                <input type="text" id="norm" name="norm" class="form-control" placeholder="Masukkan Norm Pasien">
                    <div class="input-group-append">
                    <button type="button" id="searchButton" class="btn btn-secondary">
                            <i class="fas fa-search fa-sm"></i> Cari <!-- Ikon untuk tombol cari -->
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Menampilkan Hasil -->
        <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultModalLabel">Hasil Pencarian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        <!-- Hasil pencarian akan dimasukkan di sini -->
                    </div>
                    <div class="modal-footer" id="modalFooter">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/dashboard/script_pencarian_simrs'); ?>
<?= $this->include('templates/dashboard/script_pencarian_manual'); ?>