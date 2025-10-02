<?= $this->include('templates/srs/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Makroskopis</h6>
    </div>
    <div class="card-body">
        <h1>Edit Data Makroskopis Sitologi</h1>
        <a href="<?= base_url('penerimaan_srs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Form -->
        <form id="form-srs" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_srs" value="<?= $srs['id_srs'] ?>">
            <input type="hidden" name="id_penerimaan_srs" value="<?= $srs['id_penerimaan_srs'] ?>">
            <input type="hidden" name="page_source" value="edit_makroskopis">

            <!-- Kolom Kode SRS dan Diagnosa -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kode SRS</label>
                <div class="col-sm-4">
                    <input type="text" name="kode_srs" value="<?= $srs['kode_srs'] ?? '' ?>" class="form-control">
                </div>

                <label class="col-sm-2 col-form-label">Diagnosa</label>
                <div class="col-sm-4">
                    <input type="text" name="diagnosa_klinik" value="<?= $srs['diagnosa_klinik'] ?? '' ?>" class="form-control form-control-user">
                </div>
            </div>

            <!-- Kolom Nama Pasien dan Dokter Pengirim -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Pasien</label>
                <div class="col-sm-4">
                    <p>&nbsp<?= $srs['nama_pasien'] ?? '' ?></p>
                </div>

                <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                <div class="col-sm-4">
                    <input type="text" name="dokter_pengirim" value="<?= $srs['dokter_pengirim'] ?? '' ?>" class="form-control form-control-user">
                </div>
            </div>

            <!-- Kolom Norm Pasien dan Unit Asal -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Norm Pasien</label>
                <div class="col-sm-4">
                    <p>&nbsp<?= $srs['norm_pasien'] ?? '' ?></p>
                </div>

                <label class="col-sm-2 col-form-label">Unit Asal</label>
                <div class="col-sm-4">
                    <input type="text" name="unit_asal" value="<?= $srs['unit_asal'] ?? '' ?>" class="form-control form-control-user">
                </div>
            </div>

            <!-- Kolom Tindakan Spesimen dan Tanggal Permintaan -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tindakan Spesimen</label>
                <div class="col-sm-4">
                    <input type="text" name="tindakan_spesimen" value="<?= $srs['tindakan_spesimen'] ?? '' ?>" class="form-control form-control-user">
                </div>

                <label class="col-sm-2 col-form-label">Tanggal Permintaan</label>
                <div class="col-sm-4">
                    <input type="date" name="tanggal_permintaan" value="<?= $srs['tanggal_permintaan'] ?? '' ?>" class="form-control form-control-user">
                </div>
            </div>

            <!-- Kolom Lokasi Spesimen dan Tanggal Hasil -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Lokasi Spesimen</label>
                <div class="col-sm-4">
                    <input type="text" name="lokasi_spesimen" value="<?= $srs['lokasi_spesimen'] ?? '' ?>" class="form-control form-control-user">
                </div>

                <label class="col-sm-2 col-form-label">Tanggal Hasil</label>
                <div class="col-sm-4">
                    <input type="date" name="tanggal_hasil" value="<?= $srs['tanggal_hasil'] ?? '' ?>" class="form-control form-control-user">
                </div>
            </div>

            <?= $this->include('templates/exam/riwayat'); ?>

            <!-- Kolom Foto Makroskopis -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Foto Makroskopis</label>
                <div class="col-sm-6">
                    <img src="<?= esc($srs['foto_makroskopis_srs'] !== null
                                    ? base_url('uploads/srs/makroskopis/' . $srs['foto_makroskopis_srs'])
                                    : base_url('img/no_photo.jpg')) ?>"
                        width="200"
                        alt="Foto Makroskopis"
                        class="img-thumbnail"
                        id="fotoMakroskopis"
                        data-toggle="modal"
                        data-target="#fotoModal">

                    <!-- Form Upload -->
                    <input type="file" name="foto_makroskopis_srs" id="foto_makroskopis_srs" class="form-control form-control-user mt-2">

                    <!-- Overlay Loading -->
                    <div id="loading-overlay" class="d-none">
                        <div class="spinner"></div>
                        <p class="loading-text">Mengunggah, harap tunggu...</p>
                    </div>

                    <!-- Tombol Upload -->
                    <button type="submit" class="btn btn-primary mt-2" id="uploadButton"
                        formaction="<?= base_url('srs/uploadFotoMakroskopis/' . $srs['id_srs']) ?>">
                        <i class="fas fa-cloud-upload-alt"></i> Upload
                    </button>
                </div>
            </div>

            <!-- JavaScript -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const form = document.getElementById("form-srs");
                    const uploadButton = document.getElementById("uploadButton");
                    const loadingOverlay = document.getElementById("loading-overlay");
                    uploadButton.addEventListener("click", function() {
                        // Tampilkan overlay loading
                        loadingOverlay.classList.remove("d-none");
                        // Kirim form secara langsung
                        form.action = this.getAttribute("formaction");
                        form.submit();
                    });
                });
                // Tambahkan CSS untuk overlay dan spinner
                const style = document.createElement("style");
                style.innerHTML = `
                #loading-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 9999;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                }
                .spinner {
                    width: 60px;
                    height: 60px;
                    border: 8px solid #f3f3f3;
                    border-top: 8px solid #3498db;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }
                .loading-text {
                    color: white;
                    margin-top: 10px;
                    font-size: 18px;
                    font-weight: bold;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                `;
                document.head.appendChild(style);
            </script>

            <!-- Kolom Makroskopis -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Makroskopis</label>
                <div class="col-sm-10">
                    <textarea class="form-control summernote" name="makroskopis_srs" id="makroskopis_srs"><?= $srs['makroskopis_srs'] ?? '' ?></textarea>
                </div>
            </div>

            <!-- Kolom Dokter dan Jumlah Slide -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="jumlah_slide">Jumlah Slide</label>
                <div class="col-sm-4">
                    <input type="number"
                        class="form-control form-control-sm jumlah-slide-input"
                        data-id="<?= $srs['id_srs']; ?>"
                        value="<?= $srs['jumlah_slide']; ?>"
                        min="0" step="1" style="width:100px;">
                </div>
                <label class="col-sm-2 col-form-label" for=""></label>
                <div class="col-sm-4">
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="form-group row">
                <div class="col-sm-6 text-center mb-3">
                    <button type="submit"
                        class="btn btn-success btn-user w-100"
                        formaction="<?= base_url('srs/update/' . $srs['id_srs']); ?>">
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
</div>

