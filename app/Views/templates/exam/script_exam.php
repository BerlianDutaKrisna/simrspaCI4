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


        // Menangani event click pada tombol Penerima untuk memunculkan modal
        $('#penerimaModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var id_hpa = button.data('id_hpa'); // Kode HPA
            var penerima_hpa = button.data('penerima_hpa'); // Nama penerima

            var modal = $(this);
            modal.find('#id_hpa').val(id_hpa); // Isi id_hpa ke input hidden
            modal.find('#penerima_hpa').val(""); // Isi penerima_hpa dengan data dari tombol
        });
        // ==========================
        // Modal Status HPA
        // ==========================
        $('#statusHpaModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var idHpa = button.data('id_hpa'); // Extract id_hpa from data-* attributes
            var statusHpa = button.data('status_hpa'); // Extract status_hpa

            var modal = $(this);
            modal.find('.modal-body #id_hpa').val(idHpa); // Set id_hpa in the modal input
            modal.find('.modal-body #status_hpa').val(statusHpa); // Set status_hpa in the select
        });

        // ==========================
        // Hapus DATA
        // ==========================
        $(document).on("click", ".delete-hpa, .delete-pengirisan", function() {
            var action = $(this).data("action"); // Menyimpan data action (hpa atau pengirisan)
            var id_hpa = $(this).data("id_hpa");
            var id_pengirisan = $(this).data("id_pengirisan");

            console.log("Action:", action);
            console.log("ID HPA:", id_hpa);
            console.log("ID Pengirisan:", id_pengirisan);

            // Menyimpan data ID yang dibutuhkan untuk operasi delete
            $("#confirmDelete").data("action", action);
            $("#confirmDelete").data("id_hpa", id_hpa);
            $("#confirmDelete").data("id_pengirisan", id_pengirisan);

            // Menampilkan modal konfirmasi
            $('#deleteModal').modal('show');
        });

        // Menangani klik konfirmasi hapus pada modal
        $("#confirmDelete").on("click", function() {
            var action = $(this).data("action");
            var id_hpa = $(this).data("id_hpa");
            var id_pengirisan = $(this).data("id_pengirisan");

            var url = "";
            var data = {};

            if (action === "hpa") {
                url = "<?= base_url('exam/delete'); ?>"; // URL penghapusan HPA
                data = {
                    id_hpa: id_hpa
                };
            } else if (action === "pengirisan") {
                url = "<?= base_url('pengirisan/delete'); ?>"; // URL penghapusan pengirisan
                data = {
                    id_pengirisan: id_pengirisan,
                    id_hpa: id_hpa
                };
            }

            // Mengirimkan permintaan AJAX untuk menghapus data
            $.ajax({
                url: url,
                type: "POST",
                data: data,
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
                            <a href="<?= site_url('penerimaan/edit_penerimaan') ?>?id_penerimaan=${id_penerimaan}" class="btn btn-warning">Edit</a>
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
    });
</script>