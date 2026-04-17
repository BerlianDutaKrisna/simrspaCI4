<script>
    // fungsi copy
    function copyToClipboard(text) {
        let textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        textArea.remove();

        showToast("Copied: " + text);
    }

    // toast
    function showToast(message) {
        const toast = document.createElement("div");
        toast.innerText = message;

        toast.style.position = "fixed";
        toast.style.top = "20px";
        toast.style.left = "50%";
        toast.style.transform = "translateX(-50%)";
        toast.style.background = "#1cc88a";
        toast.style.color = "#fff";
        toast.style.padding = "10px 20px";
        toast.style.borderRadius = "5px";
        toast.style.boxShadow = "0 0 10px rgba(0,0,0,0.2)";
        toast.style.zIndex = "9999";
        toast.style.opacity = "0";
        toast.style.transition = "opacity 0.3s";

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "1";
        }, 100);

        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 2000);
    }

    // event delegation (WAJIB untuk DataTable)
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("copy-norm")) {
            copyToClipboard(e.target.dataset.text);
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function syncKunjunganHariIni() {
            fetch('<?= base_url("api/kunjungan/getKunjunganHariIni") ?>', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Sinkronisasi selesai:", data);
                })
                .catch(error => {
                    console.error("Gagal sinkronisasi:", error);
                });
        }
        syncKunjunganHariIni();
        setInterval(syncKunjunganHariIni, 60000);
    });
</script>
<!-- Footer -->
<footer class="sticky-footer bg-danger text-white">
    <div class="container my-auto">
        <div class="row">
            <!-- Left Side: Developer Information -->
            <div class="col-md-6 text-center text-md-left">
                <p class="mb-0">© 2025 SIMRSPA — Developed by <strong>Berlian Duta Krisna, S.Tr.Kes</strong></p>
                <p class="mb-0 text-sm">Versi 2.0</p>
                <p class="text-sm">
                    <i class="fab fa-php"></i> PHP (CodeIgniter 4),
                    <i class="fab fa-bootstrap"></i> Bootstrap 4,
                    <i class="fas fa-database"></i> MySQL (MySQLi)
                </p>
            </div>
            <!-- Right Side: Social Media & Contact Info -->
            <div class="col-md-6 text-center text-md-right">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="https://www.linkedin.com/in/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                            <!-- Ikon LinkedIn, mengarah ke profil LinkedIn -->
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.instagram.com/berliandutakrisna/" class="text-white" target="_blank">
                            <i class="fab fa-instagram"></i>
                            <!-- Ikon Instagram, mengarah ke profil Instagram -->
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="mailto:berliandutakrisna@gmail.com" class="text-white">
                            <i class="fas fa-envelope"></i>
                            <!-- Ikon email, mengarah ke aplikasi email dengan alamat yang telah ditentukan -->
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- End of Footer -->

<!-- Footer Section Ends Here -->
</div>
<!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script> <!-- Menambahkan jQuery untuk interaksi DOM -->
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script> <!-- Menambahkan Bootstrap JS untuk komponen interaktif -->

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/jquery-easing/jquery.easing.min.js') ?>"></script> <!-- Menambahkan efek easing untuk transisi -->

<!-- Custom scripts for all pages -->
<script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script> <!-- Skrip utama untuk pengelolaan halaman admin -->

<!-- Page level plugins -->


<!-- Page level custom scripts -->
<script src="<?= base_url('assets/chart/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/analisisdata/chart-pie-demo.js') ?>"></script>
<!-- Table Search plugins -->
<script src="<?= base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/datatables-demo.js') ?>"></script>
<script src="<?= base_url('assets/datatables/js/datatables-demoButtons.js') ?>"></script>

</html>