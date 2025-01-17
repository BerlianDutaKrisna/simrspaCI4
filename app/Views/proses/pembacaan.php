<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pembacaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pembacaan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pembacaan/proses_pembacaan'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Pembacaan</th>
                            <th>Aksi</th>
                            <th>Kualitas Sediaan</th>
                            <th>jumlah slide</th>
                            <th>Dokter</th>
                            <th>Mulai Pembacaan</th>
                            <th>Selesai Pembacaan</th>
                            <th>Deadline Hasil</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pembacaanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pembacaanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pembacaan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pembacaan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user"
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_pembacaan'] === "Proses Pembacaan"): ?>
                                            <input type="hidden" name="total_nilai_mutu" value="<?= $row['total_nilai_mutu']; ?>">
                                            <!-- Menampilkan form checkbox ketika status pembacaan adalah 'Proses pembacaan' -->
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_4"
                                                    value="10"
                                                    id="indikator_4_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_4_<?= $row['id_mutu']; ?>">
                                                    Sediaan tanpa lipatan
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_5"
                                                    value="10"
                                                    id="indikator_5_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_5_<?= $row['id_mutu']; ?>">
                                                    Sediaan tanpa goresan mata pisau
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_6"
                                                    value="10"
                                                    id="indikator_6_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_6_<?= $row['id_mutu']; ?>">
                                                    Kontras warna sediaan cukup jelas
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_7"
                                                    value="10"
                                                    id="indikator_7_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_7_<?= $row['id_mutu']; ?>">
                                                    Sediaan tanpa gelembung udara
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_8"
                                                    value="10"
                                                    id="indikator_8_<?= $row['id_mutu']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_8_<?= $row['id_mutu']; ?>">
                                                    Sediaan tanpa bercak / sidik jari
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <!-- Menampilkan total_nilai_mutu jika status pembacaan 'Belum Diperiksa' atau 'Sudah Diperiksa' -->
                                            <?= $row['total_nilai_mutu']; ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['jumlah_slide']; ?></td>
                                    <td><?= $row['nama_user_dokter_pemotongan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pembacaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pembacaan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pembacaan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pembacaan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['tanggal_hasil']) ? 'Belum diisi' : esc(date('d-m-Y', strtotime($row['tanggal_hasil']))); ?>
                                    </td>
                                    <?php if (in_array($row['status_pembacaan'], ["Proses Pembacaan"])): ?>
                                        <td>
                                            <?php if (session()->get('update_success')): ?>
                                                <a href="<?= base_url('exam/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-success btn-sm">
                                                    <i class="fas fa-pen"></i> Detail
                                                </a>
                                                <?php session()->remove('update_success'); // Menghapus session setelah ditampilkan 
                                                ?>
                                            <?php else: ?>
                                                <a href="<?= base_url('exam/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-warning btn-sm mx-1">
                                                    <i class="fas fa-pen"></i> Detail
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php elseif (in_array($row['status_pembacaan'], ["Selesai Pembacaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('exam/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-warning btn-sm mx-1">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                            <a href="<?= base_url('cetak/cetak_proses/' . esc($row['id_hpa'])) ?>" class="btn btn-info btn-sm mx-1">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Belum ada sampel untuk pembacaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>