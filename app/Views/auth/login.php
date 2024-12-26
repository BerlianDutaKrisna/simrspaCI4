<?= $this->include('templates/auth/header_auth'); ?>

<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image">

    </div>
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
                </div>
                <!-- Input untuk Password -->
                <div class="form-group position-relative">
                    <input type="password" name="password" id="password" class="form-control form-control-user" placeholder="Masukkan Password" autocomplete="off" value="<?= old('password'); ?>" required>
                    <button type="button" id="togglePassword" class="btn btn-link position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #000;">
                        <i class="fas fa-star-of-life"></i>
                    </button>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                </button>
            </form>
        </div>
        <div class="text-center mt-3">
            <a class="small" href="<?= base_url('users/register_users'); ?>" style="color: white; text-decoration: none;">Belum punya akun? Daftar sekarang!</a>
        </div>
    </div>
</div>
<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/auth/footer_auth'); ?>

<script>
    // Script untuk menampilkan/mengubah visibility password
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>