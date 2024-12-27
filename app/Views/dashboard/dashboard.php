<?= $this->include('templates/dashboard/header_dashboard'); ?> <!-- Menyertakan header dashboard -->
<?= $this->include('templates/dashboard/navbar_dashboard'); ?> <!-- Menyertakan navbar dashboard -->
<?= $this->include('dashboard/jumlah_sampel_belum_selesai'); ?> <!-- Menyertakan jumlah sampel yang belum selesai -->
<?= $this->include('dashboard/pencarian_pemeriksaan'); ?> <!-- Menyertakan bagian pencarian pemeriksaan -->
<?= $this->include('dashboard/tambah_pasien'); ?> <!-- Menyertakan tombol untuk menambah pasien -->
<?= $this->include('dashboard/jenis_pemeriksaan'); ?> <!-- Menyertakan jenis pemeriksaan -->

<div class="card shadow mb-4"> <!-- Card untuk menampilkan informasi -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table live proses</h6> <!-- Judul tabel -->
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode HPA</th>
                        <th>Nama Pasien</th>
                        <th>Norm Pasien</th>
                        <th>Diagnosa</th>
                        <th>Tindakan Spesimen</th>
                        <th>Status Hpa</th>
                        <th>Status Proses</th> <!-- Kolom Status Proses -->
                        <th>Nama User</th>
                        <th>Mulai Pengerjaan</th>
                        <th>Selesai Pengerjaan</th>
                        <th>Tanggal Hasil</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($hpaData)) : ?>
                        <?php foreach ($hpaData as $row) : ?>
                            <tr>
                                <td><?= esc($row['kode_hpa']); ?></td>
                                <td><?= esc($row['nama_pasien']); ?></td>
                                <td><?= esc($row['norm_pasien']); ?></td>
                                <td><?= esc($row['diagnosa_klinik']); ?></td>
                                <td><?= esc($row['tindakan_spesimen']); ?></td>
                                <td><?= esc($row['status_hpa']); ?></td>
                                <td>
                                    <?php
                                    // Tentukan status proses berdasarkan status hpa
                                    switch ($row['status_hpa']) {
                                        case 'Penerimaan':
                                            echo esc($row['status_penerimaan']); // Menampilkan status_penerimaan jika status_hpa Penerimaan
                                            break;
                                        case 'Pengirisan':
                                            echo esc($row['status_pengirisan']); // Menampilkan status_pengirisan jika status_hpa Pengirisan
                                            break;
                                        default:
                                            echo '-'; // Menampilkan tanda "-" jika status_hpa tidak sesuai
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    switch ($row['status_hpa']) {
                                        case 'Penerimaan':
                                            echo esc($row['nama_user_penerimaan']);
                                            break;
                                        case 'Pengirisan':
                                            echo esc($row['nama_user_pengirisan']);
                                            break;
                                        case 'Pemotongan':
                                            echo esc($row['nama_user_pemotongan']);
                                            break;
                                        default:
                                            echo '-';
                                            break;
                                    }
                                    ?>
                                </td>
                                <!-- Mulai Pengerjaan -->
                                <td>
                                    <?php
                                    $startTimeField = '';
                                    switch ($row['status_hpa']) {
                                        case 'Penerimaan':
                                            $startTimeField = 'mulai_penerimaan';
                                            break;
                                        case 'Pengirisan':
                                            $startTimeField = 'mulai_pengirisan';
                                            break;
                                        case 'Pemotongan':
                                            $startTimeField = 'mulai_pemotongan';
                                            break;
                                        default:
                                            $startTimeField = 'waktu_pengerjaan';
                                            break;
                                    }

                                    echo empty($row[$startTimeField]) ? '-' : esc(date('H:i, d-m-Y', strtotime($row[$startTimeField])));
                                    ?>
                                </td>
                                <!-- Selesai Pengerjaan -->
                                <td>
                                    <?php
                                    $endTimeField = '';
                                    switch ($row['status_hpa']) {
                                        case 'Penerimaan':
                                            $endTimeField = 'selesai_penerimaan';
                                            break;
                                        case 'Pengirisan':
                                            $endTimeField = 'selesai_pengirisan';
                                            break;
                                        case 'Pemotongan':
                                            $endTimeField = 'selesai_pemotongan';
                                            break;
                                        default:
                                            $endTimeField = 'selesai_pengerjaan';
                                            break;
                                    }

                                    echo empty($row[$endTimeField]) ? '-' : esc(date('H:i, d-m-Y', strtotime($row[$endTimeField])));
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
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data yang tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('dashboard/grafik_pemeriksaan'); ?> <!-- Menyertakan grafik pemeriksaan -->
<?= $this->include('templates/notifikasi'); ?> <!-- Menyertakan notifikasi -->
<?= $this->include('templates/dashboard/footer_dashboard'); ?> <!-- Menyertakan footer dashboard -->