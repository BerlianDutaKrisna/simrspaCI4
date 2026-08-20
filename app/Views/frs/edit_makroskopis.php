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
                            <label for="signature" class="font-weight-bold">Tampilan digital Pasien</label>
                            <!-- Preview Signature -->
                            <div class="border rounded text-center p-3 d-flex flex-column justify-content-center align-items-center" 
                                style="border: 2px dashed #4e73df !important; min-height: 120px; background-color: #f8faff !important; box-shadow: inset 0 1px 3px rgba(0,0,0,0.04);">
                                <img id="signaturePreview"
                                    src=""
                                    alt="Preview Tanda Tangan"
                                    style="max-width:100%; max-height:100px; display:none; object-fit:contain;">
                                <small id="noSignatureText" class="text-muted">
                                    <i class="fas fa-signature mr-1 text-secondary"></i>Belum ada tanda tangan
                                </small>
                            </div>

                            <!-- Hidden input untuk simpan base64 -->
                            <input type="hidden" name="concentSignaturePasien" id="concentSignaturePasien">
                        </div>
                    </div>

                    <?php if (!empty($signature)) : ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                try {
                                    var sigObj = <?= json_encode($signature) ?> || {};
                                    var parsedJson = {};
                                    if (sigObj.formrm_json) {
                                        if (typeof sigObj.formrm_json === 'string') {
                                            try { parsedJson = JSON.parse(sigObj.formrm_json) || {}; } catch(e) { parsedJson = {}; }
                                        } else if (typeof sigObj.formrm_json === 'object') {
                                            parsedJson = sigObj.formrm_json || {};
                                        }
                                    }
                                    var informedData = parsedJson.informed_data || {};
                                    var persetujuanData = parsedJson.persetujuan_data || {};

                                    // Helper: set select by case-insensitive value or text
                                    function setSelect(selectId, value) {
                                        if (!value) return false;
                                        var el = document.getElementById(selectId);
                                        if (!el) return false;
                                        var valLower = value.toString().trim().toLowerCase();
                                        for (var i = 0; i < el.options.length; i++) {
                                            var optVal = el.options[i].value.trim().toLowerCase();
                                            var optText = el.options[i].text.trim().toLowerCase();
                                            if (optVal === valLower || optText === valLower) {
                                                el.selectedIndex = i;
                                                return true;
                                            }
                                        }
                                        return false;
                                    }

                                    // 1. Preview signature
                                    var sigImg = sigObj.concentSignaturePasien || persetujuanData.consentSignaturePersetujuan || informedData.consentSignaturePersetujuan || null;
                                    if (sigImg && sigImg.trim() !== '') {
                                        var img = document.getElementById('signaturePreview');
                                        var noSig = document.getElementById('noSignatureText');
                                        if (img) {
                                            img.src = sigImg;
                                            img.style.display = 'block';
                                        }
                                        if (noSig) {
                                            noSig.style.display = 'none';
                                        }
                                        var hidden = document.getElementById('concentSignaturePasien');
                                        if (hidden) {
                                            hidden.value = sigImg;
                                        }
                                    }

                                    // 2. Nama Hubungan Pasien
                                    var namaHub = sigObj.nama_hubungan_pasien || persetujuanData.persetujuan_nama || informedData.informed_nama || '';
                                    if (namaHub && namaHub.trim() !== '') {
                                        var patientName = "<?= esc($frs['nama_pasien'] ?? '') ?>";
                                        if (namaHub.trim().toLowerCase() === patientName.toLowerCase() || namaHub.trim().toLowerCase() === 'pasien') {
                                            var sel = document.getElementById('nama_hubungan_pasien');
                                            if (sel) {
                                                setSelect('nama_hubungan_pasien', patientName);
                                            }
                                            var inputNama = document.getElementById('nama_lainnya');
                                            if (inputNama) {
                                                inputNama.classList.add('d-none');
                                                inputNama.value = '';
                                            }
                                        } else {
                                            var sel = document.getElementById('nama_hubungan_pasien');
                                            if (sel) sel.value = 'lainnya';
                                            var inputNama = document.getElementById('nama_lainnya');
                                            if (inputNama) {
                                                inputNama.classList.remove('d-none');
                                                inputNama.value = namaHub;
                                            }
                                        }
                                    }

                                    // 3. Hubungan dengan Pasien
                                    var hub = sigObj.hubungan_dengan_pasien || persetujuanData.persetujuan_hubungan || informedData.informed_hubungan || '';
                                    if (hub && hub.trim() !== '') {
                                        setSelect('hubungan_dengan_pasien', hub);
                                    }

                                    // 4. Jenis Kelamin Hubungan Pasien
                                    var jk = sigObj.jenis_kelamin_hubungan_pasien || persetujuanData.persetujuan_jk || parsedJson.jenis_kelamin || sigObj.jenis_kelamin || '';
                                    if (jk && jk.trim() !== '') {
                                        var selJk = document.getElementById('jenis_kelamin_hubungan_pasien');
                                        if (selJk) {
                                            var jkUpper = jk.trim().toUpperCase();
                                            if (jkUpper.startsWith('L') || jkUpper === 'LAKI-LAKI') {
                                                selJk.value = 'L';
                                            } else if (jkUpper.startsWith('P') || jkUpper === 'PEREMPUAN') {
                                                selJk.value = 'P';
                                            }
                                        }
                                    }

                                    // 5. Usia Hubungan Pasien
                                    var usiaVal = sigObj.usia_hubungan_pasien || persetujuanData.usia_hubungan_pasien || persetujuanData.persetujuan_usia || informedData.usia_hubungan_pasien || null;
                                    if (!usiaVal) {
                                        var tglLahirHub = sigObj.tgl_lahir_hubungan_pasien || persetujuanData.persetujuan_tgl_lahir || null;
                                        var tglTrans = "<?= esc($frs['tanggal_transaksi'] ?? '') ?>";
                                        if (tglLahirHub && tglLahirHub !== tglTrans) {
                                            var computedAge = hitungUsia(tglLahirHub);
                                            if (computedAge !== "" && computedAge >= 0) {
                                                usiaVal = computedAge;
                                            }
                                        }
                                    }
                                    if (!usiaVal && (hub.toLowerCase() === 'pasien' || namaHub.toLowerCase() === 'pasien' || namaHub.toLowerCase() === "<?= strtolower(esc($frs['nama_pasien'] ?? '')) ?>")) {
                                        var patientBirth = "<?= esc($frs['tanggal_lahir_pasien'] ?? '') ?>";
                                        if (patientBirth) {
                                            usiaVal = hitungUsia(patientBirth);
                                        }
                                    }
                                    if (usiaVal !== null && usiaVal !== "") {
                                        var usiaInput = document.getElementById('usia_hubungan_pasien');
                                        if (usiaInput) {
                                            usiaInput.value = usiaVal;
                                        }
                                    }

                                    // 6. Dokter & Analis
                                    var dok = sigObj.dokter_pelaksana || informedData.inform_nama || null;
                                    if (dok) setSelect('dokter_pemeriksa', dok);

                                    var petugas = sigObj.petugas_pelaksana || informedData.pegawai_nama || null;
                                    if (petugas) setSelect('analis_priksa', petugas);

                                } catch (e) {
                                    console.error('Error restoring signature preview', e);
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tombol simpan -->
            <div class="form-group row">
                <div class="col-sm-6 text-center mb-3">
                    <button type="button" class="btn btn-success btn-user w-100 mb-3" onclick="submitWithSignature()">
                        <i class="fas fa-save"></i> Simpan
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
                <div class="alert alert-info">
                    <p class="mb-2">
                        Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana yang saya beri tanda tangan/ parah di kolom ini, dan telah memahaminya
                    </p>
                    <p class="mb-2">
                        Komplikasi yang mungkin timbul apabila tindakan tersebut tidak dilakukan.
                    </p>
                    <p class="mb-0">
                        saya bertanggungjawab atas segala akibat yang mungkin timbul sebagai akibat dilakukan tindakan kedokteran tersebut.
                    </p>
                </div>
                <!-- Highlighted Signature Pad Box -->
                <div class="signature-box-wrapper p-3 rounded" style="background-color: #f8faff; border: 2px dashed #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="font-weight-bold text-primary small">
                            <i class="fas fa-pen-nib mr-1"></i> KOLOM TANDA TANGAN DIGITAL
                        </span>
                        <span class="badge badge-primary px-2 py-1">
                            <i class="fas fa-info-circle mr-1"></i> Silakan tanda tangan di dalam kotak berikut
                        </span>
                    </div>
                    
                    <div class="position-relative bg-white rounded border" style="border-color: #d1d3e2 !important; box-shadow: inset 0 1px 3px rgba(0,0,0,0.06);">
                        <canvas id="signature-pad" style="width:100%; height:220px; display:block; cursor:crosshair; touch-action:none;"></canvas>
                        
                        <!-- Visual Guide Line for Signature -->
                        <div style="position: absolute; bottom: 25px; left: 20px; right: 20px; border-bottom: 1.5px dashed #cbd5e1; pointer-events: none; display: flex; justify-content: space-between; color: #94a3b8; font-size: 11px; font-weight: 500;">
                            <span>✕ Tanda tangan di atas garis ini</span>
                            <span class="d-none d-sm-inline">Area Penandatanganan</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2 px-1 text-muted" style="font-size: 11px;">
                        <span><i class="fas fa-shield-alt mr-1 text-success"></i> Dokumen Persetujuan Medis</span>
                        <span><i class="fas fa-mouse-pointer mr-1"></i> Gunakan mouse, stylus, atau sentuhan layar</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="clearSignature()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan</button>
            </div>

        </div>
    </div>
</div>

<!-- Script untuk menyimpan tanda tangan digital dan data repository -->
<script src="<?= base_url('js/signatureRepository.js') ?>"></script>

<!-- Script untuk mengelola dropdown dan input lainnya -->
<script>
    function toggleSearchValue() {
        let namaHubungan = document.getElementById("nama_hubungan_pasien").value;
        let inputNamaLainnya = document.getElementById("nama_lainnya");
        let hubunganPasien = document.getElementById("hubungan_dengan_pasien");
        let jenisKelamin = document.getElementById("jenis_kelamin_hubungan_pasien");
        let usiaPasien = document.getElementById("usia_hubungan_pasien");

        if (namaHubungan === "<?= esc($frs['nama_pasien'] ?? '') ?>") {
            hubunganPasien.value = "Pasien";
            let jkPasien = "<?= esc($frs['jenis_kelamin_pasien'] ?? '') ?>";
            jenisKelamin.value = (jkPasien.startsWith('L') || jkPasien === 'Laki-laki') ? 'L' : ((jkPasien.startsWith('P') || jkPasien === 'Perempuan') ? 'P' : jkPasien);
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
            inputNamaLainnya.value = "";
        }
    }

    function hitungUsia(tanggalLahir) {
        if (!tanggalLahir) return "";
        let birthDate = new Date(tanggalLahir);
        if (isNaN(birthDate.getTime())) return "";
        let today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        let monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age >= 0 ? age : "";
    }

    function getNamaHubunganPasien() {
        let selectElement = document.getElementById("nama_hubungan_pasien").value;
        let inputNamaLainnya = document.getElementById("nama_lainnya").value;
        return selectElement === "lainnya" ? inputNamaLainnya : selectElement;
    }

    document.getElementById("nama_hubungan_pasien").addEventListener("change", function() {
        console.log("Nama Hubungan Pasien:", getNamaHubunganPasien());
    });

    document.getElementById("nama_lainnya").addEventListener("input", function() {
        console.log("Nama Hubungan Pasien (input lainnya):", getNamaHubunganPasien());
    });
</script>

<!-- Script untuk tanda tangan digital -->
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
        const ratio = 1;
        canvas.style.width = "100%";
        canvas.style.height = "220px";
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 220 * ratio;
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

        // 1. Signature Pasien
        let dataURL = signaturePad.toDataURL();

        document.getElementById('signaturePreview').src = dataURL;
        document.getElementById('signaturePreview').style.display = 'block';
        document.getElementById('noSignatureText').style.display = 'none';
        document.getElementById('concentSignaturePasien').value = dataURL;

        // 2. Save via helper
        buildAndSendSignaturePayload(dataURL)
            .then(res => {
                console.log("Signature save response:", res);
                $('#signatureModal').modal('hide');
            })
            .catch(err => {
                console.error("Error Fetch:", err);
                $('#signatureModal').modal('hide');
            });
    }
