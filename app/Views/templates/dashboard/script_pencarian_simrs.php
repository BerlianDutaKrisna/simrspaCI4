<script>
    const baseURL = "<?= base_url() ?>";

    function showLoadingSimrs() {
        document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                <p class="mt-3">Sedang memuat data pasien...</p>
            </div>
        `;
        document.getElementById('modalFooter').innerHTML = '';
        $('#resultModal').modal('show');
    }

    function searchPatientSimrs() {
        const normInput = document.getElementById('norm_simrs');
        const norm = normInput ? normInput.value.trim() : '';

        if (!norm) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Masukkan Norm pasien terlebih dahulu.</p>`;
            document.getElementById('modalFooter').innerHTML = `<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            $('#resultModal').modal('show');
            return;
        }

        if (norm.length !== 6 || !/^\d+$/.test(norm)) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus berupa 6 digit angka.</p>`;
            document.getElementById('modalFooter').innerHTML = `<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>`;
            $('#resultModal').modal('show');
            return;
        }

        showLoadingSimrs();

        fetch('<?= base_url("simrs/modal_search") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ norm_simrs: norm })
        })
        .then(res => res.json())
        .then(data => {
            function formatDate(dateString) {
                const d = new Date(dateString);
                return `${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth()+1).padStart(2, '0')}-${d.getFullYear()}`;
            }
            function formatDateTime(dateTimeString) {
                const d = new Date(dateTimeString);
                return `${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth()+1).padStart(2, '0')}-${d.getFullYear()} ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
            }

            if (data.status === 'success') {
                const patients = data.data;
                let html = `<table class="table table-sm table-bordered">
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

                let selectedData = null;
                document.querySelectorAll('.btn-checklist').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.btn-checklist').forEach(b => b.classList.replace('btn-primary','btn-outline-primary'));
                        this.classList.replace('btn-outline-primary','btn-primary');
                        selectedData = patients[this.dataset.index];
                        document.getElementById('register_api').value = JSON.stringify(selectedData);
                    });
                });

                document.getElementById('actionFormSimrs').addEventListener('submit', e => {
                    if (!selectedData) {
                        e.preventDefault();
                        alert("Silakan pilih salah satu pemeriksaan terlebih dahulu.");
                    }
                });

            } else {
                document.getElementById('modalBody').innerHTML = `<p class="text-danger">Cek apakah Pasien sudah daftar / >3 Hari / Server mati</p>`;
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
        if (e.key === 'Enter') { e.preventDefault(); searchPatientSimrs(); }
    });
</script>
