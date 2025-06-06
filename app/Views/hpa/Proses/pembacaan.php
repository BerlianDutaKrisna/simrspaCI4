<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pembacaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pembacaan Histopatologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/proses/button_pembacaan'); ?>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pembacaan_hpa/proses_pembacaan'); ?>" method="POST">
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
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>jumlah slide</th>
                            <th>Dokter</th>
                            <th>Status Pembacaan</th>
                            <th>Mulai Pembacaan</th>
                            <th>Selesai Pembacaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pembacaanDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pembacaanDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pembacaan_hpa']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu_hpa']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pembacaan_hpa' => $row['status_pembacaan_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <?php if (in_array($row['status_pembacaan_hpa'], ["Proses Pembacaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('hpa/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_pembacaan_hpa'], ["Selesai Pembacaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('hpa/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['jumlah_slide']; ?></td>
                                    <td><?= $row['nama_user_dokter_pemotongan_hpa']; ?></td>
                                    <td><?= $row['status_pembacaan_hpa']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pembacaan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pembacaan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pembacaan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pembacaan_hpa']))); ?>
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
                                <td colspan="12" class="text-center">Belum ada sampel untuk pembacaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>