<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Users</h6> <!-- Judul halaman -->
    </div>
    <div class="card-body">
        <h1>Data Users</h1> <!-- Judul Data User -->

        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Tombol Tambah User -->
        <a href="<?= base_url('/users/register_users') ?>" class="btn btn-success mb-3">Tambah User</a>

        <!-- Tabel Data Users -->
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama User</th>
                    <th>Status User</th>
                    <th class="text-center" style="width: 150px;">Aksi</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?> <!-- Jika ada data users -->
                    <?php $i = 1; ?>
                    <?php foreach ($users as $user) : ?> <!-- Loop untuk setiap user -->
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['nama_user']) ?></td>
                            <td><?= esc($user['status_user']) ?></td>
                            <td class="text-center">
                                <!-- Tombol Edit -->
                                <a href="<?= base_url('users/edit_user/' . esc($user['id_user'])) ?>" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <!-- Tombol Hapus dengan data-target dan data-id untuk modal -->
                                <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                                    data-id="<?= esc($user['id_user']) ?>" data-nama="<?= esc($user['nama_user']) ?>">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data yang tersedia</td>
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
            <div class="modal-body">Yakin ingin menghapus user ini?</div>
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
    $('#deleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var userId = button.data('id'); // ID pengguna yang dihapus
        var userName = button.data('nama'); // Nama pengguna yang dihapus

        // Mengubah teks modal untuk menampilkan nama pengguna
        var modal = $(this);
        modal.find('.modal-body').text('Anda yakin ingin menghapus user ' + userName + '?');

        // Mengubah href link untuk penghapusan
        modal.find('#confirmDelete').attr('href', '<?= base_url("users/delete/") ?>' + userId);
    });
</script>