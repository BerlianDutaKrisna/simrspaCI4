document.addEventListener("DOMContentLoaded", function () {
    fetch("<?= base_url('chart-data') ?>")
        .then(response => response.json())
        .then(data => {
            let labels = data.map(item => item.bulan);
            let values = data.map(item => item.jumlah_sample);

            var ctx = document.getElementById("myAreaChart").getContext("2d");
            var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Jumlah Sample",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        data: values,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        })
        .catch(error => console.error("Error fetching chart data:", error));
});
