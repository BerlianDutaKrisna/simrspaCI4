<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data HPA</h6>
    </div>
    <div class="card-body">
        <h1>Data HPA</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Hpa</th>
                        <th>Nama Pasien</th>
                        <th>Norm Pasien</th>
                        <th>Jenis Kelamin Pasien</th>
                        <th>Tanggal Lahir Pasien</th>
                        <th>Alamat Pasien</th>
                        <th>Dokter Pengirim</th>
                        <th>Unit Asal</th>
                        <th>Status Pasien</th>
                        <th>Diagnosa Klinik</th>
                        <th>Tanggal Hasil</th>
                        <th>Status Hpa</th>
                        <th>Hasil Hpa</th>
                        <th class="text-center" style="width: 150px;">Penerima</th>
                        <th>Nama Penerima / Hubungan</th>
                        <th>Tanggal Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hpaData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($hpaData as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= esc($row['kode_hpa'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['nama_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?php
                                    $jenis_kelamin = $row['jenis_kelamin_pasien'] ?? 'Belum Diisi';
                                    $usia = '';

                                    if (!empty($row['tanggal_lahir_pasien'])) {
                                        $tanggal_lahir = new DateTime($row['tanggal_lahir_pasien']);
                                        $hari_ini = new DateTime();
                                        $usia = $hari_ini->diff($tanggal_lahir)->y;
                                    }

                                    echo esc($jenis_kelamin) . ($usia !== '' ? " / {$usia}" : '');
                                    ?>
                                </td>
                                <td>
                                    <?= empty($row['tanggal_lahir_pasien']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_lahir_pasien']))); ?>
                                </td>
                                <td><?= esc($row['alamat_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['dokter_pengirim'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['unit_asal'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['status_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['diagnosa_klinik'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?php
                                    if (empty($row['tanggal_hasil'])) {
                                        echo 'Belum diisi';
                                    } else {
                                        setlocale(LC_TIME, 'id_ID.utf8');
                                        $tanggal = new DateTime($row['tanggal_hasil']);
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

                                <td><?= esc($row['status_hpa'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['hasil_hpa'] ?? 'Belum Ada Hasil') ?></td>
                                <td class="text-center">
                                    <a href="#"
                                        class="btn btn-info btn-sm penerima-btn"
                                        data-toggle="modal"
                                        data-target="#penerimaModal"
                                        data-id_hpa="<?= esc($row['id_hpa']) ?>"
                                        data-penerima_hpa="<?= esc($row['penerima_hpa']) ?>">
                                        <i class="fas fa-people-arrows"></i> Penerima
                                    </a>
                                </td>
                                <td><?= esc($row['penerima_hpa'] ?? 'Belum Diterima') ?></td>
                                <td>
                                    <?= empty($row['tanggal_penerima']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['tanggal_penerima']))); ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="17" class="text-center">Tidak ada data yang tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('templates/exam/modal_exam') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/exam/script_exam') ?>