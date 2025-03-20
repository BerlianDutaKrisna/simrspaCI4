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
                        <th>Kode Pemeriksaan</th>
                        <th>Jenis Pemeriksaan</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin / Usia</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Dokter Pengirim</th>
                        <th>Unit Asal</th>
                        <th>Diagnosa Klinik</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($Data)) : ?>
                        <?php
                        $lastDate = null; // Variabel untuk menyimpan tanggal sebelumnya
                        $i = 1; // Nomor urut dalam satu tanggal

                        function formatTanggalIndonesia($tanggal)
                        {
                            $hari = [
                                'Sunday' => 'Minggu',
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu'
                            ];

                            $bulan = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];

                            $timestamp = strtotime($tanggal);
                            $hariIndo = $hari[date('l', $timestamp)];
                            $tgl = date('d', $timestamp);
                            $bln = $bulan[date('m', $timestamp)];
                            $thn = date('Y', $timestamp);

                            return "$hariIndo, $tgl $bln $thn";
                        }
                        ?>
                        <?php foreach ($Data as $row) : ?>
                            <?php
                            $tanggal_permintaan = $row['tanggal_permintaan'] ?? null;
                            $formattedDate = empty($tanggal_permintaan) ? '-' : formatTanggalIndonesia($tanggal_permintaan);

                            // Jika tanggal berubah, tampilkan header tanggal baru & reset nomor urut
                            if ($formattedDate !== $lastDate) :
                                $i = 1;
                            ?>
                                <tr class="bg-light">
                                    <td colspan="16" class="font-weight-bold text-center"><?= esc($formattedDate) ?></td>
                                </tr>
                                <?php $lastDate = $formattedDate; ?>
                            <?php endif; ?>

                            <!-- Baris Data Pasien -->
                            <tr>
                                <td><?= $i ?></td> <!-- Nomor urut dalam satu tanggal -->
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['kode_pemeriksaan'] ?? '-') ?></td>
                                <td><?= esc($row['jenis_pemeriksaan'] ?? '-') ?></td>
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
                                <td><?= esc($row['diagnosa_klinik'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc(strip_tags($row['hasil'] ?? 'Belum Ada Hasil')) ?></td>
                            </tr>
                            <?php $i++; ?> <!-- Nomor urut bertambah dalam satu tanggal -->
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="16" class="text-center">Tidak ada data yang tersedia</td>
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