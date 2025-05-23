<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pemotongan Tipis</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pemotongan Tipis Histopatologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pemotongan_tipis_hpa/proses_pemotongan_tipis'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Pemotongan Tipis</th>
                            <th>Diagnosa Klinik</th>
                            <th>Analis</th>
                            <th>Mulai Pemotongan Tipis</th>
                            <th>Selesai Pemotongan Tipis</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pemotonganTipisDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pemotonganTipisDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pemotongan_tipis_hpa']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu_hpa']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pemotongan_tipis_hpa' => $row['status_pemotongan_tipis_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pemotongan_tipis_hpa']; ?></td>

                                    <td><?= $row['diagnosa_klinik']; ?></td>
                                    <td><?= $row['nama_user_pemotongan_tipis_hpa']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pemotongan_tipis_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pemotongan_tipis_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pemotongan_tipis_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pemotongan_tipis_hpa']))); ?>
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk pemotongan tipis</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>