<script>
    const baseURL = "<?= base_url('api') ?>/";

    document.querySelectorAll('.btn-kirim-ulang').forEach(btn => {
        btn.addEventListener('click', function() {
            const idtransaksi = this.dataset.id;
            const norm = this.dataset.norm;

            // Tampilkan loading di modal
            document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-warning" style="width: 3rem; height: 3rem"></div>
                <p class="mt-3">Sedang mengirim ulang data...</p>
            </div>
        `;
            document.getElementById('modalFooter').innerHTML = '';
            $('#resultModal').modal('show');

            // Tentukan endpoint: jika ada idtransaksi → kirimById, kalau kosong → kirim pakai norm
            let url = baseURL + 'pengiriman-data-simrs/kirim';
            if (idtransaksi && idtransaksi !== '-') {
                url = baseURL + 'pengiriman-data-simrs/kirim/' + idtransaksi;
            }

            // Kirim request ke server
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        norm: norm
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('modalBody').innerHTML = `
                    <div class="alert alert-success">
                        <strong>Berhasil!</strong><br>
                        ID Transaksi: ${data.idtransaksi}<br>
                        Status kunjungan juga sudah diperbarui menjadi <b>Terdaftar</b>.
                    </div>
                    <pre>${JSON.stringify(data.response, null, 2)}</pre>
                `;
                    } else {
                        document.getElementById('modalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Gagal!</strong><br>
                        ${data.message || 'Terjadi kesalahan.'}
                    </div>
                `;
                    }

                    document.getElementById('modalFooter').innerHTML = `
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            `;
                })
                .catch(err => {
                    document.getElementById('modalBody').innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error!</strong><br>
                    ${err}
                </div>
            `;
                });
        });
    });
</script>