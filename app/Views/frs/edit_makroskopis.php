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

            <?= $this->include('templates/fnab/informed_consent'); ?>

            <!-- Pengkajian Awal Medis -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengkajian Awal Medis</h6>
                </div>
                <div class="card-body">
                    <!-- DATA MEDIS -->
                    <h5 class="font-weight-bold border-bottom pb-2 mb-3 mt-2">Data Medis (Anamnesis)</h5>
                    <div class="form-group">
                        <label>1. Keluhan Utama</label>
                        <textarea class="form-control" name="keluhan_utama" rows="3" placeholder="Tuliskan keluhan utama pasien..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>2. Riwayat Penyakit</label>
                        <textarea class="form-control" name="riwayat_penyakit" rows="3" placeholder="Tuliskan riwayat penyakit pasien..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>3. Riwayat Pengobatan</label>
                        <textarea class="form-control" name="riwayat_pengobatan" rows="3" placeholder="Tuliskan riwayat pengobatan sebelumnya..."></textarea>
                    </div>

                    <!-- PEMERIKSAAN FISIK (CANVAS ANATOMI) -->
                    <h5 class="font-weight-bold border-bottom pb-2 mb-3 mt-4">Pemeriksaan Fisik</h5>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card bg-light border">
                                <div class="card-header py-3 d-flex flex-column flex-lg-row justify-content-between align-items-center bg-white">

                                    <!-- Pilihan Background Gender (Di Tengah & Responsif) -->
                                    <div class="d-flex justify-content-center align-items-center mb-3 mb-lg-0 w-100 w-lg-auto">
                                        <div class="btn-group-toggle d-flex justify-content-center w-100" data-toggle="buttons">

                                            <!-- Tombol Laki-laki -->
                                            <label class="btn btn-outline-primary active mx-2 px-3 px-sm-4 shadow-sm flex-fill flex-sm-grow-0 text-center" id="label-bg-laki" onclick="changeAnatomyBg('L')" style="border-radius: 0.5rem; max-width: 200px;">
                                                <input type="radio" name="bg_gender_anatomy" id="bg_laki" value="L" autocomplete="off" checked>
                                                <i class="fas fa-male mr-1 mr-sm-2"></i> Laki-laki
                                            </label>

                                            <!-- Tombol Perempuan -->
                                            <label class="btn btn-outline-danger mx-2 px-3 px-sm-4 shadow-sm flex-fill flex-sm-grow-0 text-center" id="label-bg-perempuan" onclick="changeAnatomyBg('P')" style="border-radius: 0.5rem; max-width: 200px;">
                                                <input type="radio" name="bg_gender_anatomy" id="bg_perempuan" value="P" autocomplete="off">
                                                <i class="fas fa-female mr-1 mr-sm-2"></i> Perempuan
                                            </label>

                                        </div>
                                    </div>

                                    <!-- Toolbar Drawing Tools (Rapi & Responsif) -->
                                    <div class="d-flex align-items-center justify-content-center flex-wrap w-100 w-lg-auto">

                                        <!-- Pilihan Warna Pen -->
                                        <div class="d-flex align-items-center mx-2 mb-2 mb-sm-0">
                                            <span class="small font-weight-bold mr-1 text-secondary">Warna:</span>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Warna">
                                                <button type="button" class="btn btn-sm btn-danger active" id="btn-color-red" onclick="setAnatomyColor('#dc3545', this)" title="Merah (Titik/Nodul)"><i class="fas fa-circle"></i></button>
                                                <button type="button" class="btn btn-sm btn-primary" id="btn-color-blue" onclick="setAnatomyColor('#007bff', this)" title="Biru"><i class="fas fa-circle"></i></button>
                                                <button type="button" class="btn btn-sm btn-dark" id="btn-color-black" onclick="setAnatomyColor('#212529', this)" title="Hitam"><i class="fas fa-circle"></i></button>
                                                <button type="button" class="btn btn-sm btn-warning" id="btn-color-yellow" onclick="setAnatomyColor('#ffc107', this)" title="Kuning"><i class="fas fa-circle"></i></button>
                                            </div>
                                        </div>

                                        <!-- Ukuran Garis -->
                                        <div class="d-flex align-items-center mx-2 mb-2 mb-sm-0">
                                            <span class="small font-weight-bold mr-1 text-secondary">Ukuran:</span>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Ukuran">
                                                <button type="button" class="btn btn-outline-secondary" onclick="setAnatomyLineWidth(2, this)" title="Kecil">Kecil</button>
                                                <button type="button" class="btn btn-outline-secondary active" onclick="setAnatomyLineWidth(4, this)" title="Sedang">Sedang</button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="setAnatomyLineWidth(8, this)" title="Besar">Besar</button>
                                            </div>
                                        </div>

                                        <!-- Aksi: Undo & Reset -->
                                        <div class="btn-group btn-group-sm mx-2 mb-2 mb-sm-0" role="group">
                                            <button type="button" class="btn btn-outline-secondary" onclick="undoAnatomyDraw()" title="Batalkan Goresan Terakhir">
                                                <i class="fas fa-undo"></i> Undo
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="resetAnatomyCanvas()" title="Hapus Semua Coretan">
                                                <i class="fas fa-trash-alt"></i> Reset
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-body text-center p-2" style="background-color: #f8f9fc;">
                                    <div class="d-inline-block position-relative border rounded shadow-sm overflow-hidden" style="max-width: 100%; background: #ffffff;">
                                        <!-- Canvas Gambar Anatomi Tubuh -->
                                        <canvas id="anatomy-canvas" width="800" height="600" style="width: 100%; max-width: 800px; height: auto; display: block; cursor: crosshair; touch-action: none;"></canvas>
                                    </div>
                                    <div class="text-muted mt-1 small">
                                        <i class="fas fa-info-circle mr-1 text-primary"></i> Tandai lokasi nodul/massa/lesi secara langsung di atas gambar anatomi tubuh menggunakan mouse atau layar sentuh.
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input untuk simpan base64 gambar pemeriksaan fisik -->
                            <input type="hidden" name="pemeriksaan_fisik_gambar" id="pemeriksaan_fisik_gambar">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Catatan Deskripsi Pemeriksaan Fisik</label>
                        <textarea class="form-control" name="pemeriksaan_fisik" rows="3" placeholder="Tuliskan deskripsi pemeriksaan fisik (misal: lokasi benjolan/nodul, ukuran, konsistensi, batas, mobilitas, nyeri tekan)..."></textarea>
                    </div>

                    <!-- DIAGNOSIS, TERAPI, RENCANA, CATATAN -->
                    <div class="row mt-4">
                        <div class="col-md-6 form-group">
                            <h5 class="font-weight-bold border-bottom pb-2 mb-3">Diagnosis</h5>
                            <textarea class="form-control" name="diagnosis" rows="4" placeholder="Masukkan diagnosis..."></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <h5 class="font-weight-bold border-bottom pb-2 mb-3">Terapi/Tindakan</h5>
                            <textarea class="form-control" name="terapi_tindakan" rows="4" placeholder="Masukkan terapi/tindakan..."></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <h5 class="font-weight-bold border-bottom pb-2 mb-3">Rencana Kerja</h5>
                            <textarea class="form-control" name="rencana_kerja" rows="4" placeholder="Masukkan rencana kerja..."></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <h5 class="font-weight-bold border-bottom pb-2 mb-3">Catatan Penting</h5>
                            <textarea class="form-control" name="catatan_penting" rows="4" placeholder="Tambahkan catatan penting jika ada..."></textarea>
                        </div>
                    </div>
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

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>
<?= $this->include('templates/frs/cetak_informasi'); ?>

