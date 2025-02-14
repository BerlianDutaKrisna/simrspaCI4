<div class="card shadow mb-4">
    <div class="card-header py-4">
        <h6 class="m-0 font-weight-bold text-primary">Jenis Tindakan</h6>
    </div>
    <div class="card-body">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            <!-- Tombol 1: Penerimaan -->
            <div class="col">
                <a href="<?= base_url('penerimaan/index_penerimaan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">1</b> Penerimaan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-clipboard"></i> <!-- Ikon untuk Penerimaan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 2: Pengirisan -->
            <div class="col">
                <a href="<?= base_url('pengirisan/index_pengirisan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">2</b> Pengirisan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-puzzle-piece"></i> <!-- Ikon untuk Pengirisan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 3: Pemotongan -->
            <div class="col">
                <a href="<?= base_url('pemotongan/index_pemotongan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">3</b> Pemotongan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-cut"></i> <!-- Ikon untuk Pemotongan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 4: Pemprosesan -->
            <div class="col">
                <a href="<?= base_url('pemprosesan/index_pemprosesan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">4</b> Pemprosesan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-sync"></i> <!-- Ikon untuk Pemprosesan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 5: Penanaman -->
            <div class="col">
                <a href="<?= base_url('penanaman/index_penanaman') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">5</b> Penanaman</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-download"></i> <!-- Ikon untuk Penanaman -->
                    </span>
                </a>
            </div>

            <!-- Tombol 6: Pemotongan Tipis -->
            <div class="col">
                <a href="<?= base_url('pemotongan_tipis/index_pemotongan_tipis') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">6</b> Pemotongan Tipis</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-grip-horizontal"></i> <!-- Ikon untuk Pemotongan Tipis -->
                    </span>
                </a>
            </div>

            <!-- Tombol 7: Pewarnaan -->
            <div class="col">
                <a href="<?= base_url('pewarnaan/index_pewarnaan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">7</b> Pewarnaan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-fill-drip"></i> <!-- Ikon untuk Pewarnaan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 8: Pembacaan -->
            <div class="col">
                <a href="<?= base_url('pembacaan/index_pembacaan') ?>" class="btn btn-info btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">8</b> Pembacaan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-microscope"></i> <!-- Ikon untuk Pembacaan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 9: Penulisan -->
            <div class="col">
                <a href="<?= base_url('penulisan/index_penulisan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">9</b> Penulisan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-keyboard"></i> <!-- Ikon untuk Penulisan -->
                    </span>
                </a>
            </div>

            <!-- Tombol 10: Pemverifikasi -->
            <div class="col">
                <a href="<?= base_url('pemverifikasi/index_pemverifikasi') ?>" class="btn btn-success btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">10</b> Pemverifikasi</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-check-square"></i>
                    </span>
                </a>
            </div>

            <!-- Tombol 11: Autorized -->
            <div class="col">
                <a href="<?= base_url('autorized/index_autorized') ?>" class="btn btn-info btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">11</b> Authorized</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-vote-yea"></i>
                    </span>
                </a>
            </div>

            <!-- Tombol 11: pencetakan -->
            <div class="col">
                <a href="<?= base_url('pencetakan/index_pencetakan') ?>" class="btn btn-primary btn-icon-split btn-sm d-flex justify-content-between m-2">
                    <span class="text"><b style="color: white">12</b> pencetakan</span>
                    <span class="icon text-white-50">
                        <i class="fas fa-print"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>