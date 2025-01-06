<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pemverifikasi</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pemverifikasi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pemverifikasi/proses_pemverifikasi'); ?>" method="POST">
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
                            <th>Status Pemverifikasi</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai Pemverifikasi</th>
                            <th>Selesai Pemverifikasi</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pemverifikasiData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pemverifikasiData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pemverifikasi']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pemverifikasi']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user"
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pemverifikasi']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pemverifikasi']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pemverifikasi']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pemverifikasi']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pemverifikasi']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                    <?php if (in_array($row['status_pemverifikasi'], ["Proses Pemverifikasi"])): ?>
                                        <td>
                                            <?php if (session()->get('update_success')): ?>
                                                <a href="<?= base_url('exam/edit_exam/' . esc($row['id_hpa'])) ?>" class="btn btn-success btn-sm">
                                                    <i class="fas fa-pen"></i> Detail
                                                </a>
                                                <?php session()->remove('update_success'); // Menghapus session setelah ditampilkan 
                                                ?>
                                            <?php else: ?>
                                                <a href="<?= base_url('exam/edit_exam/' . esc($row['id_hpa'])) ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-pen"></i> Detail
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php elseif (in_array($row['status_pemverifikasi'], ["Selesai Pemverifikasi"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/print_exam/' . esc($row['id_hpa'])) ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-print"></i> Print
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk pemverifikasi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>