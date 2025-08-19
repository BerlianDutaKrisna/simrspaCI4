<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Kunjungan Pasien</h6>
            <span class="badge badge-info p-2">Ringkasan Hari Ini</span>
        </div>
        <div class="card-body">
            <h1 class="h4 mb-3">Daftar Kunjungan</h1>
            <!-- Tombol Kembali ke Dashboard -->
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">
                <i class="fas fa-reply"></i> Kembali
            </a>
            <div>
                <a href="<?= base_url('/api/kunjungan/indexAll') ?>" class="btn btn-secondary mb-3">
                    <i class="fas fa-list"></i> Tampilkan Semua Data
                </a>
            </div>
            <!-- Tabel Data Kunjungan -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center shadow-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>No. Pendaftaran</th>
                            <th>No. RM</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin / Usia</th>
                            <th>Tgl Lahir</th>
                            <th>Alamat</th>
                            <th>Jenis Pasien</th>
                            <th>Dokter Perujuk</th>
                            <th>Unit Asal</th>
                            <th>Pemeriksaan</th>
                            <th>Diagnosa Klinik</th>
                            <th>Status</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)) : ?>
                            <?php
                            $i = 1;
                            $uniquePatients = [];
                            $totalHarga = 0;
                            ?>
                            <?php foreach ($data as $row) : ?>
                                <?php
                                $rowClass = empty($row['hasil']) ? 'table-danger' : '';
                                $timestamp = !empty($row['tanggal']) ? strtotime($row['tanggal']) : null;
                                $jam = $timestamp ? date('H:i:s', $timestamp) : '--:--:--';
                                $tgl = $timestamp ? date('d-m-Y', $timestamp) : '';

                                if (!empty($row['norm'])) {
                                    $uniquePatients[$row['norm']] = true;
                                }

                                $tagihan = !empty($row['tagihan']) ? (float)$row['tagihan'] : 0;
                                $totalHarga += $tagihan;
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td><?= $i ?></td>
                                    <td>
                                        <?php if (empty($row['hasil'])): ?>
                                            <span class="text-danger"><?= $jam ?></span> <?= $tgl ?>
                                        <?php else: ?>
                                            <?= $jam ?> <?= $tgl ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['register'] ?? '') ?></td>
                                    <td><?= esc($row['norm'] ?? '') ?></td>
                                    <td><?= esc($row['nama'] ?? '') ?></td>
                                    <td><?= esc(($row['jeniskelamin'] ?? '') . ' / ' . ($row['pasien_usia'] ?? '')) ?></td>
                                    <td><?= !empty($row['tgl_lhr']) ? esc(date('d-m-Y', strtotime($row['tgl_lhr']))) : '-' ?></td>
                                    <td><?= esc($row['alamat'] ?? '') ?></td>
                                    <td><?= esc($row['jenispasien'] ?? '') ?></td>
                                    <td><?= esc($row['dokterperujuk'] ?? '') ?></td>
                                    <td><?= esc($row['unitasal'] ?? '') ?></td>
                                    <td><?= esc($row['pemeriksaan'] ?? '') ?></td>
                                    <td><?= esc($row['diagnosaklinik'] ?? '') ?></td>
                                    <td>
                                        <?php if (empty($row['hasil'])): ?>
                                            <strong class="text-danger">Belum Terdaftar</strong>
                                        <?php else: ?>
                                            <strong class="text-success">Terdaftar</strong>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= 'Rp. ' . number_format($tagihan, 0, ',', '.') ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="14">Tidak ada data pendaftaran hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <?php if (!empty($data)) : ?>
                        <tfoot class="font-weight-bold bg-light">
                            <tr>
                                <td colspan="13" class="text-right">Total Pasien Hari ini</td>
                                <td colspan="2"><?= count($uniquePatients) ?></td>
                            </tr>
                            <tr>
                                <td colspan="13" class="text-right">Total Harga</td>
                                <td colspan="2"><?= 'Rp. ' . number_format($totalHarga, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/script_syncKunjungan'); ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>