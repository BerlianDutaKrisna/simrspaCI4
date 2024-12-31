<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Traker of Histologi Process</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div> -->
    <!-- Judul Halaman -->
    <h1 class="h3 mb-2 text-gray-800">Jumlah sampel yang belum selesai</h1>
    <!-- Deskripsi Halaman -->
    <p class="mb-3 text-gray-500">A goal is a dream with a deadline - Napoleon Hill</p>

    <!-- Content Row -->
    <div class="row">
        <!-- Histopatologi RESUME -->
<div class="col-xl-3 col-md-6 mb-4">
<a href="<?= base_url('exam/index_buku_penerima') ?>" class="stretched-link" style="text-decoration: none;">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-s font-weight-bold text-danger text-uppercase mb-1">
                            HISTOPATOLOGI ( HPA )
                        </div>
                        <!-- Menampilkan jumlah sampel Histopatologi -->
                        <div class="h2 mb-0 font-weight-bold text-gray-800"><?= esc($countHpa); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-drumstick-bite fa-2x text-gray-500"></i> <!-- Ikon Histopatologi -->
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>


        <!-- SITOLOGI RESUME -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-s font-weight-bold text-success text-uppercase mb-1">
                                SITOLOGI ( SRS )
                            </div>
                            <!-- Menampilkan jumlah sampel Sitologi -->
                            <div class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-prescription-bottle fa-2x text-gray-500"></i> <!-- Ikon Sitologi -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FINE NEEDLE ASPIRATION BIOPSY RESUME -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-s font-weight-bold text-info text-uppercase mb-1">
                                Fine Needle Aspiration Biopsy ( FNAB )
                            </div>
                            <!-- Menampilkan jumlah sampel FNAB -->
                            <div class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-syringe fa-2x text-gray-500"></i> <!-- Ikon FNAB -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IMUNOHISTOKIMIA RESUME -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-s font-weight-bold text-warning text-uppercase mb-1">
                                Imunohistokimia
                            </div>
                            <!-- Menampilkan jumlah sampel Imunohistokimia -->
                            <div class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-vials fa-2x text-gray-500"></i> <!-- Ikon Imunohistokimia -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
