<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card-body">
    <div class="table-responsive">
        <form action="" method="post">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <div class="m-3">
                            <h3 class="text">Penerimaan</h3>
                        </div>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>No</th>
                        <th>Kode HPA</th>
                        <th>Nama Pasien</th>
                        <th>Deadline Hasil</th>
                        <th>Analis</th>
                        <th>Aksi</th>
                        <th>Status Proses</th>
                        <th>Kualitas Sediaan</th>
                    </tr>
                    <tr>
                        <!-- Row data should be inserted here -->
                    </tr>
                    <tr>
                        <td colspan="8" class="text-center">No data available</td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="form-group col-6">
                    <button type="submit" name="btn_proses_mulai" class="btn btn-danger btn-user btn-block">
                        <i class="fas fa-play"></i> Mulai Pengecekan
                    </button>
                </div>
                <div class="form-group col-6">
                    <button type="submit" name="btn_proses_selesai" class="btn btn-success btn-user btn-block">
                        <i class="fas fa-pause"></i> Selesai Pengecekan
                    </button>
                </div>
            </div>

            <div class="form-group col-12">
                <button type="submit" name="btn_proses_lanjut" class="btn btn-info btn-user btn-block">
                    <i class="fas fa-step-forward"></i> Lanjutkan Mengiris
                </button>
            </div>

            <div class="form-group col-12">
                <button type="submit" name="btn_proses_kembali" class="btn btn-warning btn-user btn-block">
                    <i class="fas fa-undo-alt"></i> Kembalikan Pengecekan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>