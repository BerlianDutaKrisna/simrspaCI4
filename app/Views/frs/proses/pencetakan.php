<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pencetakan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pencetakan Fine Needle Aspiration Biopsy</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <?= $this->include('templates/proses/button_pencetakan'); ?>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pencetakan_frs/proses_pencetakan'); ?>" method="POST">
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
                            <th>Kode FRS</th>
                            <th>Nama Pasien</th>
                            <th>Status pencetakan</th>
                            <th>Admin</th>
                            <th>Mulai pencetakan</th>
                            <th>Selesai pencetakan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pencetakanDatafrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pencetakanDatafrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pencetakan_frs']; ?>:<?= $row['id_frs']; ?>:<?= $row['id_mutu_frs']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pencetakan_frs' => $row['status_pencetakan_frs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <?php if (in_array($row['status_pencetakan_frs'], ["Proses Pencetakan"])): ?>
                                        <td>
                                            <a href="<?= base_url('frs/edit_print/' .
                                                            esc($row['id_frs']) .
                                                            '?redirect=index_pencetakan_frs') ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-print"></i> Cetak Hasil
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_pencetakan_frs'], ["Selesai Pencetakan"])): ?>
                                        <td>
                                        <a href="<?= base_url('frs/edit_print/' .
                                                            esc($row['id_frs']) .
                                                            '?redirect=index_pencetakan_frs') ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-print"></i> Cetak Hasil
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><?= $row['kode_frs']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pencetakan_frs']; ?></td>
                                    <td><?= $row['nama_user_pencetakan_frs']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pencetakan_frs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pencetakan_frs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pencetakan_frs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pencetakan_frs']))); ?>
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
                                <td colspan="12" class="text-center">Belum ada sampel untuk pencetakan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>