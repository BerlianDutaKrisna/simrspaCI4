<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Registrasi</h6>
    </div>
    <div class="card-body">
        <h1>Buku Registrasi Laboratorrium Patologi Anatomi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-center" id="dataTableButtons" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No RM</th>
                        <th>Kode FRS</th>
                        <th>Kode HPA</th>
                        <th>Kode SRS</th>
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
                        <th>Hasil</th>
                        <th>Status</th>
                        <th>Nama Penerima / Hubungan</th>
                        <th>Tanggal Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($Data)) : ?>
                        <?php
                        $lastDate = null; // Menyimpan tanggal sebelumnya
                        ?>
                        <?php foreach ($Data as $row) : ?>
                            <?php
                            $tanggal_permintaan = $row['tanggal_permintaan'] ?? null;
                            $formattedDate = '-';

                            if (!empty($tanggal_permintaan)) {
                                $tanggal = new DateTime($tanggal_permintaan);
                                $formatter = new IntlDateFormatter(
                                    'id_ID',
                                    IntlDateFormatter::FULL,
                                    IntlDateFormatter::NONE,
                                    'Asia/Jakarta',
                                    IntlDateFormatter::GREGORIAN,
                                    'EEEE, dd MMMM yyyy'
                                );
                                $formattedDate = $formatter->format($tanggal);
                            }

                            // Jika tanggal berbeda dari sebelumnya, tampilkan header baru & reset nomor urut
                            if ($formattedDate !== $lastDate) :
                                $i = 1; // Reset nomor urut
                            ?>
                                <tr class="bg-light">
                                    <td colspan="19" class="font-weight-bold text-center"><?= esc($formattedDate) ?></td>
                                </tr>
                                <?php $lastDate = $formattedDate; ?>
                            <?php endif; ?>

                            <!-- Data Pasien -->
                            <tr>
                                <td><?= $i ?></td> <!-- Nomor urut yang di-reset setiap tanggal baru -->
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['kode_frs'] ?? '') ?></td>
                                <td><?= esc($row['kode_hpa'] ?? '') ?></td>
                                <td><?= esc($row['kode_srs'] ?? '') ?></td>
                                <td><?= esc($row['kode_ihc'] ?? '') ?></td>
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
                                    <?= empty($row['tanggal_lahir_pasien']) ? 'Belum Diisi' : esc(date('d-m-Y', strtotime($row['tanggal_lahir_pasien']))); ?>
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
                                        echo esc($tanggal->format('d-m-Y'));
                                    }
                                    ?>
                                </td>
                                <td><?= esc(strip_tags($row['hasil_hpa'] ?? 'Belum Ada Hasil')) ?></td>
                                <td><?= esc($row['status_hpa'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?= esc($row['penerima_hpa'] ?? $row['penerima_frs'] ?? $row['penerima_srs'] ?? $row['penerima_ihc'] ?? 'Belum Diterima') ?>
                                </td>
                                <td>
                                    <?= empty($row['tanggal_penerima']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['tanggal_penerima']))); ?>
                                </td>
                            </tr>
                            <?php $i++; ?> <!-- Nomor urut bertambah dalam hari yang sama -->
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="19" class="text-center">Tidak ada data yang tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('templates/hpa/modal') ?>
<?= $this->include('templates/frs/modal') ?>
<?= $this->include('templates/srs/modal') ?>
<?= $this->include('templates/ihc/modal') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/hpa/script') ?>
<?= $this->include('templates/frs/script') ?>
<?= $this->include('templates/srs/script') ?>
<?= $this->include('templates/ihc/script') ?>