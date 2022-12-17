<!-- Overview -->
<table class="table table-bordered table-hover" width="100%" cellspacing="0">
  <tbody>
    <tr class="app_color_green">
      <td><b>Total Income</b></td>
      <td class="fright"><b><?php echo currency_format($total['income']); ?> <i class="fa fa-arrow-up"></i></b></td>
    </tr>
    <tr class="app_color_blue">
      <td><b>Total Investment</b></td>
      <td class="fright"><b><?php echo currency_format($total['investment']); ?> <i class="fa fa-arrow-down"></i></b></td>
    </tr>
    <tr class="app_color_red">
      <td><b>Total Expenses</b></td>
      <td class="fright"><b><?php echo currency_format($total['expenses']); ?> <i class="fa fa-arrow-down"></i></b></td>
    </tr>
    <?php if($total['total'] >= 0) { ?>
      <tr class="app_heading_bc">
        <td><b>Amount In Hands</b></td>
        <td class="fright"><b><?php echo currency_format($total['total']); ?> <i class="fa fa-arrow-up"></i></b></td>
      </tr>
    <?php } else { ?>
      <tr class="app_heading_bc">
        <td><b>Extra Expenses</b></td>
        <td class="fright"><b><?php echo "- ".currency_format($total['total']); ?> <i class="fa fa-arrow-down"></i></b></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<div class="pb5"></div>
<!-- Overview -->

<!-- Graphs -->
<div class="card card-header-actions">
  <div class="card-header personal_graphs_header"><b id="chart_name">AREA CHART</b>
    <div class="dropdown no-caret">
      <select class="form-control" name="chart_type" id="chart_type" onchange="getMonthlyChart(this)" required>
        <option value="BAR_CHART">BAR CHART</option>
        <option value="AREA_CHART" selected>AREA CHART</option>
        <option value="PIE_CHART">PIE CHART</option>
      </select>
    </div>
  </div>
  <!-- Show chart view -->
  <div id="showchartview"></div>
  <!-- Show chart view -->
</div>
<!-- Graphs -->

<!-- get charts using script -->
<script>
function getMonthlyChart(input_type)
{
  /** Get selected field name & value */
  var domain        = '<?php echo base_url(); ?>';
  var selectedText  = input_type.options[input_type.selectedIndex].innerHTML;
  var selectedValue = input_type.value;
  var dataString    = 'chart_type='+selectedValue;
  var selected_date = '<?php echo $date; ?>';

  /** init var */
  var str = '';
  str += '<iframe id="iframe" onload="calcHeight();" src="'+domain+'personal/monthly/charts?chart_type='+selectedValue+'&date='+selected_date+'" class="chart_iframe"></iframe>';

  /** append */
  document.getElementById('showchartview').innerHTML = str;
  document.getElementById('chart_name').innerHTML = selectedValue.replace("_", " ");
}

/** By default load area chart */
var str     = '';
var domain  = '<?php echo base_url(); ?>';
var value   = "AREA_CHART";
var date    = '<?php echo $date; ?>';
str += '<iframe id="iframe" onload="calcHeight();" src="'+domain+'personal/monthly/charts?chart_type='+value+'&date='+date+'" class="chart_iframe"></iframe>';
document.getElementById('showchartview').innerHTML = str;
</script>
<!-- get charts using script -->