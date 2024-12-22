<div class="card shadow mb-4">
    <div class="card-header py-4">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Pemeriksaan</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Input dan tombol cari -->
            <div class="col-md-8 col-sm-12 mb-3">
                <div class="input-group">
                    <input type="text" name="norm_pasien" id="SearchPatient" class="form-control" 
                            placeholder="Masukkan Norm pasien" autocomplete="off" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="btnSearchPatient">
                            <i class="fas fa-search fa-sm"></i> Cari
                        </button>
                    </div>
                </div>
            </div>

<!-- Modal untuk hasil pencarian -->
<div class="modal fade" id="modalPatientSearch" tabindex="-1" aria-labelledby="modalPatientSearchLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPatientSearchLabel">Hasil Pencarian Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBodyPatientSearch">
                <!-- Hasil pencarian akan dimuat di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Event handler untuk tombol cari
        $('#btnSearchPatient').on('click', function () {
            const norm_pasien = $('#SearchPatient').val(); // Ambil nilai dari input

            if (norm_pasien.trim() === '') {
                alert('Harap masukkan No RM pasien!');
                return;
            }

            // Kirim request AJAX ke server
            $.ajax({
                url: '/patient/search_patient', // Endpoint pencarian pasien
                type: 'POST',
                data: { norm_pasien: norm_pasien },
                dataType: 'json',
                success: function (response) {
                    $('#modalBodyPatientSearch').html(''); // Kosongkan isi modal sebelumnya

                    if (response.success) {
                        // Jika data ditemukan
                        $('#modalBodyPatientSearch').html(`
                            <p>Nama Pasien: ${response.data.nama_pasien}</p>
                            <p>Alamat: ${response.data.alamat_pasien}</p>
                            <p>Tanggal Lahir: ${response.data.tanggal_lahir_pasien}</p>
                            <p>Jenis Kelamin: ${response.data.jenis_kelamin_pasien}</p>
                            <button class="btn btn-primary" id="btnTambahPemeriksaan">Tambah Pemeriksaan</button>
                        `);
                    } else {
                        // Jika data tidak ditemukan
                        $('#modalBodyPatientSearch').html(`
                            <p>${response.message}</p>
                            <button class="btn btn-secondary" id="btnTambahPasien">Tambah Pasien</button>
                        `);
                    }

                    // Tampilkan modal
                    $('#modalPatientSearch').modal('show');
                },
                error: function () {
                    alert('Terjadi kesalahan saat mencari data pasien.');
                }
            });
        });
    });
</script>
