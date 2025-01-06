<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit mutu</h6>
    </div>
    <div class="card-body">
        <h1 class="h4 text-gray-900 mb-4">Edit Data Mutu</h1>

        <a href="<?= base_url('exam/index_exam') ?>" class="btn btn-primary mb-3">Kembali</a>

        <form action="<?= base_url('mutu/update_mutu') ?>" method="POST">
            <input type="hidden" name="id_mutu" value="<?= $mutuData['id_mutu'] ?>">

            <div class="row mb-3">
                <!-- Kode HPA -->
                <div class="col-md-6">
                    <label for="kode_hpa">Kode HPA:</label>
                    <p><?= $mutuData['kode_hpa'] ?></p>
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
                <!-- Volume Cairan Sesuai -->
                <div class="col-md-6">
                    <label for="indikator_1">Volume Cairan Sesuai:</label>
                    <input type="text" class="form-control" id="indikator_1" name="indikator_1" value="<?= $mutuData['indikator_1'] ?>">
                </div>

                <!-- Jaringan Terfiksasi Merata -->
                <div class="col-md-6">
                    <label for="indikator_2">Jaringan Terfiksasi Merata:</label>
                    <input type="text" class="form-control" id="indikator_2" name="indikator_2" value="<?= $mutuData['indikator_2'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Blok Parafin Tidak Ada Fragmentasi -->
                <div class="col-md-6">
                    <label for="indikator_3">Blok Parafin Tidak Ada Fragmentasi:</label>
                    <input type="text" class="form-control" id="indikator_3" name="indikator_3" value="<?= $mutuData['indikator_3'] ?>">
                </div>

                <!-- Sediaan Tanpa Lipatan -->
                <div class="col-md-6">
                    <label for="indikator_4">Sediaan Tanpa Lipatan:</label>
                    <input type="text" class="form-control" id="indikator_4" name="indikator_4" value="<?= $mutuData['indikator_4'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Sediaan Tanpa Goresan Mata Pisau -->
                <div class="col-md-6">
                    <label for="indikator_5">Sediaan Tanpa Goresan Mata Pisau:</label>
                    <input type="text" class="form-control" id="indikator_5" name="indikator_5" value="<?= $mutuData['indikator_5'] ?>">
                </div>

                <!-- Kontras Warna Sediaan Cukup Jelas -->
                <div class="col-md-6">
                    <label for="indikator_6">Kontras Warna Sediaan Cukup Jelas:</label>
                    <input type="text" class="form-control" id="indikator_6" name="indikator_6" value="<?= $mutuData['indikator_6'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Sediaan Tanpa Gelembung Udara -->
                <div class="col-md-6">
                    <label for="indikator_7">Sediaan Tanpa Gelembung Udara:</label>
                    <input type="text" class="form-control" id="indikator_7" name="indikator_7" value="<?= $mutuData['indikator_7'] ?>">
                </div>

                <!-- Sediaan Tanpa Bercak / Sidik Jari -->
                <div class="col-md-6">
                    <label for="indikator_8">Sediaan Tanpa Bercak / Sidik Jari:</label>
                    <input type="text" class="form-control" id="indikator_8" name="indikator_8" value="<?= $mutuData['indikator_8'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Indikator 9 -->
                <div class="col-md-6">
                    <label for="indikator_9">Indikator 9:</label>
                    <input type="text" class="form-control" id="indikator_9" name="indikator_9" value="<?= $mutuData['indikator_9'] ?>">
                </div>

                <!-- Indikator 10 -->
                <div class="col-md-6">
                    <label for="indikator_10">Indikator 10:</label>
                    <input type="text" class="form-control" id="indikator_10" name="indikator_10" value="<?= $mutuData['indikator_10'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <!-- Total Nilai Mutu -->
                <div class="col-md-6">
                    <label for="total_nilai_mutu">Total Nilai Mutu:</label>
                    <input type="text" class="form-control" id="total_nilai_mutu" name="total_nilai_mutu" value="<?= $mutuData['total_nilai_mutu'] ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>