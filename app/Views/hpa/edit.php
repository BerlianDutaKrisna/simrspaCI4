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
                                <h6 class="mt-4">Daftar Foto Makroskopis:</h6>
                                <div class="row mt-3">
                                    <?php if (!empty($foto)) : ?>
                                        <?php foreach ($foto as $f) : ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card shadow-sm">
                                                    <div class="image-wrapper">
                                                        <img
                                                            src="<?= base_url('uploads/hpa/foto/' . $f['nama_file']) ?>"
                                                            class="card-img-top uniform-img foto-thumbnail"
                                                            alt="Foto Makroskopis">
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- INPUT KETERANGAN (EDIT LANGSUNG) -->
                                                        <input type="text"
                                                            class="form-control mb-2 keterangan-input"
                                                            data-id="<?= $f['id_foto_makroskopis'] ?>"
                                                            value="<?= esc($f['keterangan'] ?? '') ?>">
                                                        <div class="d-flex justify-content-between">
                                                            <!-- HAPUS -->
                                                            <a href="<?= base_url('FotoMakroskopis/delete/' . $f['id_foto_makroskopis']) ?>"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Hapus foto ini?')">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </a>
                                                            <!-- SIMPAN KETERANGAN -->
                                                            <button type="button"
                                                                class="btn btn-success btn-sm btn-update-keterangan"
                                                                data-id="<?= $f['id_foto_makroskopis'] ?>">
                                                                <i class="fas fa-save"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="col-12">
                                            <p class="text-muted">Belum ada foto makroskopis.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <hr>
                                <!-- Input file -->
                                <input
                                    type="file"
                                    name="foto_makroskopis_hpa"
                                    class="form-control">
                                <!-- Input keterangan -->
                                <label class="mt-3">Keterangan Foto Makroskopis</label>
                                <input
                                    type="text"
                                    name="keterangan_foto_makroskopis"
                                    class="form-control">
                                <!-- Tombol Upload -->
                                <button
                                    type="submit"
                                    class="btn btn-primary mt-3 btn-upload"
                                    formaction="<?= base_url('FotoMakroskopis/upload/' . $hpa['id_hpa']) ?>">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Foto
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
                        <!-- Kolom Gambar Makroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Gambar Makroskopis</label>
                            <div class="col-sm-6">
                                <h6 class="mt-4">Daftar Semua Gambar:</h6>
                                <div class="row mt-3">
                                    <?php if (!empty($gambar)): ?>
                                        <?php foreach ($gambar as $g): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card shadow-sm">
                                                    <!-- Gambar -->
                                                    <div class="image-wrapper text-center">
                                                        <img src="<?= base_url('uploads/hpa/gambar/' . $g['nama_file']) ?>"
                                                            class="card-img-top uniform-img foto-thumbnail"
                                                            alt="Gambar Makroskopis"
                                                            style="object-fit:cover;height:200px;">
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- INPUT KETERANGAN -->
                                                        <input type="text"
                                                            class="form-control mb-2 keterangan-gambar-input"
                                                            data-id="<?= $g['id_gambar'] ?>"
                                                            value="<?= esc($g['keterangan']) ?>">
                                                        <!-- HAPUS -->
                                                        <a href="<?= base_url('Gambar/Delete/' . $g['id_gambar']) ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Hapus gambar ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                        <!-- SIMPAN KETERANGAN -->
                                                        <button type="button"
                                                            class="btn btn-success btn-sm btn-update-keterangan-gambar float-right"
                                                            data-id="<?= $g['id_gambar'] ?>">
                                                            <i class="fas fa-save"></i> Simpan
                                                        </button>
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
                                <hr>
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
                                    formaction="<?= base_url('Gambar/Upload/' . $hpa['id_hpa']) ?>">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Gambar
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="topografi">Topografi ICD-O</label>
                                    <input type="text" name="topografi_hpa" id="topografi" class="form-control" placeholder="Cari topografi ICD-O" value="<?= esc($hpa['topografi_hpa'] ?? '') ?>">
                                    <ul id="topografi-list" class="list-group" style="position: absolute; z-index: 1000; width: 100%; display: none;"></ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="morfologi">Morfologi ICD-O</label>
                                    <input type="text" name="morfologi_hpa" id="morfologi" class="form-control" placeholder="Cari morfologi ICD-O" value="<?= esc($hpa['morfologi_hpa'] ?? '') ?>">
                                    <ul id="morfologi-list" class="list-group" style="position: absolute; z-index: 1000; width: 100%; display: none;"></ul>
                                </div>
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

                <!-- Tombol Kirim ke SIMRS -->
                <div class="form-group row mt-2">
                    <div class="col-sm-4 text-center">
                        <button type="button"
                            id="btn-kirim-simrs"
                            class="btn btn-warning btn-user w-100"
                            onclick="bukaModalKunjunganHpa()">
                            <i class="fas fa-paper-plane"></i> Kirim ke SIMRS
                        </button>
                    </div>
                </div>

                <!-- Toast Notifikasi SIMRS -->
                <div id="simrs-toast-wrapper" style="position:fixed;top:20px;right:20px;z-index:99999;"></div>
            </form>
        </div>
    </div>
