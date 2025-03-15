<div class="row">
    <a href="<?= base_url('pemverifikasi_hpa/index') ?>" class="btn btn-danger btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPemverifikasihpa'] ?? ""); ?></b> HPA</span>
        <span class="icon text-white-50">
            <i class="fas fa-drumstick-bite"></i>
        </span>
    </a>
    <a href="<?= base_url('pemverifikasi_frs/index') ?>" class="btn btn-primary btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPemverifikasifrs'] ?? ""); ?></b> FNAB</span>
        <span class="icon text-white-50">
            <i class="fas fa-syringe"></i>
        </span>
    </a>
    <a href="<?= base_url('pemverifikasi_srs/index') ?>" class="btn btn-success btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPemverifikasisrs'] ?? ""); ?></b> SRS</span>
        <span class="icon text-white-50">
            <i class="fas fa-prescription-bottle"></i>
        </span>
    </a>
    <a href="<?= base_url('pemverifikasi_ihc/index') ?>" class="btn btn-warning btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPemverifikasiihc'] ?? ""); ?></b> IHC</span>
        <span class="icon text-white-50">
            <i class="fas fa-vials"></i>
        </span>
    </a>
</div>