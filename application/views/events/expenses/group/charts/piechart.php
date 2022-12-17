<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Links -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="<?php echo base_url(); ?>assets/v1/css/css-styles.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <!-- Links -->
</head>
<!-- Head -->
<!-- Body -->
<body>

  <!-- Content -->
  <div class="col-mb-12">
    <div class="card h-100">
      <div class="card-header hbcolor"><b>Group Expenses Pie-Chart</b></div>
      <div class="card-body border_radius_0">
        <div class="chart-pie mb-4"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
      </div>
    </div>
  </div>
  <!-- Content -->

  <!-- Script -->
  <script src="<?php echo base_url(); ?>assets/v1/js/2.9.3-chart.min.js"></script>
  <script>
  /** Pie Chart */
  var piechartdata = [<?php echo '"'.implode('","', $percentages).'"' ?>];
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: [<?php echo '"'.implode('","',  $xaxis).'"' ?>],
      datasets: [{
        data: piechartdata,
        backgroundColor: [<?php echo '"'.implode('","',  $colors).'"' ?>],
        hoverBackgroundColor: [<?php echo '"'.implode('","',  $colors).'"' ?>],
        hoverBorderColor: "rgba(234, 236, 244, 1)"
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: "#dddfeb",
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10
      },
      legend: {
          display: true
      },
      cutoutPercentage: 80
    }
  });
  </script>
  <!-- Script -->

</body>
</html>