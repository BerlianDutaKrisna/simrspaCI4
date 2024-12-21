<div class="card-body">
    <form id="formSearchPatient">
        <div class="row">
            <!-- Input dan tombol cari -->
            <div class="col-md-8 col-sm-12 mb-3">
                <div class="input-group">
                    <input type="text" name="norm" id="input_norm" class="form-control" placeholder="Enter Patient Number" autocomplete="off" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="btnSearchPatient">
                            <i class="fas fa-search fa-sm"></i> Search
                        </button>
                    </div>
                </div>
            </div>

<!-- Modal untuk hasil pencarian -->
<div class="modal fade" id="SearchPatientModal" tabindex="-1" aria-labelledby="SearchPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SearchPatientModalLabel">Search Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Area hasil pencarian -->
                <div id="searchResult">
                    <p>Enter a patient number to search.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript AJAX untuk pencarian -->
<script>
document.getElementById('formSearchPatient').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form melakukan submit default

    const norm = document.getElementById('input_norm').value; // Ambil nilai input norm_pasien
    const searchResult = document.getElementById('searchResult'); // Area untuk menampilkan hasil pencarian

    if (!norm) {
        searchResult.innerHTML = '<p class="text-danger">Patient number cannot be empty.</p>';
        $('#SearchPatientModal').modal('show'); // Tampilkan modal
        return;
    }

    // Kirim permintaan ke server menggunakan fetch
    fetch('/patient/searchPatient', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ norm: norm }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const patient = data.data;
            // Tampilkan hasil pencarian di modal
            searchResult.innerHTML = `
                <p class="text-success">Patient found:</p>
                <ul>
                    <li><strong>Patient Number:</strong> ${patient.norm_pasien}</li>
                    <li><strong>Name:</strong> ${patient.nama_pasien}</li>
                    <li><strong>Address:</strong> ${patient.alamat_pasien}</li>
                    <li><strong>Birth Date:</strong> ${patient.tanggal_lahir_pasien}</li>
                    <li><strong>Gender:</strong> ${patient.jenis_kelamin_pasien}</li>
                    <li><strong>Status:</strong> ${patient.status_pasien}</li>
                </ul>
            `;
        } else {
            // Jika data tidak ditemukan
            searchResult.innerHTML = `<p class="text-warning">${data.message}</p>`;
        }

        $('#SearchPatientModal').modal('show'); // Tampilkan modal
    })
    .catch(error => {
        // Jika terjadi error
        searchResult.innerHTML = '<p class="text-danger">An error occurred while searching for the patient.</p>';
        $('#SearchPatientModal').modal('show');
    });
});
</script>
