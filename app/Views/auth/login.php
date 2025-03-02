<?= $this->include('templates/auth/header_auth'); ?>

<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image">
        <!-- Gambar atau konten lain di sini -->
    </div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
            </div>
            <div class="text-center">
                <p class="text-gray-900 mb-4">Sistem Informasi Laboratorium Patologi Anatomi (SIM LAB PA)</p>
            </div>
            <form action="<?= base_url('auth/login'); ?>" method="POST" class="user">
                <?= csrf_field(); ?> <!-- CSRF token untuk mencegah serangan CSRF -->

                <!-- Input untuk Username -->
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control form-control-user" placeholder="Masukkan Username" autocomplete="off" value="<?= old('username'); ?>">
                </div>

                <!-- Input untuk Password -->
                <div class="form-group position-relative">
                    <input type="password" name="password" id="password" class="form-control form-control-user" placeholder="Masukkan Password" autocomplete="off">
                    <button type="button" id="togglePassword" class="btn btn-link position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 18px;">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Script untuk toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        // Toggle antara 'text' dan 'password'
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
</script>
<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/auth/footer_auth'); ?>