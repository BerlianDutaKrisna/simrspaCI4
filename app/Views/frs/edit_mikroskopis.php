<?= $this->include('templates/frs/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Mikroskopis</h6>
        </div>
        <div class="card-body">
            <h1>Edit Data Mikroskopis Fine Needle Aspiration Biopsy</h1>
            <a href="<?= base_url('pembacaan_frs/index') ?>" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form id="form-frs" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?>">
                <input type="hidden" name="id_pembacaan_frs" value="<?= $pembacaan_frs['id_pembacaan_frs'] ?>">
                <input type="hidden" name="page_source" value="edit_mikroskopis">

                <!-- Kolom Kode frs dan Diagnosa -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode frs</label>
                    <div class="col-sm-4">
                        <input type="text" name="kode_frs" value="<?= $frs['kode_frs'] ?? '' ?>" class="form-control">
                    </div>

                    <label class="col-sm-2 col-form-label">Diagnosa</label>
                    <div class="col-sm-4">
                        <input type="text" name="diagnosa_klinik" value="<?= $frs['diagnosa_klinik'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Nama Pasien dan Dokter Pengirim -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $frs['nama_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                    <div class="col-sm-4">
                        <input type="text" name="dokter_pengirim" value="<?= $frs['dokter_pengirim'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Norm Pasien dan Unit Asal -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Norm Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $frs['norm_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Unit Asal</label>
                    <div class="col-sm-4">
                        <input type="text" name="unit_asal" value="<?= $frs['unit_asal'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Tindakan Spesimen dan Tanggal Permintaan -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tindakan Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="tindakan_spesimen" value="<?= $frs['tindakan_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Permintaan</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_permintaan" value="<?= $frs['tanggal_permintaan'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Lokasi Spesimen dan Tanggal Hasil -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Lokasi Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="lokasi_spesimen" value="<?= $frs['lokasi_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Hasil</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_hasil" value="<?= $frs['tanggal_hasil'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Foto Makroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Foto Makroskopis</label>
                    <div class="col-sm-6">
                        <img src="<?= $frs['foto_makroskopis_frs'] !== null
                                        ? base_url('uploads/frs/makroskopis/' . $frs['foto_makroskopis_frs'])
                                        : base_url('img/no_photo.jpg') ?>"
                            width="200"
                            alt="Foto Makroskopis"
                            class="img-thumbnail"
                            id="fotoMakroskopis"
                            data-toggle="modal"
                            data-target="#fotoModal">
                        <input type="file" name="foto_makroskopis_frs" id="foto_makroskopis_frs" class="form-control form-control-user mt-2">
                        <button type="submit" class="btn btn-primary mt-2"
                            formaction="<?= base_url('frs/uploadFotoMakroskopis/' . $frs['id_frs']); ?>">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </button>
                    </div>
                </div>

                <!-- Kolom Makroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Makroskopis</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" name="makroskopis_frs" id="makroskopis_frs"><?= $frs['makroskopis_frs'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Kolom Foto Mikroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Foto Mikroskopis</label>
                    <div class="col-sm-6">
                        <img src="<?= $frs['foto_mikroskopis_frs'] !== null
                                        ? base_url('uploads/frs/mikroskopis/' . $frs['foto_mikroskopis_frs'])
                                        : base_url('img/no_photo.jpg') ?>"
                            width="200"
                            alt="Foto Mikroskopis"
                            class="img-thumbnail"
                            id="fotoMikroskopis"
                            data-toggle="modal"
                            data-target="#fotoModal">
                        <input type="file" name="foto_mikroskopis_frs" id="foto_mikroskopis_frs" class="form-control form-control-user mt-2">
                        <button type="submit" class="btn btn-primary mt-2"
                            formaction="<?= base_url('frs/uploadFotoMikroskopis/' . $frs['id_frs']); ?>">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </button>
                    </div>
                </div>

                <!-- Kolom Mikroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Mikroskopis</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote" name="mikroskopis_frs" id="mikroskopis_frs"><?= $frs['mikroskopis_frs'] ?? '' ?></textarea>
                    </div>
                </div>

                <!-- Kolom Hasil frs dan Dokter -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Hasil frs</label>
                    <div class="col-sm-4">
                        <textarea class="form-control summernote" name="hasil_frs" id="hasil_frs">
                            <?= $frs['hasil_frs'] ?? '' ?>
                        </textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="jumlah_slide">Dokter yang membaca</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="id_user_dokter_pembacaan_frs" name="id_user_dokter_pembacaan_frs">
                            <option value="" <?= empty($pembacaan_frs['id_user_dokter_pembacaan_frs']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status_user'] === 'Dokter'): ?>
                                    <option value="<?= $user['id_user'] ?>"
                                        <?= isset($pembacaan_frs['id_user_dokter_pembacaan_frs']) && $user['id_user'] == $pembacaan_frs['id_user_dokter_pembacaan_frs'] ? 'selected' : '' ?>>
                                        <?= $user['nama_user'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-sm-12 text-center mb-3">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('frs/update/' . $frs['id_frs']); ?>">
                            Simpan
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
                <img src="<?= base_url('uploads/frs/makroskopis/' . $frs['foto_makroskopis_frs']); ?>" class="img-fluid" alt="Foto Makroskopis" id="fotoZoom">
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
                <img src="<?= base_url('uploads/frs/mikroskopis/' . $frs['foto_mikroskopis_frs']); ?>" class="img-fluid" alt="Foto Mikroskopis" id="fotoZoom">
            </div>
        </div>
    </div>
</div>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>