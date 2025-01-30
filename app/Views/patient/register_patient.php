<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pasien</h6>
    </div>
    <div class="card-body">
        <h1>Data Pasien</h1>
        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <!-- Form untuk input data pasien -->
        <form action="<?= base_url('patient/insert') ?>" method="POST">
            <?= csrf_field(); ?> <!-- CSRF token untuk keamanan -->

            <div class="form-row">
                <!-- Form group untuk Nomor Rekam Medis dengan ukuran lebih kecil -->
                <div class="form-group col-md-3"> <!-- Ukuran kolom lebih kecil untuk norm_pasien -->
                    <label for="norm_pasien">Nomor Rekam Medis</label>
                    <!-- Input untuk norm_pasien dengan value lama jika ada dan required -->
                    <input type="text"
                        class="form-control"
                        id="norm_pasien"
                        name="norm_pasien"
                        placeholder="Masukkan Norm pasien"
                        value="<?= isset($_GET['norm_pasien']) ? esc($_GET['norm_pasien']) : old('norm_pasien'); ?>"
                        required>

                </div>

                <!-- Form group untuk Nama Pasien dengan ukuran lebih besar -->
                <div class="form-group col-md-9"> <!-- Ukuran kolom lebih besar untuk nama_pasien -->
                    <label for="nama_pasien">Nama Pasien</label>
                    <!-- Input untuk nama_pasien dengan value lama jika ada dan required -->
                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" placeholder="Masukkan nama pasien" value="<?= old('nama_pasien'); ?>" required>
                </div>
            </div>

            <!-- Form group untuk Alamat Pasien -->
            <div class="form-group">
                <label for="alamat_pasien">Alamat Pasien</label>
                <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" placeholder="Masukkan alamat pasien"><?= old('alamat_pasien'); ?></textarea>
            </div>

            <div class="form-row">
                <!-- Form group untuk Tanggal Lahir Pasien -->
                <div class="form-group col-md-4">
                    <label for="tanggal_lahir_pasien">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir_pasien" name="tanggal_lahir_pasien" placeholder="Masukkan tanggal lahir" value="<?= old('tanggal_lahir_pasien'); ?>">
                </div>

                <!-- Form group untuk Jenis Kelamin Pasien -->
                <div class="form-group col-md-4">
                    <label for="jenis_kelamin_pasien">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin_pasien" name="jenis_kelamin_pasien">
                        <option value="Belum Dipilih" selected>Pilih jenis kelamin</option>
                        <option value="L" <?= old('jenis_kelamin_pasien') == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin_pasien') == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <!-- Form group untuk Status Pasien -->
                <div class="form-group col-md-4">
                    <label for="status_pasien">Status Pasien</label>
                    <select class="form-control" id="status_pasien" name="status_pasien">
                        <option value="Belum Dipilih" selected>Pilih status pasien</option>
                        <option value="PBI" <?= old('status_pasien') == 'PBI' ? 'selected' : ''; ?>>PBI</option>
                        <option value="Non PBI" <?= old('status_pasien') == 'Non PBI' ? 'selected' : ''; ?>>Non PBI</option>
                        <option value="Umum" <?= old('status_pasien') == 'Umum' ? 'selected' : ''; ?>>Umum</option>
                    </select>
                </div>
            </div>

            <!-- Tombol untuk submit form -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>