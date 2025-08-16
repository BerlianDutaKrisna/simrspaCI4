<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <!-- Histopatologi RESUME -->
        <div class="col mb-4">
            <a href="<?= base_url('hpa/index_buku_penerima') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-danger text-uppercase mb-1">
                                    HISTOPATOLOGI (HPA)
                                </div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= esc($counts['countProseshpa'] ?? 0); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-drumstick-bite fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- FINE NEEDLE ASPIRATION BIOPSY RESUME -->
        <div class="col mb-4">
            <a href="<?= base_url('frs/index_buku_penerima') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-info text-uppercase mb-1">
                                    Fine Needle Aspiration Biopsy (FNAB)
                                </div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= esc($counts['countProsesfrs'] ?? 0); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-syringe fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- SITOLOGI RESUME -->
        <div class="col mb-4">
            <a href="<?= base_url('srs/index_buku_penerima') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-success text-uppercase mb-1">
                                    SITOLOGI (SRS)
                                </div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= esc($counts['countProsessrs'] ?? 0); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-prescription-bottle fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- IMUNOHISTOKIMIA RESUME -->
        <div class="col mb-4">
            <a href="<?= base_url('ihc/index_buku_penerima') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-warning text-uppercase mb-1">
                                    Imunohistokimia (IHC)
                                </div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= esc($counts['countProsesihc'] ?? 0); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-vials fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- PEMERIKSAAN LAIN RESUME -->
        <div class="col mb-4">
            <a href="<?= base_url('pemeriksaan_lain/index_buku_penerima') ?>" class="stretched-link" style="text-decoration: none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-primary text-uppercase mb-1">
                                    PEMERIKSAAN LAIN
                                </div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= esc($counts['countProsespemeriksaan_lain'] ?? 0); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dna fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>