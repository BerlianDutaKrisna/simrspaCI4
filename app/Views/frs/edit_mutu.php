<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit mutu</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Mutu</h1>

        <a href="<?= base_url('frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <form action="<?= base_url('mutu_frs/update') ?>" method="POST">
            <input type="hidden" name="id_mutu_frs" value="<?= $mutuData['id_mutu_frs'] ?>">

            <div class="row mb-3">
                <!-- Kode FRS -->
                <div class="col-md-6">
                    <label for="kode_frs">Kode FRS:</label>
                    <p><?= $mutuData['kode_frs'] ?></p>
                </div>
                <!-- Nama Pasien -->
                <div class="col-md-6">
                    <label for="nama_pasien">Nama Pasien:</label>
                    <p><?= $mutuData['nama_pasien'] ?></p>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Norm Pasien -->
                <div class="col-md-6">
                    <label for="norm_pasien">Norm Pasien:</label>
                    <p><?= $mutuData['norm_pasien'] ?></p>
                </div>
                <div class="col-md-3">
                    <label for="alamat_pasien">Alamat Pasien:</label>
                    <p><?= $mutuData['alamat_pasien'] ?></p>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Indikator Mutu 1 -->
                <div class="col-md-6">
                    <label for="indikator_1">Indikator Mutu 1:</label>
                    <input type="text" class="form-control" id="indikator_1" name="indikator_1" value="<?= $mutuData['indikator_1'] ?>">
                </div>

                <!-- Indikator Mutu 2 -->
                <div class="col-md-6">
                    <label for="indikator_2">Indikator Mutu 2:</label>
                    <input type="text" class="form-control" id="indikator_2" name="indikator_2" value="<?= $mutuData['indikator_2'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Indikator Mutu 3 -->
                <div class="col-md-6">
                    <label for="indikator_3">Indikator Mutu 3:</label>
                    <input type="text" class="form-control" id="indikator_3" name="indikator_3" value="<?= $mutuData['indikator_3'] ?>">
                </div>

                <!-- Indikator Mutu 4 -->
                <div class="col-md-6">
                    <label for="indikator_4">Indikator Mutu 4:</label>
                    <input type="text" class="form-control" id="indikator_4" name="indikator_4" value="<?= $mutuData['indikator_4'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Indikator Mutu 5 -->
                <div class="col-md-6">
                    <label for="indikator_5">Indikator Mutu 5:</label>
                    <input type="text" class="form-control" id="indikator_5" name="indikator_5" value="<?= $mutuData['indikator_5'] ?>">
                </div>

                <!-- Indikator Mutu 6 -->
                <div class="col-md-6">
                    <label for="indikator_6">Indikator Mutu 6:</label>
                    <input type="text" class="form-control" id="indikator_6" name="indikator_6" value="<?= $mutuData['indikator_6'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Indikator Mutu 7 -->
                <div class="col-md-6">
                    <label for="indikator_7">Indikator Mutu 7:</label>
                    <input type="text" class="form-control" id="indikator_7" name="indikator_7" value="<?= $mutuData['indikator_7'] ?>">
                </div>

                <!-- Indikator Mutu 8 -->
                <div class="col-md-6">
                    <label for="indikator_8">Indikator Mutu 8:</label>
                    <input type="text" class="form-control" id="indikator_8" name="indikator_8" value="<?= $mutuData['indikator_8'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Indikator Mutu 9 -->
                <div class="col-md-6">
                    <label for="indikator_9">Indikator Mutu 9:</label>
                    <input type="text" class="form-control" id="indikator_9" name="indikator_9" value="<?= $mutuData['indikator_9'] ?>">
                </div>

                <!-- Indikator Mutu 10 -->
                <div class="col-md-6">
                    <label for="indikator_10">Indikator Mutu 10:</label>
                    <input type="text" class="form-control" id="indikator_10" name="indikator_10" value="<?= $mutuData['indikator_10'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Total Nilai Mutu -->
                <div class="col-md-6">
                    <label for="total_nilai_mutu_frs">Total Nilai Mutu:</label>
                    <input type="text" class="form-control" id="total_nilai_mutu_frs" name="total_nilai_mutu_frs" value="<?= $mutuData['total_nilai_mutu_frs'] ?>" readonly>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>