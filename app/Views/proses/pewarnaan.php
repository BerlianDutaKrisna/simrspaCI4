<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pewarnaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pewarnaan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pewarnaan/proses_pewarnaan'); ?>" method="POST">
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
                            <th>Status Pewarnaan</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai Pewarnaan</th>
                            <th>Selesai Pewarnaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pewarnaanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pewarnaanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pewarnaan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pewarnaan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pewarnaan' => $row['status_pewarnaan'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pewarnaan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pewarnaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pewarnaan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pewarnaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pewarnaan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Belum ada sampel untuk pewarnaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>