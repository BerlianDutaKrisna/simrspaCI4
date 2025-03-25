// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pastikan data sesuai dengan yang diinginkan
console.log("Pie Chart Data:", pieChartData); // Debugging

var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["HPA", "FRS", "SRS", "IHC"], // Ganti label
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
        "rgba(255, 193, 7, 1)"  // Kuning untuk IHC
      ],
      hoverBackgroundColor: [
        "rgba(231, 74, 59, 0.8)",
        "rgba(78, 115, 223, 0.8)",
        "rgba(28, 200, 138, 0.8)",
        "rgba(255, 193, 7, 0.8)"
      ],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom'
      },
      tooltip: { 
        callbacks: {
          label: function(tooltipItem) {
            var dataset = tooltipItem.dataset;
            var index = tooltipItem.dataIndex;
            var label = dataset.labels ? dataset.labels[index] : '';
            var value = dataset.data[index] || 0;
            return label + ": " + value;
          }
        }
      }
    }
  }
});
