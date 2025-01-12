<?= $this->include('templates/cetak/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Penulisan</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Penulisan</h1>
            <a href="javascript:history.back()" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form action="<?= base_url('exam/update/' . $hpa[0]['id_hpa']) ?>" method="POST">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa[0]['id_hpa'] ?>">
                <input type="hidden" name="kode_hpa" value="<?= $hpa[0]['kode_hpa'] ?>">
                <!-- Kolom Kode HPA dan Diagnosa -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode HPA</label>
                    <div class="col-sm-4">
                        <input type="text" name="kode_hpa" value="<?= $hpa[0]['kode_hpa'] ?? '' ?>" class="form-control">
                    </div>

                    <label class="col-sm-2 col-form-label">Diagnosa</label>
                    <div class="col-sm-4">
                        <input type="text" name="diagnosa_klinik" value="<?= $hpa[0]['diagnosa_klinik'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Nama Pasien dan Dokter Pengirim -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $hpa[0]['nama_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Dokter Pengirim</label>
                    <div class="col-sm-4">
                        <input type="text" name="dokter_pengirim" value="<?= $hpa[0]['dokter_pengirim'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Norm Pasien dan Unit Asal -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Norm Pasien</label>
                    <div class="col-sm-4">
                        <p>&nbsp<?= $hpa[0]['norm_pasien'] ?? '' ?></p>
                    </div>

                    <label class="col-sm-2 col-form-label">Unit Asal</label>
                    <div class="col-sm-4">
                        <input type="text" name="unit_asal" value="<?= $hpa[0]['unit_asal'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Tindakan Spesimen dan Tanggal Permintaan -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tindakan Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="tindakan_spesimen" value="<?= $hpa[0]['tindakan_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Permintaan</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_permintaan" value="<?= $hpa[0]['tanggal_permintaan'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom Lokasi Spesimen dan Tanggal Hasil -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Lokasi Spesimen</label>
                    <div class="col-sm-4">
                        <input type="text" name="lokasi_spesimen" value="<?= $hpa[0]['lokasi_spesimen'] ?? '' ?>" class="form-control form-control-user">
                    </div>

                    <label class="col-sm-2 col-form-label">Tanggal Hasil</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_hasil" value="<?= $hpa[0]['tanggal_hasil'] ?? '' ?>" class="form-control form-control-user">
                    </div>
                </div>

                <!-- Kolom print -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="print_hpa">Penulisan</label>
                    <div class="col-sm-10">
                        <textarea class="form-control summernote_hpa" name="print_hpa" id="print_hpa" rows="5">
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
                                            <font size="5" face="verdana"><b><?= $hpa[0]['lokasi_spesimen'] ?? '' ?><br></b></font>
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
                                            <font size="5" face="verdana"><b><?= $hpa[0]['diagnosa_klinik'] ?? '' ?><br></b></font>
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
                            <p><b style="font-family: verdana; font-size: x-large;">MAKROSKOPIK :</b></p>
                            <font size="5" face="verdana"><?= $hpa[0]['makroskopis_hpa'] ?? '' ?></font>
                            <div>
                                <font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font>
                            </div>
                            <div>
                                <font size="5" face="verdana"><?= $hpa[0]['mikroskopis_hpa'] ?? '' ?></font>
                            </div>
                            <br>
                            <b>KESIMPULAN :</b> <?= $hpa[0]['lokasi_spesimen'] ?? '' ?>, <?= $hpa[0]['tindakan_spesimen'] ?? '' ?>:
                            <br><font size="5" face="verdana"><b><?= $hpa[0]['hasil_hpa'] ?? '' ?></b></font>
                        </textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12 text-center">
                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-success btn-user w-100">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/cetak/footer_cetak'); ?>