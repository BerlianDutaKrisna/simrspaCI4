<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemeriksaan</h6>
        </div>
        <div class="card-body">
            <h1>Daftar Pemeriksaan Fine Needle Aspiration Biopsy</h1>

            <!-- Tombol Kembali ke Dashboard -->
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

            <!-- Tabel Data Pemeriksaan -->
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Norm Pasien</th>
                            <th>Kode FRS</th>
                            <th>Nama Pasien</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($frsData)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($frsData as $row) : ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['norm_pasien']) ?></td>
                                    <td><?= esc($row['kode_frs']) ?></td>
                                    <td><?= esc($row['nama_pasien']) ?></td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <!-- Tombol Edit -->
                                            <a href="<?= base_url('frs/edit/' . esc($row['id_frs'])) ?>" class="btn btn-sm btn-warning mx-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <!-- Tombol Hapus FRS -->
                                            <button class="btn btn-sm btn-danger mx-1 delete-frs"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id_frs="<?= htmlspecialchars($row['id_frs'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-action="frs"
                                                aria-label="Hapus FRS">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="33">Tidak ada data yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/frs/modal') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/frs/script') ?>