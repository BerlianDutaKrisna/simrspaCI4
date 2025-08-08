<script>
    const baseURL = "<?= base_url() ?>";

    function showLoadingManual() {
        const normInput = document.getElementById('norm_manual');
        const norm = normInput ? normInput.value.trim() : '';

        document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3">Sedang memuat data pasien...</p>
            </div>
        `;

        document.getElementById('modalFooter').innerHTML = ''; // Kosongkan footer saat loading
        $('#resultModal').modal('show');
    }

    function searchPatientManual() {
        const normInput = document.getElementById('norm_manual');
        const norm = normInput ? normInput.value.trim() : '';

        if (!norm) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Masukkan Norm pasien terlebih dahulu.</p>`;
            document.getElementById('modalFooter').innerHTML = `<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            $('#resultModal').modal('show');
            return;
        }

        if (norm.length !== 6 || !/^\d+$/.test(norm)) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus berupa 6 digit angka.</p>`;
            document.getElementById('modalFooter').innerHTML = `<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            $('#resultModal').modal('show');
            return;
        }

        showLoadingManual();

        fetch('<?= base_url("patient/modal_search") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    norm: norm
                })
            })
            .then((response) => response.json())
            .then((data) => {
                function formatDate(dateString) {
                    const date = new Date(dateString);
                    return `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}`;
                }

                function formatDateTime(dateTimeString) {
                    const date = new Date(dateTimeString);
                    return `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
                }

                if (data.status === 'success') {
                    const patients = data.data;
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
                                <th>Unit Asal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                    patients.forEach((patient, index) => {
                        tableHTML += `
                        <tr>
                            <td>${patient.norm}</td>
                            <td>${patient.nama}</td>
                            <td>${formatDate(patient.tgl_lhr)}</td>
                            <td>${patient.alamat}</td>
                            <td>${formatDateTime(patient.tanggal)}</td>
                            <td>${patient.register}</td>
                            <td>${patient.pemeriksaan}</td>
                            <td>${patient.unitasal}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-checklist" data-index="${index}">
                                    <i class="fas fa-check-square"></i> Checklist
                                </button>
                            </td>
                        </tr>
                    `;
                    });

                    tableHTML += `</tbody></table>`;
                    document.getElementById('modalBody').innerHTML = tableHTML;

                    document.getElementById('modalFooter').innerHTML = `
                    <form id="actionForm" method="GET">
                        <input type="hidden" name="register_api" id="register_api">
                        <button type="submit" formaction="<?= base_url('hpa/register') ?>" class="btn btn-danger"><i class="fas fa-plus-square"></i> HPA</button>
                        <button type="submit" formaction="<?= base_url('frs/register') ?>" class="btn btn-primary"><i class="fas fa-plus-square"></i> FNAB</button>
                        <button type="submit" formaction="<?= base_url('srs/register') ?>" class="btn btn-success"><i class="fas fa-plus-square"></i> SRS</button>
                        <button type="submit" formaction="<?= base_url('ihc/register') ?>" class="btn btn-warning"><i class="fas fa-plus-square"></i> IHC</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                    </form>
                `;

                    let selectedData = null;

                    document.querySelectorAll('.btn-checklist').forEach(button => {
                        button.addEventListener('click', function() {
                            document.querySelectorAll('.btn-checklist').forEach(btn => {
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-outline-primary');
                            });

                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-primary');

                            const index = parseInt(this.dataset.index);
                            selectedData = patients[index];
                            document.getElementById('register_api').value = JSON.stringify(selectedData);
                        });
                    });

                    document.getElementById('actionForm').addEventListener('submit', function(e) {
                        if (!selectedData) {
                            e.preventDefault();
                            alert("Silakan pilih salah satu pemeriksaan terlebih dahulu.");
                        }
                    });

                    $('#resultModal').modal('show');
                } else {
                    document.getElementById('modalBody').innerHTML = `
                    <p class="text-danger">
                        Cek apakah Pasien sudah daftar / Terdaftar lebih dari 3 Hari / Server 10.250.10.107 Mati
                    </p>`;
                    document.getElementById('modalFooter').innerHTML = `
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
                    $('#resultModal').modal('show');
                }
            })
            .catch((error) => {
                document.getElementById('modalBody').innerHTML = `<p class="text-danger">Terjadi kesalahan saat mengambil data.</p>`;
                document.getElementById('modalFooter').innerHTML = `<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
                $('#resultModal').modal('show');
            });
    }

    // Tombol dan enter event untuk pencarian manual
    document.addEventListener('DOMContentLoaded', function() {
        // Tombol dan enter event untuk pencarian manual
        document.getElementById('searchButtonManual').addEventListener('click', searchPatientManual);
        document.getElementById('norm_manual').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchPatientManual();
            }
        });
    });
</script>