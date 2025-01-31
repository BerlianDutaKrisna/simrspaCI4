<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pasien</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pasien</h1>

        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Tombol Tambah Pasien -->
        <a href="<?= base_url('patient/register_patient') ?>" class="btn btn-success mb-3">Tambah Pasien</a>

        <!-- Tabel Data Pasien -->
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Norm Pasien</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Status Pasien</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($patients as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= esc($row['norm_pasien']) ?></td>
                                <td><?= esc($row['nama_pasien']) ?></td>
                                <td><?= esc($row['jenis_kelamin_pasien']) ?></td>
                                <td>
                                    <?php
                                    if (empty($row['tanggal_lahir_pasien'])) {
                                        echo 'Belum diisi';
                                    } else {
                                        echo esc(date('d-m-Y', strtotime($row['tanggal_lahir_pasien'])));
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (empty($row['alamat_pasien'])) {
                                        echo 'Belum diisi';
                                    } else {
                                        echo esc($row['alamat_pasien']);
                                    }
                                    ?>
                                </td>
                                <td><?= esc($row['status_pasien']) ?></td>
                                <td class="text-center">
                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('patient/edit_patient/' . esc($row['id_pasien'])) ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                                        data-id_pasien="<?= esc($row['id_pasien']) ?>"
                                        data-nama_pasien="<?= esc($row['nama_pasien']) ?>">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Pesan jika data pasien kosong -->
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data yang tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Modal untuk konfirmasi penghapusan pasien -->
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
                <div class="modal-body">
                    Yakin ingin menghapus pasien ini?
                </div>
                <div class="modal-footer">
                    <!-- Tombol untuk membatalkan -->
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <!-- Tombol konfirmasi penghapusan -->
                    <a id="confirmDelete" class="btn btn-danger" href="#">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('templates/notifikasi') ?>
    <?= $this->include('templates/dashboard/footer_dashboard') ?>

    <!-- JavaScript untuk menangani pengisian modal -->
    <script>
        // Menangani event saat modal tampil
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var id_pasien = button.data('id_pasien'); // Ambil data-id_pasien dari atribut
            var nama_pasien = button.data('nama_pasien'); // Ambil data-nama_pasien dari atribut

            var modal = $(this); // Referensi ke modal
            // Set teks modal untuk menampilkan nama pasien
            modal.find('.modal-body').text('Anda yakin ingin menghapus pasien ' + nama_pasien + '?');

            // Atur href tombol konfirmasi dengan ID pasien
            modal.find('#confirmDelete').attr('href', '<?= base_url("patient/delete/") ?>' + id_pasien);
        });
    </script>