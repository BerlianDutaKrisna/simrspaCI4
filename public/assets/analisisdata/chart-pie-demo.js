let chart1, chart2, chart3;

function renderChart1() {
    const pieChartData = window.pieChartData || {};
    const totalPemeriksaan = [
        pieChartData.hpa || 0,
        pieChartData.frs || 0,
        pieChartData.srs || 0,
        pieChartData.ihc || 0
    ];
    const totalPemeriksaanSum = totalPemeriksaan.reduce((a, b) => a + b, 0);
    const ctx1 = document.getElementById("pieChart1");
    if (ctx1) {
        if (chart1) chart1.destroy();
        chart1 = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: totalPemeriksaanSum === 0 ? ["Tidak Ada Data"] : ["HPA", "FRS", "SRS", "IHC"],
                datasets: [{
                    data: totalPemeriksaanSum === 0 ? [1] : totalPemeriksaan,
                    backgroundColor: totalPemeriksaanSum === 0
                        ? ["#D3D3D3"]
                        : [
                            "rgba(231, 74, 59, 1)",
                            "rgba(78, 115, 223, 1)",
                            "rgba(28, 200, 138, 1)",
                            "rgba(255, 193, 7, 1)"
                        ],
                    borderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: false }
                },
                hover: { mode: null }
            }
        });
    }
}

function renderChart2() {
    const pieChartUserDataRaw = window.pieChartUserData || {};
    const userDataRaw = [
        pieChartUserDataRaw.endar || 0,
        pieChartUserDataRaw.arlina || 0,
        pieChartUserDataRaw.ilham || 0,
        pieChartUserDataRaw.berlian || 0
    ];
    const userTotal = userDataRaw.reduce((a, b) => a + b, 0);
    const ctx2 = document.getElementById("pieChart2");
    if (ctx2) {
        if (chart2) chart2.destroy();
        chart2 = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: userTotal === 0
                    ? ["Tidak Ada Data"]
                    : [
                        "Endar Pratiwi, S.Si",
                        "Arlina Kartika, A.Md.AK",
                        "Ilham Tyas Ismadi, A.Md.Kes",
                        "Berlian Duta Krisna, S.Tr.Kes"
                    ],
                datasets: [{
                    data: userTotal === 0 ? [1] : userDataRaw,
                    backgroundColor: userTotal === 0
                        ? ["#D3D3D3"]
                        : ["#FF6B6B", "#6BCB77", "#4D96FF", "#FFD93D"],
                    borderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: false }
                },
                hover: { mode: null }
            }
        });
    }
}

function renderChart3() {
    const pieChartDokterDataRaw = window.pieChartDokterData || {};
    const dokterDataRaw = [
        pieChartDokterDataRaw.vinna || 0,
        pieChartDokterDataRaw.ayu || 0
    ];
    const dokterTotal = dokterDataRaw.reduce((a, b) => a + b, 0);
    const ctx3 = document.getElementById("pieChart3");
    if (ctx3) {
        if (chart3) chart3.destroy();
        chart3 = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: dokterTotal === 0
                    ? ["Tidak Ada Data"]
                    : [
                        "dr. Vinna Chrisdianti, Sp.PA",
                        "dr. Ayu Tyasmara Pratiwi, Sp.PA"
                    ],
                datasets: [{
                    data: dokterTotal === 0 ? [1] : dokterDataRaw,
                    backgroundColor: dokterTotal === 0
                        ? ["#D3D3D3"]
                        : ["#A66DD4", "#FFA500"],
                    borderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: false }
                },
                hover: { mode: null }
            }
        });
    }
}

$('#pieChartCarousel').on('slid.bs.carousel', function (e) {
    const nextIndex = $(e.relatedTarget).index();
    if (nextIndex === 0) renderChart1();
    if (nextIndex === 1) renderChart2();
    if (nextIndex === 2) renderChart3();
});
