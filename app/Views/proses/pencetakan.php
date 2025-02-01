<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pencetakan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pencetakan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pencetakan/proses_pencetakan'); ?>" method="POST">
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
                            <th>Status Pencetakan</th>
                            <th>Aksi</th>
                            <th>Admin</th>
                            <th>Mulai Pencetakan</th>
                            <th>Selesai Pencetakan</th>
                            <th>Deadline Hasil</th>
                            <th>Print</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pencetakanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pencetakanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pencetakan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pencetakan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pencetakan' => $row['status_pencetakan'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pencetakan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pencetakan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pencetakan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pencetakan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pencetakan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                    <?php if (in_array($row['status_pencetakan'], ["Proses Pencetakan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_print_hpa/' . esc($row['id_hpa']) . '?redirect=index_pencetakan') ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_pencetakan'], ["Selesai Pencetakan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_print_hpa/' . esc($row['id_hpa']) . '?redirect=index_pencetakan') ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-print"></i> Cetak Lagi
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Belum ada sampel untuk pencetakan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>