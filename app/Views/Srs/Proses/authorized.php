<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Authorized</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Authorized Sitologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/proses/button_authorized'); ?>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('authorized_srs/proses_authorized'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Detail</th>
                            <th>Kode SRS</th>
                            <th>Nama Pasien</th>
                            <th>Status Authorized</th>
                            <th>Dokter</th>
                            <th>Mulai Authorized</th>
                            <th>Selesai Authorized</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($authorizedDatasrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($authorizedDatasrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_authorized_srs']; ?>:<?= $row['id_srs']; ?>:<?= $row['id_mutu_srs']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_authorized_srs' => $row['status_authorized_srs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <?php if (in_array($row['status_authorized_srs'], ["Proses Authorized"])): ?>
                                        <td>
                                            <a href="<?= base_url('srs/edit_print/' .
                                                            esc($row['id_srs']) .
                                                            '?redirect=index_authorized_srs') ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-eye"></i> Cek Verifikasi
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_authorized_srs'], ["Selesai Authorized"])): ?>
                                        <td>
                                            <a href="<?= base_url('srs/edit_print/' .
                                                            esc($row['id_srs']) .
                                                            '?redirect=index_authorized_srs') ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i> Cek Verifikasi
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><?= $row['kode_srs']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_authorized_srs']; ?></td>
                                    <td><?= $row['nama_user_dokter_pembacaan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_authorized_srs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_authorized_srs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_authorized_srs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_authorized_srs']))); ?>
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
                                <td colspan="12" class="text-center">Belum ada sampel untuk authorized</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>