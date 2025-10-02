<script>
    document.addEventListener("DOMContentLoaded", function() {
        function syncPengirimanSimrs() {
            fetch('<?= base_url("api/pengiriman-data-simrs/kirim") ?>', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    console.log("[SIMRS] Hasil pengiriman:", data);
                    if (data.detail) {
                        data.detail.forEach(item => {
                            if (item.status === 'success') {
                                console.log(`✅ ID ${item.id} berhasil terkirim.`, item.response);
                            } else {
                                console.error(`❌ ID ${item.id} gagal terkirim.`, item.error);
                            }
                        });
                    }
                })
                .catch(error => console.error("[SIMRS] Gagal sinkronisasi:", error));
        }

        // Jalankan pertama kali
        syncPengirimanSimrs();

        // Jalankan tiap 60 detik
        setInterval(syncPengirimanSimrs, 60000);
    });
</script>
<!-- Footer -->
<footer class="sticky-footer bg-danger text-white">
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-6 text-center text-md-left">
                <p class="mb-0">© 2025 SIMRSPA — Developed by <strong>Berlian Duta Krisna, S.Tr.Kes</strong></p>
                <p class="mb-0 text-sm">Versi 2.0</p>
                <p class="text-sm">
                    <i class="fab fa-php"></i> PHP (CodeIgniter 4),
                    <i class="fab fa-bootstrap"></i> Bootstrap 4,
                    <i class="fas fa-database"></i> MySQL (MySQLi)
                </p>
            </div>
            <div class="col-md-6 text-center text-md-right">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="https://www.linkedin.com/in/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.instagram.com/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="mailto:berliandutakrisna@gmail.com" class="text-white">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/summernote/summernote.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('.summernote_print').summernote({
            placeholder: '',
            tabsize: 2,
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'fontname']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['color', ['color']],
                ['view', ['codeview', 'help']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    // Sesuaikan tinggi berdasarkan konten
                    adjustSummernoteHeight($editable);
                },
                onInit: function() {
                    // Sesuaikan tinggi saat inisialisasi
                    adjustSummernoteHeight($('.note-editable'));
                }
            }
        });

        function adjustSummernoteHeight($editable) {
            // Atur tinggi berdasarkan konten
            $editable.css('height', 'auto'); // Reset tinggi
            $editable.css('height', $editable.prop('scrollHeight') + 'px'); // Sesuaikan tinggi
        }
    });
</script>