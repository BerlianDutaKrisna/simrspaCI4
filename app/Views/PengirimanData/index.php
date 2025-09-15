<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Pengiriman Data SIMRS</h6>
            <span class="badge badge-info p-2"><a href="<?= base_url('/api/pengiriman-data-simrs') ?>">Refresh</a></span>
        </div>
        <div class="card-body">
            <h1 class="h4 mb-3">Daftar Pengiriman</h1>
            <!-- Tombol Kembali ke Dashboard -->
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">
                <i class="fas fa-reply"></i> Kembali
            </a>

            <!-- Tabel Data Pengiriman -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center shadow-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>No Register</th>
                            <th>No RM</th>
                            <th>Nama</th>
                            <th>Pemeriksaan</th>
                            <th>Dokter PA</th>
                            <th>Status Lokasi</th>
                            <th>Diagnosa Klinik</th>
                            <th>Diagnosa Patologi</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($data as $row) : ?>
                                <?php
                                $timestamp = !empty($row['tanggal']) ? strtotime($row['tanggal']) : null;
                                $tgl = $timestamp ? date('d-m-Y H:i:s', $timestamp) : '-';
                                // Tentukan class baris berdasarkan status
                                $rowClass = ($row['status'] === 'Belum Terkirim') ? 'table-danger' : '';
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['idtransaksi'] ?? '-') ?></td>
                                    <td><?= $tgl ?></td>
                                    <td><?= esc($row['noregister'] ?? '-') ?></td>
                                    <td><?= esc($row['norm'] ?? '-') ?></td>
                                    <td><?= esc($row['nama'] ?? '-') ?></td>
                                    <td><?= esc($row['pemeriksaan'] ?? '-') ?></td>
                                    <td><?= esc($row['dokterpa'] ?? '-') ?></td>
                                    <td><?= esc($row['statuslokasi'] ?? '-') ?></td>
                                    <td><?= esc($row['diagnosaklinik'] ?? '-') ?></td>
                                    <td><?= esc($row['diagnosapatologi'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'Belum Terkirim'): ?>
                                            <strong class="text-danger"><?= esc($row['status']) ?></strong>
                                        <?php elseif ($row['status'] === 'Terkirim'): ?>
                                            <strong class="text-success"><?= esc($row['status']) ?></strong>
                                        <?php else: ?>
                                            <strong><?= esc($row['status'] ?? '-') ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['created_at'] ?? '-') ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="13">Tidak ada data pengiriman.</td>
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