<div class="row">
    <a href="<?= base_url('pewarnaan_hpa/index') ?>" class="btn btn-danger btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPewarnaanhpa'] ?? ""); ?></b> HPA</span>
        <span class="icon text-white-50">
            <i class="fas fa-drumstick-bite"></i>
        </span>
    </a>
    <a href="<?= base_url('pewarnaan_frs/index') ?>" class="btn btn-primary btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPewarnaanfrs'] ?? ""); ?></b> FNAB</span>
        <span class="icon text-white-50">
            <i class="fas fa-syringe"></i>
        </span>
    </a>
    <a href="<?= base_url('pewarnaan_srs/index') ?>" class="btn btn-success btn-icon-split m-3">
        <span class="text"><b style="color: white"><?= esc($counts['countPewarnaansrs'] ?? ""); ?></b> SRS</span>
        <span class="icon text-white-50">
            <i class="fas fa-prescription-bottle"></i>
        </span>
    </a>
</div>