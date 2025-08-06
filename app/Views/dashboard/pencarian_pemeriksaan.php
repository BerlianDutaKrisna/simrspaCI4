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
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                            <!-- Tombol akan ditambahkan di sini -->
                        </div>
                    </div>
                </div>
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
            $('#resultModal').modal('show');
            return;
        }

        if (norm.length !== 6) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus terdiri dari 6 karakter.</p>`;
            $('#resultModal').modal('show');
            return;
        }

        if (!/^\d+$/.test(norm)) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus berupa angka.</p>`;
            $('#resultModal').modal('show');
            return;
        }

        fetch('<?= base_url("patient/modal_search") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    norm: norm
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                // Fungsi format tanggal
                function formatDate(dateString) {
                    const date = new Date(dateString);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }

                function formatDateTime(dateTimeString) {
                    const date = new Date(dateTimeString);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    return `${day}-${month}-${year} ${hours}:${minutes}`;
                }

                if (data.status === 'success') {
                    const patients = data.data;
                    const norm = patients[0].norm;

                    let tableHTML = `
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Norm</th>
                                <th>Nama</th>
                                <th>Tgl Lahir</th>
                                <th>Alamat</th>
                                <th>Tanggal Daftar</th>
                                <th>No. Register</th>
                                <th>Pemeriksaan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                    patients.forEach((patient) => {
                        tableHTML += `
                        <tr>
                            <td>${patient.norm}</td>
                            <td>${patient.nama}</td>
                            <td>${formatDate(patient.tgl_lhr)}</td>
                            <td>${patient.alamat}</td>
                            <td>${formatDateTime(patient.tanggal)}</td>
                            <td>${patient.register}</td>
                            <td>${patient.pemeriksaan}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-checklist" data-idtransaksi="${patient.idtransaksi}">
                                    <i class="fas fa-check-square"></i> Checklist
                                </button>
                            </td>
                        </tr>
                    `;
                    });

                    tableHTML += `</tbody></table>`;
                    document.getElementById('modalBody').innerHTML = tableHTML;

                    document.getElementById('modalFooter').innerHTML = `
                    <a href="<?= base_url('hpa/register') ?>?norm_pasien=${norm}" class="btn btn-danger"><i class="fas fa-plus-square"></i> HPA</a>
                    <a href="<?= base_url('frs/register') ?>?norm_pasien=${norm}" class="btn btn-primary"><i class="fas fa-plus-square"></i> FNAB</a>
                    <a href="<?= base_url('srs/register') ?>?norm_pasien=${norm}" class="btn btn-success"><i class="fas fa-plus-square"></i> SRS</a>
                    <a href="<?= base_url('ihc/register') ?>?norm_pasien=${norm}" class="btn btn-warning"><i class="fas fa-plus-square"></i> IHC</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                `;

                    // Event listener untuk checklist (satu aktif)
                    document.querySelectorAll('.btn-checklist').forEach(function(button) {
                        button.addEventListener('click', function() {
                            // Reset semua button
                            document.querySelectorAll('.btn-checklist').forEach(btn => {
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-outline-primary');
                            });

                            // Aktifkan yang dipilih
                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-primary');

                            const idTransaksi = this.dataset.idtransaksi;
                            console.log("Checklist ID terpilih:", idTransaksi);
                        });
                    });

                    $('#resultModal').modal('show');
                } else {
                    document.getElementById('modalBody').innerHTML = `<p class="text-danger">Pasien belum terdaftar pada Layanan PM pada SIMRS.</p>`;
                    document.getElementById('modalFooter').innerHTML = `<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
                    $('#resultModal').modal('show');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    // Tombol cari dan enter
    document.getElementById('searchButton').addEventListener('click', searchPatient);
    document.getElementById('norm').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchPatient();
        }
    });
</script>