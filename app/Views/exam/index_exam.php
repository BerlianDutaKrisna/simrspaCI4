<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data HPA</h6> <!-- Judul halaman -->
    </div>
    <div class="card-body">
        <h1>Data HPA</h1> <!-- Judul Data HPA -->

        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Tabel Data HPA -->
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
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
                    <th class="text-center" style="width: 150px;">Penerima</th> <!-- Kolom Aksi -->
                    <th>Nama Penerima / Hubungan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hpaData)) : ?>  <!-- Jika ada data HPA -->
                    <?php foreach ($hpaData as $row) : ?> <!-- Loop untuk setiap data HPA -->
                        <tr>
                            <td><?= esc($row['norm_pasien']) ?></td>
                            <td><?= esc($row['kode_hpa']) ?></td>
                            <td><?= esc($row['nama_pasien']) ?></td>
                            <td><?= esc($row['jenis_kelamin_pasien']) ?></td>
                            <td><?= esc($row['tanggal_lahir_pasien']) ?></td>
                            <td><?= esc($row['alamat_pasien']) ?></td>
                            <td><?= esc($row['dokter_pengirim']) ?></td>
                            <td><?= esc($row['unit_asal']) ?></td>
                            <td><?= esc($row['status_pasien']) ?></td>
                            <td><?= esc($row['diagnosa_klinik']) ?></td>
                            <td><?= esc($row['tanggal_hasil']) ?></td>
                            <td><?= esc($row['status_hpa']) ?></td>
                            <td class="text-center">
                                <!-- Tombol Penerima dengan data-id untuk modal -->
                                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#penerimaModal"
                                    data-id_hpa="<?= esc($row['id_hpa']) ?>"
                                    data-penerima_hpa="<?= esc($row['penerima_hpa']) ?>">
                                    <i class="fas fa-people-arrows"></i> Penerima
                                </a>
                            </td>
                            <td><?= esc($row['penerima_hpa']) ?></td>
                        </tr>
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

<!-- Modal Penerima-->
<div class="modal fade" id="penerimaModal" tabindex="-1" role="dialog" aria-labelledby="penerimaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penerimaModalLabel">Penerima Hasil Hpa</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('exam/penerima') ?>" method="POST">
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

<!-- JavaScript untuk menangani pengisian modal -->
<script>
    // Menangani event click pada tombol Penerima
    $('#penerimaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var id_hpa = button.data('id_hpa'); // ID HPA
        var penerima_hpa = button.data('penerima_hpa'); // Nama Penerima (Hubungan Penerima)

        // Mengosongkan input sebelum modal muncul
        var modal = $(this);
        modal.find('#id_hpa').val(id_hpa);  // Menetapkan ID HPA
        modal.find('#penerima_hpa').val(''); // Mengosongkan input penerima_hpa
    });
</script>
