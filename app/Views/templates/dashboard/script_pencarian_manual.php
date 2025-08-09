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
        fetch('<?= base_url('patient/modal_search') ?>', {
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
                    <a href="<?= base_url('hpa/register') ?>?norm_pasien=${norm}" class="btn btn-danger"><i class="fas fa-plus-square"></i> HPA</a>
                    <a href="<?= base_url('frs/register') ?>?norm_pasien=${norm}" class="btn btn-primary"><i class="fas fa-plus-square"></i> FNAB</a>
                    <a href="<?= base_url('srs/register') ?>?norm_pasien=${norm}" class="btn btn-success"><i class="fas fa-plus-square"></i> SRS</a>
                    <a href="<?= base_url('ihc/register') ?>?norm_pasien=${norm}" class="btn btn-warning"><i class="fas fa-plus-square"></i> IHC</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                `;
                } else {
                    // Jika status 'error', tampilkan pesan kesalahan
                    document.getElementById('modalBody').innerHTML = `<p>${data.message}</p>`;

                    // Tambahkan tombol 'Tambah Pasien'
                    document.getElementById('modalFooter').innerHTML = `
                    <a href="<?= base_url('patient/register_patient') ?>?norm_pasien=${norm}" class="btn btn-success"><i class="fas fa-plus-square"></i> Tambah Pasien</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
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