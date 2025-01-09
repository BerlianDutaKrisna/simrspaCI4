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
    $(document).ready(function() {
        // Gabungkan data makroskopis dan mikroskopis
        var makroskopis = "<?= addslashes($hpa['makroskopis_hpa'] ?? '') ?>";
        var mikroskopis = "<?= addslashes($hpa['mikroskopis_hpa'] ?? '') ?>";

        // Gabungkan keduanya dan masukkan ke dalam summernote
        var combinedData = makroskopis + "<br><br>" + mikroskopis;

        // Set data gabungan ke dalam summernote
        $('.summernote').summernote('code', combinedData);
    });
</script>
</body>

</html>