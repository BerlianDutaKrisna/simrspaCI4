<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pemeriksaan</h6>
    </div>
    <div class="card-body">
        <h1>Data Pasien Fine Needle Aspiration Biopsy</h1>
        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Menampilkan Data Pasien dalam 3 kolom 2 baris -->
        <div class="row">
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
                <p class="form-control-plaintext">
                    <?= isset($patient['alamat_pasien']) && !empty($patient['alamat_pasien']) ? esc($patient['alamat_pasien']) : 'Belum diisi'; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <strong>Tanggal Lahir:</strong>
                <p class="form-control-plaintext">
                    <?= isset($patient['tanggal_lahir_pasien']) && !empty($patient['tanggal_lahir_pasien']) ? esc(date('d-m-Y', strtotime($patient['tanggal_lahir_pasien']))) : 'Belum diisi'; ?>
                </p>
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
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pemeriksaan Fine Needle Aspiration Biopsy</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('frs/insert') ?>" method="POST">
            <?= csrf_field(); ?> <!-- CSRF token untuk keamanan -->

            <!-- Hidden input untuk id_pasien -->
            <input type="hidden" name="id_pasien" value="<?= isset($patient['id_pasien']) ? esc($patient['id_pasien']) : ''; ?>">
            <div class="form-row">
                <!-- Form group untuk Kode FRS -->
                <div class="form-group col-md-3">
                    <label for="kode_frs">Kode FRS</label>
                    <input type="text" class="form-control" id="kode_frs" name="kode_frs"
                        value="<?= esc($kode_frs); ?>">
                </div>

                <!-- Form group untuk Unit Asal -->
                <div class="form-group col-md-3">
                    <label for="unit_asal">Unit Asal</label>
                    <select class="form-control" id="unit_asal" name="unit_asal" onchange="handleUnitAsalChange(this)">
                        <option value="" selected>Belum Dipilih</option>
                        <option value="Klinik Bedah">Klinik Bedah</option>
                        <option value="Klinik Bedah Onkologi">Klinik Bedah Onkologi</option>
                        <option value="Klinik Bedah Mulut">Klinik Bedah Mulut</option>
                        <option value="Klinik Anak">Klinik Anak</option>
                        <option value="Klinik Paru">Klinik Paru</option>
                        <option value="Klinik THT">Klinik THT</option>
                        <option value="Klinik Kulit dan Kelamin">Klinik Kulit dan Kelamin</option>
                        <option value="OK Elektif">OK ELEKTIF</option>
                        <option value="OK Emergency">OK EMERGENCY</option>
                        <option value="Klinik">Poli/Klinik lainya</option>
                        <option value="Ruangan">Ruangan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="unit_asal_detail" name="unit_asal_detail" placeholder="Masukkan Unit Asal Lainnya">
                </div>

                <!-- Form group untuk Dokter Pengirim -->
                <div class="form-group col-md-3">
                    <label for="dokter_pengirim">Dokter Pengirim</label>
                    <select class="form-control" id="dokter_pengirim" name="dokter_pengirim" onchange="handleDokterPengirimChange(this)">
                        <option value="" selected>Belum Dipilih</option>
                        <option value="dr. Ihyan Amri, Sp.B">dr. Ihyan Amri, Sp.B</option>
                        <option value="dr. Andy Achmad Suanda, Sp.B">dr. Andy Achmad Suanda, Sp.B</option>
                        <option value="dr. Agus Maulana,Sp.B,FinaCs,M.Ked.Klin">dr. Agus Maulana,Sp.B,FinaCs,M.Ked.Klin</option>
                        <option value="dr. Fransiscus Arifin, Sp.">dr. Fransiscus Arifin, Sp.B</option>
                        <option value="dr. I Putu Agus Suarta, Sp.OG K.Onk">dr. I Putu Agus Suarta, Sp.OG K.Onk</option>
                        <option value="dr. Dharma Putra Perjuangan Banjarnahor, Sp.OG K. FM">dr. Dharma Putra Perjuangan Banjarnahor, Sp.OG K. FM</option>
                        <option value="dr. Nur Aisah Wardani, Sp.P">dr. Nur Aisah Wardani, Sp.P</option>
                        <option value="dr. Susaniwati, Sp.P.">dr. Susaniwati, Sp.P.</option>
                        <option value="dr. Pramanindyah Bekti Anjani, Sp.P">dr. Pramanindyah Bekti Anjani, Sp.P.</option>
                        <option value="dr. Nurlaella Iswan Nusi, Sp.OG">dr. Nurlaella Iswan Nusi, Sp.OG.</option>
                        <option value="drg. Okky Prasetyo, Sp.BM">drg. Okky Prasetyo, Sp.BM</option>
                        <option value="dr. Hendarti Praharaningsih Eddy Saputra, Sp. A">dr. Hendarti Praharaningsih Eddy Saputra, Sp. A</option>
                        <option value="dr. Intani Dewi Syahti Fauzi, Sp.A.">dr. Intani Dewi Syahti Fauzi, Sp.A.</option>
                        <option value="dr. Retna Hastuti, Sp.A">dr. Retna Hastuti, Sp.A</option>
                        <option value="dr. Ita Puspita Dewi, Sp.KK, FINSDV, FAADV">dr. Ita Puspita Dewi, Sp.KK, FINSDV, FAADV</option>
                        <option value="dr. Desy Hinda Pramita, Sp.KK">dr. Desy Hinda Pramita, Sp.KK</option>
                        <option value="dr. Eri Chusairi Yulianto, Sp.THT">dr. Eri Chusairi Yulianto, Sp.THT</option>
                        <option value="dr. Yahya Haryo Nugroho, Sp.PD">dr. Yahya Haryo Nugroho, Sp.PD</option>
                        <option value="dr. Purwakaning Purnomo Agung, M.Kes., Sp.PD">dr. Purwakaning Purnomo Agung, M.Kes., Sp.PD</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="dokter_pengirim_custom" name="dokter_pengirim_custom" placeholder="Masukkan Dokter Pengirim Lainnya">
                </div>

                <!-- Form group untuk Tanggal Permintaan -->
                <div class="form-group col-md-3">
                    <label for="tanggal_permintaan">Tanggal Permintaan</label>
                    <input type="date" class="form-control" id="tanggal_permintaan" name="tanggal_permintaan" value="<?= old('tanggal_permintaan') ?: date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="tanggal_hasil">Tanggal Hasil</label>
                    <input type="date" class="form-control" id="tanggal_hasil" name="tanggal_hasil" value="<?= old('tanggal_hasil'); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="lokasi_spesimen">Lokasi Spesimen</label>
                    <input type="text" class="form-control" id="lokasi_spesimen" name="lokasi_spesimen" placeholder="Masukkan Lokasi Spesimen" value="<?= old('lokasi_spesimen'); ?>">
                </div>


                <div class="form-group col-md-3">
                    <label for="diagnosa_klinik">Diagnosa Klinik</label>
                    <input type="text" class="form-control" id="diagnosa_klinik" name="diagnosa_klinik" placeholder="Masukkan Diagnosa Klinik" value="<?= old('diagnosa_klinik'); ?>">
                </div>
                <!-- Form group untuk Tindakan Spesimen -->
                <div class="form-group col-md-3">
                    <label for="tindakan_spesimen">Tindakan Spesimen</label>
                    <select class="form-control" id="tindakan_spesimen" name="tindakan_spesimen" onchange="handleTindakanSpesimenChange(this)">
                        <option value="FNAB" selected>FNAB</option>
                        <option value="FNAB dengan tuntunan CT-Scan">FNAB dengan tuntunan CT-Scan</option>
                        <option value="FNAB dengan tuntunan USG">FNAB dengan tuntunan USG</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="tindakan_spesimen_custom" name="tindakan_spesimen_custom" placeholder="Masukkan Tindakan Spesimen Lainnya">
                </div>
            </div>

            <!-- Tombol untuk submit form -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Riweyat -->
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

