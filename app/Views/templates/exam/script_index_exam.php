<!-- JavaScript untuk modal tetap sama -->
<script>
    $(document).ready(function() {
        // ==========================
        // Fungsi Format Tanggal & Waktu
        // ==========================
        function formatDateTime(dateString) {
            if (!dateString || isNaN(Date.parse(dateString))) return "-"; // Validasi input
            var date = new Date(dateString);

            // Format waktu (HH:mm)
            var hours = date.getHours().toString().padStart(2, "0");
            var minutes = date.getMinutes().toString().padStart(2, "0");
            var time = hours + ":" + minutes;

            // Format tanggal (DD-MM-YYYY)
            var day = date.getDate().toString().padStart(2, "0");
            var month = (date.getMonth() + 1).toString().padStart(2, "0");
            var year = date.getFullYear();

            return time + ", " + day + "-" + month + "-" + year;
        }

        // ==========================
        // Modal Status HPA
        // ==========================
        $("#statusHpaModal").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);
            var id_hpa = button.data("id_hpa");
            var status_hpa = button.data("status_hpa");

            var modal = $(this);
            modal.find("#id_hpa").val(id_hpa);
            modal.find("#status_hpa").val(status_hpa);
        });

        // ==========================
        // Detail Penerimaan
        // ==========================
        $(document).on("click", ".view-penerimaan", function() {
            var id_penerimaan = $(this).data("id_penerimaan");

            // Lakukan aksi seperti AJAX di sini
            console.log("ID Penerimaan:", id_penerimaan);

            $.ajax({
                url: "<?= base_url('penerimaan/penerimaan_details'); ?>",
                type: "GET",
                data: {
                    id_penerimaan: encodeURIComponent(id_penerimaan)
                },
                success: function(response) {
                    if (response.error) {
                        $("#modalBody").html(`<p>${response.error}</p>`);
                        $("#modalFooter").html(
                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                        );
                    } else {
                        var detailHtml = `
                            <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                            <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_penerimaan || "-"}</p>
                            <p><strong>Status Penerimaan:</strong> ${response.status_penerimaan || "-"}</p>
                            <p><strong>Mulai Penerimaan:</strong> ${formatDateTime(response.mulai_penerimaan)}</p>
                            <p><strong>Selesai Penerimaan:</strong> ${formatDateTime(response.selesai_penerimaan)}</p>
                            <p><strong>Volume Fiksasi:</strong> ${response.indikator_1 || "-"}</p>
                            <p><strong>Terfiksasi Merata:</strong> ${response.indikator_2 || "-"}</p>
                        `;
                        $("#modalBody").html(detailHtml);

                        var footerHtml = `
                            <a href="<?= base_url('penerimaan/edit'); ?>?id_penerimaan=${encodeURIComponent(
                            id_penerimaan
                        )}" class="btn btn-warning">Edit</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        `;
                        $("#modalFooter").html(footerHtml);
                    }
                    $("#viewModal").modal("show");
                },
                error: function() {
                    $("#modalBody").html('<p>Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>');
                    $("#modalFooter").html(
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                    );
                },
            });
        });

        // ==========================
        // Detail Pengirisan
        // ==========================
        $(document).on("click", ".view-pengirisan", function() {
            var id_pengirisan = $(this).data("id_pengirisan");

            $.ajax({
                url: "<?= base_url('pengirisan/pengirisan_details'); ?>",
                type: "GET",
                data: {
                    id_pengirisan: encodeURIComponent(id_pengirisan)
                },
                success: function(response) {
                    if (response.error) {
                        $("#modalBody").html(`<p>${response.error}</p>`);
                        $("#modalFooter").html(
                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                        );
                    } else {
                        var detailHtml = `
                            <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                            <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pengirisan || "-"}</p>
                            <p><strong>Status Pengirisan:</strong> ${response.status_pengirisan || "-"}</p>
                            <p><strong>Mulai Pengirisan:</strong> ${formatDateTime(response.mulai_pengirisan)}</p>
                            <p><strong>Selesai Pengirisan:</strong> ${formatDateTime(response.selesai_pengirisan)}</p>
                        `;
                        $("#modalBody").html(detailHtml);

                        var footerHtml = `
                            <a href="<?= site_url('pengirisan/edit_pengirisan') ?>?id_pengirisan=${id_pengirisan}" class="btn btn-warning">Edit</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        `;
                        $("#modalFooter").html(footerHtml);
                    }
                    $("#viewModal").modal("show");
                },
                error: function() {
                    $("#modalBody").html('<p>Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>');
                    $("#modalFooter").html(
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                    );
                },
            });
        });
        // Hapus Pengirisan
        $(document).on("click", ".delete-pengirisan", function() {
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_hpa = $(this).data("id_hpa");

            console.log("ID pengirisan:", id_pengirisan);

            // Menyimpan data ID yang dibutuhkan untuk operasi delete
            $("#confirmDelete").data("id_pengirisan", id_pengirisan);
            $("#confirmDelete").data("id_hpa", id_hpa);

            // Menampilkan modal konfirmasi
            $('#deleteModal').modal('show');
        });

        // Menangani klik konfirmasi hapus pada modal
        $("#confirmDelete").on("click", function() {
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_hpa = $(this).data("id_hpa");

            // Mengirimkan permintaan AJAX untuk menghapus data
            $.ajax({
                url: "<?= base_url('pengirisan/delete'); ?>",
                type: "POST",
                data: {
                    id_pengirisan: id_pengirisan,
                    id_hpa: id_hpa
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Refresh halaman setelah operasi
                    } else {
                        alert("Gagal menghapus data.");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan pada server.");
                }
            });
            // Menutup modal setelah konfirmasi hapus
            $('#deleteModal').modal('hide');
        });

    });
</script>