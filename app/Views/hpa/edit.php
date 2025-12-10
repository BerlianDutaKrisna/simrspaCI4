<?= $this->include('templates/hpa/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit</h6>
        </div>

        <div class="card-body">
            <h1>Edit Data Histopatologi</h1>
            <a href="<?= base_url('hpa/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

            <!-- Form -->
            <form id="form-hpa" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa['id_hpa'] ?>">
                <input type="hidden" name="id_mutu_hpa" value="<?= $hpa['id_mutu_hpa'] ?>">
                <input type="hidden" name="total_nilai_mutu_hpa" value="<?= $hpa['total_nilai_mutu_hpa']; ?>">

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

                <?= $this->include('templates/exam/riwayat'); ?>

                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
                        <!-- Kolom Foto Makroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Foto Makroskopis</label>
                            <div class="col-sm-6">

                                <!-- Input file -->
                                <input type="file"
                                    name="foto_makroskopis_hpa"
                                    id="foto_makroskopis_hpa"
                                    class="form-control form-control-user">

                                <!-- Input keterangan -->
                                <label class="mt-3">Keterangan Foto Makroskopis</label>
                                <input type="text"
                                    class="form-control"
                                    name="keterangan_foto_makroskopis"
                                    id="keterangan_foto_makroskopis">

                                <!-- Tombol Upload -->
                                <button type="submit"
                                    class="btn btn-primary mt-3 btn-upload"
                                    id="uploadFotoButton"
                                    formaction="<?= base_url('FotoMakroskopis/upload/' . $hpa['id_hpa']) ?>">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Foto
                                </button>

                                <hr>

                                <!-- ================================ -->
                                <!-- DAFTAR SEMUA FOTO (GRID CARD) -->
                                <!-- ================================ -->
                                <h6 class="mt-4">Daftar Foto Makroskopis:</h6>

                                <div class="row mt-3">
                                    <?php if (!empty($foto)): ?>
                                        <?php foreach ($foto as $f): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card shadow-sm">

                                                    <div class="image-wrapper">
                                                        <img src="<?= base_url('uploads/hpa/foto/' . $f['nama_file']) ?>"
                                                            class="card-img-top uniform-img foto-thumbnail"
                                                            alt="Foto Makroskopis"
                                                            style="cursor:pointer;">
                                                    </div>

                                                    <div class="card-body">
                                                        <p class="card-text mb-2">
                                                            <?= esc($f['keterangan'] ?? 'Tidak ada keterangan') ?>
                                                        </p>

                                                        <!-- Tombol Hapus -->
                                                        <a href="<?= base_url('FotoMakroskopis/delete/' . $f['id_foto_makroskopis']) ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Hapus foto ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <p class="text-muted">Belum ada foto makroskopis.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
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
                    </div>
                </div>
                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
                        <!-- Kolom Makroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Makroskopis</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="makroskopis_hpa" id="makroskopis_hpa"><?= $hpa['makroskopis_hpa'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Kolom Jumlah Slide dan Tombol Cetak Stiker -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="jumlah_slide">Jumlah Slide</label>
                            <div class="col-sm-2">
                                <input type="number" name="jumlah_slide" id="jumlah_slide"
                                    class="form-control form-control-sm jumlah-slide-input"
                                    data-id="<?= $hpa['id_hpa']; ?>"
                                    value="<?= $hpa['jumlah_slide']; ?>"
                                    min="0" step="1" style="width:100px;">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-outline-info btn-sm btn-cetak-stiker"
                                    data-id="<?= esc($hpa['id_hpa']); ?>"
                                    data-kode="<?= esc($hpa['kode_hpa']); ?>">
                                    <i class="fas fa-print"></i> Cetak Stiker
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

                        <!-- Kolom Hasil HPA dan Jumlah Slide -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hasil Hpa</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="hasil_hpa" id="hasil_hpa"><?= $hpa['hasil_hpa'] ?? '' ?></textarea>
                            </div>
                        </div>
                        <!-- Dokter Pemotong -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Dokter yang memotong</label>
                            <div class="col-sm-5">
                                <select class="form-control" id="id_user_dokter_pemotongan_hpa" name="id_user_dokter_pemotongan_hpa">
                                    <option value="" <?= empty($hpa['id_user_dokter_pemotongan_hpa']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
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
                            <label class="col-sm-2 col-form-label" for="jumlah_slide">Potong ulang</label>
                            <div class="col-sm-2">
                                <label class="col-form-label" for="PUG">Potong ulang Gross (PUG)</label>
                                <?php
                                $options = ['0', '1', '2', '3'];
                                $pugValue = isset($hpa['PUG']) ? (string)$hpa['PUG'] : '';
                                ?>
                                <select class="form-control" id="PUG" name="PUG" onchange="handlePugChange(this)">
                                    <?php foreach ($options as $opt): ?>
                                        <option value="<?= $opt ?>" <?= ($pugValue === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                    <option value="lainnya" <?= (!in_array($pugValue, $options) && $pugValue !== '') ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                                <input
                                    type="text"
                                    class="form-control mt-2 <?= (!in_array($pugValue, $options) && $pugValue !== '') ? '' : 'd-none' ?>"
                                    id="pug_custom"
                                    name="pug_custom"
                                    placeholder="Masukkan nilai PUG lainnya"
                                    value="<?= (!in_array($pugValue, $options) && $pugValue !== '') ? esc($pugValue) : '' ?>">
                            </div>

                            <div class="col-sm-2">
                                <label class="col-form-label" for="PUB">Potong ulang Block (PUB)</label>
                                <?php
                                $pubValue = isset($hpa['PUB']) ? (string)$hpa['PUB'] : '';
                                ?>
                                <select class="form-control" id="PUB" name="PUB" onchange="handlePUBChange(this)">
                                    <?php foreach ($options as $opt): ?>
                                        <option value="<?= $opt ?>" <?= ($pubValue === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                    <option value="lainnya" <?= (!in_array($pubValue, $options) && $pubValue !== '') ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                                <input
                                    type="text"
                                    class="form-control mt-2 <?= (!in_array($pubValue, $options) && $pubValue !== '') ? '' : 'd-none' ?>"
                                    id="PUB_custom"
                                    name="PUB_custom"
                                    placeholder="Masukkan nilai PUB lainnya"
                                    value="<?= (!in_array($pubValue, $options) && $pubValue !== '') ? esc($pubValue) : '' ?>">
                            </div>
                        </div>

                        <script>
                            function handlePugChange(select) {
                                if ($(select).val() === 'lainnya') {
                                    $('#pug_custom').removeClass('d-none');
                                } else {
                                    $('#pug_custom').addClass('d-none').val('');
                                }
                            }

                            function handlePUBChange(select) {
                                if ($(select).val() === 'lainnya') {
                                    $('#PUB_custom').removeClass('d-none');
                                } else {
                                    $('#PUB_custom').addClass('d-none').val('');
                                }
                            }
                        </script>

                        <!-- Kolom Gambar Makroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Gambar Makroskopis</label>

                            <div class="col-sm-6">

                                <!-- Input File -->
                                <input type="file"
                                    name="gambar_makroskopis_hpa"
                                    id="gambar_makroskopis_hpa"
                                    class="form-control form-control-user">

                                <!-- Input Keterangan -->
                                <label class="mt-3">Keterangan Gambar Makroskopis</label>
                                <input type="text"
                                    class="form-control"
                                    name="keterangan_gambar_makroskopis"
                                    id="keterangan_gambar_makroskopis">

                                <!-- Tombol Upload -->
                                <button type="submit"
                                    class="btn btn-primary mt-3 btn-upload"
                                    formaction="<?= base_url('Gambar/UploadGambarMakroskopis/' . $hpa['id_hpa']) ?>">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Foto
                                </button>

                                <hr>

                                <!-- ================================ -->
                                <!-- DAFTAR SEMUA GAMBAR (GRID CARD) -->
                                <!-- ================================ -->
                                <h6 class="mt-4">Daftar Semua Gambar:</h6>

                                <div class="row mt-3">

                                    <?php if (!empty($gambar)): ?>
                                        <?php foreach ($gambar as $g): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card shadow-sm">

                                                    <!-- Wrapper agar ukuran seragam -->
                                                    <div class="image-wrapper">
                                                        <img src="<?= base_url('uploads/hpa/gambar/' . $g['nama_file']) ?>"
                                                            class="card-img-top uniform-img foto-thumbnail"
                                                            alt="Gambar Makroskopis"
                                                            style="cursor:pointer;">
                                                    </div>

                                                    <div class="card-body">
                                                        <p class="card-text mb-2">
                                                            <?= esc($g['keterangan'] ?? 'Tidak ada keterangan') ?>
                                                        </p>

                                                        <!-- Tombol Hapus -->
                                                        <a href="<?= base_url('Gambar/Delete/' . $g['id_gambar']) ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Hapus gambar ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php else: ?>
                                        <div class="col-12">
                                            <p class="text-muted">Belum ada gambar makroskopis.</p>
                                        </div>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Mutu HPA</label>
                            <div class="col-sm-10">
                                <div class="form-check mb-3">
                                    <input type="checkbox" id="checkAll_<?= $hpa['id_mutu_hpa']; ?>" class="form-check-input">
                                    <label class="form-check-label" for="checkAll_<?= $hpa['id_mutu_hpa']; ?>">Pilih Semua</label>
                                </div>

                                <div class="row">
                                    <!-- Baris 1 -->
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_1" value="10"
                                                id="indikator_1_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_1'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_1_<?= $hpa['id_mutu_hpa']; ?>">Cek kesesuaian identitas?</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_2" value="10"
                                                id="indikator_2_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_2'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_2_<?= $hpa['id_mutu_hpa']; ?>">Volume cairan sesuai?</label>
                                        </div>
                                    </div>

                                    <!-- Baris 2 -->
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_3" value="10"
                                                id="indikator_3_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_3'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_3_<?= $hpa['id_mutu_hpa']; ?>">Block parafin tidak ada fragmentasi?</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_4" value="10"
                                                id="indikator_4_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_4'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_4_<?= $hpa['id_mutu_hpa']; ?>">Jaringan terfiksasi merata?</label>
                                        </div>
                                    </div>

                                    <!-- Baris 3 -->
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_5" value="10"
                                                id="indikator_5_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_5'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_5_<?= $hpa['id_mutu_hpa']; ?>">Potongan tipis dan merata?</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_6" value="10"
                                                id="indikator_6_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_6'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_6_<?= $hpa['id_mutu_hpa']; ?>">Sediaan tanpa lipatan?</label>
                                        </div>
                                    </div>

                                    <!-- Baris 4 -->
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_7" value="10"
                                                id="indikator_7_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_7'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_7_<?= $hpa['id_mutu_hpa']; ?>">Sediaan tanpa goresan mata pisau?</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_8" value="10"
                                                id="indikator_8_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_8'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_8_<?= $hpa['id_mutu_hpa']; ?>">Kontras warna sediaan cukup jelas?</label>
                                        </div>
                                    </div>

                                    <!-- Baris 5 -->
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_9" value="10"
                                                id="indikator_9_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_9'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_9_<?= $hpa['id_mutu_hpa']; ?>">Sediaan tanpa gelembung udara?</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="indikator_10" value="10"
                                                id="indikator_10_<?= $hpa['id_mutu_hpa']; ?>"
                                                class="form-check-input child-checkbox"
                                                <?= ($hpa['indikator_10'] !== "0") ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="indikator_10_<?= $hpa['id_mutu_hpa']; ?>">Sediaan tanpa bercak jari?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkAll = document.getElementById('checkAll_<?= $hpa['id_mutu_hpa']; ?>');
                            const checkboxes = document.querySelectorAll('.child-checkbox');

                            checkAll.addEventListener('change', function() {
                                checkboxes.forEach(cb => cb.checked = checkAll.checked);
                            });

                            checkboxes.forEach(cb => {
                                cb.addEventListener('change', function() {
                                    checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
                                });
                            });

                            checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
                        });
                    </script>

                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hasil keseluruhan:</label>
                        </div>
                        <textarea class="form-control summernote_hasil" name="print_hpa" id="print_hpa" rows="5">
                            <?= $hpa['print_hpa'] ?? '' ?>
                        </textarea>
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Dokter yang Membaca</label>
                            <div class="col-sm-5">
                                <select class="form-control" id="id_user_dokter_pembacaan_hpa" name="id_user_dokter_pembacaan_hpa">
                                    <option value="" <?= empty($hpa['id_user_dokter_pembacaan_hpa']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                                    <?php foreach ($users as $user): ?>
                                        <?php if ($user['status_user'] === 'Dokter'): ?>
                                            <option value="<?= $user['id_user'] ?>"
                                                <?= isset($pembacaan_hpa['id_user_dokter_pembacaan_hpa']) && $user['id_user'] == $pembacaan_hpa['id_user_dokter_pembacaan_hpa'] ? 'selected' : '' ?>>
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
                            formaction="<?= base_url('hpa/update/' . $hpa['id_hpa']); ?>">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-info btn-user w-100 w-md-auto" onclick="cetakProses()">
                            <i class="fas fa-print"></i> Cetak Proses
                        </button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-primary btn-user w-100 w-md-auto" onclick="cetakPrintHpa()">
                            <i class="fas fa-print"></i> Cetak Hasil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS CARD GAMBAR SERAGAM -->
<style>
    .image-wrapper {
        width: 100%;
        height: 180px;
        /* Tinggi seragam */
        overflow: hidden;
        border-bottom: 1px solid #eee;
    }

    .uniform-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Proporsional memenuhi area */
        object-position: center;
    }
</style>

<!-- JavaScript Upload + Overlay -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("form-hpa");
        const loadingOverlay = document.getElementById("loading-overlay");

        // Semua tombol upload (foto & gambar) pakai class ini
        const uploadButtons = document.querySelectorAll(".btn-upload");

        uploadButtons.forEach(function(button) {
            button.addEventListener("click", function(e) {
                e.preventDefault(); // cegah submit default

                // Tampilkan overlay loading
                if (loadingOverlay) {
                    loadingOverlay.classList.remove("d-none");
                }

                // Set action form sesuai formaction pada tombol
                form.action = this.getAttribute("formaction");
                form.submit();
            });
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

<!-- Modal untuk Menampilkan Gambar yang Diperbesar -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- ukuran lebih besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Preview Gambar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" id="fotoZoom" alt="Preview Gambar">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const thumbnails = document.querySelectorAll(".foto-thumbnail");
        const fotoZoom = document.getElementById("fotoZoom");

        thumbnails.forEach(function(img) {
            img.addEventListener("click", function() {
                const src = this.getAttribute("src");
                fotoZoom.setAttribute("src", src);
                $('#fotoModal').modal('show'); // buka modal Bootstrap
            });
        });
    });
</script>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/hpa/footer_edit'); ?>
<?= $this->include('templates/hpa/cetak_proses'); ?>
<?= $this->include('templates/hpa/cetak_stiker'); ?>
<?= $this->include('templates/hpa/cetak_print'); ?>

// jumlah slide ajax
<script>
    $(document).ready(function() {
        $(".jumlah-slide-input").on("change", function() {
            let input = $(this);
            let id_hpa = input.data("id");
            let jumlah_slide = input.val();

            $.ajax({
                url: "<?= base_url('hpa/update_jumlah_slide'); ?>",
                type: "POST",
                data: {
                    id_hpa: id_hpa,
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