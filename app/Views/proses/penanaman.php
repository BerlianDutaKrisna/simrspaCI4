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
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Penanaman</th>
                            <th>Aksi</th>
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
                                            class="form-control form-control-user"
                                            autocomplete="off">
                                    </td>
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
                                <td colspan="10" class="text-center">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tombol -->
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <button type="button" class="btn btn-danger btn-user btn-block" onclick="setAction('mulai')">
                        Mulai Penanaman
                    </button>
                </div>
                <div class="form-group col-12 col-md-6">
                    <button type="button" class="btn btn-success btn-user btn-block" onclick="setAction('selesai')">
                        <i class="fas fa-pause"></i> Selesai Penanaman
                    </button>
                </div>
                <div class="form-group col-12">
                    <button type="button" class="btn btn-info btn-user btn-block" onclick="setAction('lanjut')">
                        <i class="fas fa-step-forward"></i> Lanjutkan Menanam
                    </button>
                </div>
                <div class="form-group col-12">
                    <button type="button" class="btn btn-warning btn-user btn-block" onclick="setAction('kembalikan')">
                        <i class="fas fa-undo-alt"></i> Kembalikan Penanaman
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function setAction(action) {
        document.getElementById('action').value = action;
        document.getElementById('mainForm').submit(); // Kirim form setelah action diset
    }
</script>

<?= $this->include('templates/dashboard/footer_dashboard'); ?>