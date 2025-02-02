<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="container-fluid">
    <!-- Card untuk tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemeriksaan</h6>
        </div>
        <div class="card-body">
            <h1>Daftar Pemeriksaan</h1>

            <!-- Tombol Kembali ke Dashboard -->
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

            <!-- Tabel Data Pemeriksaan -->
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Norm Pasien</th>
                            <th>Status Hpa</th>
                            <th>Penerimaan</th>
                            <th>Pengirisan</th>
                            <th>Pemotongan</th>
                            <th>Pemprosesan</th>
                            <th>Penanaman</th>
                            <th>Pemotongan Tipis</th>
                            <th>Pewarnaan</th>
                            <th>Pembacaan</th>
                            <th>Penulisan</th>
                            <th>Pemverifikasi</th>
                            <th>Authorized</th>
                            <th>Pencetakan</th>
                            <th>Mutu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($hpaData)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($hpaData as $row) : ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= esc($row['kode_hpa']) ?></td>
                                    <td><?= esc($row['nama_pasien']) ?></td>
                                    <td><?= esc($row['norm_pasien']) ?></td>
                                    <td>
                                        <a href="#"
                                            class="btn btn-info btn-sm"
                                            data-toggle="modal"
                                            data-target="#statusHpaModal"
                                            data-id_hpa="<?= esc($row['id_hpa']) ?>"
                                            data-status_hpa="<?= esc($row['status_hpa']) ?>">
                                            <?= esc($row['status_hpa']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_penerimaan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail Penerimaan -->
                                                <button class="btn btn-sm btn-warning view-penerimaan"
                                                    data-action="penerimaan"
                                                    data-id_penerimaan="<?= htmlspecialchars($row['id_penerimaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat Penerimaan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pengirisan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail Pengirisan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pengirisan"
                                                    data-action="pengirisan"
                                                    data-id_pengirisan="<?= htmlspecialchars($row['id_pengirisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat Pengirisan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus Pengirisan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pengirisan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pengirisan="<?= htmlspecialchars($row['id_pengirisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pengirisan"
                                                    aria-label="Hapus pengirisan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemotongan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pemotongan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pemotongan"
                                                    data-action="pemotongan"
                                                    data-id_pemotongan="<?= htmlspecialchars($row['id_pemotongan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pemotongan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pemotongan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pemotongan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pemotongan="<?= htmlspecialchars($row['id_pemotongan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pemotongan"
                                                    aria-label="Hapus pemotongan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemprosesan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pemprosesan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pemprosesan"
                                                    data-action="pemprosesan"
                                                    data-id_pemprosesan="<?= htmlspecialchars($row['id_pemprosesan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pemprosesan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pemprosesan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pemprosesan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pemprosesan="<?= htmlspecialchars($row['id_pemprosesan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pemprosesan"
                                                    aria-label="Hapus pemprosesan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_penanaman'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail penanaman -->
                                                <button class="btn btn-sm btn-warning mx-1 view-penanaman"
                                                    data-action="penanaman"
                                                    data-id_penanaman="<?= htmlspecialchars($row['id_penanaman'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat penanaman">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus penanaman -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-penanaman"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_penanaman="<?= htmlspecialchars($row['id_penanaman'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="penanaman"
                                                    aria-label="Hapus penanaman">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemotongan_tipis'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pemotongan_tipis -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pemotongan_tipis"
                                                    data-action="pemotongan_tipis"
                                                    data-id_pemotongan_tipis="<?= htmlspecialchars($row['id_pemotongan_tipis'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pemotongan_tipis">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pemotongan_tipis -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pemotongan_tipis"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pemotongan_tipis="<?= htmlspecialchars($row['id_pemotongan_tipis'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pemotongan_tipis"
                                                    aria-label="Hapus pemotongan_tipis">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pewarnaan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pewarnaan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pewarnaan"
                                                    data-action="pewarnaan"
                                                    data-id_pewarnaan="<?= htmlspecialchars($row['id_pewarnaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pewarnaan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pewarnaan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pewarnaan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pewarnaan="<?= htmlspecialchars($row['id_pewarnaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pewarnaan"
                                                    aria-label="Hapus pewarnaan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pembacaan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pembacaan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pembacaan"
                                                    data-action="pembacaan"
                                                    data-id_pembacaan="<?= htmlspecialchars($row['id_pembacaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pembacaan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pembacaan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pembacaan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pembacaan="<?= htmlspecialchars($row['id_pembacaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pembacaan"
                                                    aria-label="Hapus pembacaan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_penulisan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail penulisan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-penulisan"
                                                    data-action="penulisan"
                                                    data-id_penulisan="<?= htmlspecialchars($row['id_penulisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat penulisan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus penulisan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-penulisan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_penulisan="<?= htmlspecialchars($row['id_penulisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="penulisan"
                                                    aria-label="Hapus penulisan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemverifikasi'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pemverifikasi -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pemverifikasi"
                                                    data-action="pemverifikasi"
                                                    data-id_pemverifikasi="<?= htmlspecialchars($row['id_pemverifikasi'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pemverifikasi">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pemverifikasi -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pemverifikasi"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pemverifikasi="<?= htmlspecialchars($row['id_pemverifikasi'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pemverifikasi"
                                                    aria-label="Hapus pemverifikasi">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_autorized'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail autorized -->
                                                <button class="btn btn-sm btn-warning mx-1 view-autorized"
                                                    data-action="autorized"
                                                    data-id_autorized="<?= htmlspecialchars($row['id_autorized'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat autorized">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus autorized -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-autorized"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_autorized="<?= htmlspecialchars($row['id_autorized'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="autorized"
                                                    aria-label="Hapus autorized">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pencetakan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail pencetakan -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pencetakan"
                                                    data-action="pencetakan"
                                                    data-id_pencetakan="<?= htmlspecialchars($row['id_pencetakan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pencetakan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus pencetakan -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pencetakan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pencetakan="<?= htmlspecialchars($row['id_pencetakan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-action="pencetakan"
                                                    aria-label="Hapus pencetakan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_mutu'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol untuk Melihat Detail mutu -->
                                                <button class="btn btn-sm btn-warning mx-1 view-mutu"
                                                    data-action="mutu"
                                                    data-id_mutu="<?= htmlspecialchars($row['id_mutu'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat mutu">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <!-- Tombol Edit -->
                                            <a href="<?= base_url('exam/edit_exam/' . esc($row['id_hpa'])) ?>" class="btn btn-sm btn-warning mx-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <!-- Tombol Hapus HPA -->
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

<?= $this->include('templates/exam/modal_exam') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/exam/script_exam') ?>