</script>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>
<?= $this->include('templates/frs/cetak_informasi'); ?>

<script>
    // Helper: build payload and send to signature/save
    function buildAndSendSignaturePayload(dataURL) {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        let data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        let dokterId = data.dokter_pemeriksa || "";
        let analisId = data.analis_priksa || "";
        let dokterNama = (window.dokterMap && dokterMap[dokterId]) ? dokterMap[dokterId].nama : "";
        let concentSignatureDokter = (window.dokterMap && dokterMap[dokterId]) ? dokterMap[dokterId].ttd : "";
        let analisNama = (window.analisMap && analisMap[analisId]) ? analisMap[analisId].nama : "";
        let concentSignaturePetugas = (window.analisMap && analisMap[analisId]) ? analisMap[analisId].ttd : "";

        let dateTimeSignature = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Jakarta' }).replace('T', ' ');

        let namaHubunganVal = getNamaHubunganPasien();
        let hubunganVal = data.hubungan_dengan_pasien || "";
        let jkHubunganVal = document.getElementById('jenis_kelamin_hubungan_pasien').value;
        let jkHubunganText = jkHubunganVal === 'L' ? 'Laki-laki' : (jkHubunganVal === 'P' ? 'Perempuan' : '');
        let usiaHubunganVal = document.getElementById('usia_hubungan_pasien').value || "";

        const informedData = {
            dpjp_awal: document.querySelector('input[name="dokter_pengirim"]')?.value || "",
            inform_nama: dokterNama,
            informed_hubungan: hubunganVal,
            informed_nama: namaHubunganVal,
            diagnosis_kerja: data.diagnosa_klinik || "",
            diagnosis_field2: "",
            diagnosis_field3: "",
            dasar_diagnosis: "Surat rujukan SMF lain",
            indikasi: "Nodul / massa",
            tata_cara: "SWAB dengan kapas alkohol, suntik dengan jarum 25G / 27G atau Spinal 25G",
            tujuan: "Untuk memastikan diagnosis",
            risiko: "Terjadi pneumothorax saat FNAB dengan CT SCAN Guiding",
            komplikasi: "Infeksi, perdarahan ditempat suntikan",
            lain_lain: "",
            usia_hubungan_pasien: usiaHubunganVal,
            consentDatePersetujuan: dateTimeSignature.replace(' ', 'T'),
            consentSignaturePersetujuan: dataURL,
            consentGiverSignatureNamePersetujuan: data.nama_pasien,
            dokterSignaturePersetujuan: concentSignatureDokter,
            dokterSignatureNamePersetujuan: dokterNama,
            perawatSignaturePersetujuan: concentSignaturePetugas,
            pegawai_nama: analisNama,
        };

        const consentData = {
            persetujuan_hubungan: hubunganVal,
            persetujuan_nama: namaHubunganVal,
            persetujuan_tgl_lahir: data.tanggal,
            usia_hubungan_pasien: usiaHubunganVal,
            persetujuan_jk: jkHubunganText,
            persetujuan_alamat: data.alamat_pasien,
            pasien_nama: data.nama_pasien,
            pasien_jk: data.jenis_kelamin_pasien || jkHubunganText,
            no_rm: data.norm_pasien,
            alamat: data.alamat_pasien,
            pasien_tgl_lahir: data.tanggal_lahir_pasien,
            consentDatePersetujuan: dateTimeSignature.replace(' ', 'T'),
            consentSignaturePersetujuan: dataURL,
            consentGiverSignatureNamePersetujuan: data.nama_pasien,
            dokterSignaturePersetujuan: concentSignatureDokter,
            dokterSignatureNamePersetujuan: dokterNama,
            perawatSignaturePersetujuan: concentSignaturePetugas,
            pegawai_nama: analisNama,
        };

        const payload = {
            formrm_jenis: "INFORMED FNAB",
            formrm_kode: "09PA",
            m_pasien_id: null,
            t_pendaftaran_id: data.idtransaksi,
            id_transaksi: data.idtransaksi,
            formrm_norm: data.norm_pasien,
            tanggal: data.tanggal,
            register: data.register,
            noregister: data.kode_frs,
            formrm_created_by: analisNama,
            formrm_created_date: dateTimeSignature.replace(' ', 'T'),
            dokter_pelaksana: dokterNama,
            petugas_pelaksana: analisNama,
            pemberi_informasi: dokterNama,
            hubungan_dengan_pasien: hubunganVal,
            nama_hubungan_pasien: namaHubunganVal,
            jenis_kelamin_hubungan_pasien: jkHubunganVal,
            usia_hubungan_pasien: usiaHubunganVal,
            diagnosis_kerja: data.diagnosa_klinik || "",
            concentSignaturePasien: dataURL,
            concentSignatureDokter: concentSignatureDokter,
            concentSignaturePetugas: concentSignaturePetugas,
            dateTimeSignature: dateTimeSignature,
            formrm_json: {
                nama: data.nama_pasien,
                jenis_kelamin: data.jenis_kelamin_pasien || jkHubunganText,
                no_rm: data.norm_pasien,
                alamat: data.alamat_pasien,
                tgl_lahir: data.tanggal_lahir_pasien,
                informed_data: informedData,
                persetujuan_data: consentData,
            }
        };

        return fetch("<?= base_url('signature/save') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('[name=csrf_test_name]').value
            },
            body: JSON.stringify(payload)
        }).then(response => response.json());
    }

    // Called by main Simpan button: save signature then submit form to frs/update_print
    function submitWithSignature() {
        try {
            var dataURL = document.getElementById('concentSignaturePasien').value || '';
            buildAndSendSignaturePayload(dataURL)
                .then(res => {
                    console.log('Signature save response', res);
                    var form = document.querySelector('form');
                    form.action = "<?= base_url('frs/update_print/' . ($frs['id_frs'] ?? '')) ?>";
                    form.submit();
                })
                .catch(err => {
                    console.error('Error saving signature:', err);
                    var form = document.querySelector('form');
                    form.action = "<?= base_url('frs/update_print/' . ($frs['id_frs'] ?? '')) ?>";
                    form.submit();
                });
        } catch (e) {
            console.error(e);
            var form = document.querySelector('form');
            form.action = "<?= base_url('frs/update_print/' . ($frs['id_frs'] ?? '')) ?>";
            form.submit();
        }
    }
</script>