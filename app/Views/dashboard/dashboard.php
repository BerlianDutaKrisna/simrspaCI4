<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates//dashboard/navbar_dashboard'); ?>
<?= $this->include('dashboard/jumlah_sampel_belum_selesai'); ?>
<?= $this->include('dashboard/pencarian_pemeriksaan'); ?>
<?= $this->include('dashboard/tambah_pasien'); ?>
<?= $this->include('dashboard/jenis_pemeriksaan'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Table live proses</h6>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <a href="samples_accepted.php" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-clipboard"></i>
                </span>
                <span class="text">Penerimaan</span>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Sampel</th>
                        <th>Nomer Rekamedis</th>
                        <th>Nama Pasien</th>
                        <th>Status Proses</th>
                        <th>Waktu Mulai</th>
                        <th>Janji Hasil</th>
                        <th>Analis</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Isi Tabel -->
                    <tr>
                        <td>1</td>
                        <td>SS1234</td>
                        <td>RM5678</td>
                        <td>John Doe</td>
                        <td>Proses</td>
                        <td>08:00</td>
                        <td>09:00</td>
                        <td>Dr. Jane</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>SS5678</td>
                        <td>RM1234</td>
                        <td>Jane Smith</td>
                        <td>Proses</td>
                        <td>09:00</td>
                        <td>10:00</td>
                        <td>Dr. John</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('dashboard/grafik_pemeriksaan'); ?>
<?= $this->include('dashboard/data_keseluruhan_pemeriksaan'); ?>
<?= $this->include('templates/dashboard/footer_dashboard'); ?>