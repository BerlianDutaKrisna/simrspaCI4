<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Laporan</h6>
    </div>
    <div class="card-body">
        <h1>Buku Laporan Kerja Histopatologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/laporan/button_laporan_kerja'); ?>

        <form method="GET" action="#">
            <?= csrf_field(); ?>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="filterInput">Filter berdasarkan</label>
                        <select class="form-control" id="filterInput" name="filterInput" onchange="togglefilterValue()">
                            <option value="">Pilih Kriteria</option>
                            <option value="norm_pasien">Nomor Rekam Medis</option>
                            <option value="nama_pasien">Nama Pasien</option>
                            <option value="dokter_pengirim">Dokter Pengirim</option>
                            <option value="unit_asal">Unit Asal</option>
                            <option value="diagnosa_klinik">Diagnosa Klinik</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="filterValueContainer" style="display: none;">
                        <label for="filterValue">Masukkan Nilai</label>
                        <input type="text" class="form-control" id="filterValue" name="filterValue"
                            value="<?= htmlspecialchars(old('filterValue')) ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="filterDate">Tanggal Pencarian</label>
                        <input type="date" class="form-control" id="filterDate" name="filterDate"
                            value="<?= old('filterDate') ?: date('Y-m-01', strtotime('-1 month')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="filterDate2">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="filterDate2" name="filterDate2"
                            value="<?= old('filterDate2') ?: date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <script>
            function togglefilterValue() {
                var filterInput = document.getElementById("filterInput");
                var filterValueContainer = document.getElementById("filterValueContainer");
                var filterValue = document.getElementById("filterValue");

                if (filterInput.value) {
                    filterValueContainer.style.display = "block";
                } else {
                    filterValueContainer.style.display = "none";
                    filterValue.value = "";
                }
            }
        </script>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTableButtons" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>REGISTER</th>
                        <th>NAMA</th>
                        <th>NO RM</th>
                        <th>TANGGAL TRANSAKSI</th>
                        <th>PENERIMAAN</th>
                        <th>PEMOTONGAN</th>
                        <th>PEMPROSESAN</th>
                        <th>PENANAMAN</th>
                        <th>PEMOTONGAN TIPIS</th>
                        <th>PEWARNAAN</th>
                        <th>PEMBACAAN</th>
                        <th>PENULISAN</th>
                        <th>PEMVERIFIKASI</th>
                        <th>AUTHORISED</th>
                        <th>PENCETAKAN</th>
                        <th>TOTAL WAKTU</th>
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
                                    <?= empty($row['tanggal_permintaan']) ? 'Belum Diisi' : esc(date('d-m-Y', strtotime($row['tanggal_permintaan']))); ?>
                                </td>

                                <?php
                                if (!function_exists('formatTanggalIndo')) {
                                    function formatTanggalIndo($datetime)
                                    {
                                        if (empty($datetime)) return 'Belum Diisi';

                                        $timestamp = strtotime($datetime);

                                        $hariInggris = date('l', $timestamp);
                                        $hariIndonesia = [
                                            'Sunday' => 'Minggu',
                                            'Monday' => 'Senin',
                                            'Tuesday' => 'Selasa',
                                            'Wednesday' => 'Rabu',
                                            'Thursday' => 'Kamis',
                                            'Friday' => 'Jumat',
                                            'Saturday' => 'Sabtu'
                                        ];

                                        $hari = $hariIndonesia[$hariInggris];
                                        $tanggal = date('d-m-Y', $timestamp);
                                        $jam = date('H:i', $timestamp);

                                        return '<b>' . esc($hari) . '<br>' . esc($tanggal) . '</b><br>' . esc($jam);
                                    }
                                }
                                ?>

                                <td><?= esc($row['durasi_penerimaan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_penerimaan_hpa']) ?></td>
                                <td><?= esc($row['durasi_pemotongan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pemotongan_hpa']) ?></td>
                                <td><?= esc($row['durasi_pemprosesan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pemprosesan_hpa']) ?></td>
                                <td><?= esc($row['durasi_penanaman_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_penanaman_hpa']) ?></td>
                                <td><?= esc($row['durasi_pemotongan_tipis_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pemotongan_tipis_hpa']) ?></td>
                                <td><?= esc($row['durasi_pewarnaan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pewarnaan_hpa']) ?></td>
                                <td><?= esc($row['durasi_pembacaan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pembacaan_hpa']) ?></td>
                                <td><?= esc($row['durasi_penulisan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_penulisan_hpa']) ?></td>
                                <td><?= esc($row['durasi_pemverifikasi_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pemverifikasi_hpa']) ?></td>
                                <td><?= esc($row['durasi_authorized_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_authorized_hpa']) ?></td>
                                <td><?= esc($row['durasi_pencetakan_hpa'] ?? '') ?><br><?= formatTanggalIndo($row['mulai_pencetakan_hpa']) ?></td>
                                <td><?= esc($row['total_waktu_kerja'] ?? '') ?></td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="15" class="text-center">Tidak ada data yang tersedia</td>
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