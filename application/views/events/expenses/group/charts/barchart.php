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
      <div class="card-header hbcolor"><b>Group Expenses Bar-Chart</b></div>
      <div class="card-body border_radius_0">
        <div class="chart-bar"><canvas id="myBarChart" width="100%" height="30"></canvas></div><br>
      </div>
    </div>
  </div>
  <!-- Content -->

  <!-- Script -->
  <script src="<?php echo base_url(); ?>assets/v1/js/2.9.3-chart.min.js"></script>
  <script>
  /** Variables */
  var currency = "<?php echo $this->session->userdata('currency'); ?>";

  /**
   * format number
   */
  function currency_format(number, decimals, dec_point, thousands_sep) {
    /** example: currency_format(1234.56, 2, ',', ' '); return: '1 234,56' */
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };

    /** Fix for IE parseFloat(0.55).toFixed(0) = 0; */
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }

    /** return value */
    return s.join(dec);
  }

  /** Bar Chart */
  var ctx = document.getElementById("myBarChart");
  var myBarChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: [<?php echo '"'.implode('","', $xaxis).'"' ?>],
      datasets: [{
        label: "Expenses",
        backgroundColor: "rgba(0, 97, 242, 1)",
        hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
        borderColor: "#4e73df",
        data: [<?php echo implode(',',  $data) ?>]
      }]
    },
    options: {
      maintainAspectRatio: false,
      layout: {
        padding: {
          left: 10,
          right: 25,
          top: 25,
          bottom: 0
        }
      },
      scales: {
        xAxes: [{
          time: {
            unit: "month"
          },
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            maxTicksLimit: 12
          },
          maxBarThickness: 25
        }],
        yAxes: [{
          ticks: {
            suggestedMin: 0,
            maxTicksLimit: 6,
            padding: 10,
            /** Include a dollar sign in the ticks */
            callback: function(value, index, values) {
              return currency + currency_format(value);
            }
          },
          gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
          }
        }]
      },
      legend: {
        display: false
      },
      tooltips: {
        titleMarginBottom: 10,
        titleFontColor: "#6e707e",
        titleFontSize: 14,
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: "#dddfeb",
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
        callbacks: {
          label: function(tooltipItem, chart) {
            var datasetLabel =
                chart.datasets[tooltipItem.datasetIndex].label || "";
            return datasetLabel + ": "+ currency + currency_format(tooltipItem.yLabel);
          }
        }
      }
    }
  });
  </script>
  <!-- Script -->

</body>
</html>




