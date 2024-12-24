<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pemeriksaan</h6>
    </div>
    <div class="card-body">
        <h1>Data Pasien</h1>
        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Menampilkan Data Pasien dalam 3 kolom 2 baris -->
        <div class="row">
            <!-- Baris 1 -->
            <div class="col-md-4 mb-3">
                <strong>Nomor Rekam Medis:</strong>
                <p class="form-control-plaintext"><?= isset($patient['norm_pasien']) ? esc($patient['norm_pasien']) : ''; ?></p>
            </div>
            <div class="col-md-4 mb-3">
                <strong>Nama Pasien:</strong>
                <p class="form-control-plaintext"><?= isset($patient['nama_pasien']) ? esc($patient['nama_pasien']) : ''; ?></p>
            </div>
            <div class="col-md-4 mb-3">
                <strong>Alamat Pasien:</strong>
                <p class="form-control-plaintext"><?= isset($patient['alamat_pasien']) ? esc($patient['alamat_pasien']) : ''; ?></p>
            </div>
        </div>

        <div class="row">
            <!-- Baris 2 -->
            <div class="col-md-4 mb-3">
                <strong>Tanggal Lahir:</strong>
                <p class="form-control-plaintext"><?= isset($patient['tanggal_lahir_pasien']) ? esc($patient['tanggal_lahir_pasien']) : ''; ?></p>
            </div>
            <div class="col-md-4 mb-3">
                <strong>Jenis Kelamin:</strong>
                <p class="form-control-plaintext"><?= isset($patient['jenis_kelamin_pasien']) ? esc($patient['jenis_kelamin_pasien']) : ''; ?></p>
            </div>
            <div class="col-md-4 mb-3">
                <strong>Status Pasien:</strong>
                <p class="form-control-plaintext"><?= isset($patient['status_pasien']) ? esc($patient['status_pasien']) : ''; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pemeriksaan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('exam/insert') ?>" method="POST">
            <?= csrf_field(); ?> <!-- CSRF token untuk keamanan -->

            <div class="form-row">
                <!-- Form group untuk Kode HPA -->
                <div class="form-group col-md-3">
                    <label for="kode_hpa">Kode HPA</label>
                    <input type="text" class="form-control" id="kode_hpa" name="kode_hpa" placeholder="Masukkan Kode HPA" value="<?= old('kode_hpa'); ?>" required>
                </div>

                <!-- Form group untuk Unit Asal -->
                <div class="form-group col-md-3">
                    <label for="unit_asal">Unit Asal</label>
                    <input type="text" class="form-control" id="unit_asal" name="unit_asal" placeholder="Masukkan Unit Asal" value="<?= old('unit_asal'); ?>" required>
                </div>

                <!-- Form group untuk Dokter Pengirim -->
                <div class="form-group col-md-3">
                    <label for="dokter_pengirim">Dokter Pengirim</label>
                    <input type="text" class="form-control" id="dokter_pengirim" name="dokter_pengirim" placeholder="Masukkan Dokter Pengirim" value="<?= old('dokter_pengirim'); ?>" required>
                </div>

                <!-- Form group untuk Tanggal Terima -->
                <div class="form-group col-md-3">
                    <label for="tanggal_terima">Tanggal Terima</label>
                    <input type="date" class="form-control" id="tanggal_terima" name="tanggal_terima" value="<?= old('tanggal_terima'); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <!-- Form group untuk Tanggal Hasil -->
                <div class="form-group col-md-3">
                    <label for="tanggal_hasil">Tanggal Hasil</label>
                    <input type="date" class="form-control" id="tanggal_hasil" name="tanggal_hasil" value="<?= old('tanggal_hasil'); ?>" required>
                </div>

                <!-- Form group untuk Lokasi Spesimen -->
                <div class="form-group col-md-3">
                    <label for="lokasi_spesimen">Lokasi Spesimen</label>
                    <input type="text" class="form-control" id="lokasi_spesimen" name="lokasi_spesimen" placeholder="Masukkan Lokasi Spesimen" value="<?= old('lokasi_spesimen'); ?>" required>
                </div>

                <!-- Form group untuk Tindakan Spesimen -->
                <div class="form-group col-md-3">
                    <label for="tindakan_spesimen">Tindakan Spesimen</label>
                    <input type="text" class="form-control" id="tindakan_spesimen" name="tindakan_spesimen" placeholder="Masukkan Tindakan Spesimen" value="<?= old('tindakan_spesimen'); ?>" required>
                </div>

                <!-- Form group untuk Diagnosa Klinik -->
                <div class="form-group col-md-3">
                    <label for="diagnosa_klinik">Diagnosa Klinik</label>
                    <input type="text" class="form-control" id="diagnosa_klinik" name="diagnosa_klinik" placeholder="Masukkan Diagnosa Klinik" value="<?= old('diagnosa_klinik'); ?>" required>
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
