<!-- Topbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow mb-4">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Brand Logo and Text -->
    <div class="navbar-brand d-flex align-items-center">
        <i class="fas fa-binoculars" style="font-size: 2rem; color: #FF5733;"></i>
        <span class="ml-2 text-danger font-weight-bold" style="font-size: 1.5rem;">Traker Histopatologi</span>
    </div>

    <!-- Navbar Toggle for Mobile (Collapsing menu) -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Topbar Navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">

            <!-- Display Nama User Always (Desktop and Mobile) -->
            <li class="nav-item d-flex align-items-center">
                <!-- Nama User -->
                <span class="mr-2 text-gray-600 small font-weight-bold"><?= esc($nama_user); ?></span>
            </li>

            <!-- User Information Dropdown -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- Gambar Profil -->
                    <img class="img-profile rounded-circle" src="<?= base_url('img/analis.jpg') ?>" alt="User Profile Picture" style="width: 35px; height: 35px;">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                    </a>
                    <a class="dropdown-item" href="<?= base_url('/users') ?>">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= base_url('/logout') ?>" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Keluar
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
