<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan SRS</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
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
                            <th>Kode srs</th>
                            <th>Nama Pasien</th>
                            <th>Aksi</th>
                            <th>Kelengkapan Form</th>
                            <th>Analis</th>
                            <th>Mulai Penerimaan</th>
                            <th>Selesai Penerimaan</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaanDatasrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penerimaanDatasrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['kode_srs']); ?></td>
                                    <td><?= esc($row['nama_pasien']); ?></td>
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
                                    <td>
                                        <?php if ($row['status_penerimaan_srs'] === "Proses Penerimaan"): ?>
                                            <input type="hidden" name="total_nilai_mutu" value="<?= esc($row['total_nilai_mutu_srs']); ?>">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_1"
                                                    value="10"
                                                    id="indikator_1_<?= esc($row['id_mutu_srs']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_1_<?= esc($row['id_mutu_srs']); ?>">
                                                Lokasi dan Diagnosa tertulis pada form?
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <?= esc($row['total_nilai_mutu_srs']); ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['nama_user_penerimaan_srs']); ?></td>
                                    <td>
                                        <?= empty($row['mulai_penerimaan_srs']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['mulai_penerimaan_srs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penerimaan_srs']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['selesai_penerimaan_srs']))); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($row['tanggal_hasil'])) {
                                            echo 'Belum diisi';
                                        } else {
                                            echo esc(strftime('%A, %d-%m-%Y', strtotime($row['tanggal_hasil'])));
                                        }
                                        ?>
                                    </td>
                                    <?php if (in_array($row['status_penerimaan_srs'], ["Proses Penerimaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_mikroskopis/' . esc($row['id_srs'])) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_penerimaan_srs'], ["Selesai Penerimaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_mikroskopis/' . esc($row['id_srs'])) ?>" class="btn btn-success btn-sm mx-1">
                                                <i class="fas fa-pen"></i> Detail
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