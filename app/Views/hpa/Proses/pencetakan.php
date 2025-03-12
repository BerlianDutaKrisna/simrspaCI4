<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table pencetakan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar pencetakan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pencetakan_hpa/proses_pencetakan'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status pencetakan</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai pencetakan</th>
                            <th>Selesai pencetakan</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pencetakanDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pencetakanDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pencetakan_hpa']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pencetakan_hpa']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu_hpa']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pencetakan_hpa' => $row['status_pencetakan_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pencetakan_hpa']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pencetakan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pencetakan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pencetakan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pencetakan_hpa']))); ?>
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
                                    <?php if (in_array($row['status_pencetakan_hpa'], ["Proses Pencetakan"])): ?>
                                        <td>
                                            <a href="<?= base_url('hpa/edit_print_hpa/' .
                                                            esc($row['id_hpa']) . '/' .
                                                            esc($row['id_penerimaan_hpa']) . '/' .
                                                            esc($row['id_pembacaan_hpa']) . '/' .
                                                            esc(isset($row['id_pemverifikasi_hpa']) ? $row['id_pemverifikasi_hpa'] : '0'). '/' .
                                                            esc(isset($row['id_authorized_hpa']) ? $row['id_authorized_hpa'] : '0') . '/' .
                                                            esc(isset($row['id_pencetakan_hpa']) ? $row['id_pencetakan_hpa'] : '0') .
                                                            '?redirect=index_pencetakan_hpa') ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-eye"></i> Cek Penulisan
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_pencetakan_hpa'], ["Selesai Pencetakan"])): ?>
                                        <td>
                                        <a href="<?= base_url('hpa/edit_print_hpa/' .
                                                            esc($row['id_hpa']) . '/' .
                                                            esc($row['id_penerimaan_hpa']) . '/' .
                                                            esc($row['id_pembacaan_hpa']) . '/' .
                                                            esc(isset($row['id_pemverifikasi_hpa']) ? $row['id_pemverifikasi_hpa'] : '0'). '/' .
                                                            esc(isset($row['id_authorized_hpa']) ? $row['id_authorized_hpa'] : '0') . '/' .
                                                            esc(isset($row['id_pencetakan_hpa']) ? $row['id_pencetakan_hpa'] : '0') .
                                                            '?redirect=index_pencetakan_hpa') ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i> Cek Penulisan
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
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