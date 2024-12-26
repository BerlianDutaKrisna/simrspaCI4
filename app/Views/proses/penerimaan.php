<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penerimaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penerimaan</h1>
        <!-- Tombol Kembali ke Dashboard -->
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>
        <form action="<?= base_url('penerimaan/mulai_penerimaan'); ?>" method="POST">
            <!-- Tabel Responsif -->
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
                                        <input type="checkbox" name="id_proses[]" value="<?= $row['id_penerimaan']; ?>:<?= $row['id_hpa']; ?>" class="form-control form-control-user" autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_penerimaan'] === 'Proses Pemeriksaan'): ?>
                                            <div class="kualitas-sediaan-form">
                                                <input type="hidden" name="kualitas_sediaan" value="">
                                                <div class="form-group">
                                                    <label for="ks1">Volume cairan fiksasi sesuai ?</label>
                                                    <input type="checkbox" name="ks1" value="10" id="ks1">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ks2">Jaringan terfiksasi merata ?</label>
                                                    <input type="checkbox" name="ks2" value="10" id="ks2">
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div>
                                                Kualitas Sediaan %
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['nama_user_penerimaan']; ?></td>
                                    <td>
                                        <?php
                                        if (empty($row['mulai_penerimaan'])) {
                                            echo '-';
                                        } else {
                                            $formattedTime = esc(date('H:i', strtotime($row['mulai_penerimaan'])));
                                            $formattedDate = esc(date('d-m-Y', strtotime($row['mulai_penerimaan'])));
                                            echo "<span style='color: #28a745; font-weight: bold;'>{$formattedTime}</span> <span style='color: #007bff;'>| {$formattedDate}</span>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($row['selesai_penerimaan'])) {
                                            echo '-';
                                        } else {
                                            echo esc(date('H:i , d-m-Y', strtotime($row['selesai_penerimaan'])));
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($row['tanggal_hasil'])) {
                                            echo 'Belum diisi';
                                        } else {
                                            echo esc(date('d-m-Y', strtotime($row['tanggal_hasil'])));
                                        }
                                        ?>
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
            </div>

            <!-- Tombol Aksi Responsif -->
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <input type="submit" name="btn_proses_mulai" value="Mulai Pengecekan" class="btn btn-danger btn-user btn-block">
                </div>
                <div class="form-group col-12 col-md-6">
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