<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Laporan</h6>
    </div>
    <div class="card-body">
        <h1>Buku Laporan Pemeriksaan Histopatologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/laporan/button_laporan_pemeriksaan'); ?>

        <form method="GET" action="<?= base_url('hpa/filter'); ?>">
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
                            value="<?= esc($_GET['filterValue'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="filterDate">Tanggal Pencarian</label>
                        <input type="date" class="form-control" id="filterDate" name="filterDate"
                            value="<?= esc($_GET['filterDate'] ?? date('Y-m-01', strtotime('-1 month'))); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="filterDate2">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="filterDate2" name="filterDate2"
                            value="<?= esc($_GET['filterDate2'] ?? date('Y-m-d')); ?>">
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

        <div class="mb-3">
            <strong>Total Data Pasien:</strong> <?= count($hpaData) ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTableButtons" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Tanggal Permintaan</th>
                        <th>No RM</th>
                        <th>KODE HPA</th>
                        <th>NAMA</th>
                        <th>Status Sampel</th>
                        <th>Cek kesesuaian identitas</th>
                        <th>Volume cairan sesuai</th>
                        <th>Block Parafin tidak ada fragmentasi</th>
                        <th>Jaringan terfiksasi merata</th>
                        <th>Potongan tipis dan merata</th>
                        <th>Sediaan tanpa lipatan</th>
                        <th>Sediaan tanpa goresan mata pisau</th>
                        <th>Kontras warna jelas</th>
                        <th>Sediaan tanpa gelembung udara</th>
                        <th>Sediaan tanpa bercak jari</th>
                        <th><strong>Total Nilai Mutu</strong></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hpaData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($hpaData as $row) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <?= empty($row['tanggal_permintaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['tanggal_permintaan']))); ?>
                                </td>
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['kode_hpa'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['nama_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><strong class="<?= in_array($row['status_hpa'] ?? '', ['Authorized', 'Selesai']) ? 'text-success' : '' ?>"><?= esc($row['status_hpa'] ?? 'Belum Diisi') ?></strong></td>
                                <td><?= esc($row['indikator_1'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_2'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_3'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_4'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_5'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_6'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_7'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_8'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_9'] ?? 0) ?></td>
                                <td><?= esc($row['indikator_10'] ?? 0) ?></td>
                                <td>
                                    <b><?= esc($row['total_nilai_mutu_hpa'] ?? 0) ?></b>
                                    <?php if (($row['total_nilai_mutu_hpa'] ?? 0) > 80): ?>
                                        <span class="text-success"> Baik</span>
                                    <?php else: ?>
                                        <span class="text-warning"> Perlu Perbaikan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('hpa/edit/' . $row['id_hpa']) ?>"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="17">Data tidak tersedia</td>
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