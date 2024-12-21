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
        <!-- Tabel Data Users -->
        <form action="<?= base_url('patient/insert') ?>" method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nomor_rekam_medis">Nomor Rekam Medis</label>
                    <input type="text" class="form-control" id="nomor_rekam_medis" name="nomor_rekam_medis" placeholder="Masukkan nomor rekam medis">
                </div>
                <div class="form-group col-md-6">
                    <label for="nama_pasien">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" placeholder="Masukkan nama pasien">
                </div>
            </div>
            <div class="form-group">
                <label for="alamat_pasien">Alamat Pasien</label>
                <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" placeholder="Masukkan alamat pasien"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Masukkan tanggal lahir">
                </div>
                <div class="form-group col-md-4">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                        <option value="Belum Dipilih" selected>Pilih jenis kelamin</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="status_pasien">Status Pasien</label>
                    <select class="form-control" id="status_pasien" name="status_pasien">
                        <option value="Belum Dipilih" selected>Pilih status pasien</option>
                        <option value="PBI">PBI</option>
                        <option value="Non PBI">Non PBI</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/dashboard/footer_dashboard'); ?>