<div class="row">
    <a href="<?= base_url('penulisan_hpa/index') ?>" class="btn btn-danger btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPenulisanhpa'] ?? ""); ?></b> HPA</span>
        <span class="icon text-white-50">
            <i class="fas fa-drumstick-bite"></i>
        </span>
    </a>
    <a href="<?= base_url('penulisan_frs/index') ?>" class="btn btn-primary btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPenulisanfrs'] ?? ""); ?></b> FNAB</span>
        <span class="icon text-white-50">
            <i class="fas fa-syringe"></i>
        </span>
    </a>
    <a href="<?= base_url('penulisan_srs/index') ?>" class="btn btn-success btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPenulisansrs'] ?? ""); ?></b> SRS</span>
        <span class="icon text-white-50">
            <i class="fas fa-prescription-bottle"></i>
        </span>
    </a>
    <a href="<?= base_url('penulisan_ihc/index') ?>" class="btn btn-warning btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPenulisanihc'] ?? ""); ?></b> IHC</span>
        <span class="icon text-white-50">
            <i class="fas fa-vials"></i>
        </span>
    </a>
</div>