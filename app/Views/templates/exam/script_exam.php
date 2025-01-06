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
        $(document).on("click", ".view-penerimaan, .view-pengirisan", function() {
            var action = $(this).data("action"); // Menyimpan data action (penerimaan atau pengirisan)
            var id_hpa = $(this).data("id_penerimaan"); // Mendapatkan ID HPA (untuk penerimaan)
            var id_pengirisan = $(this).data("id_pengirisan"); // Mendapatkan ID Pengirisan (untuk pengirisan)

            console.log("Action:", action);
            console.log("ID HPA:", id_hpa);
            console.log("ID Pengirisan:", id_pengirisan);

            var url = "";
            var data = {};
            var type = ""; // Menyimpan tipe (penerimaan atau pengirisan)
            var id = ""; // Menyimpan ID yang sesuai

            // Tentukan URL dan data yang diperlukan berdasarkan action
            if (action === "penerimaan") {
                url = "<?= base_url('penerimaan/penerimaan_details'); ?>"; // URL untuk detail penerimaan
                data = {
                    id_penerimaan: encodeURIComponent(id_hpa)
                }; // ID Penerimaan
                type = "penerimaan"; // Set type ke 'penerimaan'
                id = id_hpa; // Set ID ke id_hpa
            } else if (action === "pengirisan") {
                url = "<?= base_url('pengirisan/pengirisan_details'); ?>"; // URL untuk detail pengirisan
                data = {
                    id_pengirisan: encodeURIComponent(id_pengirisan)
                }; // ID Pengirisan
                type = "pengirisan"; // Set type ke 'pengirisan'
                id = id_pengirisan; // Set ID ke id_pengirisan
            }

            // Mengambil data detail melalui AJAX
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                success: function(response) {
                    if (response.error) {
                        $("#modalBody").html(`<p>${response.error}</p>`);
                        $("#modalFooter").html(
                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                        );
                    } else {
                        var detailHtml = "";
                        if (action === "penerimaan") {
                            // Menampilkan detail penerimaan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_penerimaan || "-"}</p>
                        <p><strong>Status Penerimaan:</strong> ${response.status_penerimaan || "-"}</p>
                        <p><strong>Mulai Penerimaan:</strong> ${formatDateTime(response.mulai_penerimaan)}</p>
                        <p><strong>Selesai Penerimaan:</strong> ${formatDateTime(response.selesai_penerimaan)}</p>
                        <p><strong>Volume Fiksasi:</strong> ${response.indikator_1 || "-"}</p>
                        <p><strong>Terfiksasi Merata:</strong> ${response.indikator_2 || "-"}</p>
                    `;
                        } else if (action === "pengirisan") {
                            // Menampilkan detail pengirisan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pengirisan || "-"}</p>
                        <p><strong>Status Pengirisan:</strong> ${response.status_pengirisan || "-"}</p>
                        <p><strong>Mulai Pengirisan:</strong> ${formatDateTime(response.mulai_pengirisan)}</p>
                        <p><strong>Selesai Pengirisan:</strong> ${formatDateTime(response.selesai_pengirisan)}</p>
                    `;
                        }

                        $("#modalBody").html(detailHtml);

                        // Menggunakan base_url yang dihasilkan oleh PHP
                        var base_url = "<?= base_url(); ?>"; // Diberikan oleh PHP ke JavaScript

                        // Kemudian buat URL dinamis berdasarkan 'type' dan 'id'
                        var footerHtml = `
                    <a href="${base_url}/${type}/edit_${type}?id_${type}=${id}" class="btn btn-warning">Edit</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                `;

                        $("#modalFooter").html(footerHtml);
                    }

                    // Menampilkan modal dengan footer yang telah diperbarui
                    $("#viewModal").modal("show");
                },
                error: function() {
                    $("#modalBody").html('<p>Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>');
                    $("#modalFooter").html(
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                    );
                }
            });
        });
    });
</script>