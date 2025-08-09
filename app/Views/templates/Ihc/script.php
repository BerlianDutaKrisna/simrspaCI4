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
        // Modal Penerima IHC
        // ==========================
        $('#penerimaModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id_ihc = button.data('id_ihc');
            const modal = $(this);

            modal.find('#id_ihc').val(id_ihc);
            modal.find('#penerima_ihc').val("");
        });

        // ==========================
        // Modal Status IHC
        // ==========================
        $('#statusihcModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            modal.find('#id_ihc').val(button.data('id_ihc'));
            modal.find('#status_ihc').val(button.data('status_ihc'));
        });

        // ==========================
        // Hapus Data (Set Modal)
        // ==========================
        const deleteSelectors = [
            "ihc", "pembacaan", "penulisan",
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
            confirmBtn.data("id_ihc", button.data("id_ihc"));

            modal.modal('show');
        });

        // ==========================
        // Konfirmasi Hapus (Ajax)
        // ==========================
        $("#confirmDelete").on("click", function() {
            const action = $(this).data("action");
            const id_ihc = $(this).data("id_ihc");

            console.log("Action:", action);
            console.log("ID IHC:", id_ihc);

            const urlMap = {
                ihc: "<?= base_url('ihc/delete'); ?>",
                pemotongan: "<?= base_url('pemotongan_ihc/delete'); ?>",
                pemprosesan: "<?= base_url('pemprosesan_ihc/delete'); ?>",
                penanaman: "<?= base_url('penanaman_ihc/delete'); ?>",
                pemotongan_tipis: "<?= base_url('pemotongan_tipis_ihc/delete'); ?>",
                pewarnaan: "<?= base_url('pewarnaan_ihc/delete'); ?>",
                pembacaan: "<?= base_url('pembacaan_ihc/delete'); ?>",
                penulisan: "<?= base_url('penulisan_ihc/delete'); ?>",
                pemverifikasi: "<?= base_url('pemverifikasi_ihc/delete'); ?>",
                authorized: "<?= base_url('authorized_ihc/delete'); ?>",
                pencetakan: "<?= base_url('pencetakan_ihc/delete'); ?>",
            };

            const idName = `id_${action}`;
            const data = {
                [idName]: $(this).data(idName)
            };
            if (action !== 'ihc') {
                data["id_ihc"] = id_ihc;
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
                const url = `${baseUrl}${proses}_ihc/${proses}_details?id_${proses}_ihc=${id}`;

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
                            <p><strong>Kode IHC:</strong> ${data.kode_ihc}</p>
                        `;
                                let indikatorList = '';
                                indikatorList += `<li class="list-group-item"><strong>KTP?</strong>: ${data.indikator_1}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>BPJS?</strong>: ${data.indikator_2}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>No Telfon Pasien?</strong>: ${data.indikator_3}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Hasil Lab Sebelumnya?</strong>: ${data.indikator_4}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Indikator 5?</strong>: ${data.indikator_5}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Indikator 6?</strong>: ${data.indikator_6}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Indikator 7?</strong>: ${data.indikator_7}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Indikator 8?</strong>: ${data.indikator_8}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Indikator 9?</strong>: ${data.indikator_9}</li>`;
                                indikatorList += `<li class="list-group-item"><strong>Indikator 10?</strong>: ${data.indikator_10}</li>`;
                                indikatorList += `<li class="list-group-item"><strong><b></b>Total Nilai Mutu: ${data.total_nilai_mutu_ihc}</b></strong></li>`;

                                body.html(`
                            ${patientInfo}
                            <ul class="list-group mt-3">
                                ${indikatorList}
                            </ul>
                        `);
                            } else {
                                // Format tanggal
                                let mulai = formatDateTime(data[`mulai_${proses}_ihc`]);
                                let selesai = formatDateTime(data[`selesai_${proses}_ihc`]);
                                let user = data[`nama_user_${proses}_ihc`];

                                body.html(`
                            <ul class="list-group">
                                <p><strong>No. RM:</strong> ${data.norm_pasien}</p>
                                <p><strong>Nama Pasien:</strong> ${data.nama_pasien}</p>
                                <p><strong>Kode IHC:</strong> ${data.kode_ihc}</p>
                                <p><strong>Mulai ${proses}:</strong> ${mulai}</p>
                                <p><strong>Selesai ${proses}:</strong> ${selesai}</p>
                                <p><strong>User ${proses}:</strong> ${user}</p>
                            </ul>
                        `);
                            }

                            $('#viewModalLabel').text('Detail Proses ' + proses.replace('_', ' '));
                            footer.html(`
                        <a href="${baseUrl}${proses}_ihc/edit?id_${proses}_ihc=${id}" class="btn btn-warning">
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