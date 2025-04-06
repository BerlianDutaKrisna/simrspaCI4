<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan SRS</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/proses/button_penerimaan'); ?>
        <!-- Form -->
        <form id="mainForm" method="POST" action="<?= base_url('penerimaan_srs/proses_penerimaan') ?>">
            <input type="hidden" id="action" name="action">
            <?= csrf_field(); ?>

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Detail</th>
                            <th>Kode SRS</th>
                            <th>Nama Pasien</th>
                            <th>Analis</th>
                            <th>Mulai Penerimaan</th>
                            <th>Selesai Penerimaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaanDatasrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penerimaanDatasrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= esc($row['id_penerimaan_srs']); ?>:<?= esc($row['id_srs']); ?>:<?= esc($row['id_mutu_srs']); ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penerimaan_srs' => $row['status_penerimaan_srs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <?php if (in_array($row['status_penerimaan_srs'], ["Proses Penerimaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('srs/edit_makroskopis/' . esc($row['id_srs'])) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_penerimaan_srs'], ["Selesai Penerimaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('srs/edit_makroskopis/' . esc($row['id_srs'])) ?>" class="btn btn-success btn-sm mx-1">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><?= esc($row['kode_srs']); ?></td>
                                    <td><?= esc($row['nama_pasien']); ?></td>
                                    <td><?= esc($row['nama_user_penerimaan_srs']); ?></td>
                                    <td>
                                        <?= empty($row['mulai_penerimaan_srs']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['mulai_penerimaan_srs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penerimaan_srs']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['selesai_penerimaan_srs']))); ?>
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk penerimaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <?= $this->include('templates/proses/button_proses'); ?>
        <?= $this->include('dashboard/jenis_tindakan'); ?>
        <?= $this->include('templates/notifikasi'); ?>
        <?= $this->include('templates/dashboard/footer_dashboard'); ?>