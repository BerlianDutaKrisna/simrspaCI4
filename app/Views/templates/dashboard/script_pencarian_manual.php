<script>
    function searchPatientManual() {
        const norm = document.getElementById('norm').value.trim();

        if (!norm) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Masukkan Norm pasien terlebih dahulu.</p>`;
            $('#resultModal').modal('show');
            return;
        }
        if (norm.length !== 6 || !/^\d+$/.test(norm)) {
            document.getElementById('modalBody').innerHTML = `<p class="text-danger">Norm pasien harus berupa 6 digit angka.</p>`;
            $('#resultModal').modal('show');
            return;
        }

        fetch('<?= base_url('patient/modal_search') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ norm: norm })
        })
        .then(res => res.json())
        .then(data => {
            function formatDate(dateString) {
                const d = new Date(dateString);
                return `${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth()+1).padStart(2, '0')}-${d.getFullYear()}`;
            }

            if (data.status === 'success') {
                const p = data.data;
                document.getElementById('modalBody').innerHTML = `
                    <p><strong>Norm:</strong> ${p.norm_pasien}</p>
                    <p><strong>Nama:</strong> ${p.nama_pasien}</p>
                    <p><strong>Alamat:</strong> ${p.alamat_pasien || 'Belum diisi'}</p>
                    <p><strong>Jenis Kelamin/Tanggal Lahir:</strong> ${p.jenis_kelamin_pasien} / ${p.tanggal_lahir_pasien ? formatDate(p.tanggal_lahir_pasien) : 'Belum diisi'}</p>
                    <p><strong>Status:</strong> ${p.status_pasien}</p>
                `;
                document.getElementById('modalFooter').innerHTML = `
                    <a href="<?= base_url('hpa/register') ?>?norm_pasien=${norm}" class="btn btn-danger"><i class="fas fa-plus-square"></i> HPA</a>
                    <a href="<?= base_url('frs/register') ?>?norm_pasien=${norm}" class="btn btn-primary"><i class="fas fa-plus-square"></i> FNAB</a>
                    <a href="<?= base_url('srs/register') ?>?norm_pasien=${norm}" class="btn btn-success"><i class="fas fa-plus-square"></i> SRS</a>
                    <a href="<?= base_url('ihc/register') ?>?norm_pasien=${norm}" class="btn btn-warning"><i class="fas fa-plus-square"></i> IHC</a>
                    <button class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                `;
            } else {
                document.getElementById('modalBody').innerHTML = `<p>${data.message}</p>`;
                document.getElementById('modalFooter').innerHTML = `
                    <a href="<?= base_url('patient/register_patient') ?>?norm_pasien=${norm}" class="btn btn-success"><i class="fas fa-plus-square"></i> Tambah Pasien</a>
                    <button class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                `;
            }
            $('#resultModal').modal('show');
        })
        .catch(err => console.error('Error:', err));
    }

    document.getElementById('searchButton').addEventListener('click', searchPatientManual);
    document.getElementById('norm').addEventListener('keypress', e => {
        if (e.key === 'Enter') { e.preventDefault(); searchPatientManual(); }
    });
</script>
