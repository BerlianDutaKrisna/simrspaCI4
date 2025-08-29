<?= $this->include('templates/frs/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Makroskopis</h6>
    </div>
    <div class="card-body">
        <h1>Edit Data Makroskopis Fine Needle Aspiration Biopsy</h1>
        <a href="<?= base_url('penerimaan_frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Form Utama -->
        <form id="form-frs" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?? '' ?>">
            <input type="hidden" name="id_penerimaan_frs" value="<?= $frs['id_penerimaan_frs'] ?? '' ?>">
            <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

            <!-- Kode FRS dan Diagnosa -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kode FRS</label>
                <div class="col-sm-4">
                    <input type="text" name="kode_frs" value="<?= $frs['kode_frs'] ?? '' ?>" class="form-control">
                </div>

                <label class="col-sm-2 col-form-label">Diagnosa</label>
                <div class="col-sm-4">
                    <input type="text" name="diagnosa_klinik" value="<?= $frs['diagnosa_klinik'] ?? '' ?>" class="form-control">
                </div>
            </div>

            <!-- Nama Pasien dan Dokter Pengirim -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Pasien</label>
                <div class="col-sm-4">
                    <p>&nbsp;<?= $frs['nama_pasien'] ?? '' ?></p>
                </div>

                <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                <div class="col-sm-4">
                    <input type="text" name="dokter_pengirim" value="<?= $frs['dokter_pengirim'] ?? '' ?>" class="form-control">
                </div>
            </div>

            <!-- Norm Pasien dan Unit Asal -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Norm Pasien</label>
                <div class="col-sm-4">
                    <p>&nbsp;<?= $frs['norm_pasien'] ?? '' ?></p>
                </div>

                <label class="col-sm-2 col-form-label">Unit Asal</label>
                <div class="col-sm-4">
                    <input type="text" name="unit_asal" value="<?= $frs['unit_asal'] ?? '' ?>" class="form-control">
                </div>
            </div>

            <?= $this->include('templates/exam/riwayat'); ?>

            <!-- Informed Consent -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informed Consent Tindakan FNAB</h6>
                </div>
                <div class="card-body">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_pasien" value="<?= esc($frs['id_pasien'] ?? ''); ?>">

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="dokter_pemeriksa">Dokter Pemeriksa</label>
                            <select class="form-control" id="dokter_pemeriksa" name="dokter_pemeriksa">
                                <option value="____________________">-- Pilih Dokter --</option>
                                <option value="dr. Vinna Chrisdianti, Sp.PA">dr. Vinna Chrisdianti, Sp.PA</option>
                                <option value="dr. Ayu Tyasmara Pratiwi, Sp.PA">dr. Ayu Tyasmara Pratiwi, Sp.PA</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="nama_hubungan_pasien">Nama Hubungan Pasien</label>
                            <select class="form-control" id="nama_hubungan_pasien" name="nama_hubungan_pasien" onchange="toggleSearchValue()">
                                <option value="____________________">-- Pilih Penandatangan --</option>
                                <option value="<?= esc($frs['nama_pasien'] ?? '') ?>">Pasien Sendiri</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <input type="text" class="form-control mt-2 d-none" id="nama_lainnya" name="nama_lainnya" placeholder="Masukkan Nama Lainnya">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="hubungan_dengan_pasien">Hubungan dengan Pasien</label>
                            <select class="form-control" id="hubungan_dengan_pasien" name="hubungan_dengan_pasien">
                                <option value="____________________">-- Pilih Hubungan --</option>
                                <option value="Pasien Sendiri">Pasien Sendiri</option>
                                <option value="Orang tua">Orang tua</option>
                                <option value="Anak">Anak</option>
                                <option value="Istri">Istri</option>
                                <option value="Suami">Suami</option>
                                <option value="Saudara">Saudara</option>
                                <option value="Pengantar">Pengantar</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="jenis_kelamin_hubungan_pasien">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin_hubungan_pasien" name="jenis_kelamin_hubungan_pasien">
                                <option value="____________________">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="usia_hubungan_pasien">Usia Hubungan Pasien</label>
                            <input type="number" class="form-control" id="usia_hubungan_pasien" name="usia_hubungan_pasien" value="">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function toggleSearchValue() {
                    let namaHubungan = document.getElementById("nama_hubungan_pasien").value;
                    let inputNamaLainnya = document.getElementById("nama_lainnya");
                    let hubunganPasien = document.getElementById("hubungan_dengan_pasien");
                    let jenisKelamin = document.getElementById("jenis_kelamin_hubungan_pasien");
                    let usiaPasien = document.getElementById("usia_hubungan_pasien");

                    if (namaHubungan === "<?= esc($frs['nama_pasien'] ?? '') ?>") {
                        hubunganPasien.value = "Pasien Sendiri";
                        jenisKelamin.value = "<?= esc($frs['jenis_kelamin_pasien'] ?? '') ?>";
                        usiaPasien.value = hitungUsia("<?= esc($frs['tanggal_lahir_pasien'] ?? '') ?>");
                    } else {
                        hubunganPasien.value = "";
                        jenisKelamin.value = "";
                        usiaPasien.value = "";
                    }

                    // Tampilkan atau sembunyikan input nama lainnya
                    if (namaHubungan === "lainnya") {
                        inputNamaLainnya.classList.remove("d-none");
                        inputNamaLainnya.focus();
                    } else {
                        inputNamaLainnya.classList.add("d-none");
                        inputNamaLainnya.value = ""; // Reset nilai input jika tidak dipilih "Lainnya"
                    }
                }

                function hitungUsia(tanggalLahir) {
                    if (!tanggalLahir) return "";
                    let birthDate = new Date(tanggalLahir);
                    let today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    let monthDiff = today.getMonth() - birthDate.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    return age;
                }

                function getNamaHubunganPasien() {
                    let selectElement = document.getElementById("nama_hubungan_pasien").value;
                    let inputNamaLainnya = document.getElementById("nama_lainnya").value;

                    // Jika "Lainnya" dipilih, gunakan nilai input, jika tidak, gunakan nilai dropdown
                    return selectElement === "lainnya" ? inputNamaLainnya : selectElement;
                }

                // Event listener untuk menangkap perubahan nilai
                document.getElementById("nama_hubungan_pasien").addEventListener("change", function() {
                    console.log("Nama Hubungan Pasien:", getNamaHubunganPasien());
                });

                document.getElementById("nama_lainnya").addEventListener("input", function() {
                    console.log("Nama Hubungan Pasien (input lainnya):", getNamaHubunganPasien());
                });
            </script>

            <!-- Tombol Tutup -->
            <div class="form-group row">
                <div class="col-sm-6 text-center mb-3">
                    <button type="submit" class="btn btn-success btn-user w-100 mb-3" formaction="<?= base_url('frs/update_print/' . ($frs['id_frs'] ?? '')); ?>">
                        <i class="fas fa-window-close"></i> Tutup
                    </button>
                </div>
                <div class="col-sm-6 text-center">
                    <!-- Tombol Cetak -->
                    <button type="button" class="btn btn-info btn-user w-100 w-md-auto" onclick="cetakProses()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>
<?= $this->include('templates/frs/cetak_informasi'); ?>