<script>
    // Function to set Tanggal Hasil based on Tanggal Permintaan
    function setTanggalHasil() {
        const tanggalPermintaan = document.getElementById('tanggal_permintaan');
        const tanggalHasil = document.getElementById('tanggal_hasil');

        const tanggalPermintaanValue = new Date(tanggalPermintaan.value);

        // Check if Tanggal Permintaan is a valid date
        if (!isNaN(tanggalPermintaanValue)) {
            tanggalPermintaanValue.setDate(tanggalPermintaanValue.getDate() + 1); // Tambah 1 hari

            // Jika hasilnya Sabtu (6), tambahkan lagi 2 hari agar menjadi Senin
            if (tanggalPermintaanValue.getDay() === 6) {
                tanggalPermintaanValue.setDate(tanggalPermintaanValue.getDate() + 2);
            }

            // Set Tanggal Hasil dengan format YYYY-MM-DD
            tanggalHasil.value = tanggalPermintaanValue.toISOString().split('T')[0];
        }
    }

    // Set Tanggal Hasil on page load if Tanggal Permintaan is filled
    window.addEventListener('load', function() {
        if (document.getElementById('tanggal_permintaan').value) {
            setTanggalHasil();
        }
    });

    // Update Tanggal Hasil whenever Tanggal Permintaan is changed
    document.getElementById('tanggal_permintaan').addEventListener('change', setTanggalHasil);

    function handleUnitAsalChange(selectElement) {
        const customInput = document.getElementById('unit_asal_detail');
        if (selectElement.value === 'Ruangan' || selectElement.value === 'Klinik' || selectElement.value === 'lainnya') {
            customInput.classList.remove('d-none');
        } else {
            customInput.classList.add('d-none');
            customInput.value = '';
        }
    }

    function handleDokterPengirimChange(selectElement) {
        const customInput = document.getElementById('dokter_pengirim_custom');
        if (selectElement.value === 'lainnya') {
            customInput.classList.remove('d-none');
        } else {
            customInput.classList.add('d-none');
            customInput.value = '';
        }
    }

    function handleTindakanSpesimenChange(selectElement) {
        const customInput = document.getElementById('tindakan_spesimen_custom');
        if (selectElement.value === 'lainnya') {
            customInput.classList.remove('d-none');
        } else {
            customInput.classList.add('d-none');
            customInput.value = '';
        }
    }
</script>

<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>