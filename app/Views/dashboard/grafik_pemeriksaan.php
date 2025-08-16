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
                        <a class="dropdown-item" href="<?= base_url('hpa/laporan_pemeriksaan'); ?>"><i class="fas fa-drumstick-bite fa-sm fa-fw mr-2 text-gray-600"></i> Sample Histopatologi</a>
                        <a class="dropdown-item" href="<?= base_url('frs/laporan_pemeriksaan'); ?>"><i class="fas fa-syringe fa-sm fa-fw mr-2 text-gray-600"></i> Sample Fine Needle Aspiration Biopsy</a>
                        <a class="dropdown-item" href="<?= base_url('srs/laporan_pemeriksaan'); ?>"><i class="fas fa-prescription-bottle fa-sm fa-fw mr-2 text-gray-600"></i> Sample Sitologi</a>
                        <a class="dropdown-item" href="<?= base_url('ihc/laporan_pemeriksaan'); ?>"><i class="fas fa-vials fa-sm fa-fw mr-2 text-gray-600"></i> Sample Imunohistokimia</a>
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

            var allLabels = [...new Set([
                ...chartData.hpa.map(data => data.bulan + " " + data.tahun),
                ...chartData.frs.map(data => data.bulan + " " + data.tahun),
                ...chartData.srs.map(data => data.bulan + " " + data.tahun),
                ...chartData.ihc.map(data => data.bulan + " " + data.tahun)
            ])];

            // Mapping nama bulan Indonesia ke angka 1-12
            const monthMap = {
                "Januari": 1,
                "Februari": 2,
                "Maret": 3,
                "April": 4,
                "Mei": 5,
                "Juni": 6,
                "Juli": 7,
                "Agustus": 8,
                "September": 9,
                "Oktober": 10,
                "November": 11,
                "Desember": 12
            };

            allLabels.sort((a, b) => {
                const [monthA, yearA] = a.split(" ");
                const [monthB, yearB] = b.split(" ");

                if (parseInt(yearA) !== parseInt(yearB)) {
                    return parseInt(yearA) - parseInt(yearB);
                }
                return monthMap[monthA] - monthMap[monthB];
            });


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
                        <a class="dropdown-item" href="#"><i class="fas fa-poll fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Jenis Pemeriksaan</a>
                        <a class="dropdown-item" href="<?= base_url('patient/laporan'); ?>"><i class="fas fa-procedures fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Jumlah Pasien</a>
                        <a class="dropdown-item" href="<?= base_url('users/laporan'); ?>"><i class="fas fa-users fa-sm fa-fw mr-2 text-gray-600"></i>Laporan Seluruh Kinerja Users</a>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <div id="pieChartCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <canvas id="pieChart1" class="w-100" width="400" height="275"></canvas>
                        </div>
                        <div class="carousel-item">
                            <canvas id="pieChart2" class="w-100" width="400" height="275"></canvas>
                        </div>
                        <div class="carousel-item">
                            <canvas id="pieChart3" class="w-100" width="400" height="275"></canvas>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#pieChartCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="sr-only">Sebelumnya</span>
                    </a>
                    <a class="carousel-control-next" href="#pieChartCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="sr-only">Berikutnya</span>
                    </a>
                </div>

                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-chart-pie"></i> Total Keseluruhan Data
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.pieChartData = <?= json_encode($pieChartData); ?>;
        window.pieChartUserData = <?= json_encode($pieChartUserData); ?>;
        window.pieChartDokterData = <?= json_encode($pieChartDokterData); ?>;
    </script>

</div>