<!-- Konfigurasi data untuk scriptFnab.js -->
<script>
    window.fnabConfig = {
        namaPasien: <?= json_encode($frs['nama_pasien'] ?? '') ?>,
        jenisKelaminPasien: <?= json_encode($frs['jenis_kelamin_pasien'] ?? '') ?>,
        tanggalLahirPasien: <?= json_encode($frs['tanggal_lahir_pasien'] ?? '') ?>,
        tanggalTransaksi: <?= json_encode($frs['tanggal_transaksi'] ?? '') ?>,
        saveSignatureUrl: <?= json_encode(base_url('signature/save')) ?>,
        updatePrintUrl: <?= json_encode(base_url('frs/update_print/' . ($frs['id_frs'] ?? ''))) ?>,
        signature: <?= !empty($signature) ? json_encode($signature) : 'null' ?>,
        imgLaki: <?= json_encode(base_url('img/GambarLakiLaki.png')) ?>,
        imgPerempuan: <?= json_encode(base_url('img/GambarPerempuan.png')) ?>
    };
</script>

<!-- Script untuk menyimpan tanda tangan digital dan data repository -->
<script src="<?= base_url('js/signatureRepository.js') ?>"></script>
<!-- Script khusus FNAB -->
<script src="<?= base_url('js/scriptFnab.js') ?>"></script>