<script>
    $(document).ready(function() {

        // ==========================
        // Fungsi Format Tanggal & Waktu
        // ==========================
        function formatDateTime(dateString) {
            if (!dateString || isNaN(Date.parse(dateString))) return "-";
            const date = new Date(dateString);
            const time = date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const formattedDate = date.toLocaleDateString('id-ID');
            return `${time}, ${formattedDate}`;
        }

        // ==========================
        // Modal Penerima HPA
        // ==========================
        $('#penerimaModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id_hpa = button.data('id_hpa');
            const modal = $(this);

            modal.find('#id_hpa').val(id_hpa);
            modal.find('#penerima_hpa').val("");
        });

        // ==========================
        // Modal Status HPA
        // ==========================
        $('#statusHpaModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            modal.find('#id_hpa').val(button.data('id_hpa'));
            modal.find('#status_hpa').val(button.data('status_hpa'));
        });

        // ==========================
        // Hapus Data (Set Modal)
        // ==========================
        const deleteSelectors = [
            "hpa", "frs", "pengirisan", "pemotongan", "pemprosesan", "penanaman",
            "pemotongan_tipis", "pewarnaan", "pembacaan", "penulisan",
            "pemverifikasi", "autorized", "pencetakan"
        ];

        $(document).on("click", deleteSelectors.map(sel => `.delete-${sel}`).join(", "), function() {
            const button = $(this);
            const modal = $('#deleteModal');
            const confirmBtn = $('#confirmDelete');

            confirmBtn.data("action", button.data("action"));
            deleteSelectors.forEach(sel => {
                confirmBtn.data(`id_${sel}`, button.data(`id_${sel}`));
            });
            confirmBtn.data("id_hpa", button.data("id_hpa"));

            modal.modal('show');
        });

        // ==========================
        // Konfirmasi Hapus (Ajax)
        // ==========================
        $("#confirmDelete").on("click", function() {
            const action = $(this).data("action");
            const id_hpa = $(this).data("id_hpa");

            const urlMap = {
                hpa: "<?= base_url('hpa/delete'); ?>",
                frs: "<?= base_url('frs/delete'); ?>",
                pengirisan: "<?= base_url('pengirisan/delete'); ?>",
                pemotongan: "<?= base_url('pemotongan/delete'); ?>",
                pemprosesan: "<?= base_url('pemprosesan/delete'); ?>",
                penanaman: "<?= base_url('penanaman/delete'); ?>",
                pemotongan_tipis: "<?= base_url('pemotongan_tipis/delete'); ?>",
                pewarnaan: "<?= base_url('pewarnaan/delete'); ?>",
                pembacaan: "<?= base_url('pembacaan/delete'); ?>",
                penulisan: "<?= base_url('penulisan/delete'); ?>",
                pemverifikasi: "<?= base_url('pemverifikasi/delete'); ?>",
                autorized: "<?= base_url('autorized/delete'); ?>",
                pencetakan: "<?= base_url('pencetakan/delete'); ?>",
            };

            const idName = `id_${action}`;
            const data = {
                [idName]: $(this).data(idName)
            };
            if (action !== 'hpa') {
                data["id_hpa"] = id_hpa;
            }

            $.ajax({
                url: urlMap[action],
                type: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert("Gagal menghapus data.");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan pada server.");
                }
            });

            $('#deleteModal').modal('hide');
        });

        // ==========================
        // Detail Proses Penerimaan
        // ==========================
        $(document).on('click', '.btn-view-proses', function() {
            const proses = $(this).data('proses');
            const id = $(this).data('id');
            const modal = $('#viewModal');
            const body = modal.find('#modalBody');
            const footer = modal.find('#modalFooter');

            body.html('<div class="text-center">Memuat data...</div>');
            footer.html('');

            if (proses === 'penerimaan') {
                const url = `/penerimaan_hpa/penerimaan_details?id_penerimaan_hpa=${id}`;

                $.getJSON(url)
                    .done(function(data) {
                        if (data.error) {
                            body.html(`<div class="alert alert-danger">${data.error}</div>`);
                        } else {
                            body.html(`
                        <ul class="list-group">
                            <p><strong>Nama Pasien:</strong> ${data.nama_pasien}</p>
                            <p><strong>No. RM:</strong> ${data.norm_pasien}</p>
                            <p><strong>Waktu Penerimaan:</strong> ${data.mulai_penerimaan_hpa}</p>
                            <p><strong>User Penerima:</strong> ${data.nama_user_penerimaan_hpa}</p>
                            <p><strong>Status Penerimaan:</strong> ${data.status_penerimaan_hpa}</p>
                        </ul>
                    `);
                            footer.html(`
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    `);
                            modal.modal('show');
                        }
                    })
                    .fail(function(jqxhr, textStatus, error) {
                        body.html(`<div class="alert alert-danger">Terjadi kesalahan saat mengambil data.</div>`);
                        console.error("AJAX Error:", textStatus, error);
                    });
            }
        });


    });
</script>