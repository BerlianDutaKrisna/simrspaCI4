<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Kunjungan Pasien</h6>
            <span class="badge badge-info p-2">
                <a href="<?= base_url('/api/pengiriman-data-simrs') ?>">Pengiriman Data Simrs</a>
            </span>
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
            <?php
                // Hitung jumlah baris OK EMERGENCY & OK ELEKTIF yang Belum Terdaftar
                $count_ok_emg  = 0;
                $count_ok_elkt = 0;
                if (!empty($data)) {
                    foreach ($data as $_row) {
                        if (!isset($_row['unitasal'], $_row['status']) || $_row['status'] !== 'Belum Terdaftar') continue;
                        if ($_row['unitasal'] === 'OK EMERGENCY') $count_ok_emg++;
                        if ($_row['unitasal'] === 'OK ELEKTIF')   $count_ok_elkt++;
                    }
                }
            ?>
            <?php if ($count_ok_emg > 0): ?>
            <div class="alert alert-danger d-flex align-items-center mb-2 shadow-sm" role="alert"
                 style="border-left: 6px solid #c0392b; border-radius: 6px;">
                <span class="icon-pulse-danger mr-3" style="font-size: 1.8rem;">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </span>
                <div>
                    <strong>Perhatian!</strong>
                    Terdapat <strong><?= $count_ok_emg ?> pasien</strong> dari unit
                    <strong>OK EMERGENCY</strong> dengan status <strong class="text-danger">Belum Terdaftar</strong>.
                    Harap segera diproses.
                </div>
            </div>
            <?php endif; ?>
            <?php if ($count_ok_elkt > 0): ?>
            <div class="alert alert-danger d-flex align-items-center mb-2 shadow-sm" role="alert"
                 style="border-left: 6px solid #c0392b; border-radius: 6px;">
                <span class="icon-pulse-danger mr-3" style="font-size: 1.8rem;">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </span>
                <div>
                    <strong>Perhatian!</strong>
                    Terdapat <strong><?= $count_ok_elkt ?> pasien</strong> dari unit
                    <strong>OK ELEKTIF</strong> dengan status <strong class="text-danger">Belum Terdaftar</strong>.
                    Harap segera diproses.
                </div>
            </div>
            <?php endif; ?>
            <?php if ($count_ok_emg > 0 || $count_ok_elkt > 0): ?>
            <style>
            @keyframes pulse-danger {
                0%   { transform: scale(1);   opacity: 1; }
                50%  { transform: scale(1.2); opacity: 0.7; }
                100% { transform: scale(1);   opacity: 1; }
            }
            .icon-pulse-danger { animation: pulse-danger 1.1s ease-in-out infinite; display: inline-block; }
            </style>
            <?php endif; ?>
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
                            $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            ?>
                            <?php foreach ($data as $row) : ?>
                                <?php
                                $timestamp = !empty($row['tanggal']) ? strtotime($row['tanggal']) : null;
                                $jam = $timestamp ? date('H:i:s', $timestamp) : '--:--:--';
                                $tgl = $timestamp ? date('d-m-Y', $timestamp) : '';
                                $hari = $timestamp ? $hariIndo[date('w', $timestamp)] : '';
                                if (!empty($row['norm'])) {
                                    $uniquePatients[$row['norm']] = true;
                                }
                                $tagihan = !empty($row['tagihan']) ? (float)$row['tagihan'] : 0;
                                $totalHarga += $tagihan;
                                // Tentukan class baris berdasarkan status
                                $rowClass = ($row['status'] === 'Belum Terdaftar') ? 'table-danger' : '';
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
                                    <td><span
                                            class="copy-norm"
                                            data-text="<?= esc($row['norm']); ?>"
                                            style="cursor:pointer; color:black;">
                                            <?= esc($row['norm']); ?>
                                        </span></td>
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
                                            <strong class="text-danger"><?= esc($row['status']) ?></strong><br>
                                            <button
                                                class="btn btn-success btn-xs mt-1 btn-tandai"
                                                style="font-size:0.75rem;"
                                                data-id="<?= $row['id'] ?>"
                                                data-nama="<?= esc($row['nama'] ?? '') ?>">
                                                <i class="fas fa-check"></i> Tandai Terdaftar
                                            </button>
                                        <?php elseif ($row['status'] === 'Terdaftar'): ?>
                                            <strong class="text-success"><?= esc($row['status']) ?></strong>
                                        <?php else: ?>
                                            <strong><?= esc($row['status'] ?? '-') ?></strong>
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

<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-tandai');
    if (!btn) return;

    const id   = btn.dataset.id;
    const nama = btn.dataset.nama;

    if (!confirm('Tandai pasien "' + nama + '" sebagai Terdaftar?')) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    fetch(`<?= base_url('api/kunjungan/update-status') ?>/${id}`, {
        method : 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const td  = btn.closest('td');
            const tr  = btn.closest('tr');
            td.innerHTML = '<strong class="text-success">Terdaftar</strong>';
            tr.classList.remove('table-danger');
            // update warna jam di kolom tanggal
            const tdTgl = tr.querySelector('td:nth-child(2) span');
            if (tdTgl) tdTgl.classList.replace('text-danger', 'text-success');
        } else {
            alert('Gagal: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Tandai Terdaftar';
        }
    })
    .catch(() => {
        alert('Terjadi kesalahan jaringan.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Tandai Terdaftar';
    });
});
</script>

<?= $this->include('templates/dashboard/footer_dashboard') ?>