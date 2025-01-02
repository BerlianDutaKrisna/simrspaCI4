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
                                                <!-- Tombol Lihat -->
                                                <button class="btn btn-sm btn-warning view-penerimaan"
                                                    data-id_penerimaan="<?= htmlspecialchars($row['id_penerimaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat penerimaan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pengirisan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol Lihat -->
                                                <button class="btn btn-sm btn-warning mx-1 view-pengirisan"
                                                    data-id_pengirisan="<?= htmlspecialchars($row['id_pengirisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pengirisan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <button class="btn btn-sm btn-danger mx-1 delete-pengirisan"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_pengirisan="<?= htmlspecialchars($row['id_pengirisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Hapus pengirisan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemotongan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemprosesan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_penanaman'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemotongan_tipis'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pewarnaan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pembacaan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_penulisan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pemverifikasi'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pencetakan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_mutu'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning mx-1">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger mx-1" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <!-- Tombol Edit -->
                                            <a href="#" class="btn btn-sm btn-warning mx-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-sm btn-danger mx-1 delete-hpa"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id_hpa="<?= htmlspecialchars($row['id_hpa'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-kode_hpa="<?= htmlspecialchars($row['kode_hpa'], ENT_QUOTES, 'UTF-8') ?>"
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

<!-- Modal Status Hpa -->
<div class="modal fade" id="statusHpaModal" tabindex="-1" role="dialog" aria-labelledby="statusHpaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusHpaModalLabel">Edit Status Hpa</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('exam/update_status_hpa') ?>/<?= esc($row['id_hpa']) ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_hpa" name="id_hpa" value="">
                    <div class="form-group">
                        <label for="status_hpa">Status Hpa</label>
                        <select class="form-control" id="status_hpa" name="status_hpa">
                            <option value="Belum Diproses" <?= old('status_hpa', esc($row['status_hpa'])) == 'Belum Diproses' ? 'selected' : '' ?>>Belum Diproses</option>
                            <option value="Terdaftar" <?= old('status_hpa', esc($row['status_hpa'])) == 'Terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                            <option value="Penerimaan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Penerimaan' ? 'selected' : '' ?>>Penerimaan</option>
                            <option value="Pengirisan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pengirisan' ? 'selected' : '' ?>>Pengirisan</option>
                            <option value="Pemotongan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemotongan' ? 'selected' : '' ?>>Pemotongan</option>
                            <option value="Pemprosesan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemprosesan' ? 'selected' : '' ?>>Pemprosesan</option>
                            <option value="Penanaman" <?= old('status_hpa', esc($row['status_hpa'])) == 'Penanaman' ? 'selected' : '' ?>>Penanaman</option>
                            <option value="Pemotongan Tipis" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemotongan Tipis' ? 'selected' : '' ?>>Terdaftar</option>
                            <option value="Pewarnaan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pewarnaan' ? 'selected' : '' ?>>Pewarnaan</option>
                            <option value="Pembacaan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pembacaan' ? 'selected' : '' ?>>Pembacaan</option>
                            <option value="Penulisan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Penulisan' ? 'selected' : '' ?>>Penulisan</option>
                            <option value="Pemverifikasi" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pemverifikasi' ? 'selected' : '' ?>>Pemverifikasi</option>
                            <option value="Pencetakan" <?= old('status_hpa', esc($row['status_hpa'])) == 'Pencetakan' ? 'selected' : '' ?>>Pencetakan</option>
                            <option value="Selesai Diproses" <?= old('status_hpa', esc($row['status_hpa'])) == 'Selesai Diproses' ? 'selected' : '' ?>>Selesai Diproses</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/exam/modal_index_exam') ?>
<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/exam/script_index_exam') ?>
