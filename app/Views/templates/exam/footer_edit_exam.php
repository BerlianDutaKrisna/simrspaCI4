<footer class="sticky-footer bg-danger text-white">
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-6 text-center text-md-left">
                <p class="mb-0">© 2025 SIMRSPA - Developed by <strong>Berlian Duta Krisna S.Tr.Kes</strong></p>
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

<!-- CDN Bootstrap 5 JS (bundle, menyertakan Popper.js) -->
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- CDN Summernote JS (Versi terbaru) -->
<script src="<?= base_url('assets/summernote/summernote.js') ?>"></script>
<!-- Inisialisasi Summernote -->
<script>
    function handleJumlahSlideChange(selectElement) {
        const customInput = document.getElementById('jumlah_slide_custom');
        if (selectElement.value === 'lainnya') {
            customInput.classList.remove('d-none');
        } else {
            customInput.classList.add('d-none');
            customInput.value = '';
        }
    }

    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']], // tombol gaya teks
                ['font', ['fontsize', 'fontname']], // font dan ukuran font
                ['para', ['ul', 'ol', 'paragraph']], // format paragraf
                ['color', ['color']], // pilihan warna
                ['view', ['codeview', 'help']] // menampilkan kode HTML dan bantuan
            ],
        });
    });

    $(document).ready(function() {
        $('.summernote_hasil').summernote({
            placeholder: '',
            tabsize: 2,
            height: 750,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']], // tombol gaya teks
                ['font', ['fontsize', 'fontname']], // font dan ukuran font
                ['para', ['ul', 'ol', 'paragraph']], // format paragraf
                ['color', ['color']], // pilihan warna
                ['view', ['codeview', 'help']] // menampilkan kode HTML dan bantuan
            ],
        });
    });

    $(document).ready(function() {
        $('.summernote_print').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
            airMode: true
        });

        // Cek status penulisan
        var statusPenulisan = "<?= $penulisan['status_penulisan'] ?? '' ?>";

        if (statusPenulisan !== "Selesai Penulisan") {
            $('.summernote_print').summernote('disable');
        }
    });
</script>
</body>

</html>