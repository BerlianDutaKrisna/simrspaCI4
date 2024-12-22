<!-- Content Row -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Keseluruhan Pemeriksaan</h6> <!-- Judul halaman -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode HPA</th> <!-- Kolom untuk kode HPA -->
                                    <th>Nama Pasien</th> <!-- Kolom untuk nama pasien -->
                                    <th>No RM Pasien</th> <!-- Kolom untuk nomor rekamedis pasien -->
                                    <th>Diagnosa</th> <!-- Kolom untuk diagnosa -->
                                    <th>Hasil HPA</th> <!-- Kolom untuk hasil pemeriksaan HPA -->
                                    <th>Aksi</th> <!-- Kolom untuk tindakan (edit, delete) -->
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th> <!-- Footer kosong untuk Kode HPA -->
                                    <th></th> <!-- Footer kosong untuk Nama Pasien -->
                                    <th></th> <!-- Footer kosong untuk No RM Pasien -->
                                    <th></th> <!-- Footer kosong untuk Diagnosa -->
                                    <th></th> <!-- Footer kosong untuk Hasil HPA -->
                                    <th></th> <!-- Footer kosong untuk Aksi -->
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td></td> <!-- Data Kode HPA kosong untuk sekarang -->
                                    <td></td> <!-- Data Nama Pasien kosong untuk sekarang -->
                                    <td></td> <!-- Data No RM kosong untuk sekarang -->
                                    <td></td> <!-- Data Diagnosa kosong untuk sekarang -->
                                    <td></td> <!-- Data Hasil HPA kosong untuk sekarang -->
                                    <td>
                                        <a href="" class="btn btn-warning">Edit</a> <!-- Tombol Edit untuk mengubah data -->
                                        <a href="" class="btn btn-danger" onclick="return confirm('Anda yakin menghapusnya')">Delete</a> <!-- Tombol Delete untuk menghapus data, dengan konfirmasi -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
</div>
