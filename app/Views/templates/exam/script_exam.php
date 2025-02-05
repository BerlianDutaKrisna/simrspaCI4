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
        $(document).on("click", ".delete-hpa, .delete-pengirisan, .delete-pemotongan, .delete-pemprosesan, .delete-penanaman, .delete-pemotongan_tipis, .delete-pewarnaan, .delete-pembacaan, .delete-penulisan, .delete-pemverifikasi, .delete-autorized, .delete-pencetakan", function() {
            var action = $(this).data("action"); // Menyimpan data action (hpa atau pengirisan)
            var id_hpa = $(this).data("id_hpa");
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_pemotongan = $(this).data("id_pemotongan");
            var id_pemprosesan = $(this).data("id_pemprosesan");
            var id_penanaman = $(this).data("id_penanaman");
            var id_pemotongan_tipis = $(this).data("id_pemotongan_tipis");
            var id_pewarnaan = $(this).data("id_pewarnaan");
            var id_pembacaan = $(this).data("id_pembacaan");
            var id_penulisan = $(this).data("id_penulisan");
            var id_pemverifikasi = $(this).data("id_pemverifikasi");
            var id_autorized = $(this).data("id_autorized");
            var id_pencetakan = $(this).data("id_pencetakan");

            // Menyimpan data ID yang dibutuhkan untuk operasi delete
            $("#confirmDelete").data("action", action);
            $("#confirmDelete").data("id_hpa", id_hpa);
            $("#confirmDelete").data("id_pengirisan", id_pengirisan);
            $("#confirmDelete").data("id_pemotongan", id_pemotongan);
            $("#confirmDelete").data("id_pemprosesan", id_pemprosesan);
            $("#confirmDelete").data("id_penanaman", id_penanaman);
            $("#confirmDelete").data("id_pemotongan_tipis", id_pemotongan_tipis);
            $("#confirmDelete").data("id_pewarnaan", id_pewarnaan);
            $("#confirmDelete").data("id_pembacaan", id_pembacaan);
            $("#confirmDelete").data("id_penulisan", id_penulisan);
            $("#confirmDelete").data("id_pemverifikasi", id_pemverifikasi);
            $("#confirmDelete").data("id_autorized", id_autorized);
            $("#confirmDelete").data("id_pencetakan", id_pencetakan);

            // Menampilkan modal konfirmasi
            $('#deleteModal').modal('show');
        });

        // Menangani klik konfirmasi hapus pada modal
        $("#confirmDelete").on("click", function() {
            var action = $(this).data("action");
            var id_hpa = $(this).data("id_hpa");
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_pemotongan = $(this).data("id_pemotongan");
            var id_pemprosesan = $(this).data("id_pemprosesan");
            var id_penanaman = $(this).data("id_penanaman");
            var id_pemotongan_tipis = $(this).data("id_pemotongan_tipis");
            var id_pewarnaan = $(this).data("id_pewarnaan");
            var id_pembacaan = $(this).data("id_pembacaan");
            var id_penulisan = $(this).data("id_penulisan");
            var id_pemverifikasi = $(this).data("id_pemverifikasi");
            var id_autorized = $(this).data("id_autorized");
            var id_pencetakan = $(this).data("id_pencetakan");

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
            } else if (action === "pemotongan") {
                url = "<?= base_url('pemotongan/delete'); ?>"; // URL penghapusan pemotongan
                data = {
                    id_pemotongan: id_pemotongan,
                    id_hpa: id_hpa
                };
            } else if (action === "pemprosesan") {
                url = "<?= base_url('pemprosesan/delete'); ?>"; // URL penghapusan pemprosesan
                data = {
                    id_pemprosesan: id_pemprosesan,
                    id_hpa: id_hpa
                };
            } else if (action === "penanaman") {
                url = "<?= base_url('penanaman/delete'); ?>"; // URL penghapusan penanaman
                data = {
                    id_penanaman: id_penanaman,
                    id_hpa: id_hpa
                };
            } else if (action === "pemotongan_tipis") {
                url = "<?= base_url('pemotongan_tipis/delete'); ?>"; // URL penghapusan pemotongan_tipis
                data = {
                    id_pemotongan_tipis: id_pemotongan_tipis,
                    id_hpa: id_hpa
                };
            } else if (action === "pewarnaan") {
                url = "<?= base_url('pewarnaan/delete'); ?>"; // URL penghapusan pewarnaan
                data = {
                    id_pewarnaan: id_pewarnaan,
                    id_hpa: id_hpa
                };
            } else if (action === "pembacaan") {
                url = "<?= base_url('pembacaan/delete'); ?>"; // URL penghapusan pembacaan
                data = {
                    id_pembacaan: id_pembacaan,
                    id_hpa: id_hpa
                };
            } else if (action === "penulisan") {
                url = "<?= base_url('penulisan/delete'); ?>"; // URL penghapusan penulisan
                data = {
                    id_penulisan: id_penulisan,
                    id_hpa: id_hpa
                };
            } else if (action === "pemverifikasi") {
                url = "<?= base_url('pemverifikasi/delete'); ?>"; // URL penghapusan pemverifikasi
                data = {
                    id_pemverifikasi: id_pemverifikasi,
                    id_hpa: id_hpa
                };
            } else if (action === "autorized") {
                url = "<?= base_url('autorized/delete'); ?>"; // URL penghapusan autorized
                data = {
                    id_autorized: id_autorized,
                    id_hpa: id_hpa
                };
            } else if (action === "pencetakan") {
                url = "<?= base_url('pencetakan/delete'); ?>"; // URL penghapusan pencetakan
                data = {
                    id_pencetakan: id_pencetakan,
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
        // Detail Proses
        // ==========================
        $(document).on("click", ".view-penerimaan, .view-pengirisan, .view-pemotongan, .view-pemprosesan, .view-penanaman, .view-pemotongan_tipis, .view-pewarnaan, .view-pembacaan, .view-penulisan, .view-pemverifikasi, .view-autorized, .view-pencetakan, .view-mutu", function() {
            var action = $(this).data("action");
            var id_penerimaan = $(this).data("id_penerimaan");
            var id_pengirisan = $(this).data("id_pengirisan");
            var id_pemotongan = $(this).data("id_pemotongan");
            var id_pemprosesan = $(this).data("id_pemprosesan");
            var id_penanaman = $(this).data("id_penanaman");
            var id_pemotongan_tipis = $(this).data("id_pemotongan_tipis");
            var id_pewarnaan = $(this).data("id_pewarnaan");
            var id_pembacaan = $(this).data("id_pembacaan");
            var id_penulisan = $(this).data("id_penulisan");
            var id_pemverifikasi = $(this).data("id_pemverifikasi");
            var id_autorized = $(this).data("id_autorized");
            var id_pencetakan = $(this).data("id_pencetakan");
            var id_mutu = $(this).data("id_mutu");

            var url = "";
            var data = {};
            var type = ""; // Menyimpan tipe (penerimaan atau pengirisan)
            var id = ""; // Menyimpan ID yang sesuai

            // Tentukan URL dan data yang diperlukan berdasarkan action
            if (action === "penerimaan") {
                url = "<?= base_url('penerimaan/penerimaan_details'); ?>";
                data = {
                    id_penerimaan: encodeURIComponent(id_penerimaan)
                };
                type = "penerimaan";
                id = id_penerimaan;
            } else if (action === "pengirisan") {
                url = "<?= base_url('pengirisan/pengirisan_details'); ?>";
                data = {
                    id_pengirisan: encodeURIComponent(id_pengirisan)
                };
                type = "pengirisan";
                id = id_pengirisan;
            } else if (action === "pemotongan") {
                url = "<?= base_url('pemotongan/pemotongan_details'); ?>";
                data = {
                    id_pemotongan: encodeURIComponent(id_pemotongan)
                };
                type = "pemotongan";
                id = id_pemotongan;
            } else if (action === "pemprosesan") {
                url = "<?= base_url('pemprosesan/pemprosesan_details'); ?>";
                data = {
                    id_pemprosesan: encodeURIComponent(id_pemprosesan)
                };
                type = "pemprosesan";
                id = id_pemprosesan;
            } else if (action === "penanaman") {
                url = "<?= base_url('penanaman/penanaman_details'); ?>";
                data = {
                    id_penanaman: encodeURIComponent(id_penanaman)
                };
                type = "penanaman";
                id = id_penanaman;
            } else if (action === "pemotongan_tipis") {
                url = "<?= base_url('pemotongan_tipis/pemotongan_tipis_details'); ?>";
                data = {
                    id_pemotongan_tipis: encodeURIComponent(id_pemotongan_tipis)
                };
                type = "pemotongan_tipis";
                id = id_pemotongan_tipis;
            } else if (action === "pewarnaan") {
                url = "<?= base_url('pewarnaan/pewarnaan_details'); ?>";
                data = {
                    id_pewarnaan: encodeURIComponent(id_pewarnaan)
                };
                type = "pewarnaan";
                id = id_pewarnaan;
            } else if (action === "pembacaan") {
                url = "<?= base_url('pembacaan/pembacaan_details'); ?>";
                data = {
                    id_pembacaan: encodeURIComponent(id_pembacaan)
                };
                type = "pembacaan";
                id = id_pembacaan;
            } else if (action === "penulisan") {
                url = "<?= base_url('penulisan/penulisan_details'); ?>";
                data = {
                    id_penulisan: encodeURIComponent(id_penulisan)
                };
                type = "penulisan";
                id = id_penulisan;
            } else if (action === "pemverifikasi") {
                url = "<?= base_url('pemverifikasi/pemverifikasi_details'); ?>";
                data = {
                    id_pemverifikasi: encodeURIComponent(id_pemverifikasi)
                };
                type = "pemverifikasi";
                id = id_pemverifikasi;
            } else if (action === "autorized") {
                url = "<?= base_url('autorized/autorized_details'); ?>";
                data = {
                    id_autorized: encodeURIComponent(id_autorized)
                };
                type = "autorized";
                id = id_autorized;
            } else if (action === "pencetakan") {
                url = "<?= base_url('pencetakan/pencetakan_details'); ?>";
                data = {
                    id_pencetakan: encodeURIComponent(id_pencetakan)
                };
                type = "pencetakan";
                id = id_pencetakan;
            } else if (action === "mutu") {
                url = "<?= base_url('mutu/mutu_details'); ?>";
                data = {
                    id_mutu: encodeURIComponent(id_mutu)
                };
                type = "mutu";
                id = id_mutu;
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
                        } else if (action === "pemotongan") {
                            // Menampilkan detail pemotongan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pemotongan || "-"}</p>
                        <p><strong>Status Pemotongan:</strong> ${response.status_pemotongan || "-"}</p>
                        <p><strong>Mulai Pemotongan:</strong> ${formatDateTime(response.mulai_pemotongan)}</p>
                        <p><strong>Selesai Pemotongan:</strong> ${formatDateTime(response.selesai_pemotongan)}</p>
                    `;
                        } else if (action === "pemprosesan") {
                            // Menampilkan detail pemprosesan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pemprosesan || "-"}</p>
                        <p><strong>Status Pemprosesan:</strong> ${response.status_pemprosesan || "-"}</p>
                        <p><strong>Mulai Pemprosesan:</strong> ${formatDateTime(response.mulai_pemprosesan)}</p>
                        <p><strong>Selesai Pemprosesan:</strong> ${formatDateTime(response.selesai_pemprosesan)}</p>
                    `;
                        } else if (action === "penanaman") {
                            // Menampilkan detail penanaman
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_penanaman || "-"}</p>
                        <p><strong>Status Penanaman:</strong> ${response.status_penanaman || "-"}</p>
                        <p><strong>Mulai Penanaman:</strong> ${formatDateTime(response.mulai_penanaman)}</p>
                        <p><strong>Selesai Penanaman:</strong> ${formatDateTime(response.selesai_penanaman)}</p>
                    `;
                        } else if (action === "pemotongan_tipis") {
                            // Menampilkan detail pemotongan_tipis
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pemotongan_tipis || "-"}</p>
                        <p><strong>Status Pemotongan Tipis:</strong> ${response.status_pemotongan_tipis || "-"}</p>
                        <p><strong>Mulai Pemotongan Tipis:</strong> ${formatDateTime(response.mulai_pemotongan_tipis)}</p>
                        <p><strong>Selesai Pemotongan Tipis:</strong> ${formatDateTime(response.selesai_pemotongan_tipis)}</p>
                    `;
                        } else if (action === "pewarnaan") {
                            // Menampilkan detail pewarnaan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pewarnaan || "-"}</p>
                        <p><strong>Status Pewarnaan:</strong> ${response.status_pewarnaan || "-"}</p>
                        <p><strong>Mulai Pewarnaan:</strong> ${formatDateTime(response.mulai_pewarnaan)}</p>
                        <p><strong>Selesai Pewarnaan:</strong> ${formatDateTime(response.selesai_pewarnaan)}</p>
                    `;
                        } else if (action === "pembacaan") {
                            // Menampilkan detail pembacaan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pembacaan || "-"}</p>
                        <p><strong>Status Pembacaan:</strong> ${response.status_pembacaan || "-"}</p>
                        <p><strong>Mulai Pembacaan:</strong> ${formatDateTime(response.mulai_pembacaan)}</p>
                        <p><strong>Selesai Pembacaan:</strong> ${formatDateTime(response.selesai_pembacaan)}</p>
                    `;
                        } else if (action === "penulisan") {
                            // Menampilkan detail penulisan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_penulisan || "-"}</p>
                        <p><strong>Status Penulisan:</strong> ${response.status_penulisan || "-"}</p>
                        <p><strong>Mulai Penulisan:</strong> ${formatDateTime(response.mulai_penulisan)}</p>
                        <p><strong>Selesai Penulisan:</strong> ${formatDateTime(response.selesai_penulisan)}</p>
                    `;
                        } else if (action === "pemverifikasi") {
                            // Menampilkan detail pemverifikasi
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pemverifikasi || "-"}</p>
                        <p><strong>Status Pemverifikasi:</strong> ${response.status_pemverifikasi || "-"}</p>
                        <p><strong>Mulai Pemverifikasi:</strong> ${formatDateTime(response.mulai_pemverifikasi)}</p>
                        <p><strong>Selesai Pemverifikasi:</strong> ${formatDateTime(response.selesai_pemverifikasi)}</p>
                    `;
                        } else if (action === "autorized") {
                            // Menampilkan detail autorized
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_autorized || "-"}</p>
                        <p><strong>Status autorized:</strong> ${response.status_autorized || "-"}</p>
                        <p><strong>Mulai autorized:</strong> ${formatDateTime(response.mulai_autorized)}</p>
                        <p><strong>Selesai autorized:</strong> ${formatDateTime(response.selesai_autorized)}</p>
                    `;
                        } else if (action === "pencetakan") {
                            // Menampilkan detail pencetakan
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Dikerjakan Oleh:</strong> ${response.nama_user_pencetakan || "-"}</p>
                        <p><strong>Status Pencetakan:</strong> ${response.status_pencetakan || "-"}</p>
                        <p><strong>Mulai Pencetakan:</strong> ${formatDateTime(response.mulai_pencetakan)}</p>
                        <p><strong>Selesai Pencetakan:</strong> ${formatDateTime(response.selesai_pencetakan)}</p>
                    `;
                        } else if (action === "mutu") {
                            // Menampilkan detail mutu
                            detailHtml = `
                        <p><strong>Kode HPA:</strong> ${response.kode_hpa || "-"}</p>
                        <p><strong>Volume cairan sesuai:</strong> ${response.indikator_1 || "-"}</p>
                        <p><strong>Jaringan terfiksasi merata:</strong> ${response.indikator_2 || "-"}</p>
                        <p><strong>Blok parafin tidak ada fragmentasi:</strong> ${response.indikator_3 || "-"}</p>
                        <p><strong>Sediaan tanpa lipatan:</strong> ${response.indikator_4 || "-"}</p>
                        <p><strong>Sediaan tanpa goresan mata pisau:</strong> ${response.indikator_5 || "-"}</p>
                        <p><strong>Kontras warna sediaan cukup jelas:</strong> ${response.indikator_6 || "-"}</p>
                        <p><strong>Sediaan tanpa gelembung udara:</strong> ${response.indikator_7 || "-"}</p>
                        <p><strong>Sediaan tanpa bercak / sidik jari:</strong> ${response.indikator_8 || "-"}</p>
                        <p><strong>Indikator 9:</strong> ${response.indikator_9 || "-"}</p>
                        <p><strong>Indikator 10:</strong> ${response.indikator_10 || "-"}</p>
                        <p><strong>Total nilai mutu:</strong> ${response.total_nilai_mutu || "-"}</p>
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