</div>

<!-- CSS CARD FOTO & GAMBAR SERAGAM -->
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-update-keterangan").forEach(btn => {
            btn.addEventListener("click", function() {
                const id = this.dataset.id;
                const input = document.querySelector(
                    '.keterangan-input[data-id="' + id + '"]'
                );
                if (!input) {
                    alert("Input tidak ditemukan");
                    return;
                }
                fetch("<?= base_url('FotoMakroskopis/update/') ?>" + id, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: new URLSearchParams({
                            keterangan: input.value,
                            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.status === "success") {
                            alert("Keterangan berhasil disimpan");
                        } else {
                            alert(res.message ?? "Gagal menyimpan");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Terjadi kesalahan server");
                    });
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-update-keterangan-gambar").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.dataset.id;
                const input = document.querySelector(
                    '.keterangan-gambar-input[data-id="' + id + '"]'
                );
                if (!input) {
                    alert("Input keterangan tidak ditemukan");
                    return;
                }
                fetch("<?= base_url('Gambar/update/') ?>" + id, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: new URLSearchParams({
                            keterangan: input.value,
                            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === "success") {
                            alert("Keterangan gambar berhasil disimpan");
                        } else {
                            alert(result.message ?? "Gagal menyimpan keterangan");
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Terjadi kesalahan server");
                    });
            });
        });
    });
</script>
<!-- Modal untuk Menampilkan Gambar yang Diperbesar -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                $('#fotoModal').modal('show');
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

<!-- Modal Pilih Kunjungan Pasien SIMRS (HPA) -->
<div class="modal fade" id="modalKunjunganSimrsHpa" tabindex="-1" role="dialog" aria-labelledby="modalKunjunganHpaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold" id="modalKunjunganHpaLabel">
                    <i class="fas fa-hospital"></i> Pilih Kunjungan Pasien - Kirim ke SIMRS (HPA)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Loading -->
                <div id="hpa-kunjungan-loading" class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data kunjungan pasien...</p>
                </div>
                <!-- Error -->
                <div id="hpa-kunjungan-error" class="alert alert-danger d-none"></div>
                <!-- Tabel -->
                <div id="hpa-kunjungan-table-wrapper" class="d-none">
                    <p class="text-muted mb-2">Pilih kunjungan yang sesuai untuk dikirim ke SIMRS:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Register</th>
                                    <th>Tanggal</th>
                                    <th>Norm</th>
                                    <th>Nama</th>
                                    <th>Pemeriksaan</th>
                                    <th>Diagnosa Klinik</th>
                                    <th>Hasil</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="hpa-kunjungan-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Script: Kirim ke SIMRS via Modal Kunjungan (HPA) -->
