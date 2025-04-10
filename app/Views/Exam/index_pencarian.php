<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Registrasi</h6>
    </div>
    <div class="card-body">
        <h1>Buku Registrasi Laboratorrium Patologi Anatomi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <form method="GET" action="<?= base_url('exam/search') ?>">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="searchInput">Cari berdasarkan</label>
                        <select class="form-control" id="searchInput" name="searchInput" onchange="toggleSearchValue()">
                            <option value="">Pilih Kriteria</option>
                            <option value="norm_pasien">Nomor Rekam Medis</option>
                            <option value="nama_pasien">Nama Pasien</option>
                            <option value="dokter_pengirim">Dokter Pengirim</option>
                            <option value="unit_asal">Unit Asal</option>
                            <option value="diagnosa_klinik">Diagnosa Klinik</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="searchValueContainer" style="display: none;">
                        <label for="searchValue">Masukkan Nilai</label>
                        <input type="text" class="form-control" id="searchValue" name="searchValue">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="searchDate">Tanggal Pencarian</label>
                        <input type="date" class="form-control" id="searchDate" name="searchDate"
                            value="<?= old('searchDate') ?: date('Y-m-d', strtotime('-7 days')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="searchDate2">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="searchDate2" name="searchDate2"
                            value="<?= old('searchDate2') ?: date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="button"
                            class="btn btn-info btn-user w-100 w-md-auto"
                            onclick="cetakPencarian()">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <script>
            function toggleSearchValue() {
                var searchInput = document.getElementById("searchInput");
                var searchValueContainer = document.getElementById("searchValueContainer");
                var searchValue = document.getElementById("searchValue");

                if (searchInput.value) {
                    searchValueContainer.style.display = "block";
                } else {
                    searchValueContainer.style.display = "none";
                    searchValue.value = "";
                }
            }
        </script>

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
                                <?php
                                $kode = $row['kode_pemeriksaan'] ?? '-';
                                $class = 'text-dark'; // default

                                if (strpos($kode, 'H.') === 0) {
                                    $class = 'text-danger';
                                } elseif (strpos($kode, 'FRS.') === 0) {
                                    $class = 'text-primary';
                                } elseif (strpos($kode, 'SRS.') === 0) {
                                    $class = 'text-success';
                                } elseif (strpos($kode, 'IHC.') === 0) {
                                    $class = 'text-warning';
                                }
                                ?>
                                <td class="<?= $class ?>"><strong><?= esc($kode) ?></strong></td>
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

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/exam/cetak_pencarian'); ?>