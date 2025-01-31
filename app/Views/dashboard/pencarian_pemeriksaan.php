<div class="card shadow mb-4">
    <!-- Card Header: Judul untuk card -->
    <div class="card-header py-4">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Pemeriksaan</h6>
    </div>

    <!-- Card Body: Konten utama dari card -->
    <div class="card-body">
        <div class="row">
            <!-- Kolom untuk input dan tombol pencarian -->
            <div class="col-md-8 col-sm-12 mb-3">
                <div class="input-group">
                    <!-- Input untuk Norm Pasien -->
                    <input type="text" id="norm" name="norm" class="form-control" placeholder="Masukkan Norm Pasien" required>
                    <!-- Tombol pencarian -->
                    <div class="input-group-append">
                        <button type="button" id="searchButton" class="btn btn-primary">
                            <i class="fas fa-search fa-sm"></i> Cari <!-- Ikon untuk tombol cari -->
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Menampilkan Hasil -->
            <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="resultModalLabel">Hasil Pencarian</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modalBody">
                            <!-- Hasil pencarian akan dimasukkan di sini -->
                        </div>
                        <div class="modal-footer" id="modalFooter">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <!-- Tombol akan ditambahkan di sini -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script untuk Mengontrol Modal dan Pencarian -->
            <script>
                // Fungsi untuk menjalankan pencarian
                function searchPatient() {
                    const norm = document.getElementById('norm').value;

                    // Validasi input
                    if (!norm) {
                        document.getElementById('modalBody').innerHTML = `<p class="text-danger">Masukkan Norm pasien terlebih dahulu.</p>`;
                        $('#resultModal').modal('show'); // Menampilkan modal dengan pesan kesalahan
                        return;
                    }

                    // Validasi panjang NoRM (misalnya 6 karakter)
                    if (norm.length !== 6) {
                        document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus terdiri dari 6 karakter.</p>`;
                        $('#resultModal').modal('show'); // Menampilkan modal dengan pesan kesalahan
                        return;
                    }

                    // Validasi apakah NoRM hanya mengandung angka
                    if (!/^\d+$/.test(norm)) {
                        document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus berupa angka.</p>`;
                        $('#resultModal').modal('show'); // Menampilkan modal dengan pesan kesalahan
                        return;
                    }

                    // Mengirim permintaan AJAX
                    fetch('<?= site_url('patient/modal_search') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                norm: norm
                            }),
                        })
                        .then((response) => response.json()) // Mengonversi respons ke JSON
                        .then((data) => {
                            console.log('Data:', data); // Menampilkan data JSON
                            // Fungsi untuk memformat tanggal menjadi d-m-Y
                            function formatDate(dateString) {
                                const date = new Date(dateString);
                                const day = String(date.getDate()).padStart(2, '0');
                                const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
                                const year = date.getFullYear();

                                return `${day}-${month}-${year}`;
                            }
                            // Jika status 'success', tampilkan hasil di modal
                            if (data.status === 'success') {
                                const patient = data.data;
                                document.getElementById('modalBody').innerHTML = `
                    <p><strong>Norm:</strong> ${patient.norm_pasien}</p>
                    <p><strong>Nama:</strong> ${patient.nama_pasien}</p>
                    <p><strong>Alamat:</strong> ${patient.alamat_pasien ? patient.alamat_pasien : 'Belum diisi'}</p>
                    <p><strong>Jenis Kelamin/Tanggal Lahir:</strong> ${patient.jenis_kelamin_pasien} / ${patient.tanggal_lahir_pasien ? formatDate(patient.tanggal_lahir_pasien) : 'Belum diisi'}</p>
                    <p><strong>Status:</strong> ${patient.status_pasien}</p>
                `;

                                // Tambahkan tombol 'Tambah Pemeriksaan'
                                document.getElementById('modalFooter').innerHTML = `
                    <a href="<?= site_url('exam/register_exam') ?>?norm_pasien=${norm}" class="btn btn-success">Tambah Pameriksaan</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                `;
                            } else {
                                // Jika status 'error', tampilkan pesan kesalahan
                                document.getElementById('modalBody').innerHTML = `<p>${data.message}</p>`;

                                // Tambahkan tombol 'Tambah Pasien'
                                document.getElementById('modalFooter').innerHTML = `
                    <a href="<?= site_url('patient/register_patient') ?>?norm_pasien=${norm}" class="btn btn-success">Tambah Pasien</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                `;
                            }

                            // Menampilkan modal
                            $('#resultModal').modal('show');
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }

                // Menambahkan event listener untuk klik tombol pencarian
                document.getElementById('searchButton').addEventListener('click', searchPatient);

                // Menambahkan event listener untuk menekan tombol Enter pada input
                document.getElementById('norm').addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Mencegah form submit default
                        searchPatient(); // Menjalankan pencarian ketika tombol Enter ditekan
                    }
                });
            </script>