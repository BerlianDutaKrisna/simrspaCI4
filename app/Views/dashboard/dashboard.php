<?= $this->include('templates/dashboard/header_dashboard'); ?> <!-- Menyertakan header dashboard -->
<?= $this->include('templates/dashboard/navbar_dashboard'); ?> <!-- Menyertakan navbar dashboard -->
<?= $this->include('dashboard/jumlah_sampel_belum_selesai'); ?> <!-- Menyertakan jumlah sampel yang belum selesai -->
<?= $this->include('dashboard/pencarian_pemeriksaan'); ?> <!-- Menyertakan bagian pencarian pemeriksaan -->
<?= $this->include('dashboard/tambah_pasien'); ?> <!-- Menyertakan tombol untuk menambah pasien -->
<?= $this->include('dashboard/jenis_tindakan'); ?>

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
                        <th>Status Proses</th>
                        <th>Dikerjakan Oleh</th>
                        <th>Mulai Pengerjaan</th>
                        <th>Selesai Pengerjaan</th>
                        <th>Deadline Hasil</th>
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
                                            echo esc($row['status_penerimaan']);
                                            break;
                                        case 'Pengirisan':
                                            echo esc($row['status_pengirisan']);
                                            break;
                                        case 'Pemotongan':
                                            echo esc($row['status_pemotongan']);
                                            break;
                                        case 'Pemprosesan':
                                            echo esc($row['status_pemprosesan']);
                                            break;
                                        case 'Penanaman':
                                            echo esc($row['status_penanaman']);
                                            break;
                                        case 'Pemotongan Tipis':
                                            echo esc($row['status_pemotongan_tipis']);
                                            break;
                                        case 'Pewarnaan':
                                            echo esc($row['status_pewarnaan']);
                                            break;
                                        case 'Pembacaan':
                                            echo esc($row['status_pembacaan']);
                                            break;
                                        case 'Penulisan':
                                            echo esc($row['status_penulisan']);
                                            break;
                                        case 'Pemverifikasi':
                                            echo esc($row['status_pemverifikasi']);
                                            break;
                                        case 'Pencetakan':
                                            echo esc($row['status_pencetakan']);
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
                                            echo !empty($row['nama_user_penerimaan']) ? esc($row['nama_user_penerimaan']) : '-';
                                            break;
                                        case 'Pengirisan':
                                            echo !empty($row['nama_user_pengirisan']) ? esc($row['nama_user_pengirisan']) : '-';
                                            break;
                                        case 'Pemotongan':
                                            echo !empty($row['nama_user_pemotongan']) ? esc($row['nama_user_pemotongan']) : '-';
                                            break;
                                        case 'Pemprosesan':
                                            echo !empty($row['nama_user_pemprosesan']) ? esc($row['nama_user_pemprosesan']) : '-';
                                            break;
                                        case 'Penanaman':
                                            echo !empty($row['nama_user_penanaman']) ? esc($row['nama_user_penanaman']) : '-';
                                            break;
                                        case 'Pemotongan Tipis':
                                            echo !empty($row['nama_user_pemotongan_tipis']) ? esc($row['nama_user_pemotongan_tipis']) : '-';
                                            break;
                                        case 'Pewarnaan':
                                            echo !empty($row['nama_user_pewarnaan']) ? esc($row['nama_user_pewarnaan']) : '-';
                                            break;
                                        case 'Pembacaan':
                                            echo !empty($row['nama_user_pembacaan']) ? esc($row['nama_user_pembacaan']) : '-';
                                            break;
                                        case 'Penulisan':
                                            echo !empty($row['nama_user_penulisan']) ? esc($row['nama_user_penulisan']) : '-';
                                            break;
                                        case 'Pemverifikasi':
                                            echo !empty($row['nama_user_pemverifikasi']) ? esc($row['nama_user_pemverifikasi']) : '-';
                                            break;
                                        case 'Pencetakan':
                                            echo !empty($row['nama_user_pencetakan']) ? esc($row['nama_user_pencetakan']) : '-';
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
                                        case 'Penanaman':
                                            $startTimeField = 'mulai_penanaman';
                                            break;
                                        case 'Pemotongan Tipis':
                                            $startTimeField = 'mulai_pemotongan_tipis';
                                            break;
                                        case 'Pewarnaan':
                                            $startTimeField = 'mulai_pewarnaan';
                                            break;
                                        case 'Pemprosesan':
                                            $startTimeField = 'mulai_pemprosesan';
                                            break;
                                        case 'Pembacaan':
                                            $startTimeField = 'mulai_pembacaan';
                                            break;
                                        case 'Penulisan':
                                            $startTimeField = 'mulai_penulisan';
                                            break;
                                        case 'Pemverifikasi':
                                            $startTimeField = 'mulai_pemverifikasi';
                                            break;
                                        case 'Pencetakan':
                                            $startTimeField = 'mulai_pencetakan';
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
                                        case 'Penanaman':
                                            $endTimeField = 'selesai_penanaman';
                                            break;
                                        case 'Pemotongan Tipis':
                                            $endTimeField = 'selesai_pemotongan_tipis';
                                            break;
                                        case 'Pewarnaan':
                                            $endTimeField = 'selesai_pewarnaan';
                                            break;
                                        case 'Pemprosesan':
                                            $endTimeField = 'selesai_pemprosesan';
                                            break;
                                        case 'Pembacaan':
                                            $endTimeField = 'selesai_pembacaan';
                                            break;
                                        case 'Penulisan':
                                            $endTimeField = 'selesai_penulisan';
                                            break;
                                        case 'Pemverifikasi':
                                            $endTimeField = 'selesai_pemverifikasi';
                                            break;
                                        case 'Pencetakan':
                                            $endTimeField = 'selesai_pencetakan';
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