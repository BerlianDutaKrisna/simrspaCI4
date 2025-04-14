<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Laporan</h6>
    </div>

    <div class="card-body">
        <h1 class="mb-4">Buku Laporan Pasien</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">
            <i class="fas fa-reply"></i> Kembali
        </a>

        <form method="GET" action="<?= base_url('patient/filter'); ?>" class="mb-4">
            <div class="form-group">
                <input type="text" name="keyword" class="form-control" placeholder="Cari pasien (norm, nama, alamat...)" value="<?= esc($keyword) ?>">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                    <select name="jenis_kelamin_pasien" class="form-control">
                        <option value="">-- Semua Jenis Kelamin --</option>
                        <option value="L" <?= $jenis_kelamin_pasien == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $jenis_kelamin_pasien == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="status_pasien">Status Pasien:</label>
                    <select name="status_pasien" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="PBI" <?= $status_pasien == 'PBI' ? 'selected' : '' ?>>PBI</option>
                        <option value="Non PBI" <?= $status_pasien == 'Non PBI' ? 'selected' : '' ?>>Non PBI</option>
                        <option value="Umum" <?= $status_pasien == 'Umum' ? 'selected' : '' ?>>Umum</option>
                    </select>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>

        <!-- Total data info -->
        <div class="mb-3">
            <strong>Total Data Pasien:</strong> <?= count($patientData) ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTableButtons" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NORM</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patientData)) : ?>
                        <?php $i = 1; foreach ($patientData as $row) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($row['norm_pasien']) ?></td>
                                <td><?= esc($row['nama_pasien']) ?></td>
                                <td><?= esc($row['alamat_pasien']) ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal_lahir_pasien'])) ?></td>
                                <td><?= esc($row['jenis_kelamin_pasien']) ?></td>
                                <td><?= esc($row['status_pasien']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">Tidak ada data yang tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
