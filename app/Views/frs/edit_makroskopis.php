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
            <input type="hidden" name="redirect" value="edit_makroskopis">
            <input type="hidden" name="dokter_pengirim" value="<?= $frs['dokter_pengirim'] ?? '' ?>">
            

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
                    <input type="hidden" name="idtransaksi" value="<?= isset($frs['id_transaksi']) ? (int) $frs['id_transaksi'] : '' ?>">
                    <input type="hidden" name="tanggal" value="<?= !empty($frs['tanggal_transaksi']) ? esc($frs['tanggal_transaksi']) : '' ?>">
                    <input type="hidden" name="register" value="<?= isset($frs['no_register']) ? esc($frs['no_register']) : '' ?>">

                    <input type="hidden" name="id_pasien" value="<?= esc($frs['id_pasien'] ?? ''); ?>">
                    <input type="hidden" name="norm_pasien" value="<?= esc($frs['norm_pasien'] ?? ''); ?>">
                    <input type="hidden" name="nama_pasien" value="<?= esc($frs['nama_pasien'] ?? ''); ?>">
                    <input type="hidden" name="tanggal_lahir_pasien" value="<?= esc($frs['tanggal_lahir_pasien'] ?? ''); ?>">
                    <input type="hidden" name="jenis_kelamin_pasien" value="<?= esc($frs['jenis_kelamin_pasien'] ?? ''); ?>">
                    <input type="hidden" name="alamat_pasien" value="<?= esc($frs['alamat_pasien'] ?? ''); ?>">

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="dokter_pemeriksa">Dokter Pemeriksa</label>
                            <select class="form-control" id="dokter_pemeriksa" name="dokter_pemeriksa">
                                <option value="____________________"
                                    <?= empty($frs['id_user_dokter_penerimaan_frs']) ? 'selected' : '' ?>>
                                    -- Pilih Dokter --
                                </option>
                                <option value="1"
                                    <?= ($frs['id_user_dokter_penerimaan_frs'] ?? '') === "1" ? 'selected' : '' ?>>
                                    dr. Vinna Chrisdianti, Sp.PA
                                </option>
                                <option value="2"
                                    <?= ($frs['id_user_dokter_penerimaan_frs'] ?? '') === "2" ? 'selected' : '' ?>>
                                    dr. Ayu Tyasmara Pratiwi, Sp.PA
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="nama_hubungan_pasien">Nama Hubungan Pasien</label>
                            <select class="form-control" id="nama_hubungan_pasien" name="nama_hubungan_pasien" onchange="toggleSearchValue()">
                                <option value="____________________">-- Pilih Penandatangan --</option>
                                <option value="<?= esc($frs['nama_pasien'] ?? '') ?>">Pasien</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <input type="text" class="form-control mt-2 d-none" id="nama_lainnya" name="nama_lainnya" placeholder="Masukkan Nama Lainnya">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="hubungan_dengan_pasien">Hubungan dengan Pasien</label>
                            <select class="form-control" id="hubungan_dengan_pasien" name="hubungan_dengan_pasien">
                                <option value="____________________">-- Pilih Hubungan --</option>
                                <option value="Pasien">Pasien</option>
                                <option value="Orang tua">Orang Tua</option>
                                <option value="Anak">Anak</option>
                                <option value="Istri">Istri</option>
                                <option value="Suami">Suami</option>
                                <option value="Saudara">Saudara</option>
                                <option value="Wali">Wali</option>
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
                            <label for="analis_priksa">Analis Pemeriksa</label>
                            <select class="form-control" id="analis_priksa" name="analis_priksa">
                                <option value="____________________"
                                    <?= empty($frs['id_user_penerimaan_frs']) ? 'selected' : '' ?>>
                                    -- Pilih Analis --
                                </option>
                                <option value="3"
                                    <?= ($frs['id_user_penerimaan_frs'] ?? '') === "3" ? 'selected' : '' ?>>
                                    Endar Pratiwi, S.Si
                                </option>
                                <option value="4"
                                    <?= ($frs['id_user_penerimaan_frs'] ?? '') === "4" ? 'selected' : '' ?>>
                                    Arlina Kartika, A.Md.AK
                                </option>
                                <option value="5"
                                    <?= ($frs['id_user_penerimaan_frs'] ?? '') === "5" ? 'selected' : '' ?>>
                                    Ilham Tyas Ismadi, A.Md.Kes
                                </option>
                                <option value="6"
                                    <?= ($frs['id_user_penerimaan_frs'] ?? '') === "6" ? 'selected' : '' ?>>
                                    Berlian Duta Krisna, S.Tr.Kes
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="usia_hubungan_pasien">Usia Hubungan Pasien</label>
                            <input type="number" class="form-control" id="usia_hubungan_pasien" name="usia_hubungan_pasien" value="">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="signature"> Tanda tangan digital Pasien</label>

                            <button type="button"
                                class="btn btn-primary btn-user w-100 w-md-auto mb-2"
                                onclick="openSignatureModal()">
                                <i class="fas fa-signature"></i> Tanda tangan digital Pasien
                            </button>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="signature"> Tampilan digital Pasien</label>
                            <!-- Preview Signature -->
                            <div class="border rounded text-center p-5">
                                <img id="signaturePreview"
                                    src=""
                                    alt="Preview Tanda Tangan"
                                    style="max-width:100%; display:none;">
                                <small id="noSignatureText" class="text-muted">Belum ada tanda tangan</small>
                            </div>

                            <!-- Hidden input untuk simpan base64 -->
                            <input type="hidden" name="concentSignaturePasien" id="concentSignaturePasien">
                        </div>
                    </div>
                </div>
            </div>

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

