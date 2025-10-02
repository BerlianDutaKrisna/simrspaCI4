<div class="card shadow mb-4">
    <div class="card-header py-4">
        <h6 class="m-0 font-weight-bold text-primary">Jenis Tindakan</h6>
    </div>
    <div class="card-body">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            <!-- Tombol 1: Penerimaan -->
            <div class="col">
                <a href="<?= base_url('penerimaan_hpa/index') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">
                            <?php
                            $contsTotalPenerimaan =
                                ($counts['countPenerimaanhpa'] ?? 0) +
                                ($counts['countPenerimaanfrs'] ?? 0) +
                                ($counts['countPenerimaansrs'] ?? 0) +
                                ($counts['countPenerimaanihc'] ?? 0);
                            ?>
                            <?= esc($contsTotalPenerimaan ?? 0) ?>
                        </b> Penerimaan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-clipboard"></i>
                    </span>
                </a>
            </div>

            <!-- Tombol 2: Pemotongan -->
            <div class="col">
                <a href="<?= base_url('pemotongan_hpa/index') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white"><?= esc($counts['countPemotonganhpa'] ?? 0); ?></b> Pemotongan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-cut"></i> <!-- Ikon untuk Pemotongan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 3: Pemprosesan -->
            <div class="col">
                <a href="<?= base_url('pemprosesan_hpa/index') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white"><?= esc($counts['countPemprosesanhpa'] ?? 0); ?></b> Pemprosesan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-sync"></i> <!-- Ikon untuk Pemprosesan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 4: Penanaman -->
            <div class="col">
                <a href="<?= base_url('penanaman_hpa/index') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white"><?= esc($counts['countPenanamanhpa'] ?? 0); ?></b> Penanaman</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-download"></i> <!-- Ikon untuk Penanaman -->
                    </span>
                </a>
            </div>

            <!-- Tombol 5: Pemotongan Tipis -->
            <div class="col">
                <a href="<?= base_url('pemotongan_tipis_hpa/index') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white"><?= esc($counts['countPemotonganTipishpa'] ?? 0); ?></b> Pemotongan Tipis</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-grip-horizontal"></i> <!-- Ikon untuk Pemotongan Tipis -->
                    </span>
                </a>
            </div>

            <!-- Tombol 6: Pewarnaan -->
            <div class="col">
                <a href="<?= base_url('pewarnaan_hpa/index') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">
                            <?php
                            $contsTotalPewarnaan =
                                ($counts['countPewarnaanhpa'] ?? 0) +
                                ($counts['countPewarnaanfrs'] ?? 0) +
                                ($counts['countPewarnaansrs'] ?? 0) +
                                ($counts['countPewarnaanihc'] ?? 0);
                            ?>
                            <?= esc($contsTotalPewarnaan ?? 0) ?>
                        </b> Pewarnaan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-fill-drip"></i> <!-- Ikon untuk Pewarnaan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 7: Pembacaan -->
            <div class="col">
                <a href="<?= base_url('pembacaan_hpa/index') ?>" class="btn btn-info btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">
                            <?php
                            $contsTotalPembacaan =
                                ($counts['countPembacaanhpa'] ?? 0) +
                                ($counts['countPembacaanfrs'] ?? 0) +
                                ($counts['countPembacaansrs'] ?? 0) +
                                ($counts['countPembacaanihc'] ?? 0);
                            ?>
                            <?= esc($contsTotalPembacaan ?? 0) ?>
                        </b> Screening / Pembacaan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-microscope"></i> <!-- Ikon untuk Pembacaan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 8: Penulisan -->
            <div class="col">
                <a href="<?= base_url('penulisan_hpa/index') ?>" class="btn btn-secondary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">
                            <?php
                            $contsTotalPenulisan =
                                ($counts['countPenulisanhpa'] ?? 0) +
                                ($counts['countPenulisanfrs'] ?? 0) +
                                ($counts['countPenulisansrs'] ?? 0) +
                                ($counts['countPenulisanihc'] ?? 0);
                            ?>
                            <?= esc($contsTotalPenulisan ?? 0) ?>
                        </b> Penulisan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-keyboard"></i> <!-- Ikon untuk Penulisan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 9: Pemverifikasi -->
            <div class="col">
                <a href="<?= base_url('pemverifikasi_hpa/index') ?>" class="btn btn-success btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white"><?php
                                                                $contsTotalPemverifikasi =
                                                                    ($counts['countPemverifikasihpa'] ?? 0) +
                                                                    ($counts['countPemverifikasifrs'] ?? 0) +
                                                                    ($counts['countPemverifikasisrs'] ?? 0) +
                                                                    ($counts['countPemverifikasiihc'] ?? 0);
                                                                ?>
                            <?= esc($contsTotalPemverifikasi ?? 0) ?></b> Pemverifikasi</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-check-square"></i>
                    </span>
                </a>
            </div>

            <!-- Tombol 10: Autorized -->
            <div class="col">
                <a href="<?= base_url('authorized_hpa/index') ?>" class="btn btn-info btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white"><?php
                                                                $contsTotalAuthorised =
                                                                    ($counts['countAuthorizedhpa'] ?? 0) +
                                                                    ($counts['countAuthorizedfrs'] ?? 0) +
                                                                    ($counts['countAuthorizedsrs'] ?? 0) +
                                                                    ($counts['countAuthorizedihc'] ?? 0);
                                                                ?>
                            <?= esc($contsTotalAuthorised ?? 0) ?></b> Authorized</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-vote-yea"></i>
                    </span>
                </a>
            </div>

            <!-- Tombol 11: pencetakan -->
            <div class="col">
                <a href="<?= base_url('pencetakan_hpa/index') ?>" class="btn btn-secondary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">
                            <?php
                            $contsTotalPencetakan =
                                ($counts['countPencetakanhpa'] ?? 0) +
                                ($counts['countPencetakanfrs'] ?? 0) +
                                ($counts['countPencetakansrs'] ?? 0) +
                                ($counts['countPencetakanihc'] ?? 0);
                            ?>
                            <?= esc($contsTotalPencetakan ?? 0) ?>
                        </b> Pencetakan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-print"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>