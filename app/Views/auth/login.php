<?= $this->include('templates/auth/header_auth'); ?>

<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
            </div>

            <div class="text-center">
                <p class="text-gray-900 mb-4">Sistem Informasi Laboratorium Patologi Anatomi (SIM LAB PA)</p>
            </div>

            <!-- Form login -->
            <form action="<?= base_url('auth/login'); ?>" method="POST" class="user">
                <?= csrf_field(); ?> <!-- CSRF token untuk mencegah serangan CSRF -->

                <!-- Input untuk Username -->
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control form-control-user" placeholder="Masukkan Username" autocomplete="off" value="<?= old('username'); ?>" required>
                    <?php if (isset($validation) && $validation->getError('username')): ?>
                        <div class="text-danger"><?= $validation->getError('username'); ?></div> <!-- Menampilkan error username -->
                    <?php endif; ?>
                </div>

                <!-- Input untuk Password -->
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control form-control-user" placeholder="Masukkan Password" autocomplete="off" required>
                    <?php if (isset($validation) && $validation->getError('password')): ?>
                        <div class="text-danger"><?= $validation->getError('password'); ?></div> <!-- Menampilkan error password -->
                    <?php endif; ?>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                </button>
            </form>

            <div class="text-center mt-3">
                <a class="small" href="<?= base_url('register'); ?>">Belum punya akun? Daftar sekarang!</a> <!-- Link ke halaman registrasi -->
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Menampilkan Pesan Error atau Success menggunakan SB Admin 2 -->
<?php if (session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Pesan Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    // Menunggu hingga halaman selesai dimuat
    window.onload = function () {
        // Menampilkan modal jika ada pesan flash
        <?php if (session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
            $('#messageModal').modal('show');
        <?php endif; ?>
    };
</script>

<?= $this->include('templates/auth/footer_auth'); ?>
