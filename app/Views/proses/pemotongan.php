<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pemotongan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pemotongan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pemotongan/proses_pemotongan'); ?>" method="POST">
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Pemotongan</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai Pemotongan</th>
                            <th>Selesai Pemotongan</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pemotonganData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pemotonganData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pemotongan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pemotongan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user"
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pemotongan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pemotongan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pemotongan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pemotongan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pemotongan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                    <?php if ($row['status_pemotongan'] === "Proses Pemotongan"): ?>
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk pemotongan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <<?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>