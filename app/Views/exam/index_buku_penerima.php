<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data HPA</h6>
    </div>
    <div class="card-body">
        <h1>Data HPA</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Norm Pasien</th>
                        <th>Kode Hpa</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin Pasien</th>
                        <th>Tanggal Lahir Pasien</th>
                        <th>Alamat Pasien</th>
                        <th>Dokter Pengirim</th>
                        <th>Unit Asal</th>
                        <th>Status Pasien</th>
                        <th>Diagnosa Klinik</th>
                        <th>Tanggal Hasil</th>
                        <th>Status Hpa</th>
                        <th>Hasil Hpa</th>
                        <th class="text-center" style="width: 150px;">Penerima</th>
                        <th>Nama Penerima / Hubungan</th>
                        <th>Tanggal Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hpaData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($hpaData as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['kode_hpa'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['nama_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['jenis_kelamin_pasien'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?= empty($row['tanggal_lahir_pasien']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_lahir_pasien']))); ?>
                                </td>
                                <td><?= esc($row['alamat_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['dokter_pengirim'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['unit_asal'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['status_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['diagnosa_klinik'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                </td>
                                <td><?= esc($row['status_hpa'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['hasil_hpa'] ?? 'Belum Ada Hasil') ?></td>
                                <td class="text-center">
                                    <a href="#"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#penerimaModal"
                                        data-id_hpa="<?= esc($row['id_hpa']) ?>"
                                        data-penerima_hpa="<?= esc($row['penerima_hpa']) ?>">
                                        <i class="fas fa-people-arrows"></i> Penerima
                                    </a>
                                </td>
                                <td><?= esc($row['penerima_hpa'] ?? 'Belum Diterima') ?></td>
                                <td>
                                    <?= empty($row['tanggal_penerima']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['tanggal_penerima']))); ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="14" class="text-center">Tidak ada data yang tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Penerima -->
<div class="modal fade" id="penerimaModal" tabindex="-1" role="dialog" aria-labelledby="penerimaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penerimaModalLabel">Penerima Hasil HPA</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('exam/update_buku_penerima') ?>/<?= esc($row['id_hpa']) ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_hpa" name="id_hpa" value="">
                    <div class="form-group">
                        <label for="penerima_hpa">Nama Penerima / Hubungan dengan Pasien</label>
                        <input type="text" class="form-control" id="penerima_hpa" name="penerima_hpa" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>

<script>
    // Menangani event click pada tombol Penerima untuk memunculkan modal
    $('#penerimaModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var id_hpa = button.data('id_hpa'); // Kode HPA
        var penerima_hpa = button.data('penerima_hpa'); // Nama penerima

        var modal = $(this);
        modal.find('#id_hpa').val(id_hpa); // Isi id_hpa ke input hidden
        modal.find('#penerima_hpa').val(""); // Isi penerima_hpa jika ada
    });
</script>