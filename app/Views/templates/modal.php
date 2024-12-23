
<!-- Script untuk Mengontrol Modal dan Pencarian -->
<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        const norm = document.getElementById('norm').value.trim();
        if (!norm) {
            alert('Masukkan NoRM terlebih dahulu.');
            return;
        }

        fetch('<?= base_url('patient/modal_search') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ norm: norm }),
            })
            .then((response) => response.json())
            .then((data) => {
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
                } else {
                    document.getElementById('modalBody').innerHTML = `<p>${data.message}</p>`;
                }
                $('#resultModal').modal('show');
            })
            .catch((error) => {
                console.error('Error:', error);
                document.getElementById('modalBody').innerHTML = `<p>Terjadi kesalahan saat memproses permintaan. Silakan coba lagi nanti.</p>`;
                $('#resultModal').modal('show');
            });
    });
</script>