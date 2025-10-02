<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kunjungan Pasien</h6>
        </div>
        <div class="card-body">
            <h1>Daftar Kunjungan</h1>
            <!-- Tombol Kembali ke Dashboard -->
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">
                <i class="fas fa-reply"></i> Kembali
            </a>
            <div>
                <a href="<?= base_url('/api/kunjungan/index') ?>" class="btn btn-secondary mb-3">Tampilkan data hari ini</a>
            </div>
            <!-- Tabel Data Kunjungan -->
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
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
                            $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            ?>
                            <?php foreach ($data as $row) : ?>
                                <?php
                                // Tentukan class baris berdasarkan status
                                $rowClass = ($row['status'] === 'Belum Terdaftar') ? 'table-danger' : '';
                                // Format tanggal dan jam
                                $timestamp = !empty($row['tanggal']) ? strtotime($row['tanggal']) : null;
                                $jam = $timestamp ? date('H:i:s', $timestamp) : '--:--:--';
                                $tgl = $timestamp ? date('d-m-Y', $timestamp) : '';
                                $hari = $timestamp ? $hariIndo[date('w', $timestamp)] : '';
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td><?= $i ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'Belum Terdaftar'): ?>
                                            <span class="text-danger"><?= $jam ?></span> <?= $hari ?>, <?= $tgl ?>
                                        <?php elseif ($row['status'] === 'Terdaftar'): ?>
                                            <span class="text-success"><?= $jam ?></span> <?= $hari ?>, <?= $tgl ?>
                                        <?php else: ?>
                                            <?= $jam ?> <?= $hari ?>, <?= $tgl ?>
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
                                        <?php if ($row['status'] === 'Belum Terdaftar'): ?>
                                            <strong class="text-danger"><?= esc($row['status']) ?></strong>
                                        <?php elseif ($row['status'] === 'Terdaftar'): ?>
                                            <strong class="text-success"><?= esc($row['status']) ?></strong>
                                        <?php else: ?>
                                            <strong><?= esc($row['status'] ?? '-') ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['tagihan'] ?? '') ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="14">Tidak ada data pendaftaran hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>