// Set default font (menyerupai Bootstrap)
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystem,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// PIE CHART 1 – Total Pemeriksaan
const canvas1 = document.getElementById("pieChart1");
if (canvas1 && typeof pieChartData !== "undefined") {
    new Chart(canvas1, {
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
                    "rgba(231, 74, 59, 1)",
                    "rgba(78, 115, 223, 1)",
                    "rgba(28, 200, 138, 1)",
                    "rgba(255, 193, 7, 1)"
                ],
                hoverBackgroundColor: [
                    "rgba(231, 74, 59, 0.8)",
                    "rgba(78, 115, 223, 0.8)",
                    "rgba(28, 200, 138, 0.8)",
                    "rgba(255, 193, 7, 0.8)"
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
                    enabled: false
                }
            },
            hover: {
                mode: null
            }
        }
    });
}

// PIE CHART 2 – Data User
const canvas2 = document.getElementById("pieChart2");
if (canvas2 && typeof pieChartUserData !== "undefined") {
    new Chart(canvas2, {
        type: 'doughnut',
        data: {
            labels: pieChartUserData.labels || ["Endar", "Arlina", "Ilham", "Berlian"],
            datasets: [{
                data: pieChartUserData.data || [0, 0, 0, 0],
                backgroundColor: [
                    "#4e73df",
                    "#1cc88a",
                    "#36b9cc",
                    "#f6c23e"
                ],
                hoverBackgroundColor: [
                    "#2e59d9",
                    "#17a673",
                    "#2c9faf",
                    "#dda20a"
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
                    enabled: false
                }
            },
            hover: {
                mode: null
            }
        }
    });
}

// PIE CHART 3 – Data Tambahan (Opsional)
const canvas3 = document.getElementById("pieChart3");
if (canvas3 && typeof pieChartExtraData !== "undefined") {
    new Chart(canvas3, {
        type: 'doughnut',
        data: {
            labels: pieChartExtraData.labels || ["A", "B", "C", "D"],
            datasets: [{
                data: pieChartExtraData.data || [0, 0, 0, 0],
                backgroundColor: [
                    "#e74a3b",
                    "#858796",
                    "#20c9a6",
                    "#5a5c69"
                ],
                hoverBackgroundColor: [
                    "#be261c",
                    "#6c757d",
                    "#17a673",
                    "#343a40"
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
                    enabled: false
                }
            },
            hover: {
                mode: null
            }
        }
    });
}
