<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan HPA</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <?= $this->include('templates/proses/button_penerimaan'); ?>
        <!-- Form -->
        <form id="mainForm" method="POST" action="<?= base_url('penerimaan_hpa/proses_penerimaan') ?>">
            <input type="hidden" id="action" name="action">
            <?= csrf_field(); ?>

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Aksi</th>
                            <th>Kualitas Sediaan</th>
                            <th>Analis</th>
                            <th>Mulai Penerimaan</th>
                            <th>Selesai Penerimaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaanDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penerimaanDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['kode_hpa']); ?></td>
                                    <td><?= esc($row['nama_pasien']); ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= esc($row['id_penerimaan_hpa']); ?>:<?= esc($row['id_hpa']); ?>:<?= esc($row['id_mutu_hpa']); ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penerimaan_hpa' => $row['status_penerimaan_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_penerimaan_hpa'] === "Proses Penerimaan"): ?>
                                            <input type="hidden" name="total_nilai_mutu" value="<?= esc($row['total_nilai_mutu_hpa']); ?>">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_1"
                                                    value="10"
                                                    id="indikator_1_<?= esc($row['id_mutu_hpa']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_1_<?= esc($row['id_mutu_hpa']); ?>">
                                                    Vol cairan fiksasi sesuai?
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_2"
                                                    value="10"
                                                    id="indikator_2_<?= esc($row['id_mutu_hpa']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_2_<?= esc($row['id_mutu_hpa']); ?>">
                                                    jaringan terfiksasi merata?
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <?= esc($row['total_nilai_mutu_hpa']); ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['nama_user_penerimaan_hpa']); ?></td>
                                    <td>
                                        <?= empty($row['mulai_penerimaan_hpa']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['mulai_penerimaan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penerimaan_hpa']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['selesai_penerimaan_hpa']))); ?>
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