<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pasien</h6>
    </div>
    <div class="card-body">
        <h1>Edit Data Pasien</h1>
        <!-- Tombol Kembali ke Daftar Pasien -->
        <a href="<?= base_url('/patient/index_patient') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form Edit Pasien -->
        <form action="<?= base_url('/patient/update/' . $pasien['id_pasien']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <!-- Form group untuk Nomor Rekam Medis -->
                <div class="form-group col-md-3">
                    <label for="norm_pasien">Nomor Rekam Medis</label>
                    <input type="text" class="form-control <?= session('errors.norm_pasien') ? 'is-invalid' : '' ?>" 
                        id="norm_pasien" name="norm_pasien" value="<?= old('norm_pasien', $pasien['norm_pasien']) ?>" required>
                </div>

                <!-- Form group untuk Nama Pasien -->
                <div class="form-group col-md-9">
                    <label for="nama_pasien">Nama Pasien</label>
                    <input type="text" class="form-control <?= session('errors.nama_pasien') ? 'is-invalid' : '' ?>" 
                        id="nama_pasien" name="nama_pasien" value="<?= old('nama_pasien', $pasien['nama_pasien']) ?>" required>
                </div>
            </div>

            <!-- Form group untuk Alamat Pasien -->
            <div class="form-group">
                <label for="alamat_pasien">Alamat Pasien</label>
                <textarea class="form-control <?= session('errors.alamat_pasien') ? 'is-invalid' : '' ?>" 
                    id="alamat_pasien" name="alamat_pasien"><?= old('alamat_pasien', $pasien['alamat_pasien']) ?></textarea>
            </div>

            <!-- Form row untuk Tanggal Lahir, Jenis Kelamin, dan Status Pasien -->
            <div class="form-row">
                <!-- Form group untuk Tanggal Lahir Pasien -->
                <div class="form-group col-md-4">
                    <label for="tanggal_lahir_pasien">Tanggal Lahir</label>
                    <input type="date" class="form-control <?= session('errors.tanggal_lahir_pasien') ? 'is-invalid' : '' ?>" 
                        id="tanggal_lahir_pasien" name="tanggal_lahir_pasien" value="<?= old('tanggal_lahir_pasien', $pasien['tanggal_lahir_pasien']) ?>">
                </div>

                <!-- Form group untuk Jenis Kelamin Pasien -->
                <div class="form-group col-md-4">
                    <label for="jenis_kelamin_pasien">Jenis Kelamin</label>
                    <select class="form-control <?= session('errors.jenis_kelamin_pasien') ? 'is-invalid' : '' ?>" 
                        id="jenis_kelamin_pasien" name="jenis_kelamin_pasien">
                        <option value="Belum Dipilih" <?= old('jenis_kelamin_pasien', $pasien['jenis_kelamin_pasien']) == 'Belum Dipilih' ? 'selected' : '' ?>>Belum Dipilih</option>
                        <option value="L" <?= old('jenis_kelamin_pasien', $pasien['jenis_kelamin_pasien']) == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin_pasien', $pasien['jenis_kelamin_pasien']) == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <!-- Form group untuk Status Pasien -->
                <div class="form-group col-md-4">
                    <label for="status_pasien">Status Pasien</label>
                    <select class="form-control <?= session('errors.status_pasien') ? 'is-invalid' : '' ?>" 
                        id="status_pasien" name="status_pasien">
                        <option value="Belum Dipilih" <?= old('status_pasien', $pasien['status_pasien']) == 'Belum Dipilih' ? 'selected' : '' ?>>Belum Dipilih</option>
                        <option value="PBI" <?= old('status_pasien', $pasien['status_pasien']) == 'PBI' ? 'selected' : '' ?>>PBI</option>
                        <option value="Non PBI" <?= old('status_pasien', $pasien['status_pasien']) == 'Non PBI' ? 'selected' : '' ?>>Non PBI</option>
                        <option value="Umum" <?= old('status_pasien', $pasien['status_pasien']) == 'Umum' ? 'selected' : '' ?>>Umum</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>
