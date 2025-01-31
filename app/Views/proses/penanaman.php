<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penanaman</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penanaman</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('penanaman/proses_penanaman'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Penanaman</th>
                            <th>Aksi</th>
                            <th>Kualitas Sediaan</th>
                            <th>Jumlah Slide</th>
                            <th>Analis</th>
                            <th>Mulai Penanaman</th>
                            <th>Selesai Penanaman</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penanamanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penanamanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_penanaman']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_penanaman']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penanaman' => $row['status_penanaman'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_penanaman'] === "Proses Penanaman"): ?>
                                            <input type="hidden" name="total_nilai_mutu" value="<?= $row['total_nilai_mutu']; ?>">
                                            <!-- Menampilkan form checkbox ketika status penanaman adalah 'Proses Pemeriksaan' -->
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_3"
                                                    value="10"
                                                    id="indikator_3_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_3_<?= $row['id_mutu']; ?>">
                                                    Blok parafin tidak ada fragmentasi
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <!-- Menampilkan total_nilai_mutu jika status penanaman 'Belum Diperiksa' atau 'Sudah Diperiksa' -->
                                            <?= $row['total_nilai_mutu']; ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['jumlah_slide']; ?></td>
                                    <td><?= $row['nama_user_penanaman']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_penanaman']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_penanaman']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penanaman']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_penanaman']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Belum ada sampel untuk penanaman</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>