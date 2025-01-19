<?= $this->include('templates/exam/header_edit_exam'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Makroskopis</h6>
        </div>
        <div class="card-body">
            <h1>Edit Data Makroskopis</h1>
            <a href="<?= base_url('pemotongan/index_pemotongan') ?>" class="btn btn-primary mb-3">Kembali</a>

            <!-- Form -->
            <form id="form-hpa" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa['id_hpa'] ?>">

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

                <!-- Kolom Foto Makroskopis -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Foto Makroskopis</label>
                    <div class="col-sm-6">
                        <img src="<?= base_url('uploads/hpa/makroskopis/' . $hpa['foto_makroskopis_hpa']); ?>" width="200" alt="Foto Makroskopis" class="img-thumbnail" id="fotoMakroskopis" data-toggle="modal" data-target="#fotoModal">
                        <input type="file" name="foto_makroskopis_hpa" id="foto_makroskopis_hpa" class="form-control form-control-user mt-2">
                        <button type="submit" class="btn btn-primary mt-2"
                            formaction="<?= base_url('exam/uploadFotoMakroskopis/' . $hpa['id_hpa']); ?>">
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

                <!-- Kolom Dokter dan Jumlah Slide -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Dokter Makroskopis</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="id_user_dokter_pemotongan" name="id_user_dokter_pemotongan">
                            <option value="" <?= empty($hpa['id_user_dokter_pemotongan']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status_user'] === 'Dokter'): ?>
                                    <option value="<?= $user['id_user'] ?>"
                                        <?= isset($pemotongan['id_user_dokter_pemotongan']) && $user['id_user'] == $pemotongan['id_user_dokter_pemotongan'] ? 'selected' : '' ?>>
                                        <?= $user['nama_user'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <label class="col-sm-2 col-form-label" for="jumlah_slide">Jumlah Slide</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="jumlah_slide" name="jumlah_slide" onchange="handleJumlahSlideChange(this)">
                            <option value="0" <?= ($hpa['jumlah_slide'] == '0') ? 'selected' : '' ?>>0</option>
                            <option value="1" <?= ($hpa['jumlah_slide'] == '1') ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= ($hpa['jumlah_slide'] == '2') ? 'selected' : '' ?>>2</option>
                            <option value="3" <?= ($hpa['jumlah_slide'] == '3') ? 'selected' : '' ?>>3</option>
                            <option value="lainnya" <?= (!in_array($hpa['jumlah_slide'], ['0', '1', '2', '3']) ? 'selected' : '') ?>>Lainnya</option>
                        </select>
                        <input
                            type="text"
                            class="form-control mt-2 <?= (!in_array($hpa['jumlah_slide'], ['0', '1', '2', '3'])) ? '' : 'd-none' ?>"
                            id="jumlah_slide_custom"
                            name="jumlah_slide_custom"
                            placeholder="Masukkan Jumlah Slide Lainnya"
                            value="<?= (!in_array($hpa['jumlah_slide'], ['0', '1', '2', '3'])) ? $hpa['jumlah_slide'] : '' ?>">
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('exam/update/' . $hpa['id_hpa']); ?>">
                            Simpan
                        </button>
                    </div>
                    <div class="col-6 text-center">
                        <!-- Tombol Cetak -->
                        <button type="button" class="btn btn-info btn-user w-100 w-md-auto" onclick="cetakMakroskopis()">
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
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/exam/footer_edit_exam'); ?>

<script>
    function cetakMakroskopis() {
        var printContent = document.getElementById('makroskopis_hpa').value;
        var printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write(`
        <html>
        <head>
            <title>Cetak Hpa</title>
            <style>
                body {
                    font-family: Verdana, Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }
                .header, .footer, .content {
                    padding: 10px;
                }
                .header {
                    border-bottom: 2px solid #000;
                    margin-bottom: 20px;
                }
                h5 {
                    margin: 5px 0;
                }
                .footer {
                    text-align: center;
                    margin-top: 50px;
                    margin-bottom: 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                td {
                    padding: 10px;
                    text-align: left;
                    vertical-align: middle;
                    border: 1px solid black;
                }
                hr {
                    border: 1px solid #000;
                    margin: 20px 0;
                }
                .content {
                    margin-bottom: 0;
                    flex-grow: 1; /* Memastikan content memenuhi ruang */
                }
                .header-table {
                    width: 100%;
                    border: none;
                }
                .header-table td {
                    padding: 5px;
                    vertical-align: top;
                }
                .makroskopis-content-table td,
                .mikroskopis-content-table td {
                    font-size: 12px;
                    padding: 5px;
                }
                .makroskopis-content-table td:first-child,
                .mikroskopis-content-table td:first-child {
                    width: 30%;
                }
                .makroskopis-content-table td:last-child,
                .mikroskopis-content-table td:last-child {
                    width: 70%;
                }
                .makroskopis-content-table td[colspan="2"],
                .mikroskopis-content-table td[colspan="2"] {
                    height: 330px;
                    font-size: 16pt;
                    border: 1px solid black;
                    padding: 10px;
                    text-align: left;
                    vertical-align: top;
                    border: 1px dashed black; /* Garis putus-putus untuk konten */
                }
                /* Styling untuk tabel gambar */
                .gambar-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 30px;
                }
                .gambar-table td {
                    height: 110px;
                    width: 12.5%;
                    border: 1px dashed black; /* Garis putus-putus pada kolom gambar */
                    text-align: center;
                    vertical-align: middle;
                    position: relative; /* Untuk memposisikan nomor romawi di pojok kiri atas */
                }
                .gambar-table th {
                    text-align: center;
                    font-size: 14px;
                    padding: 10px;
                    border: 1px solid black; /* Garis solid pada judul "Gambar" */
                    font-weight: bold;
                }
                .gambar-table td .romawi {
                    position: absolute;
                    top: 5px;
                    left: 5px;
                    font-size: 10pt;
                    font-weight: normal;
                    color: rgba(0, 0, 0, 0.3); /* Warna abu-abu pudar dengan transparansi */
                }
            </style>
        </head>
        <body>
            <div class="header">
                <table class="header-table">
                    <tr>
                        <td rowspan="2" style="width: 20%; font-size: 15px; font-weight: bold; text-align: center;">
                            Kode HPA: <?= esc($hpa['kode_hpa'] ?? '') ?>
                        </td>
                        <td style="width: 20%;">Nama Pasien: <?= esc($hpa['nama_pasien'] ?? '') ?></td>
                        <td style="width: 20%;">Dokter Pengirim: <?= esc($hpa['dokter_pengirim'] ?? '') ?></td>
                        <td style="width: 20%;">Diagnosa Klinik: <?= esc($hpa['diagnosa_klinik'] ?? '') ?></td>
                        <td style="width: 20%;">Tanggal Permintaan: <?= isset($hpa['tanggal_permintaan']) ? date('d-m-Y', strtotime($hpa['tanggal_permintaan'])) : ''; ?></td>
                    </tr>
                    <tr>
                        <td>Norm: <?= esc($hpa['norm_pasien'] ?? '') ?></td>
                        <td>Unit Asal: <?= esc($hpa['unit_asal'] ?? '') ?></td>
                        <td>Lokasi Spesimen: <?= esc($hpa['lokasi_spesimen'] ?? '') ?></td>
                        <td>Tanggal Hasil: <?= isset($hpa['tanggal_hasil']) ? date('d-m-Y', strtotime($hpa['tanggal_hasil'])) : ''; ?></td>
                    </tr>
                </table>
            </div>
            <div class="content">
                <!-- Tabel Makroskopis -->
                <table class="makroskopis-content-table">
                    <tr>
                        <td>Makroskopis</td>
                        <td>Analis PA: <?= esc($data['nama_user_pemotongan'] ?? 'Analis') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">${printContent}</td>
                    </tr>
                </table>

                <!-- Tabel Mikroskopis -->
                <table class="mikroskopis-content-table">
                    <tr>
                        <td>Mikroskopis</td>
                        <td>Dokter PA: <?= $hpa['nama_user_dokter_pemotongan'] ?? 'dokter' ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </div>
            <!-- Tabel Gambar (Footer sebagai Tabel Gambar) -->
            <div class="footer">
                <table class="gambar-table">
                    <!-- Judul Gambar -->
                    <tr>
                        <th colspan="8">Gambar</th>
                    </tr>
                    <!-- Kolom gambar (kosong dengan nomor romawi) -->
                    <tr>
                        <td><span class="romawi">I</span></td>
                        <td><span class="romawi">II</span></td>
                        <td><span class="romawi">III</span></td>
                        <td><span class="romawi">IV</span></td>
                        <td><span class="romawi">V</span></td>
                        <td><span class="romawi">VI</span></td>
                        <td><span class="romawi">VII</span></td>
                        <td><span class="romawi">VIII</span></td>
                    </tr>
                    <tr>
                        <td><span class="romawi">IX</span></td>
                        <td><span class="romawi">X</span></td>
                        <td><span class="romawi">XI</span></td>
                        <td><span class="romawi">XII</span></td>
                        <td><span class="romawi">XIII</span></td>
                        <td><span class="romawi">XIV</span></td>
                        <td><span class="romawi">XV</span></td>
                        <td><span class="romawi">XVI</span></td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
    `);

        printWindow.document.close();
        printWindow.print();
    }
</script>