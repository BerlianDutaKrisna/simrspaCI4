<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table Pengirisan</h6>
    </div>
    <div class="card-body">
        <h1>Daftar Pengirisan</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3">Kembali</a>

        <!-- Form -->
        <form id="mainForm" action="<?= base_url('pengirisan/proses_pengirisan'); ?>" method="POST">
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
                            <th>Status Pengirisan</th>
                            <th>Aksi</th>
                            <th>Analis</th>
                            <th>Mulai Pengirisan</th>
                            <th>Selesai Pengirisan</th>
                            <th>Deadline Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengirisanData)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($pengirisanData as $row): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['kode_hpa']; ?></td>
                                    <td><?= $row['nama_pasien']; ?></td>
                                    <td><?= $row['status_pengirisan']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            name="id_proses[]"
                                            value="<?= $row['id_pengirisan']; ?>:<?= $row['id_hpa']; ?>:<?= $row['id_mutu']; ?>"
                                            class="form-control form-control-user checkbox-item"
                                            data-status='<?= json_encode([
                                                                'status_pengirisan' => $row['status_pengirisan'] ?? "",
                                                            ]) ?>'
                                            autocomplete="off">
                                    </td>
                                    <td><?= $row['nama_user_pengirisan']; ?></td>
                                    <td>
                                        <?= empty($row['mulai_pengirisan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['mulai_pengirisan']))); ?>
                                    </td>
                                    <td>
                                        <?= empty($row['selesai_pengirisan']) ? '-' : esc(date('H:i , d-m-Y', strtotime($row['selesai_pengirisan']))); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($row['tanggal_hasil'])) {
                                            echo 'Belum diisi';
                                        } else {
                                            setlocale(LC_TIME, 'id_ID.utf8'); // Pastikan server mendukung lokal Indonesia
                                            $tanggal = new DateTime($row['tanggal_hasil']);
                                            $formatter = new IntlDateFormatter(
                                                'id_ID',
                                                IntlDateFormatter::FULL,
                                                IntlDateFormatter::NONE,
                                                'Asia/Jakarta',
                                                IntlDateFormatter::GREGORIAN,
                                                'EEEE, dd-MM-yyyy' // Format dengan nama hari
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
                                <td colspan="10" class="text-center">Belum ada sampel untuk pengirisan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <?= $this->include('templates/proses/button_proses'); ?>
            <?= $this->include('dashboard/jenis_tindakan'); ?>
            <?= $this->include('templates/notifikasi'); ?>
            <?= $this->include('templates/dashboard/footer_dashboard'); ?>