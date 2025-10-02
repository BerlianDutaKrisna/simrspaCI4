
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