<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tanda Tangan Digital</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="border rounded p-2">
                    <canvas id="signature-pad" style="width:100%; height:200px;"></canvas>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="clearSignature()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan</button>
            </div>

        </div>
    </div>
</div>
// Script untuk mengelola dropdown dan input lainnya
<script src="<?= base_url('js/signatureRepository.js') ?>"></script>
<script>
    function toggleSearchValue() {
        let namaHubungan = document.getElementById("nama_hubungan_pasien").value;
        let inputNamaLainnya = document.getElementById("nama_lainnya");
        let hubunganPasien = document.getElementById("hubungan_dengan_pasien");
        let jenisKelamin = document.getElementById("jenis_kelamin_hubungan_pasien");
        let usiaPasien = document.getElementById("usia_hubungan_pasien");

        if (namaHubungan === "<?= esc($frs['nama_pasien'] ?? '') ?>") {
            hubunganPasien.value = "Pasien";
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

// Script untuk tanda tangan digital
<script>
    let signaturePad;
    let canvas;

    function openSignatureModal() {
        $('#signatureModal').modal('show');

        setTimeout(() => {
            canvas = document.getElementById('signature-pad');

            if (!canvas) {
                console.error("Canvas tidak ditemukan!");
                return;
            }

            resizeCanvas();
            signaturePad = new SignaturePad(canvas);
        }, 200);
    }

    function resizeCanvas() {

        // Kurangi resolusi internal canvas
        const ratio = 1;

        // Ukuran tampilan tetap besar
        canvas.style.width = "100%";
        canvas.style.height = "200px";

        // Ukuran pixel internal
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 200 * ratio;

        // Scale context
        canvas.getContext("2d").scale(ratio, ratio);
    }

    function clearSignature() {
        if (signaturePad) {
            signaturePad.clear();
        }
    }

    function saveSignature() {
        if (!signaturePad || signaturePad.isEmpty()) {
            alert("Tanda tangan masih kosong!");
            return;
        }

        let dataURL = signaturePad.toDataURL();
        console.log(dataURL);

        $('#signatureModal').modal('hide');
    }

    function saveSignature() {
        if (!signaturePad || signaturePad.isEmpty()) {
            alert("Tanda tangan masih kosong!");
            return;
        }

        // =========================
        // 1. Signature Pasien
        // =========================
        let dataURL = signaturePad.toDataURL();

        document.getElementById('signaturePreview').src = dataURL;
        document.getElementById('signaturePreview').style.display = 'block';
        document.getElementById('noSignatureText').style.display = 'none';
        document.getElementById('concentSignaturePasien').value = dataURL;

        // =========================
        // 2. Ambil Form
        // =========================
        const form = document.querySelector('form');
        const formData = new FormData(form);

        let data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // =========================
        // 4. Ambil ID dari Form
        // =========================
        let dokterId = data.dokter_pemeriksa || "";
        let analisId = data.analis_priksa || "";

        // =========================
        // 5. Ambil Data Dokter
        // =========================
        let dokterNama = dokterMap[dokterId]?.nama || "";
        let concentSignatureDokter = dokterMap[dokterId]?.ttd || "";

        // =========================
        // 6. Ambil Data Analis
        // =========================
        let analisNama = analisMap[analisId]?.nama || "";
        let concentSignaturePetugas = analisMap[analisId]?.ttd || "";

        // =========================
        // 5. Waktu Jakarta
        // =========================
        let dateTimeSignature = new Date().toLocaleString('sv-SE', {
            timeZone: 'Asia/Jakarta'
        }).replace('T', ' ');

        // =========================
        // 6. PAYLOAD
        // =========================
        const informedData = {
            risiko: "Terjadi pneumothorax saat FNAB dengan CT SCAN Guiding",
            tujuan: "Untuk memastikan diagnosis",
            indikasi: "Nodul / massa",
            dpjp_awal: document.querySelector('input[name="dokter_pengirim"]').value,
            dpjp_awal_id: "",
            lain_lain: "",
            tata_cara: "SWAB dengan kapas alkohol, suntik dengan jarum 25G / 27G atau Spinal 25G",
            komplikasi: "Infeksi, perdarahan ditempat suntikan",

            inform_nama: dokterNama,

            informed_nama: data.nama_pasien,
            jenis_kelamin: data.jenis_kelamin_pasien,

            dasar_diagnosis: "Surat rujukan SMF lain",
            diagnosis_kerja: data.diagnosa_klinik,
            diagnosis_field2: "",
            diagnosis_field3: "",

            dokter_pelaksana: dokterNama,
            pegawai_nama: analisNama,
            pemberi_informasi: dokterNama,

            hubungan_dengan_pasien: data.hubungan_dengan_pasien,
            nama_hubungan_pasien: getNamaHubunganPasien(),

            jenis_kelamin_hubungan_pasien: data.jenis_kelamin_hubungan_pasien,
            usia_hubungan_pasien: data.usia_hubungan_pasien,

            tgl_lahir_hubungan_pasien: data.tanggal_lahir_pasien,
            alamat_hubungan_pasien: data.alamat_pasien,
            
            signature_pasien: dataURL,
            signature_dokter: concentSignatureDokter,
            signature_petugas: concentSignaturePetugas,

            tanggal_signature: dateTimeSignature
        };

        const payload = {

            formrm_jenis: "INFORMED FNAB",
            formrm_kode: "09PA",

            m_pasien_id: "",
            t_pendaftaran_id: data.idtransaksi,
            formrm_norm: data.norm_pasien,
            tanggal: data.tanggal,
            register: data.register,
            noregister: data.kode_frs,

            formrm_created_by: analisNama,
            formrm_created_date: dateTimeSignature.replace(' ', 'T'),

            formrm_json: {
                nama: data.nama_pasien,
                jenis_kelamin: data.jenis_kelamin_pasien,
                no_rm: data.norm_pasien,
                alamat: data.alamat_pasien,
                tgl_lahir: data.tanggal_lahir_pasien,

                informed_data: informedData,
            }

        };

        // =========================
        // DEBUG
        // =========================
        console.log("=== JSON ===");
        console.log(JSON.stringify(payload, null, 2));

        // =========================
        // 7. KIRIM KE CI4
        // =========================
        fetch("<?= base_url('signature/save') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('[name=csrf_test_name]').value
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(res => {
                console.log("Response:", res);
            })
            .catch(err => {
                console.error("Error:", err);
            });

        $('#signatureModal').modal('hide');
    }
</script>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>
<?= $this->include('templates/frs/cetak_informasi'); ?>