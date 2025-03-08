<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan FRS</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <?= $this->include('templates/proses/button_penerimaan'); ?>
        <!-- Form -->
        <form id="mainForm" method="POST" action="<?= base_url('penerimaan_frs/proses_penerimaan') ?>">
            <input type="hidden" id="action" name="action">
            <?= csrf_field(); ?>

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode FRS</th>
                            <th>Nama Pasien</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai Penerimaan</th>
                            <th>Selesai Penerimaan</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaanDatafrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penerimaanDatafrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['kode_frs']); ?></td>
                                    <td><?= esc($row['nama_pasien']); ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= esc($row['id_penerimaan_frs']); ?>:<?= esc($row['id_frs']); ?>:<?= esc($row['id_mutu_frs']); ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penerimaan_frs' => $row['status_penerimaan_frs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= esc($row['nama_user_penerimaan_frs']); ?></td>
                                    <td>
                                        <?= empty($row['mulai_penerimaan_frs']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['mulai_penerimaan_frs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penerimaan_frs']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['selesai_penerimaan_frs']))); ?>
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
                                    <?php if (in_array($row['status_penerimaan_frs'], ["Proses Penerimaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_makroskopis/' . esc($row['id_frs'])) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Informed Consent
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_penerimaan_frs'], ["Selesai Penerimaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_makroskopis/' . esc($row['id_frs'])) ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-pen"></i> Informed Consent
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