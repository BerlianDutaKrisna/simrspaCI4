<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penulisan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penulisan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('penulisan/proses_penulisan'); ?>" method="POST">
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Penulisan</th>
                            <th>Aksi</th>
                            <th>Admin</th>
                            <th>Mulai Penulisan</th>
                            <th>Selesai Penulisan</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penulisanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penulisanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_penulisan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_penulisan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user"
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_penulisan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_penulisan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_penulisan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penulisan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_penulisan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                    <?php if (in_array($row['status_penulisan'], ["Proses Penulisan", "Sudah Penulisan"])): ?>
                                        <td>
                                            <a href="#" class="btn btn-success btn-user btn-block">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td style="display:none;"></td>
                                    <?php endif; ?>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Belum ada sampel untuk penulisan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>