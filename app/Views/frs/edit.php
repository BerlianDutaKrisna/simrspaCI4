<?= $this->include('templates/frs/header_edit'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit</h6>
        </div>

        <div class="card-body">
            <h1>Edit Data Fine Needle Aspiration Biopsy</h1>
            <a href="<?= base_url('frs/index') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

            <!-- Form -->
            <form id="form-frs" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?>">

                <!-- Kolom Kode FRS dan Diagnosa -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode FRS</label>
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

                <?= $this->include('templates/exam/riwayat'); ?>

                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
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
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
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
                    </div>
                </div>
                <div class="form-group row">
                    <!-- Kolom Kiri -->
                    <div class="col-sm-6">
                        <!-- Kolom Makroskopis -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Makroskopis</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="makroskopis_frs" id="makroskopis_frs"><?= $frs['makroskopis_frs'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Kolom Jumlah Slide dan Tombol Cetak Stiker -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="jumlah_slide">Jumlah Slide</label>
                            <div class="col-sm-2">
                                <input type="number"
                                    class="form-control form-control-sm jumlah-slide-input"
                                    data-id="<?= $frs['id_frs']; ?>"
                                    value="<?= $frs['jumlah_slide']; ?>"
                                    min="0" step="1" style="width:100px;">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-outline-info btn-sm btn-cetak-stiker"
                                    data-id="<?= esc($frs['id_frs']); ?>"
                                    data-kode="<?= esc($frs['kode_frs']); ?>">
                                    <i class="fas fa-print"></i> Cetak Stiker
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

                        <!-- Kolom Hasil FRS dan Jumlah Slide -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hasil FRS</label>
                            <div class="col-sm-10">
                                <textarea class="form-control summernote" name="hasil_frs" id="hasil_frs"><?= $frs['hasil_frs'] ?? '' ?></textarea>
                            </div>
                        </div>
                        <!-- Dokter Pemotong -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Dokter yang puncture</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="id_user_dokter_pemotongan_frs" name="id_user_dokter_pemotongan_frs">
                                    <option value="" <?= empty($frs['id_user_dokter_pemotongan_frs']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
                                    <?php foreach ($users as $user): ?>
                                        <?php if ($user['status_user'] === 'Dokter'): ?>
                                            <option value="<?= $user['id_user'] ?>"
                                                <?= isset($pemotongan_frs['id_user_dokter_pemotongan_frs']) && $user['id_user'] == $pemotongan_frs['id_user_dokter_pemotongan_frs'] ? 'selected' : '' ?>>
                                                <?= $user['nama_user'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hasil keseluruhan:</label>
                        </div>
                        <textarea class="form-control summernote_hasil" name="print_frs" id="print_frs" rows="5">
                            <?= $frs['print_frs'] ?? '' ?>
                        </textarea>
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Dokter yang Membaca</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="id_user_dokter_pembacaan_frs" name="id_user_dokter_pembacaan_frs">
                                    <option value="" <?= empty($frs['id_user_dokter_pembacaan_frs']) ? 'selected' : '' ?>>-- Pilih Dokter --</option>
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
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('frs/update/' . $frs['id_frs']); ?>">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-info btn-user w-100 w-md-auto" onclick="cetakProses()">
                            <i class="fas fa-print"></i> Cetak Proses
                        </button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <button type="button" class="btn btn-primary btn-user w-100 w-md-auto" onclick="cetakPrintfrs()">
                            <i class="fas fa-print"></i> Cetak Hasil
                        </button>
                    </div>
                </div>

                <!-- Tombol Kirim ke SIMRS -->
                <div class="form-group row mt-2">
                    <div class="col-sm-4 text-center">
                        <button type="button"
                            id="btn-kirim-simrs-frs"
                            class="btn btn-warning btn-user w-100"
                            onclick="bukaModalKunjunganFrs()">
                            <i class="fas fa-paper-plane"></i> Kirim ke SIMRS
                        </button>
                    </div>
                </div>

                <!-- Toast Notifikasi SIMRS -->
                <div id="frs-simrs-toast-wrapper" style="position:fixed;top:20px;right:20px;z-index:99999;"></div>
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
<?= $this->include('templates/frs/cetak_proses'); ?>
<?= $this->include('templates/frs/cetak_stiker'); ?>
<?= $this->include('templates/frs/cetak_print'); ?>

<script>
    $(document).ready(function() {
        $(".jumlah-slide-input").on("change", function() {
            let input = $(this);
            let id_frs = input.data("id");
            let jumlah_slide = input.val();

            $.ajax({
                url: "<?= base_url('frs/update_jumlah_slide'); ?>",
                type: "POST",
                data: {
                    id_frs: id_frs,
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

<!-- Modal Pilih Kunjungan Pasien SIMRS (FRS) -->
<div class="modal fade" id="modalKunjunganSimrsFrs" tabindex="-1" role="dialog" aria-labelledby="modalKunjunganFrsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold" id="modalKunjunganFrsLabel">
                    <i class="fas fa-hospital"></i> Pilih Kunjungan Pasien - Kirim ke SIMRS (FRS)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="frs-kunjungan-loading" class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data kunjungan pasien...</p>
                </div>
                <div id="frs-kunjungan-error" class="alert alert-danger d-none"></div>
                <div id="frs-kunjungan-table-wrapper" class="d-none">
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
                            <tbody id="frs-kunjungan-tbody"></tbody>
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

<!-- Script: Kirim ke SIMRS via Modal Kunjungan (FRS) -->
<script>
    var normPasienFrs = '<?= esc($frs["norm_pasien"] ?? "") ?>';
    var idFrs         = '<?= esc($frs["id_frs"] ?? "") ?>';

    function bukaModalKunjunganFrs() {
        var norm = normPasienFrs;
        if (!norm) {
            tampilkanToastFrs('danger', 'Norm pasien tidak ditemukan.');
            return;
        }

        document.getElementById('frs-kunjungan-loading').classList.remove('d-none');
        document.getElementById('frs-kunjungan-error').classList.add('d-none');
        document.getElementById('frs-kunjungan-table-wrapper').classList.add('d-none');
        document.getElementById('frs-kunjungan-tbody').innerHTML = '';

        $('#modalKunjunganSimrsFrs').modal('show');

        fetch('<?= base_url("simrs/modal_search") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ norm_simrs: norm })
        })
        .then(function(res) { return res.json(); })
        .then(function(result) {
            document.getElementById('frs-kunjungan-loading').classList.add('d-none');
            if (result.status !== 'success' || !result.data || result.data.length === 0) {
                var errDiv = document.getElementById('frs-kunjungan-error');
                errDiv.textContent = result.message || 'Tidak ada data kunjungan untuk pasien ini.';
                errDiv.classList.remove('d-none');
                return;
            }
            var tbody = document.getElementById('frs-kunjungan-tbody');
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
                    '<td><button class="btn btn-warning btn-sm" data-idtransaksi="' + String(item.idtransaksi || '').replace(/"/g, '&quot;').replace(/'/g, '&#039;') + '" data-register="' + String(item.register || '').replace(/"/g, '&quot;').replace(/'/g, '&#039;') + '" data-tanggal="' + String(item.tanggal || '').replace(/"/g, '&quot;').replace(/'/g, '&#039;') + '" onclick="pilihKunjunganFrs(this)"><i class="fas fa-check"></i> Pilih</button></td>' +
                    '</tr>';
                tbody.insertAdjacentHTML('beforeend', row);
            });
            document.getElementById('frs-kunjungan-table-wrapper').classList.remove('d-none');
        })
        .catch(function(err) {
            document.getElementById('frs-kunjungan-loading').classList.add('d-none');
            var errDiv = document.getElementById('frs-kunjungan-error');
            errDiv.textContent = 'Gagal menghubungi server: ' + err.message;
            errDiv.classList.remove('d-none');
        });
    }

    function pilihKunjunganFrs(button) {
        $('#modalKunjunganSimrsFrs').modal('hide');

        var selected = button && button.dataset ? button.dataset : {};
        var idtransaksi = selected.idtransaksi || '';
        var tanggal = selected.tanggal || '';
        var register = selected.register || '';

        var form = document.getElementById('form-frs');
        var btn = document.getElementById('btn-kirim-simrs-frs');
        var updateUrl = '<?= base_url("frs/update/" . $frs["id_frs"]) ?>';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan data form...';
        }
        tampilkanToastFrs('info', 'Menyimpan perubahan data form terlebih dahulu...');

        // Sinkronisasi Summernote jika ada editor
        if (window.$ && $.fn && $.fn.summernote) {
            $('.summernote, .summernote_hasil').each(function() {
                if ($(this).data('summernote')) {
                    $(this).val($(this).summernote('code'));
                }
            });
        }

        var formData = new FormData(form);

        // Eksekusi fungsi penyimpanan (seperti tombol submit) terlebih dahulu
        fetch(updateUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function() {
            tampilkanToastFrs('info', 'Data berhasil diperbarui. Mengirim ke SIMRS...');
            kirimKeSimrsFrs(idtransaksi, tanggal, register);
        })
        .catch(function(err) {
            console.warn('Penyimpanan form gagal, lanjut kirim SIMRS:', err);
            kirimKeSimrsFrs(idtransaksi, tanggal, register);
        });
    }


    function kirimKeSimrsFrs(idtransaksi, tanggal, register) {
        var btn = document.getElementById('btn-kirim-simrs-frs');
        var csrfToken = '<?= csrf_token() ?>';
        var csrfHash  = '<?= csrf_hash() ?>';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';
        var formData = new URLSearchParams();
        formData.append('id_frs', idFrs);
        formData.append('idtransaksi', idtransaksi || '');
        formData.append('tanggal', tanggal || '');
        formData.append('register', register || '');
        formData.append(csrfToken, csrfHash);
        fetch('<?= base_url("authorized_frs/kirim_ke_simrs_edit") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData.toString()
        })
        .then(function(r) { return r.json().then(function(d) { return { status: r.status, body: d }; }); })
        .then(function(result) {
            if (result.body.success) {
                tampilkanToastFrs('success', result.body.message || 'Data berhasil dikirim ke SIMRS.');
            } else {
                tampilkanToastFrs('danger', result.body.message || 'Gagal mengirim data ke SIMRS.');
            }
        })
        .catch(function(err) { tampilkanToastFrs('danger', 'Terjadi kesalahan jaringan.'); })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim ke SIMRS';
        });
    }

    function tampilkanToastFrs(type, pesan) {
        var wrapper = document.getElementById('frs-simrs-toast-wrapper');
        var warnaCls = { success:'bg-success', danger:'bg-danger', warning:'bg-warning text-dark', info:'bg-info' };
        var ikon = { success:'fas fa-check-circle', danger:'fas fa-times-circle', warning:'fas fa-exclamation-triangle', info:'fas fa-info-circle' };
        var toast = document.createElement('div');
        toast.style.cssText = 'min-width:280px;margin-bottom:8px;padding:14px 18px;border-radius:8px;color:#fff;font-size:14px;display:flex;align-items:center;gap:10px;box-shadow:0 4px 12px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease';
        toast.className = warnaCls[type] || 'bg-secondary';
        toast.innerHTML = '<i class="' + (ikon[type] || 'fas fa-bell') + '"></i> ' + pesan;
        wrapper.appendChild(toast);
        requestAnimationFrame(function() { requestAnimationFrame(function() { toast.style.opacity = '1'; }); });
        setTimeout(function() { toast.style.opacity = '0'; setTimeout(function() { toast.remove(); }, 350); }, 5000);
    }
</script>