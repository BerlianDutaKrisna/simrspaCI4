<?= $this->include('templates/auth/header_auth'); ?>

<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-register-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
            </div>
            <form action="<?= base_url('/users/insert') ?>" method="POST" enctype="multipart/form-data" class="user">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <input type="text" name="nama_user" id="nama_user" class="form-control form-control-user" placeholder="Masukan Nama Lengkap" value="<?= old('nama_user'); ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control form-control-user" placeholder="Masukan Username" value="<?= old('username'); ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control form-control-user" placeholder="Password" value="<?= old('password'); ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password2" class="form-control form-control-user" placeholder="Ulangi Password" value="<?= old('password2'); ?>" required>
                </div>
                <div class="form-group">
                    <select name="status_user" class="form-control" required>
                        <option value="Belum Dipilih" disabled selected>Pilih Status User</option>
                        <option value="Admin" <?= old('status_user') == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Dokter" <?= old('status_user') == 'Dokter' ? 'selected' : ''; ?>>Dokter</option>
                        <option value="Analis" <?= old('status_user') == 'Analis' ? 'selected' : ''; ?>>Analis</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Daftar Akun
                </button>
            </form>
            <div class="text-center mt-3">
                <a class="small" href="<?= base_url('/'); ?>">Sudah Punya Akun? Login Sekarang!</a>
            </div>
        </div>
    </div>
</div>
<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/auth/footer_auth'); ?>
