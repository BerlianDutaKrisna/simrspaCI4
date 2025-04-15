<script>
    $(document).ready(function() {

        // ==========================
        // Fungsi Format Tanggal & Waktu
        // ==========================
        function formatDateTime(dateString) {
            if (!dateString || isNaN(Date.parse(dateString))) return "-";
            const date = new Date(dateString);

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const time = `${hours}:${minutes}`;

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const formattedDate = `${day}/${month}/${year}`;

            return `${time}, ${formattedDate}`;
        }

        // ==========================
        // Modal Penerima SRS
        // ==========================
        $('#penerimaModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id_srs = button.data('id_srs');
            const modal = $(this);

            modal.find('#id_srs').val(id_srs);
            modal.find('#penerima_srs').val("");
        });

        // ==========================
        // Modal Status SRS
        // ==========================
        $('#statussrsModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            modal.find('#id_srs').val(button.data('id_srs'));
            modal.find('#status_srs').val(button.data('status_srs'));
        });

        // ==========================
        // Hapus Data (Set Modal)
        // ==========================
        const deleteSelectors = [
            "srs", "pembacaan", "penulisan",
            "pemverifikasi", "authorized", "pencetakan"
        ];

        $(document).on("click", deleteSelectors.map(sel => `.delete-${sel}`).join(", "), function() {
            const button = $(this);
            const modal = $('#deleteModal');
            const confirmBtn = $('#confirmDelete');

            confirmBtn.data("action", button.data("action"));
            deleteSelectors.forEach(sel => {
                confirmBtn.data(`id_${sel}`, button.data(`id_${sel}`));
            });
            confirmBtn.data("id_srs", button.data("id_srs"));

            modal.modal('show');
        });

        // ==========================
        // Konfirmasi Hapus (Ajax)
        // ==========================
        $("#confirmDelete").on("click", function() {
            const action = $(this).data("action");
            const id_srs = $(this).data("id_srs");

            console.log("Action:", action);
            console.log("ID SRS:", id_srs);

            const urlMap = {
                SRS: "<?= base_url('srs/delete'); ?>",
                pemotongan: "<?= base_url('pemotongan_srs/delete'); ?>",
                pemprosesan: "<?= base_url('pemprosesan_srs/delete'); ?>",
                penanaman: "<?= base_url('penanaman_srs/delete'); ?>",
                pemotongan_tipis: "<?= base_url('pemotongan_tipis_srs/delete'); ?>",
                pewarnaan: "<?= base_url('pewarnaan_srs/delete'); ?>",
                pembacaan: "<?= base_url('pembacaan_srs/delete'); ?>",
                penulisan: "<?= base_url('penulisan_srs/delete'); ?>",
                pemverifikasi: "<?= base_url('pemverifikasi_srs/delete'); ?>",
                authorized: "<?= base_url('authorized_srs/delete'); ?>",
                pencetakan: "<?= base_url('pencetakan_srs/delete'); ?>",
            };

            const idName = `id_${action}`;
            const data = {
                [idName]: $(this).data(idName)
            };
            if (action !== 'srs') {
                data["id_srs"] = id_srs;
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
        // Detail Proses 
        // ==========================
        $(document).ready(function() {
            const baseUrl = "<?= base_url() ?>";

            $('.btn-view-proses').on('click', function() {
                const id = $(this).data('id');
                const proses = $(this).data('proses');
                console.log(id, proses);
                const url = `${baseUrl}${proses}_srs/${proses}_details?id_${proses}_srs=${id}`;

                // Bersihkan konten modal terlebih dahulu
                const body = $('#viewModalBody');
                const footer = $('#viewModalFooter');
                body.html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
                footer.html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>');

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            body.html(`<div class="alert alert-danger">${data.error}</div>`);
                        } else {
                            if (proses === 'mutu') {
                                // Informasi utama pasien
                                const patientInfo = `
                            <p><strong>No. RM:</strong> ${data.norm_pasien}</p>
                            <p><strong>Nama Pasien:</strong> ${data.nama_pasien}</p>
                            <p><strong>Kode SRS:</strong> ${data.kode_srs}</p>
                        `;
                                let indikatorList = '';
                                indikatorList += `<li class="list-group-item"><strong>Vol cairan fiksasi sesuai?</strong>: ${data.indikator_1}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Jaringan terfiksasi merata?</strong>: ${data.indikator_2}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Blok parafin tidak ada fragmentasi?</strong>: ${data.indikator_3}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Sediaan tanpa lipatan?</strong>: ${data.indikator_4}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Sediaan tanpa goresan mata pisau?</strong>: ${data.indikator_5}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Kontras warna sediaan cukup jelas?</strong>: ${data.indikator_6}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Sediaan tanpa gelembung udara?</strong>: ${data.indikator_7}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Sediaan tanpa bercak / sidik jari?</strong>: ${data.indikator_8}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Kosong</strong>: ${data.indikator_9}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Kosong</strong>: ${data.indikator_10}</li>`;
                                indikatorList += `<li class="list-group-item"><strong><b></b>Total Nilai Mutu: ${data.total_nilai_mutu_srs}</b></strong></li>`;

                                body.html(`
                            ${patientInfo}
                            <ul class="list-group mt-3">
                                ${indikatorList}
                            </ul>
                        `);
                            } else {
                                // Format tanggal
                                let mulai = formatDateTime(data[`mulai_${proses}_srs`]);
                                let selesai = formatDateTime(data[`selesai_${proses}_srs`]);
                                let user = data[`nama_user_${proses}_srs`];

                                body.html(`
                            <ul class="list-group">
                                <p><strong>No. RM:</strong> ${data.norm_pasien}</p>
                                <p><strong>Nama Pasien:</strong> ${data.nama_pasien}</p>
                                <p><strong>Kode SRS:</strong> ${data.kode_srs}</p>
                                <p><strong>Mulai ${proses}:</strong> ${mulai}</p>
                                <p><strong>Selesai ${proses}:</strong> ${selesai}</p>
                                <p><strong>User ${proses}:</strong> ${user}</p>
                            </ul>
                        `);
                            }

                            $('#viewModalLabel').text('Detail Proses ' + proses.replace('_', ' '));
                            footer.html(`
                        <a href="${baseUrl}${proses}_srs/edit?id_${proses}_srs=${id}" class="btn btn-warning">
                            <i class="fas fa-pen"></i> Edit
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    `);
                            $('#viewModal').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        body.html(`<div class="alert alert-danger">Terjadi kesalahan saat mengambil data.</div>`);
                        footer.html(`
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                `);
                        $('#viewModalLabel').text('Kesalahan');
                        $('#viewModal').modal('show');
                        console.error("AJAX Error:", status, error);
                    }
                });
            });
        });

    });
</script>