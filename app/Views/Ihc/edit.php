<?= $this->include('templates/ihc/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit</h6>
        </div>

        <div class="card-body">
            <h1>Edit Data Imunohistokimia</h1>
            <a href="<?= base_url('ihc/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

            <!-- Form -->
            <form id="form-ihc" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_ihc" value="<?= $ihc['id_ihc'] ?>">

                <!-- Kolom Kode IHC dan Diagnosa -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode IHC</label>
                    <div class="col-sm-4">
                        <input type="text" name="kode_ihc" value="<?= $ihc['kode_ihc'] ?? '' ?>" class="form-control">
                    </div>

                    <label class="col-sm-2 col-form-label">Diagnosa</label>
                    <div class="col-sm-4">
                        <input type="text" name="diagnosa_klinik" value="<?= $ihc['diagnosa_klinik'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Nama Pasien dan Dokter Pengirim -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $ihc['nama_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                    <div class="col-sm-4">
                        <input type="text" name="dokter_pengirim" value="<?= $ihc['dokter_pengirim'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Norm Pasien dan Unit Asal -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Norm Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $ihc['norm_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Unit Asal</label>
                    <div class="col-sm-4">
                        <input type="text" name="unit_asal" value="<?= $ihc['unit_asal'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Tindakan Spesimen dan Tanggal Permintaan -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tindakan Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="tindakan_spesimen" value="<?= $ihc['tindakan_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Permintaan</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_permintaan" value="<?= $ihc['tanggal_permintaan'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Lokasi Spesimen dan Tanggal Hasil -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Lokasi Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="lokasi_spesimen" value="<?= $ihc['lokasi_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Hasil</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_hasil" value="<?= $ihc['tanggal_hasil'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>
                <!-- Kolom no tlp dan bpjs-->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">No Telfon Pasien</label>
                    <div class="col-sm-4">
                        <input type="text" name="no_tlp_ihc" value="<?= $ihc['no_tlp_ihc'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Nomor BPJS Pasien</label>
                    <div class="col-sm-4">
                        <input type="text" name="no_bpjs_ihc" value="<?= $ihc['no_bpjs_ihc'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>
                <!-- Kolom no ktp -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">No KTP Pasien</label>
                    <div class="col-sm-4">
                        <input type="text" name="no_ktp_ihc" value="<?= $ihc['no_ktp_ihc'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Kode Block IHC</label>
                    <div class="col-sm-4">
                        <input type="text" name="kode_block_ihc" value="<?= $ihc['kode_block_ihc'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-4">

                    </div>

                    <label class="col-sm-2 col-form-label">Kontrol Positif</label>
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <!-- Kontrol ER -->
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="ER" value="0">
                                    <input type="checkbox" class="custom-control-input" id="ER" name="ER" value="1"
                                        <?= isset($ihc['ER']) && $ihc['ER'] == 1 ? 'checked' : '' ?>>
                                    <label class="custom-control-label font-weight-bold" for="ER">Kontrol ER (Positif)</label>
                                </div>
                            </div>
                            <!-- Kontrol PR -->
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="PR" value="0">
                                    <input type="checkbox" class="custom-control-input" id="PR" name="PR" value="1"
                                        <?= isset($ihc['PR']) && $ihc['PR'] == 1 ? 'checked' : '' ?>>
                                    <label class="custom-control-label font-weight-bold" for="PR">Kontrol PR (Positif)</label>
                                </div>
                            </div>
                            <!-- Kontrol HER2 -->
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="HER2" value="0">
                                    <input type="checkbox" class="custom-control-input" id="HER2" name="HER2" value="1"
                                        <?= isset($ihc['HER2']) && $ihc['HER2'] == 1 ? 'checked' : '' ?>>
                                    <label class="custom-control-label font-weight-bold" for="HER2">Kontrol HER2 (Positif)</label>
                                </div>
                            </div>
                            <!-- Kontrol KI67 -->
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="KI67" value="0">
                                    <input type="checkbox" class="custom-control-input" id="KI67" name="KI67" value="1"
                                        <?= isset($ihc['KI67']) && $ihc['KI67'] == 1 ? 'checked' : '' ?>>
                                    <label class="custom-control-label font-weight-bold" for="KI67">Kontrol KI67 (Positif)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-4">
                        <!-- kosong / isi sesuai kebutuhan -->
                    </div>

                    <label class="col-sm-2 col-form-label">Status Kontrol</label>
                    <div class="col-sm-4">
                        <select name="status_kontrol" id="status_kontrol" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            <option value="Tersedia" <?= ($ihc['status_kontrol'] === 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Terbatas" <?= ($ihc['status_kontrol'] === 'Terbatas') ? 'selected' : '' ?>>Terbatas</option>
                            <option value="Habis" <?= ($ihc['status_kontrol'] === 'Habis') ? 'selected' : '' ?>>Habis</option>
                        </select>
                    </div>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const select = document.getElementById("status_kontrol");

                        function updateSelectColor() {
                            select.classList.remove("bg-success", "bg-warning", "bg-danger", "text-white", "text-dark");

                            if (select.value === "Tersedia") {
                                select.classList.add("bg-success", "text-white");
                            } else if (select.value === "Terbatas") {
                                select.classList.add("bg-warning", "text-dark");
                            } else if (select.value === "Habis") {
                                select.classList.add("bg-danger", "text-white");
                            }
                        }

                        select.addEventListener("change", updateSelectColor);
                        updateSelectColor(); // Panggil saat awal (jika ada nilai dari DB)
                    });
                </script>

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

                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
                        <!-- Kolom Makroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Makroskopis</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="makroskopis_ihc" id="makroskopis_ihc"><?= $ihc['makroskopis_ihc'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Kolom Mikroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Mikroskopis</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="mikroskopis_ihc" id="mikroskopis_ihc"><?= $ihc['mikroskopis_ihc'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Kolom Hasil IHC dan Jumlah Slide -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hasil Kesimpulan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="hasil_ihc" id="hasil_ihc"><?= $ihc['hasil_ihc'] ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hasil keseluruhan:</label>
                        </div>
                        <textarea class="form-control summernote_hasil" name="print_ihc" id="print_ihc" rows="5">
                            <?= $ihc['print_ihc'] ?? '' ?>
                        </textarea>
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Dokter yang Membaca</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="id_user_dokter_pembacaan_ihc" name="id_user_dokter_pembacaan_ihc">
                                    <option value="" <?= empty($ihc['id_user_dokter_pembacaan_ihc']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                                    <?php foreach ($users as $user): ?>
                                        <?php if ($user['status_user'] === 'Dokter'): ?>
                                            <option value="<?= $user['id_user'] ?>"
                                                <?= isset($pembacaan_ihc['id_user_dokter_pembacaan_ihc']) && $user['id_user'] == $pembacaan_ihc['id_user_dokter_pembacaan_ihc'] ? 'selected' : '' ?>>
                                                <?= $user['nama_user'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('ihc/update/' . $ihc['id_ihc']); ?>">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-info btn-user w-100 w-md-auto" onclick="cetakProses()">
                            <i class="fas fa-print"></i> Cetak Proses
                        </button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-primary btn-user w-100 w-md-auto" onclick="cetakPrintihc()">
                            <i class="fas fa-print"></i> Cetak Hasil
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
<?= $this->include('templates/ihc/footer_edit'); ?>
<?= $this->include('templates/ihc/cetak_proses'); ?>
<?= $this->include('templates/ihc/cetak_print'); ?>