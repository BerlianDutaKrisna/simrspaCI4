<!-- Content Row -->
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Jumlah Perbulan keseluruhan Setiap Jenis Pemeriksaan</h6> <!-- Judul grafik -->
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i> <!-- Ikon untuk dropdown -->
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Cetak Laporan:</div> <!-- Header dropdown -->
                        <a class="dropdown-item" href="<?= base_url('hpa/laporan'); ?>"><i class="fas fa-drumstick-bite fa-sm fa-fw mr-2 text-gray-600"></i> Sample Histopatologi</a>
                        <a class="dropdown-item" href="<?= base_url('frs/laporan'); ?>"><i class="fas fa-syringe fa-sm fa-fw mr-2 text-gray-600"></i> Sample Fine Needle Aspiration Biopsy</a>
                        <a class="dropdown-item" href="<?= base_url('srs/laporan'); ?>"><i class="fas fa-prescription-bottle fa-sm fa-fw mr-2 text-gray-600"></i> Sample Sitologi</a>
                        <a class="dropdown-item" href="<?= base_url('ihc/laporan'); ?>"><i class="fas fa-vials fa-sm fa-fw mr-2 text-gray-600"></i> Sample Imunohistokimia</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas> <!-- Area chart untuk menampilkan grafik -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chartData = <?= $chartData; ?>; // Data dari PHP dalam JSON
            console.log("Chart Data:", chartData); // Debugging

            if (!chartData || typeof chartData !== "object") {
                console.error("Data chart tidak valid:", chartData);
                return;
            }

            // Gabungkan semua bulan & tahun dari semua dataset agar menjadi satu sumbu X
            var allLabels = [...new Set([
                ...chartData.hpa.map(data => data.bulan + " " + data.tahun),
                ...chartData.frs.map(data => data.bulan + " " + data.tahun),
                ...chartData.srs.map(data => data.bulan + " " + data.tahun),
                ...chartData.ihc.map(data => data.bulan + " " + data.tahun)
            ])].sort(); // Sortir agar urut

            // Fungsi untuk mencocokkan data dengan label (jika tidak ada, set 0)
            function mapDataToLabels(dataset, labels) {
                return labels.map(label => {
                    var found = dataset.find(data => (data.bulan + " " + data.tahun) === label);
                    return found ? parseInt(found.total) : 0;
                });
            }

            // Mapping data ke dalam sumbu Y
            var hpaValues = mapDataToLabels(chartData.hpa, allLabels);
            var frsValues = mapDataToLabels(chartData.frs, allLabels);
            var srsValues = mapDataToLabels(chartData.srs, allLabels);
            var ihcValues = mapDataToLabels(chartData.ihc, allLabels);

            // Inisialisasi Chart.js
            var ctx = document.getElementById("myAreaChart").getContext("2d");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels, // Sumbu X (Bulan & Tahun)
                    datasets: [{
                            label: "Jumlah Sample HPA",
                            data: hpaValues,
                            backgroundColor: "rgba(231, 74, 59, 0.1)",
                            borderColor: "rgba(231, 74, 59, 1)",
                            pointBackgroundColor: "rgba(231, 74, 59, 1)",
                            pointBorderColor: "rgba(231, 74, 59, 1)",
                        },
                        {
                            label: "Jumlah Sample FRS",
                            data: frsValues,
                            backgroundColor: "rgba(78, 115, 223, 0.1)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                        },
                        {
                            label: "Jumlah Sample SRS",
                            data: srsValues,
                            backgroundColor: "rgba(28, 200, 138, 0.1)",
                            borderColor: "rgba(28, 200, 138, 1)",
                            pointBackgroundColor: "rgba(28, 200, 138, 1)",
                            pointBorderColor: "rgba(28, 200, 138, 1)",

                        },
                        {
                            label: "Jumlah Sample IHC",
                            data: ihcValues,
                            backgroundColor: "rgba(255, 193, 7, 0.1)",
                            borderColor: "rgba(255, 193, 7, 1)",
                            pointBackgroundColor: "rgba(255, 193, 7, 1)",
                            pointBorderColor: "rgba(255, 193, 7, 1)",
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10,
                                suggestedMin: 0,
                                suggestedMax: 100
                            }
                        }
                    }
                }
            });
        });
    </script>


    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Jumlah Total Keseluruhan Setiap Jenis Pemeriksaan</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Cetak Laporan:</div>
                        <a class="dropdown-item" href="#"> <i class="fas fa-poll fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Jenis Pemeriksaan</a>
                        <a class="dropdown-item" href="#"> <i class="fas fa-procedures fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Jumlah Pasien</a>
                        <a class="dropdown-item" href="#"> <i class="fas fa-users fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Kinerja Users</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-danger"></i> HPA <!-- Merah -->
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> FRS <!-- Biru -->
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> SRS <!-- Hijau -->
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> IHC <!-- Kuning -->
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var pieChartData = <?= $pieChartData; ?>; // Data dari PHP dalam JSON
            console.log("Pie Chart Data:", pieChartData); // Debugging

            if (!pieChartData || typeof pieChartData !== "object") {
                console.error("Data chart tidak valid:", pieChartData);
                return;
            }

            var ctx = document.getElementById("myPieChart").getContext("2d");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["HPA", "FRS", "SRS", "IHC"],
                    datasets: [{
                        data: [
                            pieChartData.hpa || 0,
                            pieChartData.frs || 0,
                            pieChartData.srs || 0,
                            pieChartData.ihc || 0
                        ],
                        backgroundColor: [
                            "rgba(231, 74, 59, 1)", // Merah untuk HPA
                            "rgba(78, 115, 223, 1)", // Biru untuk FRS
                            "rgba(28, 200, 138, 1)", // Hijau untuk SRS
                            "rgba(255, 193, 7, 1)" // Kuning untuk IHC
                        ],
                        borderColor: "rgba(234, 236, 244, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            enabled: false // Menonaktifkan tooltip
                        }
                    },
                    hover: {
                        mode: null // Menonaktifkan efek hover
                    }
                }
            });
        });
    </script>
</div>

<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Jumlah Total Keseluruhan Barang Habis Pakai</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Cetak Laporan:</div>
                    <a class="dropdown-item" href="#"> <i class="fas fa-boxes fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Stock Barang</a>
                    <a class="dropdown-item" href="#"> <i class="fas fa-box fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Barang Masuk</a>
                    <a class="dropdown-item" href="#"> <i class="fas fa-box-open fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Barang Keluar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="myBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['NBF 10%', 'Alkohol 96%', 'Alkohol 100%', 'Xylol', 'Hematoxilin', 'Eosin', 'Orange G', 'EA 50', 'Parafin', 'Aqua Bidest'],
            datasets: [{
                label: 'Jumlah Stok',
                data: [50, 120, 75, 10, 100, 90, 60, 45, 30, 20], // Sesuaikan jumlah data dengan labels
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(201, 203, 207, 0.2)',
                    'rgba(140, 220, 130, 0.2)',
                    'rgba(220, 140, 130, 0.2)',
                    'rgba(130, 140, 220, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(201, 203, 207, 1)',
                    'rgba(140, 220, 130, 1)',
                    'rgba(220, 140, 130, 1)',
                    'rgba(130, 140, 220, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>