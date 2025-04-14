<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Penerimaan Hasil</h6>
    </div>
    <div class="card-body">
        <h1>Buku Penerimaan Hasil Imunohistokimia</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Norm Pasien</th>
                        <th>Kode IHC</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin Pasien</th>
                        <th>Tanggal Lahir Pasien</th>
                        <th>Alamat Pasien</th>
                        <th>Dokter Pengirim</th>
                        <th>Unit Asal</th>
                        <th>Status Pasien</th>
                        <th>Diagnosa Klinik</th>
                        <th>Tanggal Hasil</th>
                        <th>Hasil IHC</th>
                        <th>Status IHC</th>
                        <th class="text-center" style="width: 150px;">Penerima</th>
                        <th>Nama Penerima / Hubungan</th>
                        <th>Tanggal Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ihcData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($ihcData as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['kode_ihc'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['nama_pasien'] ?? 'Belum Diisi') ?></td>
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
                                <td><?= esc(strip_tags($row['hasil_ihc'] ?? 'Belum Ada Hasil')) ?></td>
                                <td><strong class="<?= in_array($row['status_ihc'] ?? '', ['Authorized', 'Selesai']) ? 'text-success' : '' ?>"><?= esc($row['status_ihc'] ?? 'Belum Diisi') ?></strong></td>
                                <td class="text-center">
                                    <a href="#"
                                        class="btn btn-info btn-sm penerima-btn"
                                        data-toggle="modal"
                                        data-target="#penerimaModal"
                                        data-id_ihc="<?= esc($row['id_ihc']) ?>"
                                        data-penerima_ihc="<?= esc($row['penerima_ihc']) ?>">
                                        <i class="fas fa-people-arrows"></i> Penerima
                                    </a>
                                </td>
                                <td><?= esc($row['penerima_ihc'] ?? 'Belum Diterima') ?></td>
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

<?= $this->include('templates/ihc/modal') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/ihc/script') ?>