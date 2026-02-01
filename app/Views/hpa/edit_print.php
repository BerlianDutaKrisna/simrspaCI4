<?= $this->include('templates/hpa/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Print</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print Histopatologi</h1>

            <!-- Form -->
            <form id="form-hpa" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_hpa" value="<?= $hpa['id_hpa'] ?>">
                <input type="hidden" name="id_pemverifikasi_hpa" value="<?= $hpa['id_pemverifikasi_hpa'] ?? '' ?>">
                <input type="hidden" name="id_authorized_hpa" value="<?= $hpa['id_authorized_hpa'] ?? '' ?>">
                <input type="hidden" name="id_pencetakan_hpa" value="<?= $hpa['id_pencetakan_hpa'] ?? '' ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- data yang dikirim untuk SIMRS -->
                <input type="hidden" name="idtransaksi" value="<?= isset($hpa['id_transaksi']) ? (int) $hpa['id_transaksi'] : '' ?>">
                <input type="hidden" name="tanggal" value="<?= !empty($hpa['tanggal_transaksi']) ? esc($hpa['tanggal_transaksi']) : '' ?>">
                <input type="hidden" name="register" value="<?= isset($hpa['no_register']) ? esc($hpa['no_register']) : '' ?>">
                <input type="hidden" name="pemeriksaan" value="<?= isset($hpa['tindakan_spesimen']) ? esc($hpa['tindakan_spesimen']) : '' ?>">
                <input type="hidden" name="idpasien" value="<?= isset($hpa['id_pasien']) ? (int) $hpa['id_pasien'] : '' ?>">
                <input type="hidden" name="norm" value="<?= isset($hpa['norm_pasien']) ? esc($hpa['norm_pasien']) : '' ?>">
                <input type="hidden" name="nama" value="<?= isset($hpa['nama_pasien']) ? esc($hpa['nama_pasien']) : '' ?>">
                <!-- kolom noregister sesuai DB -->
                <input type="hidden" name="noregister" value="<?= isset($hpa['kode_hpa']) ? esc($hpa['kode_hpa']) : '' ?>">
                <!-- datetime fields -->
                <input type="hidden" name="datang" value="<?= isset($hpa['mulai_penerimaan_hpa']) ? esc($hpa['mulai_penerimaan_hpa']) : '' ?>">
                <input type="hidden" name="periksa" value="<?= isset($hpa['mulai_penerimaan_hpa']) ? esc($hpa['mulai_penerimaan_hpa']) : '' ?>">
                <input type="hidden" name="selesai" value="<?= isset($hpa['selesai_penulisan_hpa']) ? esc($hpa['selesai_penulisan_hpa']) : '' ?>">
                <!-- dokter PA text -->
                <input type="hidden" name="dokterpa" value="<?= isset($pembacaan_hpa['dokter_nama']) ? esc($pembacaan_hpa['dokter_nama']) : '' ?>">
                <!-- status lokasi text -->
                <input type="hidden" name="statuslokasi" value="<?= isset($hpa['lokasi_spesimen']) ? esc($hpa['lokasi_spesimen']) : '' ?>">
                <!-- diagnosa & mutu -->
                <input type="hidden" name="diagnosaklinik" value="<?= isset($hpa['diagnosa_klinik']) ? esc($hpa['diagnosa_klinik']) : '' ?>">
                <input type="hidden" name="diagnosapatologi" value="<?= isset($hpa['hasil_hpa']) ? esc($hpa['hasil_hpa']) : '' ?>">
                <input type="hidden" name="mutusediaan" value="<?= isset($hpa['total_nilai_mutu_hpa']) ? esc($hpa['total_nilai_mutu_hpa']) : '' ?>">

                <input type="hidden" name="status" value="Belum Terkirim">

                <!-- Tombol Kembali -->
                <div class="mb-3">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Kembali
                    </a>
                </div>

                <?= $this->include('templates/exam/riwayat'); ?>

                <!-- Kolom print -->
                <div class="form-group row mt-4">
                    <div class="col-12">
                        <textarea class="form-control summernote_print" name="print_hpa" id="print_hpa" rows="6">
                    <font size="5" face="verdana"><?= $hpa['print_hpa'] ?? '' ?></font>
                </textarea>
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

                <!-- Tombol aksi -->
                <div class="row text-center g-3">
                    <div class="col-12 col-md-4 mb-3">
                        <button type="submit" class="btn btn-success w-100"
                            formaction="<?= base_url('hpa/update_print/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <button type="submit" class="btn btn-info w-100"
                            formaction="<?= base_url('hpa/update_print/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_authorized_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <button type="submit" class="btn btn-success w-100 mb-2"
                            formaction="<?= base_url('hpa/update_print/' . $hpa['id_hpa']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-primary w-100"
                            onclick="cetakPrintHpa()"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_hpa') ? '' : 'disabled' ?>>
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/hpa/footer_cetak'); ?>
<?= $this->include('templates/hpa/cetak_print'); ?>

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