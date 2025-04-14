<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penulisan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penulisan Sitologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/proses/button_penulisan'); ?>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('penulisan_srs/proses_penulisan'); ?>" method="POST">
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
                            <th>Status Penulisan</th>
                            <th>Admin</th>
                            <th>Mulai Penulisan</th>
                            <th>Selesai Penulisan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penulisanDatasrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penulisanDatasrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_penulisan_srs']; ?>:<?= $row['id_srs']; ?>:<?= $row['id_mutu_srs']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penulisan_srs' => $row['status_penulisan_srs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <?php if (in_array($row['status_penulisan_srs'], ["Proses Penulisan"])): ?>
                                        <td>
                                            <a href="<?= base_url('srs/edit_penulisan/' . esc($row['id_srs'])) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Penulisan
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_penulisan_srs'], ["Selesai Penulisan"])): ?>
                                        <td>
                                            <a href="<?= base_url('srs/edit_penulisan/' . esc($row['id_srs'])) ?>" class="btn btn-success btn-sm mx-1">
                                                <i class="fas fa-pen"></i> Penulisan
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><?= $row['kode_srs']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_penulisan_srs']; ?></td>
                                    <td><?= $row['nama_user_penulisan_srs']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_penulisan_srs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_penulisan_srs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penulisan_srs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_penulisan_srs']))); ?>
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