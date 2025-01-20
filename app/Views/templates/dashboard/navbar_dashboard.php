<!-- Topbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 p-3">
    <!-- Brand Logo and Text -->
    <a href="<?= base_url('/dashboard') ?>" class="navbar-brand d-flex align-items-center">
        <i class="fas fa-binoculars" style="font-size: 2rem; color: #FF5733;"></i>
        <span class="ml-2 text-danger font-weight-bold" style="font-size: 1.5rem;">Traker Histopatologi</span>
    </a>

    <!-- Navbar Toggle for Mobile -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Topbar Navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto d-flex align-items-center">
            <!-- Display Nama User Always (Desktop and Mobile) -->
            <li class="nav-item d-flex align-items-center mr-3">
                <span class="mr-2 text-gray-600 small font-weight-bold"><?= esc($nama_user); ?></span>
            </li>
            <!-- User Information Dropdown -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="img-profile rounded-circle" src="<?= base_url('img/analis.jpg') ?>" alt="User Profile Picture" style="width: 35px; height: 35px;">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow-sm animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-600"></i> Profile
                    </a>
                    <!-- Dropdown untuk Settings -->
                    <a class="dropdown-item dropdown-toggle" href="#" id="settingsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-600"></i> Settings
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm animated--grow-in" aria-labelledby="settingsDropdown">
                        <a class="dropdown-item" href="<?= base_url('users/index_users') ?>">
                            <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-600"></i> User Settings
                        </a>
                        <a class="dropdown-item" href="<?= base_url('patient/index_patient') ?>">
                            <i class="fas fa-hospital-user fa-sm fa-fw mr-2 text-gray-600"></i> Patient Settings
                        </a>
                        <a class="dropdown-item" href="<?= base_url('exam/index_exam') ?>">
                            <i class="fas fa-vial fa-sm fa-fw mr-2 text-gray-600"></i> Hpa Settings
                        </a>
                    </div>
                    <div class="dropdown-divider"></div> <!-- Pembatas antara menu -->
                    <!-- Link untuk logout -->
                    <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-600"></i> Keluar
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
