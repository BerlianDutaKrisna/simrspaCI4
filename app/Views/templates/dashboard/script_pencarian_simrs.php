<script>
    const baseURL = "<?= base_url() ?>";

    function showLoadingSimrs() {
        document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem"></div>
                <p class="mt-3">Sedang memuat data pasien...</p>
            </div>
        `;
        document.getElementById('modalFooter').innerHTML = '';
        $('#resultModal').modal('show');
    }

    function searchPatientSimrs() {
        const normInput = document.getElementById('norm_simrs');
        const norm_simrs = normInput ? normInput.value.trim() : '';

        if (!norm_simrs) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Masukkan _simrs pasien terlebih dahulu.</p>`;
            document.getElementById('modalFooter').innerHTML = `<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            $('#resultModal').modal('show');
            return;
        }

        if (norm_simrs.length !== 6 || !/^\d+$/.test(norm_simrs)) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus berupa 6 digit angka.</p>`;
            document.getElementById('modalFooter').innerHTML = `<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            $('#resultModal').modal('show');
            return;
        }

        showLoadingSimrs();

        fetch('<?= base_url("api/kunjungan/modal_search/") ?>' + encodeURIComponent(norm_simrs), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                function formatDate(dateString) {
                    const d = new Date(dateString);
                    return `${String(d.getDate()).padStart(2,'0')}-${String(d.getMonth()+1).padStart(2,'0')}-${d.getFullYear()}`;
                }

                function formatDateTime(dateTimeString) {
                    const d = new Date(dateTimeString);
                    return `${String(d.getDate()).padStart(2,'0')}-${String(d.getMonth()+1).padStart(2,'0')}-${d.getFullYear()} ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
                }

                if (data.status === 'success' && data.data.length > 0) {
                    const patients = data.data;
                    let html = `<div style="max-height: 60vh; overflow-y: auto;"><table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Norm</th><th>Nama</th><th>Tgl Lahir</th>
                            <th>Alamat</th><th>Tanggal Daftar</th>
                            <th>No. Register</th><th>Pemeriksaan</th>
                            <th>Unit Asal</th><th>Aksi</th>
                        </tr>
                    </thead><tbody>`;

                    patients.forEach((p, i) => {
                        html += `<tr>
                        <td>${p.norm}</td>
                        <td>${p.nama}</td>
                        <td>${formatDate(p.tgl_lhr)}</td>
                        <td>${p.alamat}</td>
                        <td>${formatDateTime(p.tanggal)}</td>
                        <td>${p.register}</td>
                        <td>${p.pemeriksaan}</td>
                        <td>${p.unitasal}</td>
                        <td><button class="btn btn-outline-primary btn-checklist" data-index="${i}"><i class="fas fa-check-square"></i> Checklist</button></td>
                    </tr>`;
                    });

                    html += `</tbody></table>`;
                    document.getElementById('modalBody').innerHTML = html;

                    document.getElementById('modalFooter').innerHTML = `
                    <form id="actionFormSimrs" method="GET">
                        <input type="hidden" name="register_api" id="register_api">
                        <button formaction="<?= base_url('hpa/register') ?>" class="btn btn-danger"><i class="fas fa-plus-square"></i> HPA</button>
                        <button formaction="<?= base_url('frs/register') ?>" class="btn btn-primary"><i class="fas fa-plus-square"></i> FNAB</button>
                        <button formaction="<?= base_url('srs/register') ?>" class="btn btn-success"><i class="fas fa-plus-square"></i> SRS</button>
                        <button formaction="<?= base_url('ihc/register') ?>" class="btn btn-warning"><i class="fas fa-plus-square"></i> IHC</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                    </form>
                `;

                    let selectedData = [];
                    document.querySelectorAll('.btn-checklist').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.dataset.index;
                            const patient = patients[index];
                            if (selectedData.includes(patient)) {
                                // Unselect
                                selectedData = selectedData.filter(p => p !== patient);
                                this.classList.replace('btn-primary', 'btn-outline-primary');
                            } else {
                                // Select
                                selectedData.push(patient);
                                this.classList.replace('btn-outline-primary', 'btn-primary');
                            }
                            document.getElementById('selected_patients').value = JSON.stringify(selectedData);
                        });
                    });

                    document.getElementById('actionFormSimrs').addEventListener('submit', e => {
                        if (selectedData.length === 0) {
                            e.preventDefault();
                            alert("Silakan pilih minimal satu pemeriksaan.");
                        }
                    });

                } else {
                    document.getElementById('modalBody').innerHTML = `<p class="text-danger">Pasien tidak ditemukan / >3 Hari / Server mati</p>`;
                    document.getElementById('modalFooter').innerHTML = `<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
                }
            })
            .catch(() => {
                document.getElementById('modalBody').innerHTML = `<p class="text-danger">Terjadi kesalahan saat mengambil data.</p>`;
                document.getElementById('modalFooter').innerHTML = `<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            });
    }

    document.getElementById('searchButtonSimrs').addEventListener('click', searchPatientSimrs);
    document.getElementById('norm_simrs').addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchPatientSimrs();
        }
    });
</script>