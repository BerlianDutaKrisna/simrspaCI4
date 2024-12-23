<div class="container mt-5">
    <!-- Input untuk Pencarian -->
    <div class="form-group">
        <label for="norm">Cari NoRM Pasien</label>
        <input type="text" id="norm" name="norm" class="form-control" placeholder="Masukkan NoRM" required>
    </div>
    <button type="button" id="searchButton" class="btn btn-primary">Cari</button>
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
    document.getElementById('searchButton').addEventListener('click', function() {
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
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">norm pasien harus berupa angka.</p>`;
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

                // Jika status 'success', tampilkan hasil di modal
                if (data.status === 'success') {
                    const patient = data.data;
                    document.getElementById('modalBody').innerHTML = `
                <p><strong>NoRM:</strong> ${patient.norm_pasien}</p>
                <p><strong>Nama:</strong> ${patient.nama_pasien}</p>
                <p><strong>Alamat:</strong> ${patient.alamat_pasien}</p>
                <p><strong>Tanggal Lahir:</strong> ${patient.tanggal_lahir_pasien}</p>
                <p><strong>Jenis Kelamin:</strong> ${patient.jenis_kelamin_pasien}</p>
                <p><strong>Status:</strong> ${patient.status_pasien}</p>
            `;

                    // Tambahkan tombol 'Tambah Pemeriksaan'
                    document.getElementById('modalFooter').innerHTML = `
                <button type="button" class="btn btn-success">Tambah Pemeriksaan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            `;
                } else {
                    // Jika status 'error', tampilkan pesan kesalahan
                    document.getElementById('modalBody').innerHTML = `<p>${data.message}</p>`;

                    // Tambahkan tombol 'Tambah Pasien'
                    document.getElementById('modalFooter').innerHTML = `
                <button type="button" class="btn btn-success">Tambah Pasien</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            `;
                }

                // Menampilkan modal
                $('#resultModal').modal('show');
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
</script>