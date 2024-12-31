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
                    <th>Status_pasien</th>
                    <th>Diagnosa Klinik</th>
                    <th>Tanggal hasil</th>
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
                                <!-- Tombol Hapus dengan data-target dan data-id untuk modal -->
                                <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#deleteModal"
                                    data-id="<?= esc($row['id_hpa']) ?>" data-kode="<?= esc($row['kode_hpa']) ?>">
                                    Penerima
                                </a>
                            </td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8">Tidak ada data HPA.</td>  <!-- Menambahkan colspan 8 untuk mencakup kolom Aksi -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hapus Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ingin menghapus?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Yakin ingin menghapus data HPA ini?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <!-- Link untuk konfirmasi penghapusan -->
                <a id="confirmDelete" class="btn btn-danger" href="#">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>

<!-- JavaScript untuk menangani pengisian modal -->
<script>
    // Menangani event click pada tombol hapus
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var hpaId = button.data('id'); // ID HPA yang dihapus
        var kodeHpa = button.data('kode'); // Kode HPA yang dihapus

        // Mengubah teks modal untuk menampilkan kode HPA
        var modal = $(this);
        modal.find('.modal-body').text('Anda yakin ingin menghapus data HPA ' + kodeHpa + '?');
        
        // Mengubah href link untuk penghapusan
        modal.find('#confirmDelete').attr('href', '<?= base_url("hpa/delete/") ?>' + hpaId);
    });
</script>

