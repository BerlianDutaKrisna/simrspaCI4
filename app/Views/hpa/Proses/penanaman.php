<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Penanaman</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Penanaman Histopatologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('penanaman_hpa/proses_penanaman'); ?>" method="POST">
            <?= csrf_field(); ?>
            <!-- Input Hidden -->
            <input type="hidden" name="action" id="action" value="">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="align-middle text-center">
                                <div class="custom-control custom-checkbox d-inline-block">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll">Aksi Check All</label>
                                </div>
                            </th>
                            <script>
                                document.getElementById('checkAll').addEventListener('change', function() {
                                    let checkboxes = document.querySelectorAll('.checkbox-item');
                                    checkboxes.forEach(cb => {
                                        cb.checked = this.checked;

                                        // Paksa trigger event 'change' agar logika lain ikut jalan (misal toggleButtons)
                                        cb.dispatchEvent(new Event('change'));

                                        // Opsional: akses data-status jika dibutuhkan langsung
                                        if (this.checked && cb.dataset.status) {
                                            let statusObj = JSON.parse(cb.dataset.status);
                                            console.log(statusObj); // Untuk debugging
                                            // Kamu bisa push ke array atau proses data di sini
                                        }
                                    });
                                });
                            </script>
                            <th>Kualitas Sediaan</th>
                            <th>Kode HPA</th>
                            <th>Nama Pasien</th>
                            <th>Status Penanaman</th>
                            <th>Jumlah Slide</th>
                            <th>Analis</th>
                            <th>Mulai Penanaman</th>
                            <th>Selesai Penanaman</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penanamanDatahpa)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($penanamanDatahpa as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_penanaman_hpa']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu_hpa']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_penanaman_hpa' => $row['status_penanaman_hpa'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <?php if ($row['status_penanaman_hpa'] === "Proses Penanaman"): ?>
                                            <input type="hidden" name="total_nilai_mutu_hpa" value="<?= $row['total_nilai_mutu_hpa']; ?>">
                                            <!-- Menampilkan form checkbox ketika status penanaman_hpa adalah 'Proses Pemeriksaan' -->
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="indikator_3"
                                                    value="10"
                                                    id="indikator_3_<?= $row['id_mutu_hpa']; ?>"
                                                    class="form-check-input">
                                                <label class="form-check-label" for="indikator_3_<?= $row['id_mutu_hpa']; ?>">
                                                    Blok parafin tidak ada fragmentasi?
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <!-- Menampilkan total_nilai_mutu_hpa jika status penanaman_hpa 'Belum Diperiksa' atau 'Sudah Diperiksa' -->
                                            <?= $row['total_nilai_mutu_hpa']; ?> %
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_penanaman_hpa']; ?></td>
                                    <td><?= $row['jumlah_slide']; ?></td>
                                    <td><?= $row['nama_user_penanaman_hpa']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_penanaman_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_penanaman_hpa']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_penanaman_hpa']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_penanaman_hpa']))); ?>
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
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Belum ada sampel untuk penanaman</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>