<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pemeriksaan</h6>
    </div>
    <div class="card-body">
        <h1>Data Pasien Histopatologi</h1>
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

        <!-- Area collapse -->
        <div class="collapse" id="riwayatCollapse">
            <div class="card card-body">
                <?php if (!empty($riwayat_api)) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Dokter</th>
                                    <th>Diagnosa Klinik</th>
                                    <th>Pemeriksaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat_api as $i => $row) : ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= (!empty($row['tanggal']) && strtotime($row['tanggal'])) ? date('d-m-Y', strtotime($row['tanggal'])) : '-' ?></td>
                                        <td><?= esc($row['dokterpa'] ?? '-') ?></td>
                                        <td><?= esc($row['diagnosaklinik'] ?? '-') ?></td>
                                        <td><?= esc($row['pemeriksaan'] ?? '-') ?></td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm"
                                                onclick="tampilkanModal(`<?= nl2br(esc($row['hasil'] ?? 'Tidak ada hasil', 'js')) ?>`)">
                                                Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="text-muted">Tidak ada data riwayat pemeriksaan tersedia.</p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Pemeriksaan Histopatologi</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('hpa/insert') ?>" method="POST">
            <?= csrf_field(); ?> <!-- CSRF token untuk keamanan -->
            <!-- Hidden input untuk id_pasien -->
            <input type="hidden" name="id_pasien" value="<?= isset($patient['id_pasien']) ? esc($patient['id_pasien']) : ''; ?>">
            <input type="hidden" name="norm_pasien" value="<?= isset($patient['norm_pasien']) ? esc($patient['norm_pasien']) : ''; ?>">
            <input type="hidden" name="nama_pasien" value="<?= isset($patient['nama_pasien']) ? esc($patient['nama_pasien']) : ''; ?>">
            <input type="hidden" name="alamat_pasien" value="<?= isset($patient['alamat_pasien']) ? esc($patient['alamat_pasien']) : ''; ?>">
            <input type="hidden" name="tanggal_lahir_pasien" value="<?= isset($patient['tanggal_lahir_pasien']) ? esc(date('Y-m-d', strtotime($patient['tanggal_lahir_pasien']))) : ''; ?>">
            <input type="hidden" name="jenis_kelamin_pasien" value="<?= isset($patient['jenis_kelamin_pasien']) ? esc($patient['jenis_kelamin_pasien']) : ''; ?>">
            <input type="hidden" name="status_pasien" value="<?= isset($patient['status_pasien']) ? esc($patient['status_pasien']) : ''; ?>">
            <div class="form-row">
                <!-- Form group untuk Kode HPA -->
                <div class="form-group col-md-3">
                    <label for="kode_hpa">Kode HPA</label>
                    <input type="text" class="form-control" id="kode_hpa" name="kode_hpa"
                        value="<?= esc($kode_hpa); ?>">
                </div>

                <!-- Form group untuk Unit Asal -->
                <div class="form-group col-md-3">
                    <label for="unit_asal">Unit Asal</label>
                    <select class="form-control" id="unit_asal" name="unit_asal" onchange="handleUnitAsalChange(this)">
                        <?php if (!empty($patient['unitasal'])): ?>
                            <option value="<?= esc($patient['unitasal']) ?>" selected><?= esc(strtoupper($patient['unitasal'])) ?></option>
                        <?php endif; ?>
                        <option value="OK Elektif">OK ELEKTIF</option>
                        <option value="OK Emergency">OK EMERGENCY</option>
                        <option value="RSUD. EC">RSUD. EC</option>
                        <option value="Klinik">Poli/Klinik lainya</option>
                        <option value="Ruangan">Ruangan lainya</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="unit_asal_detail" name="unit_asal_detail" placeholder="Masukkan Unit Asal Lainnya">
                </div>

                <!-- Form group untuk Dokter Pengirim -->
                <div class="form-group col-md-3">
                    <label for="dokter_pengirim">Dokter Pengirim</label>
                    <select class="form-control" id="dokter_pengirim" name="dokter_pengirim" onchange="handleDokterPengirimChange(this)">
                        <?php if (!empty($patient['dokterperujuk'])): ?>
                            <option value="<?= esc($patient['dokterperujuk']) ?>" selected><?= esc($patient['dokterperujuk']) ?></option>
                        <?php endif; ?>
                        <option value="dr. Ihyan Amri, Sp.B">dr. Ihyan Amri, Sp.B</option>
                        <option value="dr. Andy Achmad Suanda, Sp.B">dr. Andy Achmad Suanda, Sp.B</option>
                        <option value="dr. Agus Maulana,Sp.B,FinaCs,M.Ked.Klin">dr. Agus Maulana,Sp.B,FinaCs,M.Ked.Klin</option>
                        <option value="dr. Fransiscus Arifin, Sp.">dr. Fransiscus Arifin, Sp.B</option>
                        <option value="dr. I Putu Agus Suarta, Sp.OG K.Onk">dr. I Putu Agus Suarta, Sp.OG K.Onk</option>
                        <option value="dr. Dharma Putra Perjuangan Banjarnahor, Sp.OG K. FM">dr. Dharma Putra Perjuangan Banjarnahor, Sp.OG K. FM</option>
                        <option value="dr. Unggul Karyo Nugroho, Sp.Og">dr. Unggul Karyo Nugroho, Sp.Og</option>
                        <option value="dr. Nurlaella Iswan Nusi, Sp.OG">dr. Nurlaella Iswan Nusi, Sp.OG.</option>
                        <option value="drg. Okky Prasetyo, Sp.BM">drg. Okky Prasetyo, Sp.BM</option>
                        <option value="dr. Taufik Indrawan, Sp.U">dr. Taufik Indrawan, Sp.U</option>
                        <option value="dr. Chairani Fitri Saphira, Sp.BP-RE (K)">dr. Chairani Fitri Saphira, Sp.BP-RE (K)</option>
                        <option value="dr. Billy Daniel Messakh, Sp.B">dr. Billy Daniel Messakh, Sp.B</option>
                        <option value="dr. Khamim Thohari, Sp.BS">dr. Khamim Thohari, Sp.BS</option>
                        <option value="dr. Danang Irsayanto, Sp. U.,M.Ked.Klin">dr. Danang Irsayanto, Sp. U.,M.Ked.Klin</option>
                        <option value="dr. Haykal Hermatyar Fatahajjad, Sp. U">dr. Haykal Hermatyar Fatahajjad, Sp. U</option>
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
                    <input type="text" class="form-control" id="lokasi_spesimen" name="lokasi_spesimen" placeholder="Masukkan Lokasi Spesimen" value="<?= esc(old('lokasi_spesimen') ?? $patient['lokasi_spesimen'] ?? '') ?>">
                </div>


                <div class="form-group col-md-3">
                    <label for="diagnosa_klinik">Diagnosa Klinik</label>
                    <input type="text" class="form-control" id="diagnosa_klinik" name="diagnosa_klinik" placeholder="Masukkan Diagnosa Klinik" value="<?= esc(old('diagnosa_klinik') ?? $patient['diagnosa_klinik'] ?? '') ?>">
                </div>
                <!-- Form group untuk Tindakan Spesimen -->
                <div class="form-group col-md-3">
                    <label for="tindakan_spesimen">Tindakan Spesimen</label>
                    <select class="form-control" id="tindakan_spesimen" name="tindakan_spesimen" onchange="handleTindakanSpesimenChange(this)">
                        <?php if (!empty($patient['tindakan_spesimen'])): ?>
                            <option value="<?= esc($patient['tindakan_spesimen']) ?>" selected><?= esc($patient['tindakan_spesimen']) ?></option>
                        <?php endif; ?>
                        <option value="Biopsi">Biopsi</option>
                        <option value="Biopsi Beberapa Tempat">Biopsi Beberapa Tempat</option>
                        <option value="Kerokan">Kerokan</option>
                        <option value="Extirpasi Tumor">Extirpasi Tumor</option>
                        <option value="Koleksistektomi">Koleksistektomi</option>
                        <option value="Tiroidektomi">Tiroidektomi</option>
                        <option value="Mastektomi">Mastektomi</option>
                        <option value="Appendiktomi">Appendiktomi</option>
                        <option value="Miomektomi">Miomektomi</option>
                        <option value="Kistektomi">Kistektomi</option>
                        <option value="Omentektomi">Omentektomi</option>
                        <option value="TAH-BSO">TAH-BSO</option>
                        <option value="Reseksi usus">Reseksi Usus</option>
                        <option value="Menentukan Radikalitas">Menentukan Radikalitas</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="tindakan_spesimen_custom" name="tindakan_spesimen_custom" placeholder="Masukkan Tindakan Spesimen Lainnya">
                </div>
            </div>

            <!-- Tombol untuk submit form -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
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
            tanggalPermintaanValue.setDate(tanggalPermintaanValue.getDate() + 7); // Add 7 days
            tanggalHasil.value = tanggalPermintaanValue.toISOString().split('T')[0]; // Set Tanggal Hasil
        }
    }

    // Set Tanggal Hasil on page load if Tanggal Permintaan is filled
    window.addEventListener('load', function() {
        // If Tanggal Permintaan has a value, set Tanggal Hasil
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