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
                                                <!-- Tombol Hapus -->
                                                <button class="btn btn-sm btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-id_penerimaan="<?= htmlspecialchars($row['id_penerimaan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Hapus penerimaan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pengirisan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <!-- Tombol Lihat -->
                                                <button class="btn btn-sm btn-warning view-pengirisan"
                                                    data-id_pengirisan="<?= htmlspecialchars($row['id_pengirisan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    aria-label="Lihat pengirisan">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <button class="btn btn-sm btn-danger delete-pengirisan"
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
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_penerimaan'] ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <a href="<?= base_url('exam/edit_hpa/' . esc($row['id_hpa'])) ?>" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModalAll"
                                                data-id="<?= esc($row['id_hpa']) ?>" data-kode_hpa="<?= esc($row['kode_hpa']) ?>">
                                                Hapus
                                            </a>
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

<!-- Modal untuk menampilkan detail -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Penerimaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalBody">
                    <!-- Data akan dimuat di sini melalui AJAX -->
                </div>
            </div>
            <div class="modal-footer" id="modalFooter">
                <!-- Tombol akan ditambahkan di sini -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus Proses ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>




<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>

<!-- JavaScript untuk modal tetap sama -->
<script>
    $(document).ready(function() {
        // ==========================
        // Fungsi Format Tanggal & Waktu
        // ==========================
        function formatDateTime(dateString) {
            if (!dateString || isNaN(Date.parse(dateString))) return "-"; // Validasi input
            var date = new Date(dateString);

            // Format waktu (HH:mm)
            var hours = date.getHours().toString().padStart(2, "0");
            var minutes = date.getMinutes().toString().padStart(2, "0");
            var time = hours + ":" + minutes;

            // Format tanggal (DD-MM-YYYY)
            var day = date.getDate().toString().padStart(2, "0");
            var month = (date.getMonth() + 1).toString().padStart(2, "0");
            var year = date.getFullYear();

            return time + ", " + day + "-" + month + "-" + year;
        }

        // ==========================
        // Modal Status HPA
        // ==========================
        $("#statusHpaModal").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);
            var id_hpa = button.data("id_hpa");
            var status_hpa = button.data("status_hpa");

            var modal = $(this);
            modal.find("#id_hpa").val(id_hpa);
            modal.find("#status_hpa").val(status_hpa);
        });

        // ==========================
        // Detail Penerimaan
        // ==========================
        $(document).on("click", ".view-penerimaan", function() {
            var id_penerimaan = $(this).data("id_penerimaan");

            // Lakukan aksi seperti AJAX di sini
            console.log("ID Penerimaan:", id_penerimaan);

            $.ajax({
                url: "<?= base_url('penerimaan/penerimaan_details'); ?>",
                type: "GET",
                data: {
                    id_penerimaan: encodeURIComponent(id_penerimaan)
                },
                success: function(response) {
                    if (response.error) {
                        $("#modalBody").html(`<p>${response.error}</p>`);
                        $("#modalFooter").html(
                            '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>'
                        );
                    } else {
                        var detailHtml = `
                            <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                            <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_penerimaan || "-"}</p>
                            <p><strong>Status Penerimaan:</strong> ${response.status_penerimaan || "-"}</p>
                            <p><strong>Mulai Penerimaan:</strong> ${formatDateTime(response.mulai_penerimaan)}</p>
                            <p><strong>Selesai Penerimaan:</strong> ${formatDateTime(response.selesai_penerimaan)}</p>
                            <p><strong>Volume Fiksasi:</strong> ${response.indikator_1 || "-"}</p>
                            <p><strong>Terfiksasi Merata:</strong> ${response.indikator_2 || "-"}</p>
                        `;
                        $("#modalBody").html(detailHtml);

                        var footerHtml = `
                            <a href="<?= base_url('penerimaan/edit'); ?>?id_penerimaan=${encodeURIComponent(
                            id_penerimaan
                        )}" class="btn btn-warning">Edit</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        `;
                        $("#modalFooter").html(footerHtml);
                    }
                    $("#viewModal").modal("show");
                },
                error: function() {
                    $("#modalBody").html('<p>Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>');
                    $("#modalFooter").html(
                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>'
                    );
                },
            });
        });

        // ==========================
        // Detail Pengirisan
        // ==========================
        $(document).on("click", ".view-pengirisan", function() {
            var id_pengirisan = $(this).data("id_pengirisan");

            $.ajax({
                url: "<?= base_url('pengirisan/pengirisan_details'); ?>",
                type: "GET",
                data: {
                    id_pengirisan: encodeURIComponent(id_pengirisan)
                },
                success: function(response) {
                    if (response.error) {
                        $("#modalBody").html(`<p>${response.error}</p>`);
                        $("#modalFooter").html(
                            '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>'
                        );
                    } else {
                        var detailHtml = `
                            <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                            <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pengirisan || "-"}</p>
                            <p><strong>Status Pengirisan:</strong> ${response.status_pengirisan || "-"}</p>
                            <p><strong>Mulai Pengirisan:</strong> ${formatDateTime(response.mulai_pengirisan)}</p>
                            <p><strong>Selesai Pengirisan:</strong> ${formatDateTime(response.selesai_pengirisan)}</p>
                        `;
                        $("#modalBody").html(detailHtml);

                        var footerHtml = `
                            <a href="<?= base_url('pengirisan/edit'); ?>?id_pengirisan=${encodeURIComponent(
                            id_pengirisan
                        )}" class="btn btn-warning">Edit</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        `;
                        $("#modalFooter").html(footerHtml);
                    }
                    $("#viewModal").modal("show");
                },
                error: function() {
                    $("#modalBody").html('<p>Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>');
                    $("#modalFooter").html(
                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>'
                    );
                },
            });
        });
        // Hapus Pengirisan
        $(document).on("click", ".delete-pengirisan", function() {
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_hpa = $(this).data("id_hpa");

            console.log("ID pengirisan:", id_pengirisan);

            // Menyimpan data ID yang dibutuhkan untuk operasi delete
            $("#confirmDelete").data("id_pengirisan", id_pengirisan);
            $("#confirmDelete").data("id_hpa", id_hpa);

            // Menampilkan modal konfirmasi
            $('#deleteModal').modal('show');
        });

        // Menangani klik konfirmasi hapus pada modal
        $("#confirmDelete").on("click", function() {
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_hpa = $(this).data("id_hpa");

            // Mengirimkan permintaan AJAX untuk menghapus data
            $.ajax({
                url: "<?= base_url('pengirisan/delete'); ?>",
                type: "POST",
                data: {
                    id_pengirisan: id_pengirisan,
                    id_hpa: id_hpa
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Refresh halaman setelah operasi
                    } else {
                        alert("Gagal menghapus data.");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan pada server.");
                }
            });
            // Menutup modal setelah konfirmasi hapus
            $('#deleteModal').modal('hide');
        });

    });
</script>