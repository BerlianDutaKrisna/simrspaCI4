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
        <form id="mainForm" action="<?= base_url('pembacaan_hpa/proses_pembacaan'); ?>" method="POST">
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
                        <?php if (!empty($pembacaanDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pembacaanDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pembacaan_hpa']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pembacaan_hpa']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu_hpa']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pembacaan_hpa' => $row['status_pembacaan_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_pembacaan_hpa'] === "Proses Pembacaan"): ?>
                                            <input type="hidden" name="total_nilai_mutu_hpa" value="<?= $row['total_nilai_mutu_hpa']; ?>">
                                            <!-- Menampilkan form checkbox ketika status pembacaan adalah 'Proses pembacaan' -->
                                            <div class="form-check">
                                                <input type="checkbox" id="checkAll_<?= $row['id_mutu_hpa']; ?>" class="form-check-input">
                                                <label class="form-check-label" for="checkAll_<?= $row['id_mutu_hpa']; ?>">
                                                    Pilih Semua
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_4"
                                                    value="10"
                                                    id="indikator_4_<?= $row['id_mutu_hpa']; ?>"
                                                    class="form-check-input child-checkbox">
                                                <label class="form-check-label" for="indikator_4_<?= $row['id_mutu_hpa']; ?>">
                                                    Sediaan tanpa lipatan
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_5"
                                                    value="10"
                                                    id="indikator_5_<?= $row['id_mutu_hpa']; ?>"
                                                    class="form-check-input child-checkbox">
                                                <label class="form-check-label" for="indikator_5_<?= $row['id_mutu_hpa']; ?>">
                                                    Sediaan tanpa goresan mata pisau
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_6"
                                                    value="10"
                                                    id="indikator_6_<?= $row['id_mutu_hpa']; ?>"
                                                    class="form-check-input child-checkbox">
                                                <label class="form-check-label" for="indikator_6_<?= $row['id_mutu_hpa']; ?>">
                                                    Kontras warna sediaan cukup jelas
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_7"
                                                    value="10"
                                                    id="indikator_7_<?= $row['id_mutu_hpa']; ?>"
                                                    class="form-check-input child-checkbox">
                                                <label class="form-check-label" for="indikator_7_<?= $row['id_mutu_hpa']; ?>">
                                                    Sediaan tanpa gelembung udara
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_8"
                                                    value="10"
                                                    id="indikator_8_<?= $row['id_mutu_hpa']; ?>"
                                                    class="form-check-input child-checkbox">
                                                <label class="form-check-label" for="indikator_8_<?= $row['id_mutu_hpa']; ?>">
                                                    Sediaan tanpa bercak / sidik jari
                                                </label>
                                            </div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const checkAll = document.getElementById('checkAll_<?= $row['id_mutu_hpa']; ?>');
                                                    const checkboxes = document.querySelectorAll('.child-checkbox');

                                                    // Ketika "Pilih Semua" dicentang/ditandai
                                                    checkAll.addEventListener('change', function() {
                                                        checkboxes.forEach(checkbox => {
                                                            checkbox.checked = checkAll.checked; // Semua checkbox mengikuti status "Pilih Semua"
                                                        });
                                                    });

                                                    // Update status "Pilih Semua" berdasarkan checkbox lainnya
                                                    checkboxes.forEach(checkbox => {
                                                        checkbox.addEventListener('change', function() {
                                                            checkAll.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                                                        });
                                                    });
                                                });
                                            </script>
                                        <?php else: ?>
                                            <!-- Menampilkan total_nilai_mutu_hpa jika status pembacaan 'Belum Diperiksa' atau 'Sudah Diperiksa' -->
                                            <?= $row['total_nilai_mutu_hpa']; ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['jumlah_slide']; ?></td>
                                    <td><?= $row['nama_user_dokter_pemotongan_hpa']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pembacaan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pembacaan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pembacaan_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pembacaan_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $tanggal_hasil = $row['tanggal_hasil'] ?? "";
                                        if ($tanggal_hasil === "") {
                                            echo '';
                                        } else {
                                            $tanggal = new DateTime($tanggal_hasil);
                                            $formatter = new IntlDateFormatter(
                                                'id_ID',
                                                IntlDateFormatter::FULL,
                                                IntlDateFormatter::NONE,
                                                'Asia/Jakarta',
                                                IntlDateFormatter::GREGORIAN,
                                                'EEEE, dd-MM-yyyy'
                                            );
                                            echo esc($formatter->format($tanggal));
                                        }
                                        ?>
                                    </td>
                                    <?php if (in_array($row['status_pembacaan_hpa'], ["Proses Pembacaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('hpa/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pen"></i> Detail
                                            </a>
                                        </td>
                                    <?php elseif (in_array($row['status_pembacaan_hpa'], ["Selesai Pembacaan"])): ?>
                                        <td>
                                            <a href="<?= base_url('hpa/edit_mikroskopis/' . esc($row['id_hpa'])) ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-pen"></i> Detail
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
                                <td colspan="12" class="text-center">Belum ada sampel untuk pembacaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>