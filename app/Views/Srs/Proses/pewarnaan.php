<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pewarnaan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pewarnaan Sitologi</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>
        <?= $this->include('templates/proses/button_pewarnaan'); ?>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pewarnaan_srs/proses_pewarnaan'); ?>" method="POST">
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
                                    <label class="custom-control-label" for="checkAll">Aksi Check Semua</label>
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
                            <th>Kode SRS</th>
                            <th>Nama Pasien</th>
                            <th>jumlah slide</th>
                            <th>Aksi Cetak Stiker</th>
                            <th>Status Pewarnaan</th>
                            <th>User</th>
                            <th>Mulai Pewarnaan</th>
                            <th>Selesai Pewarnaan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pewarnaanDatasrs)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pewarnaanDatasrs as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pewarnaan_srs']; ?>:<?= $row['id_srs']; ?>:<?= $row['id_mutu_srs']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pewarnaan_srs' => $row['status_pewarnaan_srs'] ?? ""
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['kode_srs']; ?></td>
                                    <td><b><?= esc($row['nama_pasien']); ?></b> (<?= esc($row['norm_pasien']); ?>)</td>
                                    <td>
                                        <input type="number"
                                            class="form-control form-control-sm jumlah-slide-input"
                                            data-id="<?= $row['id_srs']; ?>"
                                            value="<?= $row['jumlah_slide']; ?>"
                                            min="0" step="1" style="width:100px;">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info btn-sm btn-cetak-stiker"
                                            data-id="<?= esc($row['id_srs']); ?>"
                                            data-kode="<?= esc($row['kode_srs']); ?>">
                                            <i class="fas fa-print"></i> Cetak Stiker
                                        </button>
                                    </td>
                                    <td><?= $row['status_pewarnaan_srs']; ?></td>
                                    <td><?= $row['nama_user_pewarnaan_srs']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pewarnaan_srs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pewarnaan_srs']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pewarnaan_srs']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pewarnaan_srs']))); ?>
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk pewarnaan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/srs/cetak_stiker'); ?>
            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>

            <script>
                $(document).ready(function() {
                    $(".jumlah-slide-input").on("change", function() {
                        let input = $(this);
                        let id_srs = input.data("id");
                        let jumlah_slide = input.val();

                        $.ajax({
                            url: "<?= base_url('srs/update_jumlah_slide'); ?>",
                            type: "POST",
                            data: {
                                id_srs: id_srs,
                                jumlah_slide: jumlah_slide,
                                <?= csrf_token() ?>: "<?= csrf_hash() ?>" // CSRF protection
                            },
                            dataType: "json",
                            success: function(res) {
                                if (res.status === "success") {
                                    console.log("Jumlah slide berhasil diperbarui");

                                    // Update tombol cetak di baris yang sama
                                    let btnCetak = input.closest("tr").find(".btn-cetak-stiker");
                                    if (btnCetak.length) {
                                        btnCetak.data("slide", jumlah_slide);
                                    }
                                } else {
                                    alert("Gagal memperbarui jumlah slide!");
                                }
                            },
                            error: function(xhr, status, error) {
                                alert("Terjadi kesalahan: " + error);
                            }
                        });
                    });
                });
            </script>