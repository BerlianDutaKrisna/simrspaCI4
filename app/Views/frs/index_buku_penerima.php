<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Penerimaan Hasil</h6>
    </div>
    <div class="card-body">
        <h1>Buku Penerimaan Hasil Fine Needle Aspiration Biopsy</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode frs</th>
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
                        <th>Status frs</th>
                        <th>Hasil frs</th>
                        <th class="text-center" style="width: 150px;">Penerima</th>
                        <th>Nama Penerima / Hubungan</th>
                        <th>Tanggal Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($frsData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($frsData as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= esc($row['kode_frs'] ?? 'Belum Diisi') ?></td>
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
                                <td><?= esc($row['status_frs'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc(strip_tags($row['hasil_frs'] ?? 'Belum Ada Hasil')) ?></td>
                                <td class="text-center">
                                    <a href="#"
                                        class="btn btn-info btn-sm penerima-btn"
                                        data-toggle="modal"
                                        data-target="#penerimaModal"
                                        data-id_frs="<?= esc($row['id_frs']) ?>"
                                        data-penerima_frs="<?= esc($row['penerima_frs']) ?>">
                                        <i class="fas fa-people-arrows"></i> Penerima
                                    </a>
                                </td>
                                <td><?= esc($row['penerima_frs'] ?? 'Belum Diterima') ?></td>
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

<?= $this->include('templates/frs/modal') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/frs/script') ?>