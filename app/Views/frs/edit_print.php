<?= $this->include('templates/hpa/header_cetak'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Print</h6>
        </div>
        <div class="card-body">
            <h1 class="h3 mb-4">Form Print Fine Needle Aspiration Biopsy</h1>

            <!-- Form -->
            <form id="form-frs" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_frs" value="<?= $frs['id_frs'] ?>">
                <input type="hidden" name="id_pemverifikasi_frs" value="<?= $frs['id_pemverifikasi_frs'] ?? '' ?>">
                <input type="hidden" name="id_authorized_frs" value="<?= $frs['id_authorized_frs'] ?? '' ?>">
                <input type="hidden" name="id_pencetakan_frs" value="<?= $frs['id_pencetakan_frs'] ?? '' ?>">
                <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?? '' ?>">

                <!-- Tombol Kembali -->
                <div class="mb-3">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Kembali
                    </a>
                </div>

                <!-- Riwayat SIMRS -->
                <button class="btn btn-info mb-3" type="button" data-toggle="collapse" data-target="#riwayatSimrs" aria-expanded="false" aria-controls="riwayatSimrs">
                    <i class="fas fa-book-medical"></i> Riwayat Pemeriksaan SIMRS
                </button>

                <div class="collapse show" id="riwayatSimrs">
                    <div class="card card-body">
                        <?php if (!empty($riwayat_api)) : ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                            <th>Dokter</th>
                                            <th>Diagnosa Klinik</th>
                                            <th>Pemeriksaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($riwayat_api as $i => $row) : ?>
                                            <tr>
                                                <td><?= $i + 1 ?></td>
                                                <td><?= (!empty($row['tanggal']) && strtotime($row['tanggal'])) ? date('d-m-Y', strtotime($row['tanggal'])) : '-' ?></td>
                                                <td><?= esc($row['noregister'] ?? '-') ?></td>
                                                <td><?= esc($row['dokterpa'] ?? '-') ?></td>
                                                <td><?= esc($row['diagnosaklinik'] ?? '-') ?></td>
                                                <td><?= esc($row['pemeriksaan'] ?? '-') ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        onclick="tampilkanModal(`<?= nl2br(esc($row['hasil'] ?? 'Tidak ada hasil', 'js')) ?>`)">
                                                        Lihat Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">Tidak ada data riwayat pemeriksaan tersedia.</p>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Riwayat Lokal -->
                <button class="btn btn-outline-info mb-3" type="button" data-toggle="collapse" data-target="#riwayatLokal" aria-expanded="false" aria-controls="riwayatLokal">
                    <i class="fas fa-book-medical"></i> Riwayat Pemeriksaan Lokal
                </button>

                <div class="collapse" id="riwayatLokal">
                    <div class="row">
                        <!-- Riwayat Pemeriksaan Histopatologi (HPA) -->
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <label class="col-form-label d-block mb-2">Riwayat Pemeriksaan <b>Histopatologi</b></label>
                                    <?php if (!empty($riwayat_hpa)) : ?>
                                        <?php foreach ($riwayat_hpa as $row) : ?>
                                            <div class="border p-2 mb-2 rounded bg-light">
                                                <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                                <strong>Kode HPA:</strong> <?= esc($row['kode_hpa'] ?? '-') ?><br>
                                                <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                                <strong>Hasil HPA:</strong> <?= esc(strip_tags($row['hasil_hpa'])) ?? '-' ?><br>
                                                <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                                <button type="button" class="btn btn-info btn-sm mt-2 w-100"
                                                    onclick="tampilkanModal('<?= nl2br(esc($row['print_hpa'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-muted">Tidak ada riwayat pemeriksaan HPA.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Pemeriksaan FRS -->
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <label class="col-form-label d-block mb-2">Riwayat Pemeriksaan <b>Fine Needle Aspiration Biopsy</b></label>
                                    <?php if (!empty($riwayat_frs)) : ?>
                                        <?php foreach ($riwayat_frs as $row) : ?>
                                            <div class="border p-2 mb-2 rounded bg-light">
                                                <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                                <strong>Kode FRS:</strong> <?= esc($row['kode_frs'] ?? '-') ?><br>
                                                <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                                <strong>Hasil FRS:</strong> <?= esc(strip_tags($row['hasil_frs'])) ?? '-' ?><br>
                                                <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                                <button type="button" class="btn btn-info btn-sm mt-2 w-100"
                                                    onclick="tampilkanModal('<?= nl2br(esc($row['print_frs'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-muted">Tidak ada riwayat pemeriksaan FRS.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Pemeriksaan SRS -->
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <label class="col-form-label d-block mb-2">Riwayat Pemeriksaan <b>Sitologi</b></label>
                                    <?php if (!empty($riwayat_srs)) : ?>
                                        <?php foreach ($riwayat_srs as $row) : ?>
                                            <div class="border p-2 mb-2 rounded bg-light">
                                                <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                                <strong>Kode SRS:</strong> <?= esc($row['kode_srs'] ?? '-') ?><br>
                                                <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                                <strong>Hasil SRS:</strong> <?= esc(strip_tags($row['hasil_srs'])) ?? '-' ?><br>
                                                <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                                <button type="button" class="btn btn-info btn-sm mt-2 w-100"
                                                    onclick="tampilkanModal('<?= nl2br(esc($row['print_srs'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-muted">Tidak ada riwayat pemeriksaan SRS.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Pemeriksaan IHC -->
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <label class="col-form-label d-block mb-2">Riwayat Pemeriksaan <b>Imunohistokimia</b></label>
                                    <?php if (!empty($riwayat_ihc)) : ?>
                                        <?php foreach ($riwayat_ihc as $row) : ?>
                                            <div class="border p-2 mb-2 rounded bg-light">
                                                <strong>Tanggal Permintaan:</strong> <?= isset($row['tanggal_permintaan']) ? date('d-m-Y', strtotime($row['tanggal_permintaan'])) : '-' ?><br>
                                                <strong>Kode IHC:</strong> <?= esc($row['kode_ihc'] ?? '-') ?><br>
                                                <strong>Lokasi Spesimen:</strong> <?= esc($row['lokasi_spesimen'] ?? '-') ?><br>
                                                <strong>Hasil IHC:</strong> <?= esc(strip_tags($row['hasil_ihc'])) ?? '-' ?><br>
                                                <strong>Dokter Pembaca:</strong> <?= esc($row['dokter_nama'] ?? 'Belum Dibaca') ?><br>
                                                <button type="button" class="btn btn-info btn-sm mt-2 w-100"
                                                    onclick="tampilkanModal('<?= nl2br(esc($row['print_ihc'] ?? 'Tidak ada hasil', 'js')) ?>')">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-muted">Tidak ada riwayat pemeriksaan IHC.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom print -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control summernote_print" name="print_frs" id="print_frs" rows="5">
                            <font size="5" face="verdana"><?= $frs['print_frs'] ?? '' ?></font>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100"
                            formaction="<?= base_url('frs/update_print/' . $frs['id_frs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pemverifikasi_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-check-square"></i> Verifikasi
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-info btn-user w-100"
                            formaction="<?= base_url('frs/update_print/' . $frs['id_frs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_authorized_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-vote-yea"></i> Authorized
                        </button>
                    </div>

                    <div class="col-sm-4 text-center">
                        <button type="submit"
                            class="btn btn-success btn-user w-100 mb-3"
                            formaction="<?= base_url('frs/update_print/' . $frs['id_frs']); ?>"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button"
                            class="btn btn-primary btn-user w-100 w-md-auto"
                            onclick="cetakPrintfrs()"
                            <?= (($_GET['redirect'] ?? '') === 'index_pencetakan_frs') ? '' : 'disabled' ?>>
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Riweyat -->
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
<?= $this->include('templates/frs/footer_cetak'); ?>
<?= $this->include('templates/frs/cetak_print'); ?>