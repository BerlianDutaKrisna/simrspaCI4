<?= $this->include('templates/frs/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Makroskopis</h6>
    </div>

    <div class="card-body">
        <h1>Edit Data Makroskopis Fine Needle Aspiration Biopsy</h1>

        <a href="<?= base_url('penerimaan_frs/index') ?>" class="btn btn-primary mb-3">
            <i class="fas fa-reply"></i> Kembali
        </a>

        <form id="form-frs" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?? '' ?>">
            <input type="hidden" name="id_penerimaan_frs" value="<?= $frs['id_penerimaan_frs'] ?? '' ?>">
            <input type="hidden" name="redirect" value="edit_makroskopis">

            <!-- Kode & Diagnosa -->
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

            <!-- Pasien -->
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

            <!-- INFORMED CONSENT -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informed Consent Tindakan FNAB</h6>
                </div>

                <div class="card-body">
                    <input type="hidden" name="idtransaksi" value="<?= (int)($frs['id_transaksi'] ?? 0) ?>">
                    <input type="hidden" name="tanggal" value="<?= esc($frs['tanggal_transaksi'] ?? '') ?>">
                    <input type="hidden" name="register" value="<?= esc($frs['no_register'] ?? '') ?>">

                    <input type="hidden" name="id_pasien" value="<?= esc($frs['id_pasien'] ?? '') ?>">
                    <input type="hidden" name="norm_pasien" value="<?= esc($frs['norm_pasien'] ?? '') ?>">
                    <input type="hidden" name="nama_pasien" value="<?= esc($frs['nama_pasien'] ?? '') ?>">
                    <input type="hidden" name="tanggal_lahir_pasien" value="<?= esc($frs['tanggal_lahir_pasien'] ?? '') ?>">
                    <input type="hidden" name="jenis_kelamin_pasien" value="<?= esc($frs['jenis_kelamin_pasien'] ?? '') ?>">
                    <input type="hidden" name="alamat_pasien" value="<?= esc($frs['alamat_pasien'] ?? '') ?>">

                    <div class="form-row">

                        <!-- Dokter -->
                        <div class="form-group col-md-3">
                            <label>Dokter Pemeriksa</label>
                            <select class="form-control" name="dokter_pemeriksa">
                                <option value="">-- Pilih Dokter --</option>
                                <option value="dr. Vinna Chrisdianti, Sp.PA"
                                    <?= ($frs['dokter_pemeriksa'] ?? '') == 'dr. Vinna Chrisdianti, Sp.PA' ? 'selected' : '' ?>>
                                    dr. Vinna Chrisdianti, Sp.PA
                                </option>
                                <option value="dr. Ayu Tyasmara Pratiwi, Sp.PA"
                                    <?= ($frs['dokter_pemeriksa'] ?? '') == 'dr. Ayu Tyasmara Pratiwi, Sp.PA' ? 'selected' : '' ?>>
                                    dr. Ayu Tyasmara Pratiwi, Sp.PA
                                </option>
                            </select>
                        </div>

                        <!-- Hubungan -->
                        <div class="form-group col-md-3">
                            <label>Nama Hubungan</label>
                            <select class="form-control" id="nama_hubungan_pasien" name="nama_hubungan_pasien">
                                <option value="">-- Pilih --</option>
                                <option value="<?= esc($frs['nama_pasien'] ?? '') ?>">Pasien Sendiri</option>
                                <option value="lainnya">Lainnya</option>
                            </select>

                            <input type="text" id="nama_lainnya" name="nama_lainnya"
                                class="form-control mt-2 d-none" placeholder="Masukkan Nama">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Hubungan</label>
                            <select class="form-control" id="hubungan_dengan_pasien" name="hubungan_dengan_pasien">
                                <option value="">-- Pilih --</option>
                                <option>Pasien Sendiri</option>
                                <option>Orang tua</option>
                                <option>Anak</option>
                                <option>Istri</option>
                                <option>Suami</option>
                                <option>Saudara</option>
                                <option>Pengantar</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin_hubungan_pasien" name="jenis_kelamin_hubungan_pasien">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <!-- Analis -->
                        <div class="form-group col-md-3">
                            <label>Analis Pemeriksa</label>
                            <select class="form-control" name="analis_periksa">
                                <option value="">-- Pilih --</option>
                                <?php
                                $analisList = [
                                    "3" => "Endar Pratiwi, S.Si",
                                    "4" => "Arlina Kartika, A.Md.AK",
                                    "5" => "Ilham Tyas Ismadi, A.Md.Kes",
                                    "6" => "Berlian Duta Krisna, S.Tr.Kes"
                                ];
                                foreach ($analisList as $key => $val): ?>
                                    <option value="<?= $key ?>" <?= ($frs['id_user_penerimaan_frs'] ?? '') == $key ? 'selected' : '' ?>>
                                        <?= $val ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Usia</label>
                            <input type="number" id="usia_hubungan_pasien" name="usia_hubungan_pasien" class="form-control">
                        </div>

                        <!-- Signature -->
                        <div class="form-group col-md-3">
                            <button type="button" class="btn btn-primary w-100" onclick="openSignatureModal()">
                                <i class="fas fa-signature"></i> Tanda Tangan
                            </button>
                        </div>

                        <div class="form-group col-md-3">
                            <div class="border text-center p-3">
                                <img id="signaturePreview" style="max-width:100%; display:none;">
                                <small id="noSignatureText">Belum ada tanda tangan</small>
                            </div>
                            <input type="hidden" name="consentSignaturePasien" id="consentSignaturePasien">
                        </div>

                    </div>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="form-group row">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-success w-100"
                        formaction="<?= base_url('frs/update_print/' . ($frs['id_frs'] ?? '')) ?>">
                        Tutup
                    </button>
                </div>

                <div class="col-sm-6">
                    <button type="button" class="btn btn-info w-100" onclick="cetakProses()">
                        Cetak
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="signatureModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Tanda Tangan</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <canvas id="signature-pad" style="width:100%; height:200px;"></canvas>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="clearSignature()">Reset</button>
                <button class="btn btn-primary" onclick="saveSignature()">Simpan</button>
            </div>

        </div>
    </div>
</div>

<!-- JS -->
<script>
    let signaturePad, canvas;

    function openSignatureModal() {
        $('#signatureModal').modal('show');

        setTimeout(() => {
            canvas = document.getElementById('signature-pad');
            if (!canvas) return;

            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = 200 * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            signaturePad = new SignaturePad(canvas);
        }, 300);
    }

    function clearSignature() {
        if (signaturePad) signaturePad.clear();
    }

    function hitungUsia(tanggalLahir) {
        if (!tanggalLahir) return "";
        let birth = new Date(tanggalLahir);
        let today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        if (today < new Date(today.getFullYear(), birth.getMonth(), birth.getDate())) age--;
        return age;
    }

    document.getElementById("nama_hubungan_pasien").addEventListener("change", function() {
        let val = this.value;

        document.getElementById("nama_lainnya").classList.toggle("d-none", val !== "lainnya");

        if (val === "<?= esc($frs['nama_pasien'] ?? '') ?>") {
            document.getElementById("hubungan_dengan_pasien").value = "Pasien Sendiri";
            document.getElementById("jenis_kelamin_hubungan_pasien").value = "<?= esc($frs['jenis_kelamin_pasien'] ?? '') ?>";
            document.getElementById("usia_hubungan_pasien").value = hitungUsia("<?= esc($frs['tanggal_lahir_pasien'] ?? '') ?>");
        }
    });

    function saveSignature() {
        if (!signaturePad || signaturePad.isEmpty()) {
            alert("Tanda tangan kosong!");
            return;
        }

        let dataURL = signaturePad.toDataURL();

        document.getElementById('signaturePreview').src = dataURL;
        document.getElementById('signaturePreview').style.display = 'block';
        document.getElementById('noSignatureText').style.display = 'none';
        document.getElementById('consentSignaturePasien').value = dataURL;

        $('#signatureModal').modal('hide');
    }
</script>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>
<?= $this->include('templates/frs/cetak_informasi'); ?>