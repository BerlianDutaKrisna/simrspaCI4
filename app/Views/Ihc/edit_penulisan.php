<?= $this->include('templates/exam/header_edit_exam'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Penulisan</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Penulisan</h1>
            <a href="<?= base_url('penulisan_ihc/index') ?>" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form id="form-ihc" method="POST">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_ihc" value="<?= $ihc['id_ihc'] ?>">
                <input type="hidden" name="kode_ihc" value="<?= $ihc['kode_ihc'] ?>">
                <input type="hidden" name="id_pembacaan_ihc" value="<?= $pembacaan['id_pembacaan_ihc'] ?>">
                <input type="hidden" name="id_penulisan_ihc" value="<?= $penulisan['id_penulisan_ihc'] ?>">
                <input type="hidden" name="page_source" value="edit_penulisan">

                <!-- Kolom Kode ihc dan Diagnosa -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode ihc</label>
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
                        <p>&nbsp;<?= $ihc['nama_pasien'] ?? '' ?></p>
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
                        <p>&nbsp;<?= $ihc['norm_pasien'] ?? '' ?></p>
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

                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Makroskopis</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="makroskopis_ihc" id="makroskopis_ihc">
                            <div>
                                <font size="5" face="verdana">Dilakukan potong ulang blok parafin <?= $ihc['kode_block_ihc'] ?? '' ?> dan pengecatan imunohistokimia dengan antibodi ER, PR, Her2 Neu, serta Ki-67.</font>
                            </div>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Mikroskopis</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="mikroskopis_ihc" id="mikroskopis_ihc">
                            <div>
                                <font size="5" face="verdana">ER: </font>
                            </div>
                            <div>
                                <font size="5" face="verdana">PR: </font>
                            </div>
                            <div>
                                <font size="5" face="verdana">Her2 Neu: </font>
                            </div>
                            <div>
                                <font size="5" face="verdana">Ki-67: </font>
                            </div>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Hasil ihc</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="hasil_ihc" id="hasil_ihc">
                            <div>
                                <font size="5" face="verdana"><b>ER: </b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><b>PR: </b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><b>Her2 Neu: </b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><b>Ki-67: </b></font>
                            </div>
                            </textarea>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Tampilan:</label>
                        </div>
                        <textarea class="form-control summernote_print" name="print_ihc" id="print_ihc" rows="5">
                            <table width="800pt" height="80">
                            <tbody>
                                <tr>
                                    <td style="border: none;" width="200pt">
                                        <font size="5" face="verdana"><b>LOKASI</b></font>
                                    </td>
                                    <td style="border: none;" width="10pt">
                                        <font size="5" face="verdana"><b>:</b><br></font>
                                    </td>
                                    <td style="border: none;" width="590pt">
                                        <font size="5" face="verdana">
                                            <b><?= $ihc['lokasi_spesimen'] ?? '' ?><br></b>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;" width="200pt">
                                        <font size="5" face="verdana"><b>DIAGNOSA KLINIK</b></font>
                                    </td>
                                    <td style="border: none;" width="10pt">
                                        <font size="5" face="verdana"><b>:</b><br></font>
                                    </td>
                                    <td style="border: none;" width="590pt">
                                        <font size="5" face="verdana"><b><?= $ihc['diagnosa_klinik'] ?? '' ?><br></b></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;" width="200pt">
                                        <font size="5" face="verdana"><b>ICD</b></font>
                                    </td>
                                    <td style="border: none;" width="10pt">
                                        <font size="5" face="verdana"><b>:</b></font>
                                    </td>
                                    <td style="border: none;" width="590pt">
                                        <font size="5" face="verdana"><br></font>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                            <font size="5" face="verdana"><b>LAPORAN PEMERIKSAAN:</b></font>
                            <div>
                                <font size="5" face="verdana"><b>MAKROSKOPIK :</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><?= $ihc['makroskopis_ihc'] ?? '' ?></font>
                            </div>
                            <br>
                            <div>
                                <font size="5" face="verdana"><b>MIKROSKOPIK :</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><?= $ihc['mikroskopis_ihc'] ?? '' ?></font>
                            </div>
                            <br>
                            <div>
                                <font size="5" face="verdana"><b>KESIMPULAN :</b> Blok Parafin No. <?= $ihc['kode_block_ihc'] ?? '' ?>, <?= $ihc['tindakan_spesimen'] ?? '' ?>:</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><b><?= strtoupper($ihc['hasil_ihc'] ?? '') ?></b></font>
                            </div>
                            <br>
                        </textarea>
                    </div>
                </div>
                <!-- Kolom Hasil ihc dan Dokter -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-4">
                    </div>
                    <label class="col-sm-2 col-form-label">Dokter yang membaca</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="id_user_dokter_pembacaan_ihc" name="id_user_dokter_pembacaan_ihc">
                            <option value="" <?= empty($ihc['id_user_dokter_pembacaan_ihc']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status_user'] === 'Dokter'): ?>
                                    <option value="<?= $user['id_user'] ?>"
                                        <?= isset($pembacaan['id_user_dokter_pembacaan_ihc']) && $user['id_user'] == $pembacaan['id_user_dokter_pembacaan_ihc'] ? 'selected' : '' ?>>
                                        <?= $user['nama_user'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12 text-center">
                        <!-- Tombol Simpan -->
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('ihc/update/' . $ihc['id_ihc']); ?>">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/exam/footer_edit_exam'); ?>