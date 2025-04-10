<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemeriksaan</h6>
        </div>
        <div class="card-body">
            <h1>Daftar Pemeriksaan Histopatologi</h1>

            <!-- Tombol Kembali ke Dashboard -->
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

            <!-- Tabel Data Pemeriksaan -->
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. RM</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Aksi</th>
                            <th>Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($hpaData)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($hpaData as $row) : ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['norm_pasien']) ?></td>
                                    <td><?= esc($row['kode_hpa']) ?></td>
                                    <td><?= esc($row['nama_pasien']) ?></td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <a href="<?= base_url('hpa/edit/' . esc($row['id_hpa'])) ?>" class="btn btn-sm btn-warning mx-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger mx-1 delete-hpa"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-action="hpa"
                                                aria-label="Hapus hpa">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            <?php
                                            $prosesList = [
                                                'mutu' => $row['id_mutu'] ?? null,
                                                'penerimaan' => $row['id_penerimaan'] ?? null,
                                                'pemotongan' => $row['id_pemotongan'] ?? null,
                                                'pemprosesan' => $row['id_pemprosesan'] ?? null,
                                                'penanaman' => $row['id_penanaman'] ?? null,
                                                'pemotongan_tipis' => $row['id_pemotongan_tipis'] ?? null,
                                                'pewarnaan' => $row['id_pewarnaan'] ?? null,
                                                'pembacaan' => $row['id_pembacaan'] ?? null,
                                                'penulisan' => $row['id_penulisan'] ?? null,
                                                'verifikasi' => $row['id_pemverifikasi'] ?? null,
                                                'authorized' => $row['id_authorized'] ?? null,
                                                'cetak' => $row['id_pencetakan'] ?? null,
                                            ];

                                            foreach ($prosesList as $nama => $id) :
                                                if ($id) :
                                                    $isPrimary = in_array($nama, ['mutu', 'penerimaan']);
                                                    $btnClass = $isPrimary ? 'btn-outline-primary' : 'btn-outline-warning';
                                            ?>
                                                    <div class="btn-group btn-group-sm mx-1 mb-1">
                                                        <!-- Tombol Proses ke Modal -->
                                                        <button type="button"
                                                            class="btn <?= $btnClass ?> btn-view-proses"
                                                            data-toggle="modal"
                                                            data-target="#viewModal"
                                                            data-id="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
                                                            data-proses="<?= $nama ?>">
                                                            <?= ucfirst($nama) ?>
                                                        </button>
                                                        <?php if (!$isPrimary) : ?>
                                                            <!-- Tombol Delete -->
                                                            <button type="button"
                                                                class="btn btn-outline-danger delete-hpa"
                                                                data-toggle="modal"
                                                                data-target="#deleteModal"
                                                                data-id_hpa="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
                                                                data-action="<?= $nama ?>"
                                                                aria-label="Hapus <?= $nama ?>">
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

<?= $this->include('templates/hpa/modal') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/hpa/script') ?>