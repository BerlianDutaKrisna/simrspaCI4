<?= $this->include('templates/frs/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Penulisan</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Penulisan Fine Needle Aspiration Biopsy</h1>
            <a href="<?= base_url('penulisan_frs/index') ?>" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form id="form-frs" method="POST">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?>">
                <input type="hidden" name="kode_frs" value="<?= $frs['kode_frs'] ?>">
                <input type="hidden" name="id_pembacaan_frs" value="<?= $pembacaan['id_pembacaan_frs'] ?>">
                <input type="hidden" name="id_penulisan_frs" value="<?= $penulisan['id_penulisan_frs'] ?>">
                <input type="hidden" name="page_source" value="edit_penulisan">

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
                        <p>&nbsp;<?= $frs['nama_pasien'] ?? '' ?></p>
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
                        <p>&nbsp;<?= $frs['norm_pasien'] ?? '' ?></p>
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

                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Makroskopis</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="makroskopis_frs" id="makroskopis_frs">
                                <?= $frs['makroskopis_frs'] ?? '' ?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Mikroskopis</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="mikroskopis_frs" id="mikroskopis_frs">
                                <?= $frs['mikroskopis_frs'] ?? '' ?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Hasil frs</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="hasil_frs" id="hasil_frs">
                                <?= $frs['hasil_frs'] ?? '' ?>
                            </textarea>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label">Tampilan:</label>
                        </div>
                        <textarea class="form-control summernote_print" name="print_frs" id="print_frs" rows="5">
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
                                            <b><?= $frs['lokasi_spesimen'] ?? '' ?><br></b>
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
                                        <font size="5" face="verdana"><b><?= $frs['diagnosa_klinik'] ?? '' ?><br></b></font>
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
                                <font size="5" face="verdana"><b>LAPORAN PEMERIKSAAN:<br></b></font>
                            <div>
                                <font size="5" face="verdana"><b> MAKROSKOPIK :</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><?= $frs['makroskopis_frs'] ?? '' ?></font>
                            </div>
                            <br>
                            <div>
                                <font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><?= $frs['mikroskopis_frs'] ?? '' ?></font>
                            </div>
                            <br>
                            <div>
                                <font size="5" face="verdana"><b>KESIMPULAN :</b> <?= $frs['lokasi_spesimen'] ?? '' ?>, <?= $frs['tindakan_spesimen'] ?? '' ?>:</b></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><b><?= strtoupper($frs['hasil_frs'] ?? '') ?></b></font>
                            </div>
                            <br>
                        </textarea>
                    </div>
                </div>

                <!-- Kolom Hasil frs dan Dokter -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-4">

                    </div>

                    <label class="col-sm-2 col-form-label">Dokter yang membaca</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="id_user_dokter_pembacaan_frs" name="id_user_dokter_pembacaan_frs">
                            <option value="" <?= empty($frs['id_user_dokter_pembacaan_frs']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status_user'] === 'Dokter'): ?>
                                    <option value="<?= $user['id_user'] ?>"
                                        <?= isset($pembacaan['id_user_dokter_pembacaan_frs']) && $user['id_user'] == $pembacaan['id_user_dokter_pembacaan_frs'] ? 'selected' : '' ?>>
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
                            formaction="<?= base_url('frs/update/' . $frs['id_frs']); ?>">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/frs/footer_edit'); ?>