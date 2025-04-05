<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan Imunohistokimia</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <?= $this->include('templates/proses/button_penerimaan'); ?>
        <!-- Form -->
        <form id="mainForm" method="POST" action="<?= base_url('penerimaan_ihc/proses_penerimaan') ?>">
            <input type="hidden" id="action" name="action">
            <?= csrf_field(); ?>

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Kelengkapan Form</th>
                            <th>Kode IHC</th>
                            <th>Nama Pasien</th>
                            <th>Analis</th>
                            <th>Mulai Penerimaan</th>
                            <th>Selesai Penerimaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaanDataihc)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penerimaanDataihc as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= esc($row['id_penerimaan_ihc']); ?>:<?= esc($row['id_ihc']); ?>:<?= esc($row['id_mutu_ihc']); ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penerimaan_ihc' => $row['status_penerimaan_ihc'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_penerimaan_ihc'] === "Proses Penerimaan"): ?>
                                            <input type="hidden" name="total_nilai_mutu" value="<?= esc($row['total_nilai_mutu_ihc']); ?>">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_1"
                                                    value="10"
                                                    id="indikator_1_<?= esc($row['id_mutu_ihc']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_1_<?= esc($row['id_mutu_ihc']); ?>">
                                                    KTP
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_2"
                                                    value="10"
                                                    id="indikator_2_<?= esc($row['id_mutu_ihc']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_2_<?= esc($row['id_mutu_ihc']); ?>">
                                                    BPJS
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_3"
                                                    value="10"
                                                    id="indikator_3_<?= esc($row['id_mutu_ihc']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_3_<?= esc($row['id_mutu_ihc']); ?>">
                                                    No Telfon Pasien
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_4"
                                                    value="10"
                                                    id="indikator_4_<?= esc($row['id_mutu_ihc']); ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_4_<?= esc($row['id_mutu_ihc']); ?>">
                                                    Hasil Lab Sebelumnya
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <?= esc($row['total_nilai_mutu_ihc']); ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['kode_ihc']); ?></td>
                                    <td><?= esc($row['nama_pasien']); ?></td>
                                    <td><?= esc($row['nama_user_penerimaan_ihc']); ?></td>
                                    <td>
                                        <?= empty($row['mulai_penerimaan_ihc']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['mulai_penerimaan_ihc']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penerimaan_ihc']) ? '-' : esc(date('H:i, d-m-Y', strtotime($row['selesai_penerimaan_ihc']))); ?>
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