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
                        <th>Status Proses</th>
                        <th>Nama User</th>
                        <th>Tanggal Hasil</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th> <!-- Footer kosong untuk Kode HPA -->
                        <th></th> <!-- Footer kosong untuk Nama Pasien -->
                        <th></th> <!-- Footer kosong untuk No RM Pasien -->
                        <th></th> <!-- Footer kosong untuk Diagnosa -->
                        <th></th> <!-- Footer kosong untuk Tindakan Spesimen -->
                        <th></th> <!-- Footer kosong untuk Status HPA -->
                        <th></th> <!-- Footer kosong untuk Status Proses -->
                        <th></th> <!-- Footer kosong untuk Nama User -->
                        <th></th> <!-- Footer kosong untuk Tanggal Hasil -->
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td></td> <!-- Data Kode HPA kosong untuk sekarang -->
                        <td></td> <!-- Data Nama Pasien kosong untuk sekarang -->
                        <td></td> <!-- Data Norm Pasien kosong untuk sekarang -->
                        <td></td> <!-- Data Diagnosa kosong untuk sekarang -->
                        <td></td> <!-- Data Tindakan Spesimen kosong untuk sekarang -->
                        <td></td> <!-- Data Status HPA kosong untuk sekarang -->
                        <td></td> <!-- Data Status Proses kosong untuk sekarang -->
                        <td></td> <!-- Data Nama User kosong untuk sekarang -->
                        <td></td> <!-- Data Tanggal Hasil kosong untuk sekarang -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('dashboard/grafik_pemeriksaan'); ?> <!-- Menyertakan grafik pemeriksaan -->
<?= $this->include('templates/notifikasi'); ?> <!-- Menyertakan notifikasi -->

<?= $this->include('templates/dashboard/footer_dashboard'); ?> <!-- Menyertakan footer dashboard -->