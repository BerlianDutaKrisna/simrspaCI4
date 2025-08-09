<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pasien</h6>
    </div>
    <div class="card-body">
        <h1>Data Pasien</h1>

        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Form untuk input data pasien -->
        <form action="<?= base_url('patient/insert') ?>" method="POST">
            <?= csrf_field(); ?> <!-- CSRF token untuk keamanan -->
            <input type="hidden" name="id_pasien" id="id_pasien" value="<?= old('id_pasien') ?? ($patient['id_pasien'] ?? date('ymdHis')) ?>" required>

            <div class="form-row">
                <!-- Nomor Rekam Medis -->
                <div class="form-group col-md-3">
                    <label for="norm_pasien">Nomor Rekam Medis</label>
                    <input type="text"
                        class="form-control"
                        id="norm_pasien"
                        name="norm_pasien"
                        placeholder="Masukkan Norm pasien"
                        value="<?=
                                isset($patient) ? esc($patient['norm_pasien']) : (isset($_GET['norm']) ? esc($_GET['norm']) : old('norm_pasien')); ?>"
                        required>
                </div>

                <!-- Nama Pasien -->
                <div class="form-group col-md-9">
                    <label for="nama_pasien">Nama Pasien</label>
                    <input type="text"
                        class="form-control"
                        id="nama_pasien"
                        name="nama_pasien"
                        placeholder="Masukkan nama pasien"
                        value="<?= isset($patient['nama_pasien']) ? esc($patient['nama_pasien']) : (old('nama_pasien') ?: ''); ?>"
                        required>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label for="alamat_pasien">Alamat Pasien</label>
                <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" placeholder="Masukkan alamat pasien"><?= isset($patient['alamat_pasien']) ? esc($patient['alamat_pasien']) : (old('alamat_pasien') ?: '') ?></textarea>
            </div>

            <div class="form-row">
                <!-- Tanggal Lahir -->
                <div class="form-group col-md-4">
                    <label for="tanggal_lahir_pasien">Tanggal Lahir</label>
                    <input type="date"
                        class="form-control"
                        id="tanggal_lahir_pasien"
                        name="tanggal_lahir_pasien"
                        value="<?= isset($patient['tanggal_lahir_pasien']) ? esc($patient['tanggal_lahir_pasien']) : (old('tanggal_lahir_pasien') ?: '') ?>">
                </div>

                <!-- Jenis Kelamin -->
                <div class="form-group col-md-4">
                    <label for="jenis_kelamin_pasien">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin_pasien" name="jenis_kelamin_pasien">
                        <option value="Belum Dipilih" <?= (!isset($patient['jenis_kelamin_pasien']) && old('jenis_kelamin_pasien') !== 'L' && old('jenis_kelamin_pasien') !== 'P') ? 'selected' : '' ?>>Pilih jenis kelamin</option>
                        <option value="L" <?= (isset($patient['jenis_kelamin_pasien']) && $patient['jenis_kelamin_pasien'] == 'L') || old('jenis_kelamin_pasien') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= (isset($patient['jenis_kelamin_pasien']) && $patient['jenis_kelamin_pasien'] == 'P') || old('jenis_kelamin_pasien') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <!-- Status Pasien -->
                <div class="form-group col-md-4">
                    <label for="status_pasien">Status Pasien</label>
                    <select class="form-control" id="status_pasien" name="status_pasien">
                        <option value="Belum Dipilih" <?= (!isset($patient['status_pasien']) && old('status_pasien') === '') ? 'selected' : '' ?>>Pilih status pasien</option>
                        <option value="JKN / BPJS PBI" <?= (isset($patient['status_pasien']) && $patient['status_pasien'] == 'JKN / BPJS PBI') || old('status_pasien') == 'JKN / BPJS PBI' ? 'selected' : '' ?>>JKN / BPJS PBI</option>
                        <option value="JKN / BPJS NON PBI" <?= (isset($patient['status_pasien']) && $patient['status_pasien'] == 'JKN / BPJS NON PBI') || old('status_pasien') == 'JKN / BPJS NON PBI' ? 'selected' : '' ?>>JKN / BPJS NON PBI</option>
                        <option value="Umum" <?= (isset($patient['status_pasien']) && $patient['status_pasien'] == 'Umum') || old('status_pasien') == 'Umum' ? 'selected' : '' ?>>Umum</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>