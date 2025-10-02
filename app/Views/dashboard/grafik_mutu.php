<?php
// Data dummy statis
$chartData = [
    'kepatuhan_kebersihan_tangan' => [],
    'kepatuhan_apd'               => [],
    'identifikasi_pasien'         => [],
    'kesesuaian_form'             => [],
];

// Buat 12 bulan terakhir
$bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$tahun = date('Y');

foreach ($bulanList as $bulan) {
    $chartData['kepatuhan_kebersihan_tangan'][] = ['bulan'=>$bulan,'tahun'=>$tahun,'target'=>100,'capaian'=>rand(85,100)];
    $chartData['kepatuhan_apd'][] = ['bulan'=>$bulan,'tahun'=>$tahun,'target'=>100,'capaian'=>rand(100,100)];
    $chartData['identifikasi_pasien'][] = ['bulan'=>$bulan,'tahun'=>$tahun,'target'=>100,'capaian'=>rand(100,100)];
    $chartData['kesesuaian_form'][] = ['bulan'=>$bulan,'tahun'=>$tahun,'target'=>100,'capaian'=>rand(95,100)];
}
?>

<div class="row">
    <!-- Kepatuhan Kebersihan Tangan -->
    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kepatuhan Kebersihan Tangan</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartKebersihanTangan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kepatuhan APD -->
    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kepatuhan Penggunaan APD</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartAPD"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Identifikasi Pasien -->
    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Identifikasi Pasien</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartIdentifikasi"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kesesuaian Form dengan Sampel -->
    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kesesuaian Form Pemeriksaan</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartKesesuaianForm"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var chartData = <?= json_encode($chartData) ?>;

    function initChart(canvasId, dataKey, capaianColor) {
        var labels = chartData[dataKey].map(d => d.bulan + ' ' + d.tahun);
        var targetData = chartData[dataKey].map(d => d.target);
        var capaianData = chartData[dataKey].map(d => d.capaian);

        var ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target (%)',
                        data: targetData,
                        borderColor: 'rgba(231, 74, 59, 1)', // merah
                        backgroundColor: 'rgba(231, 74, 59, 0.1)',
                        fill: true
                    },
                    {
                        label: 'Capaian (%)',
                        data: capaianData,
                        borderColor: capaianColor,
                        backgroundColor: capaianColor.replace('1)', '0.1)'), // transparansi 0.1
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 120
                        }
                    }]
                }
            }
        });
    }

    initChart('chartKebersihanTangan', 'kepatuhan_kebersihan_tangan', 'rgba(78, 115, 223, 1)'); // biru
    initChart('chartAPD', 'kepatuhan_apd', 'rgba(28, 200, 138, 1)'); // hijau
    initChart('chartIdentifikasi', 'identifikasi_pasien', 'rgba(246, 194, 62, 1)'); // kuning
    initChart('chartKesesuaianForm', 'kesesuaian_form', 'rgba(153, 102, 255, 1)'); // ungu
});
</script>
