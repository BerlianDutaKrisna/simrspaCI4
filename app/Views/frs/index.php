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
                            <th>Aksi</th>
                            <th>No. RM</th>
                            <th>Kode FRS</th>
                            <th>Nama Pasien</th>
                            <th>Status FRS</th>
                            <th>Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($frsData)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($frsData as $row) : ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <a href="<?= base_url('frs/edit/' . esc($row['id_frs'])) ?>" class="btn btn-sm btn-warning mx-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
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
                                    <td><?= esc($row['norm_pasien']) ?></td>
                                    <td><?= esc($row['kode_frs']) ?></td>
                                    <td><?= esc($row['nama_pasien']) ?></td>
                                    <td>
                                        <a href="#"
                                            class="btn btn-info btn-sm"
                                            data-toggle="modal"
                                            data-target="#statusfrsModal"
                                            data-id_frs="<?= esc($row['id_frs']) ?>"
                                            data-status_frs="<?= esc($row['status_frs']) ?>">
                                            <?= esc($row['status_frs']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            <?php
                                            $prosesList = [
                                                'mutu' => $row['id_mutu'] ?? null,
                                                'penerimaan' => $row['id_penerimaan'] ?? null,
                                                'pewarnaan' => $row['id_pewarnaan'] ?? null,
                                                'pembacaan' => $row['id_pembacaan'] ?? null,
                                                'penulisan' => $row['id_penulisan'] ?? null,
                                                'pemverifikasi' => $row['id_pemverifikasi'] ?? null,
                                                'authorized' => $row['id_authorized'] ?? null,
                                                'pencetakan' => $row['id_pencetakan'] ?? null,
                                            ];
                                            
                                            foreach ($prosesList as $nama => $id) :
                                                if ($id) :
                                                    $isPrimary = in_array($nama, ['mutu', 'penerimaan']);
                                                    $btnClass = $isPrimary ? 'btn-outline-primary' : 'btn-outline-warning';
                                            ?>
                                                    <div class="btn-group btn-group-sm mb-1">
                                                        <!-- Tombol Lihat Proses -->
                                                        <button type="button"
                                                            class="btn <?= $btnClass ?> btn-view-proses"
                                                            data-toggle="modal"
                                                            data-target="#viewModal"
                                                            data-id="<?= esc($id) ?>"
                                                            data-proses="<?= esc($nama) ?>">
                                                            <?= ucfirst($nama) ?>
                                                        </button>
                                                        <?php if (!$isPrimary) : ?>
                                                            <!-- Tombol Hapus -->
                                                            <button type="button"
                                                                class="btn btn-outline-danger delete-<?= $nama ?>"
                                                                data-toggle="modal"
                                                                data-target="#deleteModal"
                                                                data-id_<?= $nama ?>="<?= esc($id) ?>"
                                                                data-id_frs="<?= esc($row['id_frs']) ?>"
                                                                data-action="<?= esc($nama) ?>"
                                                                aria-label="Hapus <?= esc($nama) ?>">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                            <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Tidak ada data yang tersedia.</td>
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