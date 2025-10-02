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

        // Jalankan saat halaman pertama kali dimuat
        syncKunjunganHariIni();

        // Jalankan setiap 60 detik
        setInterval(syncKunjunganHariIni, 60000);
    });
</script>