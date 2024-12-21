<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit User</h6> <!-- Judul halaman -->
    </div>
    <div class="card-body">
        <h1>Edit Data User</h1> <!-- Judul Halaman Edit -->

        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/users/index_users') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Menampilkan pesan error atau sukses -->
        <?php if (session()->has('message')): ?>
            <div class="alert alert-<?= isset(session('message')['error']) ? 'danger' : 'success' ?>" role="alert">
                <?= session('message')['error'] ?? session('message')['success'] ?>
            </div>
        <?php endif; ?>

        <!-- Form Edit User -->
        <form action="<?= base_url('/users/update/' . $user['id_user']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Field Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                    id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                <div class="invalid-feedback"><?= session('errors.username') ?></div>
            </div>

            <!-- Field Nama User -->
            <div class="form-group">
                <label for="nama_user">Nama User</label>
                <input type="text" class="form-control <?= session('errors.nama_user') ? 'is-invalid' : '' ?>" 
                    id="nama_user" name="nama_user" value="<?= old('nama_user', $user['nama_user']) ?>" required>
                <div class="invalid-feedback"><?= session('errors.nama_user') ?></div>
            </div>

            <!-- Field Status User -->
            <div class="form-group">
                <label for="status_user">Status User</label>
                <select class="form-control <?= session('errors.status_user') ? 'is-invalid' : '' ?>" 
                    id="status_user" name="status_user" required>
                    <option value="Admin" <?= old('status_user', $user['status_user']) == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Analis" <?= old('status_user', $user['status_user']) == 'Analis' ? 'selected' : '' ?>>Analis</option>
                    <option value="Dokter" <?= old('status_user', $user['status_user']) == 'Dokter' ? 'selected' : '' ?>>Dokter</option>
                    <option value="Belum Dipilih" <?= old('status_user', $user['status_user']) == 'Belum Dipilih' ? 'selected' : '' ?>>Belum Dipilih</option>
                </select>
                <div class="invalid-feedback"><?= session('errors.status_user') ?></div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>
