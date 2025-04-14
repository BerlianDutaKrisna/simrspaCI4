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

            <button class="btn btn-outline-info mb-3" type="button" data-toggle="collapse" data-target="#riwayatCollapse" aria-expanded="false" aria-controls="riwayatCollapse">
                <i class="fas fa-book-medical"></i> Riwayat Pemeriksaan
            </button>

            <div class="collapse" id="riwayatCollapse">
                <div class="form-group row">
                    <!-- Riwayat Pemeriksaan Histopatologi (HPA) -->
                    <div class="col-md-3">
                        <label class="col-form-label">Riwayat Pemeriksaan <b>Histopatologi</b></label>
                        <?php if (!empty($riwayat_hpa)) : ?>
                            <?php foreach ($riwayat_hpa as $row) : ?>
                                <div class="border p-2 mb-2">
                                    <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                    <strong>Kode HPA:</strong> <?= esc($row['kode_hpa'] ?? '-') ?><br>
                                    <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                    <strong>Hasil HPA:</strong> <?= esc(strip_tags($row['hasil_hpa'])) ?? '-' ?><br>
                                    <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="tampilkanModal('<?= nl2br(esc($row['print_hpa'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                        Lihat Detail
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Tidak ada riwayat pemeriksaan HPA.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Riwayat Pemeriksaan FRS -->
                    <div class="col-md-3">
                        <label class="col-form-label">Riwayat Pemeriksaan <b>Fine Needle Aspiration Biopsy</b></label>
                        <?php if (!empty($riwayat_frs)) : ?>
                            <?php foreach ($riwayat_frs as $row) : ?>
                                <div class="border p-2 mb-2">
                                    <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                    <strong>Kode FRS:</strong> <?= esc($row['kode_frs'] ?? '-') ?><br>
                                    <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                    <strong>Hasil FRS:</strong> <?= esc(strip_tags($row['hasil_frs'])) ?? '-' ?><br>
                                    <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="tampilkanModal('<?= nl2br(esc($row['print_frs'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                        Lihat Detail
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Tidak ada riwayat pemeriksaan FRS.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Riwayat Pemeriksaan SRS -->
                    <div class="col-md-3">
                        <label class="col-form-label">Riwayat Pemeriksaan <b>Sitologi</b></label>
                        <?php if (!empty($riwayat_srs)) : ?>
                            <?php foreach ($riwayat_srs as $row) : ?>
                                <div class="border p-2 mb-2">
                                    <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                    <strong>Kode SRS:</strong> <?= esc($row['kode_srs'] ?? '-') ?><br>
                                    <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                    <strong>Hasil SRS:</strong> <?= esc(strip_tags($row['hasil_srs'])) ?? '-' ?><br>
                                    <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="tampilkanModal('<?= nl2br(esc($row['print_srs'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                        Lihat Detail
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Tidak ada riwayat pemeriksaan SRS.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Riwayat Pemeriksaan IHC -->
                    <div class="col-md-3">
                        <label class="col-form-label">Riwayat Pemeriksaan <b>Imunohistokimia</b></label>
                        <?php if (!empty($riwayat_ihc)) : ?>
                            <?php foreach ($riwayat_ihc as $row) : ?>
                                <div class="border p-2 mb-2">
                                    <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                    <strong>Kode IHC:</strong> <?= esc($row['kode_ihc'] ?? '-') ?><br>
                                    <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                    <strong>Hasil IHC:</strong> <?= esc(strip_tags($row['hasil_ihc'])) ?? '-' ?><br>
                                    <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="tampilkanModal('<?= nl2br(esc($row['print_ihc'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                        Lihat Detail
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Tidak ada riwayat pemeriksaan IHC.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

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

<!-- Modal Riwayat -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Isi modal akan dimasukkan melalui JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- javascript untuk menampilkan modal -->
<script>
    function tampilkanModal(isi) {
        // Masukkan isi ke dalam modal
        document.getElementById("modal-body-content").innerHTML = isi;
        // Tampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById("modalDetail"));
        myModal.show();
    }
</script>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>
<?= $this->include('templates/frs/cetak_informasi'); ?>