<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>
<?= $this->include('templates/notification') ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Users</h6>
    </div>
    <div class="card-body">
        <h1>Data Users</h1>
        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <!-- Tombol Tambah User -->
        <a href="/users/create" class="btn btn-success mb-3">Tambah User</a>
        <!-- Tabel Data Users -->
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama User</th>
                    <th>Status User</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['nama_user']) ?></td>
                            <td><?= esc($user['status_user']) ?></td>
                            <td class="text-center">
                                <!-- Tombol Edit -->
                                <a href="/users/edit/<?= esc($user['id_user']) ?>" 
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <a href="/users/delete/<?= esc($user['id_user']) ?>" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">Tidak ada data pengguna.</td> <!-- Menambahkan colspan 4 untuk mencakup kolom Aksi -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('templates/dashboard/footer_dashboard'); ?>
