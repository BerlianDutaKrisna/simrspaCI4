<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('penerimaan/proses_penerimaan'); ?>" method="POST">
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Penerimaan</th>
                            <th>Aksi</th>
                            <th>Kualitas Sediaan</th>
                            <th>Analis</th>
                            <th>Mulai Penerimaan</th>
                            <th>Selesai Penerimaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penerimaanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_penerimaan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_penerimaan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user"
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_penerimaan'] === "Proses Pemeriksaan"): ?>
                                            <!-- Menampilkan form checkbox ketika status penerimaan adalah 'Proses Pemeriksaan' -->
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_1"
                                                    value="10"
                                                    id="indikator_1_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_1_<?= $row['id_mutu']; ?>">
                                                    Vol cairan fiksasi sesuai
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_2"
                                                    value="10"
                                                    id="indikator_2_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_2_<?= $row['id_mutu']; ?>">
                                                    Jaringan terfiksasi merata
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <!-- Menampilkan total_nilai_mutu jika status penerimaan 'Belum Diperiksa' atau 'Sudah Diperiksa' -->
                                            <?= isset($row['total_nilai_mutu']) && $row['total_nilai_mutu'] !== null ? $row['total_nilai_mutu'] . '%' : '0%' ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['nama_user_penerimaan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_penerimaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_penerimaan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penerimaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_penerimaan']))); ?>
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
                    <button type="button" class="btn btn-danger btn-user btn-block" onclick="setAction('mulai_penerimaan')">
                        Mulai Pengecekan
                    </button>
                </div>
                <div class="form-group col-12 col-md-6">
                    <button type="button" class="btn btn-success btn-user btn-block" onclick="setAction('selesai_penerimaan')">
                        <i class="fas fa-pause"></i> Selesai Pengecekan
                    </button>
                </div>
                <div class="form-group col-12">
                    <button type="button" class="btn btn-info btn-user btn-block" onclick="setAction('lanjut_mengiris')">
                        <i class="fas fa-step-forward"></i> Lanjutkan Mengiris
                    </button>
                </div>
                <div class="form-group col-12">
                    <button type="button" class="btn btn-warning btn-user btn-block" onclick="setAction('kembalikan_pengecekan')">
                        <i class="fas fa-undo-alt"></i> Kembalikan Pengecekan
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