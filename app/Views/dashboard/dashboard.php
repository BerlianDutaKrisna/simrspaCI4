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
        <div class="mb-4">
            <a href="samples_accepted.php" class="btn btn-primary btn-icon-split btn-sm"> <!-- Tombol untuk mengakses penerimaan sampel -->
                <span class="icon text-white-50">
                    <i class="fas fa-clipboard"></i> <!-- Ikon untuk penerimaan sampel -->
                </span>
                <span class="text">Penerimaan</span>
            </a>
        </div>
        <div class="table-responsive"> <!-- Bagian untuk membuat tabel menjadi responsif -->
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th> <!-- Nomor urut -->
                        <th>Kode Sampel</th> <!-- Kolom kode sampel -->
                        <th>Nomer Rekamedis</th> <!-- Kolom nomor rekam medis -->
                        <th>Nama Pasien</th> <!-- Kolom nama pasien -->
                        <th>Status Proses</th> <!-- Kolom status proses pemeriksaan -->
                        <th>Waktu Mulai</th> <!-- Kolom waktu mulai pemeriksaan -->
                        <th>Janji Hasil</th> <!-- Kolom janji waktu hasil -->
                        <th>Analis</th> <!-- Kolom analis yang menangani -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Isi Tabel -->
                    <tr>
                        <td>1</td> <!-- Nomor urut -->
                        <td>SS1234</td> <!-- Kode sampel -->
                        <td>RM5678</td> <!-- Nomor rekam medis -->
                        <td>John Doe</td> <!-- Nama pasien -->
                        <td>Proses</td> <!-- Status proses pemeriksaan -->
                        <td>08:00</td> <!-- Waktu mulai pemeriksaan -->
                        <td>09:00</td> <!-- Janji waktu hasil -->
                        <td>Dr. Jane</td> <!-- Nama analis -->
                    </tr>
                    <tr>
                        <td>2</td> <!-- Nomor urut -->
                        <td>SS5678</td> <!-- Kode sampel -->
                        <td>RM1234</td> <!-- Nomor rekam medis -->
                        <td>Jane Smith</td> <!-- Nama pasien -->
                        <td>Proses</td> <!-- Status proses pemeriksaan -->
                        <td>09:00</td> <!-- Waktu mulai pemeriksaan -->
                        <td>10:00</td> <!-- Janji waktu hasil -->
                        <td>Dr. John</td> <!-- Nama analis -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('dashboard/grafik_pemeriksaan'); ?> <!-- Menyertakan grafik pemeriksaan -->
<?= $this->include('dashboard/data_keseluruhan_pemeriksaan'); ?> <!-- Menyertakan data keseluruhan pemeriksaan -->
<?= $this->include('templates/notifikasi'); ?> <!-- Menyertakan notifikasi -->
<?= $this->include('templates/dashboard/footer_dashboard'); ?> <!-- Menyertakan footer dashboard -->
