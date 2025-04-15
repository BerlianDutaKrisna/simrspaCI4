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
        // Modal Penerima FRS
        // ==========================
        $('#penerimaModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id_frs = button.data('id_frs');
            const modal = $(this);

            modal.find('#id_frs').val(id_frs);
            modal.find('#penerima_frs').val("");
        });

        // ==========================
        // Modal Status FRS
        // ==========================
        $('#statusfrsModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            modal.find('#id_frs').val(button.data('id_frs'));
            modal.find('#status_frs').val(button.data('status_frs'));
        });

        // ==========================
        // Hapus Data (Set Modal)
        // ==========================
        const deleteSelectors = [
            "frs", "pembacaan", "penulisan",
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
            confirmBtn.data("id_frs", button.data("id_frs"));

            modal.modal('show');
        });

        // ==========================
        // Konfirmasi Hapus (Ajax)
        // ==========================
        $("#confirmDelete").on("click", function() {
            const action = $(this).data("action");
            const id_frs = $(this).data("id_frs");

            console.log("Action:", action);
            console.log("ID FRS:", id_frs);

            const urlMap = {
                frs: "<?= base_url('frs/delete'); ?>",
                pemotongan: "<?= base_url('pemotongan_frs/delete'); ?>",
                pemprosesan: "<?= base_url('pemprosesan_frs/delete'); ?>",
                penanaman: "<?= base_url('penanaman_frs/delete'); ?>",
                pemotongan_tipis: "<?= base_url('pemotongan_tipis_frs/delete'); ?>",
                pewarnaan: "<?= base_url('pewarnaan_frs/delete'); ?>",
                pembacaan: "<?= base_url('pembacaan_frs/delete'); ?>",
                penulisan: "<?= base_url('penulisan_frs/delete'); ?>",
                pemverifikasi: "<?= base_url('pemverifikasi_frs/delete'); ?>",
                authorized: "<?= base_url('authorized_frs/delete'); ?>",
                pencetakan: "<?= base_url('pencetakan_frs/delete'); ?>",
            };

            const idName = `id_${action}`;
            const data = {
                [idName]: $(this).data(idName)
            };
            if (action !== 'frs') {
                data["id_frs"] = id_frs;
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
                const url = `${baseUrl}${proses}_frs/${proses}_details?id_${proses}_frs=${id}`;

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
                            <p><strong>Kode FRS:</strong> ${data.kode_frs}</p>
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
                                indikatorList += `<li class="list-group-item"><strong><b></b>Total Nilai Mutu: ${data.total_nilai_mutu_frs}</b></strong></li>`;

                                body.html(`
                            ${patientInfo}
                            <ul class="list-group mt-3">
                                ${indikatorList}
                            </ul>
                        `);
                            } else {
                                // Format tanggal
                                let mulai = formatDateTime(data[`mulai_${proses}_frs`]);
                                let selesai = formatDateTime(data[`selesai_${proses}_frs`]);
                                let user = data[`nama_user_${proses}_frs`];

                                body.html(`
                            <ul class="list-group">
                                <p><strong>No. RM:</strong> ${data.norm_pasien}</p>
                                <p><strong>Nama Pasien:</strong> ${data.nama_pasien}</p>
                                <p><strong>Kode FRS:</strong> ${data.kode_frs}</p>
                                <p><strong>Mulai ${proses}:</strong> ${mulai}</p>
                                <p><strong>Selesai ${proses}:</strong> ${selesai}</p>
                                <p><strong>User ${proses}:</strong> ${user}</p>
                            </ul>
                        `);
                            }

                            $('#viewModalLabel').text('Detail Proses ' + proses.replace('_', ' '));
                            footer.html(`
                        <a href="${baseUrl}${proses}_frs/edit?id_${proses}_frs=${id}" class="btn btn-warning">
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