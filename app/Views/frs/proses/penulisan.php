<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penulisan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penulisan Fine Needle Aspiration Biopsy</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/proses/button_penulisan'); ?>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('penulisan_frs/proses_penulisan'); ?>" method="POST">
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
                            <th>Detail</th>
                            <th>Kode FRS</th>
                            <th>Nama Pasien</th>
                            <th>Status Penulisan</th>
                            <th>Admin</th>
                            <th>Mulai Penulisan</th>
                            <th>Selesai Penulisan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penulisanDatafrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penulisanDatafrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_penulisan_frs']; ?>:<?= $row['id_frs']; ?>:<?= $row['id_mutu_frs']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penulisan_frs' => $row['status_penulisan_frs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <?php if (in_array($row['status_penulisan_frs'], ["Proses Penulisan", "Belum Penulisan"])): ?>
                                        <td>
                                            <a href="<?= esc(base_url('frs/edit_penulisan/' . esc($row['id_frs']) . '?redirect=index_penulisan_frs')) ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php elseif ($row['status_penulisan_frs'] === "Selesai Penulisan"): ?>
                                        <td>
                                            <a href="<?= esc(base_url('frs/edit_penulisan/' . esc($row['id_frs']) . '?redirect=index_penulisan_frs')) ?>"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $row['kode_frs']; ?></td>
                                    <td><b><?= esc($row['nama_pasien']); ?></b> (<?= esc($row['norm_pasien']); ?>)</td>
                                    <td><?= $row['status_penulisan_frs']; ?></td>
                                    <td><?= $row['nama_user_penulisan_frs']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_penulisan_frs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_penulisan_frs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penulisan_frs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_penulisan_frs']))); ?>
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk penulisan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>