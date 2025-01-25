<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pemprosesan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pemprosesan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pemprosesan/proses_pemprosesan'); ?>" method="POST">
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
                            <th>Status Pemprosesan</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai Pemprosesan</th>
                            <th>Selesai Pemprosesan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pemprosesanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pemprosesanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pemprosesan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pemprosesan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pemprosesan' => $row['status_pemprosesan'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pemprosesan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pemprosesan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pemprosesan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pemprosesan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pemprosesan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Belum ada sampel untuk pemprosesan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>