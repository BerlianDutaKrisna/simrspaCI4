<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk hasil pencarian pasien -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hasil Pencarian Pasien</h6>
        </div>
        <div class="card-body">
            <!-- Tombol Kembali ke Daftar Pasien -->
            <a href="<?= base_url('/patient/index_patient') ?>" class="btn btn-primary mb-3">Kembali</a>

            <!-- Tampilkan Hasil Pencarian Pasien -->
            <?php if (isset($patient)): ?>
                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Nomor Rekam Medis:</strong></h4>
                        <p><?= esc($patient['norm_pasien']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4><strong>Nama Pasien:</strong></h4>
                        <p><?= esc($patient['nama_pasien']) ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Alamat Pasien:</strong></h4>
                        <p><?= esc($patient['alamat_pasien']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4><strong>Tanggal Lahir:</strong></h4>
                        <p><?= esc($patient['tanggal_lahir_pasien']) ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Jenis Kelamin:</strong></h4>
                        <p><?= esc($patient['jenis_kelamin_pasien']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4><strong>Status Pasien:</strong></h4>
                        <p><?= esc($patient['status_pasien']) ?></p>
                    </div>
                </div>

                <!-- Tombol Edit Pasien -->
                <a href="<?= base_url('/patient/edit_patient/' . $patient['id_pasien']) ?>" class="btn btn-warning mb-3">Edit Pasien</a>
            <?php else: ?>
                <p class="text-danger">Pasien dengan norm_pasien tersebut tidak ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>
