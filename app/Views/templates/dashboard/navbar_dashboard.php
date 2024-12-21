<!-- Topbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow mb-4">

    <!-- Sidebar Toggle (Topbar) -->
    <!-- Tombol untuk toggle sidebar pada layar kecil (mobile) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i> <!-- Icon menu untuk sidebar -->
    </button>

    <!-- Brand Logo and Text -->
    <!-- Menampilkan logo dan teks brand di sebelah kiri navbar -->
    <div class="navbar-brand d-flex align-items-center">
        <i class="fas fa-binoculars" style="font-size: 2rem; color: #FF5733;"></i> <!-- Ikon brand -->
        <span class="ml-2 text-danger font-weight-bold" style="font-size: 1.5rem;">Traker Histopatologi</span> <!-- Teks brand -->
    </div>

    <!-- Navbar Toggle for Mobile (Collapsing menu) -->
    <!-- Tombol untuk toggle menu di tampilan mobile -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span> <!-- Ikon toggler untuk mobile -->
    </button>

    <!-- Topbar Navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">

            <!-- Display Nama User Always (Desktop and Mobile) -->
            <!-- Menampilkan nama user secara selalu di sebelah kanan navbar -->
            <li class="nav-item d-flex align-items-center">
                <!-- Nama User -->
                <span class="mr-2 text-gray-600 small font-weight-bold"><?= esc($nama_user); ?></span> <!-- Nama user -->
            </li>

            <!-- User Information Dropdown -->
            <!-- Dropdown menu untuk user information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- Gambar Profil User -->
                    <img class="img-profile rounded-circle" src="<?= base_url('img/analis.jpg') ?>" alt="User Profile Picture" style="width: 35px; height: 35px;"> <!-- Gambar profil -->
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <!-- Link ke Profile -->
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile <!-- Ikon Profile -->
                    </a>

                    <!-- Dropdown untuk Settings -->
                    <!-- Menu untuk pengaturan user dan pasien -->
                    <a class="dropdown-item dropdown-toggle" href="#" id="settingsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings <!-- Ikon pengaturan -->
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="settingsDropdown">
                        <!-- Menu untuk pengaturan user -->
                        <a class="dropdown-item" href="<?= base_url('users/index_users') ?>">
                            <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i> User Settings <!-- Ikon pengaturan user -->
                        </a>
                        <!-- Menu untuk pengaturan pasien -->
                        <a class="dropdown-item" href="<?= base_url('patient/index_patient') ?>">
                            <i class="fas fa-hospital-user fa-sm fa-fw mr-2 text-gray-400"></i> Patient Settings <!-- Ikon pengaturan pasien -->
                        </a>
                    </div>
                    
                    <!-- Menu untuk Activity Log -->
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Activity Log <!-- Ikon Activity Log -->
                    </a>

                    <div class="dropdown-divider"></div> <!-- Pembatas antara menu -->
                    
                    <!-- Link untuk logout -->
                    <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Keluar <!-- Ikon logout -->
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ingin Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Yakin pekerjaan anda sudah selesai?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Keluar</a>
                </div>
            </div>
        </div>
    </div>
