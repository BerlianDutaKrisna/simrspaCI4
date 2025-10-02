<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Laporan</h6>
    </div>
    <div class="card-body">
        <h1>Buku Laporan Pemeriksaan Imunohistokimia</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/laporan/button_laporan_pemeriksaan'); ?>

        <form method="GET" action="<?= base_url('ihc/filter'); ?>">
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

        <div class="row justify-content-center mb-3">
            <div class="col-auto">
                <h5 class="text-center font-weight-bold">Kontrol Positif</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-auto">
                <a href="<?= base_url('ihc/laporan_ER'); ?>" class="btn btn-secondary btn-icon-split m-2">
                    <span class="text"><b style="color: white;">ER</b></span>
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                </a>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('ihc/laporan_PR'); ?>" class="btn btn-secondary btn-icon-split m-2">
                    <span class="text"><b style="color: white;">PR</b></span>
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                </a>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('ihc/laporan_HER2'); ?>" class="btn btn-secondary btn-icon-split m-2">
                    <span class="text"><b style="color: white;">HER2</b></span>
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                </a>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('ihc/laporan_KI67'); ?>" class="btn btn-secondary btn-icon-split m-2">
                    <span class="text"><b style="color: white;">KI67</b></span>
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                </a>
            </div>
        </div>

        <div class="mb-3">
            <strong>Total Data Pasien:</strong> <?= count($ihcData) ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTableButtons" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>REGISTER</th>
                        <th>NAMA</th>
                        <th>NO RM</th>
                        <th>TANGGAL LAHIR</th>
                        <th>L/P</th>
                        <th>ALAMAT</th>
                        <th>JENIS PASIEN</th>
                        <th>TANGGAL TRANSAKSI</th>
                        <th>DOKTER PERUJUK</th>
                        <th>DOKTER PA</th>
                        <th>UNIT ASAL</th>
                        <th>PEMERIKSAAN</th>
                        <th>STATUS LOKASI</th>
                        <th>DIAGNOSA KLINIK</th>
                        <th>DIAGNOSA PATOLOGI</TH>
                        <th>MUTU SEDIAAN</th>
                        <th>RESPONSE TIME</th>
                        <th>7 HARI</th>
                        <th>KODE BLOCK HPA</th>
                        <th>NO KTP</th>
                        <th>NO BPJS</th>
                        <th>NO TELP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ihcData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($ihcData as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= esc($row['kode_ihc'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['nama_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['norm_pasien'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?= empty($row['tanggal_lahir_pasien']) ? 'Belum Diisi' : esc(date('d-m-Y', strtotime($row['tanggal_lahir_pasien']))); ?>
                                </td>
                                <td><?= esc($row['jenis_kelamin_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['alamat_pasien'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['status_pasien'] ?? 'Belum Diisi') ?></td>
                                <td>
                                    <?= empty($row['tanggal_permintaan']) ? 'Belum Diisi' : esc(date('d-m-Y', strtotime($row['tanggal_permintaan']))); ?>
                                </td>
                                <td><?= esc($row['dokter_pengirim'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['dokter_pembaca'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['unit_asal'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['tindakan_spesimen'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['lokasi_spesimen'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['diagnosa_klinik'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc(strip_tags($row['hasil_ihc'] ?? 'Belum Ada Hasil')) ?></td>
                                <td><?= esc($row['total_nilai_mutu_ihc'] ?? 'Belum Diisi') ?>%</td>
                                <td>
                                    <?php
                                    if (!empty($row['mulai_penerimaan_ihc']) && !empty($row['selesai_penulisan_ihc'])) {
                                        $start = new DateTime($row['mulai_penerimaan_ihc']);
                                        $end = new DateTime($row['selesai_penulisan_ihc']);
                                        $interval = $start->diff($end);
                                        echo $interval->format('%a hari %h jam %i menit %s detik');
                                    } else {
                                        echo 'Belum lengkap';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($row['mulai_penerimaan_ihc']) && !empty($row['selesai_penulisan_ihc'])) {
                                        $start = new DateTime($row['mulai_penerimaan_ihc']);
                                        $end = new DateTime($row['selesai_penulisan_ihc']);
                                        $interval = $start->diff($end);
                                        if ($interval->days <= 7) {
                                            echo '<span class="text-success">Tepat Waktu</span>';
                                        } else {
                                            echo '<span class="text-danger">Terlambat</span>';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= esc($row['kode_block_ihc'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['no_ktp_ihc'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['no_bpjs_ihc'] ?? 'Belum Diisi') ?></td>
                                <td><?= esc($row['no_tlp_ihc'] ?? 'Belum Diisi') ?></td>
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

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/exam/cetak_pencarian'); ?>