<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card-body">
    <div class="table-responsive">
        <form action="" method="post">
            <div class="m-3">
                <h3 class="text">Penerimaan</h3>
            </div>

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode HPA</th>
                        <th>Nama Pasien</th>
                        <th>Deadline Hasil</th>
                        <th>Aksi</th>
                        <th>Status Proses</th>
                        <th>Analis</th>
                        <th>Kualitas Sediaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($penerimaanData)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($penerimaanData as $row): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $row['kode_hpa']; ?></td>
                                <td><?= $row['nama_pasien']; ?></td> <!-- Tampilkan Nama Pasien -->
                                <td>
                                    <?php
                                    if (empty($row['tanggal_hasil'])) {
                                        echo 'Belum diisi';
                                    } else {
                                        echo esc(date('d-m-Y', strtotime($row['tanggal_hasil'])));
                                    }
                                    ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="aksi[<?= $row['id_hpa'] ?>]" id="aksi<?= $row['id_hpa'] ?>" class="form-control form-control-user" autocomplete="off">
                                </td>
                                <td><?= $row['status_hpa']; ?></td> <!-- Tampilkan Status Proses -->
                                <td><?= $row['nama_user_penerimaan']; ?></td>
                                <td>
                                    <!-- Custom Checkbox untuk Kualitas Sediaan -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="hidden" name="kualitas_sediaan" value="">
                                        <div>
                                            <label>Volume cairan fiksasi sesuai ?</label>
                                            <input type="checkbox" name="ks1" value="10">
                                        </div>
                                        <div>
                                            <label>Jaringan terfiksasi merata ?</label>
                                            <input type="checkbox" name="ks2" value="10">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Action Buttons -->
            <div class="row">
                <div class="form-group col-6">
                    <button type="submit" name="btn_proses_mulai" class="btn btn-danger btn-user btn-block">
                        <i class="fas fa-play"></i> Mulai Pengecekan
                    </button>
                </div>
                <div class="form-group col-6">
                    <button type="submit" name="btn_proses_selesai" class="btn btn-success btn-user btn-block">
                        <i class="fas fa-pause"></i> Selesai Pengecekan
                    </button>
                </div>
            </div>

            <div class="form-group col-12">
                <button type="submit" name="btn_proses_lanjut" class="btn btn-info btn-user btn-block">
                    <i class="fas fa-step-forward"></i> Lanjutkan Mengiris
                </button>
            </div>

            <div class="form-group col-12">
                <button type="submit" name="btn_proses_kembali" class="btn btn-warning btn-user btn-block">
                    <i class="fas fa-undo-alt"></i> Kembalikan Pengecekan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->include('templates/notifikasi'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>