<!-- Modal untuk Menampilkan Gambar yang Diperbesar -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto Makroskopis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Gambar yang akan ditampilkan lebih besar di modal -->
                <img src="<?= base_url('uploads/srs/makroskopis/' . $srs['foto_makroskopis_srs']); ?>" class="img-fluid" alt="Foto Makroskopis" id="fotoZoom">
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/srs/footer_edit'); ?>
<?= $this->include('templates/srs/cetak_proses'); ?>

// jumlah slide ajax
<script>
    $(document).ready(function() {
        $(".jumlah-slide-input").on("change", function() {
            let input = $(this);
            let id_srs = input.data("id");
            let jumlah_slide = input.val();

            $.ajax({
                url: "<?= base_url('srs/update_jumlah_slide'); ?>",
                type: "POST",
                data: {
                    id_srs: id_srs,
                    jumlah_slide: jumlah_slide,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>" // CSRF protection
                },
                dataType: "json",
                success: function(res) {
                    if (res.status === "success") {
                        console.log("Jumlah slide berhasil diperbarui");

                        // Update tombol cetak di baris yang sama
                        let btnCetak = input.closest("tr").find(".btn-cetak-stiker");
                        if (btnCetak.length) {
                            btnCetak.data("slide", jumlah_slide);
                        }
                    } else {
                        alert("Gagal memperbarui jumlah slide!");
                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan: " + error);
                }
            });
        });
    });
</script>