<?= $this->include('templates/hpa/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Mikroskopis</h6>
        </div>
        <div class="card-body">
            <h1>Edit Data Mikroskopis Histopatologi</h1>
            <a href="<?= base_url('pembacaan_hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

            <!-- Form -->
            <form id="form-hpa" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa['id_hpa'] ?>">
                <input type="hidden" name="id_pemotongan_hpa" value="<?= $pemotongan_hpa['id_pemotongan_hpa'] ?>">
                <input type="hidden" name="id_pembacaan_hpa" value="<?= $pembacaan_hpa['id_pembacaan_hpa'] ?>">
                <input type="hidden" name="id_mutu_hpa" value="<?= $mutu_hpa['id_mutu_hpa'] ?>">
                <input type="hidden" name="page_source" value="edit_mikroskopis">
                <input type="hidden" name="total_nilai_mutu_hpa" value="<?= $mutu_hpa['total_nilai_mutu_hpa']; ?>">


                <!-- Kolom Kode HPA dan Diagnosa -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode HPA</label>
                    <div class="col-sm-4">
                        <input type="text" name="kode_hpa" value="<?= $hpa['kode_hpa'] ?? '' ?>" class="form-control">
                    </div>

                    <label class="col-sm-2 col-form-label">Diagnosa</label>
                    <div class="col-sm-4">
                        <input type="text" name="diagnosa_klinik" value="<?= $hpa['diagnosa_klinik'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Nama Pasien dan Dokter Pengirim -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $hpa['nama_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                    <div class="col-sm-4">
                        <input type="text" name="dokter_pengirim" value="<?= $hpa['dokter_pengirim'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Norm Pasien dan Unit Asal -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Norm Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $hpa['norm_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Unit Asal</label>
                    <div class="col-sm-4">
                        <input type="text" name="unit_asal" value="<?= $hpa['unit_asal'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Tindakan Spesimen dan Tanggal Permintaan -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tindakan Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="tindakan_spesimen" value="<?= $hpa['tindakan_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Permintaan</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_permintaan" value="<?= $hpa['tanggal_permintaan'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Lokasi Spesimen dan Tanggal Hasil -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Lokasi Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="lokasi_spesimen" value="<?= $hpa['lokasi_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Hasil</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_hasil" value="<?= $hpa['tanggal_hasil'] ?? '' ?>" class="form-control form-control-user">
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

                <!-- Kolom Foto Makroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Foto Makroskopis</label>
                    <div class="col-sm-6">
                        <img src="<?= $hpa['foto_makroskopis_hpa'] !== null
                                        ? base_url('uploads/hpa/makroskopis/' . $hpa['foto_makroskopis_hpa'])
                                        : base_url('img/no_photo.jpg') ?>"
                            width="200"
                            alt="Foto Makroskopis"
                            class="img-thumbnail"
                            id="fotoMakroskopis"
                            data-toggle="modal"
                            data-target="#fotoModal">
                        <input type="file" name="foto_makroskopis_hpa" id="foto_makroskopis_hpa" class="form-control form-control-user mt-2">
                        <button type="submit" class="btn btn-primary mt-2"
                            formaction="<?= base_url('hpa/uploadFotoMakroskopis/' . $hpa['id_hpa']); ?>">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </button>
                    </div>
                </div>

                <!-- Kolom Makroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Makroskopis</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" name="makroskopis_hpa" id="makroskopis_hpa"><?= $hpa['makroskopis_hpa'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Kolom Foto Mikroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Foto Mikroskopis</label>
                    <div class="col-sm-6">
                        <img src="<?= $hpa['foto_mikroskopis_hpa'] !== null
                                        ? base_url('uploads/hpa/mikroskopis/' . $hpa['foto_mikroskopis_hpa'])
                                        : base_url('img/no_photo.jpg') ?>"
                            width="200"
                            alt="Foto Mikroskopis"
                            class="img-thumbnail"
                            id="fotoMikroskopis"
                            data-toggle="modal"
                            data-target="#fotoModal">
                        <input type="file" name="foto_mikroskopis_hpa" id="foto_mikroskopis_hpa" class="form-control form-control-user mt-2">
                        <button type="submit" class="btn btn-primary mt-2"
                            formaction="<?= base_url('hpa/uploadFotoMikroskopis/' . $hpa['id_hpa']); ?>">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </button>
                    </div>
                </div>

                <!-- Kolom Mikroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Mikroskopis</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" name="mikroskopis_hpa" id="mikroskopis_hpa"><?= $hpa['mikroskopis_hpa'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Kolom Hasil HPA dan Dokter -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Hasil Kesimpulan</label>
                    <div class="col-sm-4">
                        <textarea class="form-control summernote" name="hasil_hpa" id="hasil_hpa">
                            <?= $hpa['hasil_hpa'] ?? '' ?>
                        </textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="jumlah_slide">Dokter yang membaca</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="id_user_dokter_pemotongan_hpa" name="id_user_dokter_pemotongan_hpa">
                            <option value="" <?= empty($pemotongan_hpa['id_user_dokter_pemotongan_hpa']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status_user'] === 'Dokter'): ?>
                                    <option value="<?= $user['id_user'] ?>"
                                        <?= isset($pemotongan_hpa['id_user_dokter_pemotongan_hpa']) && $user['id_user'] == $pemotongan_hpa['id_user_dokter_pemotongan_hpa'] ? 'selected' : '' ?>>
                                        <?= $user['nama_user'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">mutu_hpa</label>
                    <div class="col-sm-4">
                        <div class="form-check">
                            <input type="checkbox" id="checkAll_<?= $mutu_hpa['id_mutu_hpa']; ?>" class="form-check-input">
                            <label class="form-check-label" for="checkAll_<?= $mutu_hpa['id_mutu_hpa']; ?>">
                                Pilih Semua
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                name="indikator_4"
                                value="10"
                                id="indikator_4_<?= $mutu_hpa['id_mutu_hpa']; ?>"
                                class="form-check-input child-checkbox"
                                <?= ($mutu_hpa['indikator_4'] !== "0") ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="indikator_4_<?= $mutu_hpa['id_mutu_hpa']; ?>">
                                Sediaan tanpa lipatan
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                name="indikator_5"
                                value="10"
                                id="indikator_5_<?= $mutu_hpa['id_mutu_hpa']; ?>"
                                class="form-check-input child-checkbox"
                                <?= ($mutu_hpa['indikator_5'] !== "0") ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="indikator_5_<?= $mutu_hpa['id_mutu_hpa']; ?>">
                                Sediaan tanpa goresan mata pisau
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                name="indikator_6"
                                value="10"
                                id="indikator_6_<?= $mutu_hpa['id_mutu_hpa']; ?>"
                                class="form-check-input child-checkbox"
                                <?= ($mutu_hpa['indikator_6'] !== "0") ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="indikator_6_<?= $mutu_hpa['id_mutu_hpa']; ?>">
                                Kontras warna sediaan cukup jelas
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                name="indikator_7"
                                value="10"
                                id="indikator_7_<?= $mutu_hpa['id_mutu_hpa']; ?>"
                                class="form-check-input child-checkbox"
                                <?= ($mutu_hpa['indikator_7'] !== "0") ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="indikator_7_<?= $mutu_hpa['id_mutu_hpa']; ?>">
                                Sediaan tanpa gelembung udara
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                name="indikator_8"
                                value="10"
                                id="indikator_8_<?= $mutu_hpa['id_mutu_hpa']; ?>"
                                class="form-check-input child-checkbox"
                                <?= ($mutu_hpa['indikator_8'] !== "0") ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="indikator_8_<?= $mutu_hpa['id_mutu_hpa']; ?>">
                                Sediaan tanpa bercak / sidik jari
                            </label>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const checkAll = document.getElementById('checkAll_<?= $mutu_hpa['id_mutu_hpa']; ?>');
                                const checkboxes = document.querySelectorAll('.child-checkbox');

                                // Ketika "Pilih Semua" dicentang/ditandai
                                checkAll.addEventListener('change', function() {
                                    checkboxes.forEach(checkbox => {
                                        checkbox.checked = checkAll.checked; // Semua checkbox mengikuti status "Pilih Semua"
                                    });
                                });

                                // Update status "Pilih Semua" berdasarkan checkbox lainnya
                                checkboxes.forEach(checkbox => {
                                    checkbox.addEventListener('change', function() {
                                        checkAll.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                                    });
                                });

                                // Cek apakah semua checkbox sudah dicentang, lalu centang "Pilih Semua" jika iya
                                checkAll.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                            });
                        </script>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-sm-6 text-center mb-3">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('hpa/update/' . $hpa['id_hpa']); ?>">
                            Simpan
                        </button>
                    </div>
                    <div class="col-sm-6 text-center">
                        <!-- Tombol Cetak -->
                        <button type="button" class="btn btn-info btn-user w-100" onclick="cetakProses()">
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
                <img src="<?= base_url('uploads/hpa/makroskopis/' . $hpa['foto_makroskopis_hpa']); ?>" class="img-fluid" alt="Foto Makroskopis" id="fotoZoom">
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Menampilkan Gambar yang Diperbesar -->
<div class="modal fade" id="fotoModalMikroskopis" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto Mikroskopis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Gambar yang akan ditampilkan lebih besar di modal -->
                <img src="<?= base_url('uploads/hpa/mikroskopis/' . $hpa['foto_mikroskopis_hpa']); ?>" class="img-fluid" alt="Foto Mikroskopis" id="fotoZoom">
            </div>
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
<?= $this->include('templates/hpa/footer_edit'); ?>
<?= $this->include('templates/hpa/cetak_proses'); ?>