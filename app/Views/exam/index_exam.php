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
                                                <button class="btn btn-sm btn-warning" data-id_penerimaan="<?= $row['id_penerimaan'] ?>">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_penerimaan="<?= $row['id_penerimaan'] ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['id_pengirisan'])) : ?>
                                            <div class="d-flex justify-content-around">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id_hpa="<?= $row['id_hpa'] ?>">
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan detail penerimaan -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Penerimaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="penerimaanDetails">
                    <!-- Data akan dimuat di sini melalui AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal untuk konfirmasi penghapusan -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus Proses ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
            </div>
        </div>
    </div>
</div>



<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>

<!-- JavaScript untuk modal tetap sama -->
<script>
    // Menangani event click pada tombol status hpa untuk memunculkan modal
    $('#statusHpaModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var id_hpa = button.data('id_hpa'); // Kode HPA
        var status_hpa = button.data('status_hpa'); // Nama status hpa

        var modal = $(this);
        modal.find('#id_hpa').val(id_hpa); // Isi id_hpa ke input hidden
        modal.find('#status_hpa').val(status_hpa); // Isi status_hpa jika ada
    });

    $(document).ready(function() {
        // Menangani klik pada tombol .btn-warning dan .btn-danger
        $(".btn-warning, .btn-danger").on("click", function() {

            // Menangani klik pada tombol .btn-danger untuk menampilkan data di modal delete
            if ($(this).hasClass('btn-danger')) {
                var id_hpa = $(this).data("id_hpa");

                // Simpan data pada modal delete
                $('#deleteModal').data('id_hpa', id_hpa);
            }
            // Menangani klik pada tombol konfirmasi hapus
            $("#confirmDelete").on("click", function() {
                var id_hpa = $('#deleteModal').data('id_hpa');
                console.log(id_hpa);
                // Kirim permintaan delete menggunakan AJAX
                $.ajax({
                    url: "<?= base_url('penerimaan/delete'); ?>",
                    method: 'POST',
                    data: {
                        id_penerimaan: id_penerimaan
                    },
                    success: function(response) {
                        if (response.success) {
                            // Jika penghapusan berhasil, tutup modal dan refresh halaman
                            $('#deleteModal').modal('hide');
                            location.reload(); // Reload halaman untuk memperbarui tampilan
                        } else {
                            alert('Gagal menghapus data.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            });

        });

        // Menangani klik pada tombol .btn-warning untuk menampilkan detail penerimaan
        $(".btn-warning").on("click", function() {
            var id_penerimaan = $(this).data("id_penerimaan");

            // Mengambil detail penerimaan menggunakan AJAX
            $.ajax({
                url: "<?= base_url('penerimaan/getPenerimaanDetails'); ?>", // URL untuk mengambil detail penerimaan
                type: "GET",
                data: {
                    id_penerimaan: id_penerimaan
                },
                success: function(response) {
                    if (response.error) {
                        // Menampilkan error jika data tidak ditemukan
                        $('#penerimaanDetails').html('<p>' + response.error + '</p>');
                    } else {
                        // Fungsi untuk memformat tanggal dan waktu
                        function formatDateTime(dateString) {
                            var date = new Date(dateString);

                            // Format waktu (jam:menit)
                            var hours = date.getHours().toString().padStart(2, '0'); // Menambahkan leading zero
                            var minutes = date.getMinutes().toString().padStart(2, '0'); // Menambahkan leading zero
                            var time = hours + ':' + minutes;

                            // Format tanggal (tanggal-bulan-tahun)
                            var day = date.getDate().toString().padStart(2, '0'); // Menambahkan leading zero
                            var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Menambahkan leading zero
                            var year = date.getFullYear();

                            var dateFormatted = day + '-' + month + '-' + year;

                            // Gabungkan waktu dan tanggal
                            return time + ', ' + dateFormatted;
                        }

                        // Menampilkan detail penerimaan di modal
                        var detailHtml = '<p><strong>Kode HPA:</strong> ' + response.kode_hpa + '</p>';
                        detailHtml += '<p><strong>Dikerjakan Oleh:</strong> ' + response.nama_user_penerimaan + '</p>';
                        detailHtml += '<p><strong>Status Penerimaan:</strong> ' + response.status_penerimaan + '</p>';

                        // Format dan tampilkan data
                        detailHtml += '<p><strong>Mulai Penerimaan:</strong> ' + formatDateTime(response.mulai_penerimaan) + '</p>';
                        detailHtml += '<p><strong>Selesai Penerimaan:</strong> ' + formatDateTime(response.selesai_penerimaan) + '</p>';
                        detailHtml += '<p><strong>Volume Fiksasi:</strong> ' + response.indikator_1 + '</p>';
                        detailHtml += '<p><strong>Terfiksasi Merata:</strong> ' + response.indikator_2 + '</p>';

                        // Menampilkan data penerimaan di modal
                        $('#penerimaanDetails').html(detailHtml);
                    }
                    $('#viewModal').modal('show'); // Tampilkan modal
                },
                error: function() {
                    alert("Terjadi kesalahan saat mengambil data.");
                }
            });
        });
    });
</script>