<script>
    var normPasienHpa = '<?= esc($hpa["norm_pasien"] ?? "") ?>';
    var idHpa        = '<?= esc($hpa["id_hpa"] ?? "") ?>';

    /**
     * Membuka modal dan fetch data kunjungan pasien dari API BDRS via SimrsController.
     */
    function bukaModalKunjunganHpa() {
        var norm = normPasienHpa;
        if (!norm) {
            tampilkanToastSimrs('danger', 'Norm pasien tidak ditemukan.');
            return;
        }

        // Reset state modal
        document.getElementById('hpa-kunjungan-loading').classList.remove('d-none');
        document.getElementById('hpa-kunjungan-error').classList.add('d-none');
        document.getElementById('hpa-kunjungan-table-wrapper').classList.add('d-none');
        document.getElementById('hpa-kunjungan-tbody').innerHTML = '';

        $('#modalKunjunganSimrsHpa').modal('show');

        // Fetch kunjungan dari SimrsController (proxy server-side)
        fetch('<?= base_url("simrs/modal_search") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ norm_simrs: norm })
        })
        .then(function(res) { return res.json(); })
        .then(function(result) {
            document.getElementById('hpa-kunjungan-loading').classList.add('d-none');

            if (result.status !== 'success' || !result.data || result.data.length === 0) {
                var errDiv = document.getElementById('hpa-kunjungan-error');
                errDiv.textContent = result.message || 'Tidak ada data kunjungan untuk pasien ini.';
                errDiv.classList.remove('d-none');
                return;
            }

            var tbody = document.getElementById('hpa-kunjungan-tbody');
            result.data.forEach(function(item) {
                var hasil = (item.hasil === null || item.hasil === '' || item.hasil === undefined)
                    ? '<span class="badge badge-secondary">Belum Ada Hasil</span>'
                    : '<span class="badge badge-success">Ada Hasil</span>';
                var row = '<tr>' +
                    '<td>' + (item.idtransaksi || '-') + '</td>' +
                    '<td>' + (item.register || '-') + '</td>' +
                    '<td>' + (item.tanggal || '-') + '</td>' +
                    '<td>' + (item.norm || '-') + '</td>' +
                    '<td>' + (item.nama || '-') + '</td>' +
                    '<td>' + (item.pemeriksaan || '-') + '</td>' +
                    '<td>' + (item.diagnosaklinik || '-') + '</td>' +
                    '<td>' + hasil + '</td>' +
                    '<td><button class="btn btn-warning btn-sm" onclick="pilihKunjunganHpa(\'' + item.idtransaksi + '\')"><i class="fas fa-check"></i> Pilih</button></td>' +
                    '</tr>';
                tbody.insertAdjacentHTML('beforeend', row);
            });

            document.getElementById('hpa-kunjungan-table-wrapper').classList.remove('d-none');
        })
        .catch(function(err) {
            document.getElementById('hpa-kunjungan-loading').classList.add('d-none');
            var errDiv = document.getElementById('hpa-kunjungan-error');
            errDiv.textContent = 'Gagal menghubungi server: ' + err.message;
            errDiv.classList.remove('d-none');
        });
    }

    /**
     * Dipanggil saat user memilih satu kunjungan.
     * Tutup modal, simpan form terlebih dahulu (fungsi tombol submit), lalu jalankan kirimKeSimrsHpa.
     */
    function pilihKunjunganHpa(idtransaksi) {
        $('#modalKunjunganSimrsHpa').modal('hide');

        var form = document.getElementById('form-hpa');
        var btn = document.getElementById('btn-kirim-simrs');
        var updateUrl = '<?= base_url("hpa/update/" . $hpa["id_hpa"]) ?>';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan data form...';
        }
        tampilkanToastSimrs('info', 'Menyimpan perubahan data form terlebih dahulu...');

        // Sinkronisasi Summernote jika ada editor
        if (window.$ && $.fn && $.fn.summernote) {
            $('.summernote, .summernote_hasil').each(function() {
                if ($(this).data('summernote')) {
                    $(this).val($(this).summernote('code'));
                }
            });
        }

        var formData = new FormData(form);

        fetch(updateUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function() {
            tampilkanToastSimrs('info', 'Data berhasil diperbarui. Mengirim ke SIMRS...');
            kirimKeSimrsHpa(idtransaksi);
        })
        .catch(function(err) {
            console.warn('Penyimpanan form gagal, lanjut kirim SIMRS:', err);
            kirimKeSimrsHpa(idtransaksi);
        });
    }


    /**
     * Mengirim data HPA ke SIMRS dengan idtransaksi yang dipilih.
     */
    function kirimKeSimrsHpa(idtransaksi) {
        var btn = document.getElementById('btn-kirim-simrs');
        var csrfToken = '<?= csrf_token() ?>';
        var csrfHash  = '<?= csrf_hash() ?>';

        if (!idHpa) {
            tampilkanToastSimrs('danger', 'ID HPA tidak ditemukan pada halaman ini.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

        var formData = new URLSearchParams();
        formData.append('id_hpa', idHpa);
        formData.append('idtransaksi', idtransaksi);
        formData.append(csrfToken, csrfHash);

        fetch('<?= base_url("authorized_hpa/kirim_ke_simrs") ?>', {
            method: 'POST',
            headers: {
                'Content-Type'     : 'application/x-www-form-urlencoded',
                'X-Requested-With' : 'XMLHttpRequest'
            },
            body: formData.toString()
        })
        .then(function(response) {
            return response.json().then(function(data) {
                return { status: response.status, body: data };
            });
        })
        .then(function(result) {
            if (result.body.success) {
                tampilkanToastSimrs('success', result.body.message || 'Data berhasil dikirim ke SIMRS.');
            } else {
                tampilkanToastSimrs('danger', result.body.message || 'Gagal mengirim data ke SIMRS.');
            }
        })
        .catch(function(err) {
            console.error('[kirimKeSimrsHpa] Error:', err);
            tampilkanToastSimrs('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim ke SIMRS';
        });
    }

    /**
     * Menampilkan toast notifikasi di pojok kanan atas.
     */
    function tampilkanToastSimrs(type, pesan) {
        var wrapper = document.getElementById('simrs-toast-wrapper');
        var warnaCls = {
            success : 'bg-success',
            danger  : 'bg-danger',
            warning : 'bg-warning text-dark',
            info    : 'bg-info'
        };
        var ikon = {
            success : 'fas fa-check-circle',
            danger  : 'fas fa-times-circle',
            warning : 'fas fa-exclamation-triangle',
            info    : 'fas fa-info-circle'
        };

        var toast = document.createElement('div');
        toast.style.cssText = [
            'min-width:280px',
            'margin-bottom:8px',
            'padding:14px 18px',
            'border-radius:8px',
            'color:#fff',
            'font-size:14px',
            'display:flex',
            'align-items:center',
            'gap:10px',
            'box-shadow:0 4px 12px rgba(0,0,0,.25)',
            'opacity:0',
            'transition:opacity .3s ease'
        ].join(';');
        toast.className = warnaCls[type] || 'bg-secondary';
        toast.innerHTML = '<i class="' + (ikon[type] || 'fas fa-bell') + '"></i> ' + pesan;

        wrapper.appendChild(toast);
        requestAnimationFrame(function() {
            requestAnimationFrame(function() { toast.style.opacity = '1'; });
        });
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() { toast.remove(); }, 350);
        }, 5000);
    }
</script>

<script>
    $(function() {

        function setupAutocomplete(inputSelector, listSelector, url) {
            const $input = $(inputSelector);
            const $list = $(listSelector);

            $input.on('input', function() {
                const query = $(this).val();
                if (query.length < 1) {
                    $list.hide();
                    return;
                }

                $.ajax({
                    url: url,
                    data: {
                        q: query
                    },
                    dataType: 'json',
                    success: function(data) {
                        $list.empty();
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                $list.append(`<li class="list-group-item list-group-item-action" data-id="${item.id}">${item.text}</li>`);
                            });
                            $list.show();
                        } else {
                            $list.hide();
                        }
                    }
                });
            });

            $list.on('click', 'li', function() {
                $input.val($(this).text());
                $input.data('selected-id', $(this).data('id'));
                $list.hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.form-group').length) {
                    $list.hide();
                }
            });
        }

        // Topografi
        setupAutocomplete('#topografi', '#topografi-list', '<?= base_url("icdo-topografi/search") ?>');

        // Morfologi
        setupAutocomplete('#morfologi', '#morfologi-list', '<?= base_url("icdo-morfologi/search") ?>');

    });
</script>