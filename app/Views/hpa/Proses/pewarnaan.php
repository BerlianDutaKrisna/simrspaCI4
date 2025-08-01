<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pewarnaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pewarnaan Histopatologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pewarnaan_hpa/proses_pewarnaan'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="align-middle text-center">
                                <div class="custom-control custom-checkbox d-inline-block">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll">Aksi Check Semua</label>
                                </div>
                            </th>
                            <script>
                                document.getElementById('checkAll').addEventListener('change', function() {
                                    let checkboxes = document.querySelectorAll('.checkbox-item');
                                    checkboxes.forEach(cb => {
                                        cb.checked = this.checked;

                                        // Paksa trigger event 'change' agar logika lain ikut jalan (misal toggleButtons)
                                        cb.dispatchEvent(new Event('change'));

                                        // Opsional: akses data-status jika dibutuhkan langsung
                                        if (this.checked && cb.dataset.status) {
                                            let statusObj = JSON.parse(cb.dataset.status);
                                            console.log(statusObj); // Untuk debugging
                                            // Kamu bisa push ke array atau proses data di sini
                                        }
                                    });
                                });
                            </script>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>jumlah slide</th>
                            <th>Aksi Cetak Stiker</th>
                            <th>Status Pewarnaan</th>
                            <th>Analis</th>
                            <th>Mulai Pewarnaan</th>
                            <th>Selesai Pewarnaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pewarnaanDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pewarnaanDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pewarnaan_hpa']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu_hpa']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pewarnaan_hpa' => $row['status_pewarnaan_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['jumlah_slide']; ?></td>
                                    <?php
                                    $slide = $row['jumlah_slide'];
                                    $jumlahSlide = is_numeric($slide) ? (int)$slide : 1;
                                    ?>
                                    <td>
                                        <button class="btn btn-outline-info btn-sm btn-cetak-stiker"
                                            data-kode="<?= esc($row['kode_hpa']); ?>"
                                            data-slide="<?= $jumlahSlide; ?>">
                                            <i class="fas fa-print"></i> Cetak Stiker
                                        </button>
                                    </td>
                                    <td><?= $row['status_pewarnaan_hpa']; ?></td>
                                    <td><?= $row['nama_user_pewarnaan_hpa']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pewarnaan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pewarnaan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pewarnaan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pewarnaan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $tanggal_hasil = $row['tanggal_hasil'] ?? "";
                                        if ($tanggal_hasil === "") {
                                            echo '';
                                        } else {
                                            $tanggal = new DateTime($tanggal_hasil);
                                            $formatter = new IntlDateFormatter(
                                                'id_ID',
                                                IntlDateFormatter::FULL,
                                                IntlDateFormatter::NONE,
                                                'Asia/Jakarta',
                                                IntlDateFormatter::GREGORIAN,
                                                'EEEE, dd-MM-yyyy'
                                            );
                                            echo esc($formatter->format($tanggal));
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Belum ada sampel untuk pewarnaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?= $this->include('templates/hpa/cetak_stiker'); ?>
            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>