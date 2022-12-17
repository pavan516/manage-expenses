<!-- Heading -->
<div class="card-header personal_heading">
  <b><?php echo $year; ?> YEAR EXPENSES</b>
  <b class="pl10"><i class="far fa-calendar-alt" onclick="showYearlyDate();"></i></b>
</div>
<!-- Heading -->

<!-- Card body -->
<div class="card-body">

  <!-- Filter form -->
  <div class="width_100per center" id="yearly_date_filter">
    <div class="pt1 pb5">
      <select name="year" id="year" class="form-control" onchange="get_yearly_expenses(this)">
        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
      </select>
    </div>
  </div>
  <!-- Filter form -->

  <!-- Tabs -->
  <button id="yearly_overview" class="tablink <?php if($tab == "overview") echo "tab_active"; ?>" onclick="load_yearly_tabs('overview');"><b>OVERVIEW</b></button>
  <button id="yearly_details" class="tablink <?php if($tab == "details") echo "tab_active"; ?>" onclick="load_yearly_tabs('details');"><b>DETAILS</b></button>
  <div class="pb35"></div>
  <!-- Tabs -->

  <!-- Overview  & graphs -->
  <div id="load_yearly_overview">

    <!-- Overview -->
    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
      <tbody>
        <tr class="app_color_green">
          <td><b>Total Income</b></td>
          <td class="fright"><b><?php echo currency_format($total_income); ?> <i class="fa fa-arrow-up"></i></b></td>
        </tr>
        <tr class="app_color_blue">
          <td><b>Total Investment</b></td>
          <td class="fright"><b><?php echo currency_format($total_investment); ?> <i class="fa fa-arrow-down"></i></b></td>
        </tr>
        <tr class="app_color_red">
          <td><b>Total Expenses</b></td>
          <td class="fright"><b><?php echo currency_format($total_expenses); ?> <i class="fa fa-arrow-down"></i></b></td>
        </tr>
        <?php if($total >= 0) { ?>
          <tr class="app_heading_bc">
            <td><b>Amount In Hands</b></td>
            <td class="fright"><b><?php echo currency_format($total); ?> <i class="fa fa-arrow-up"></i></b></td>
          </tr>
        <?php } else { ?>
          <tr class="app_heading_bc">
            <td><b>Extra Expenses</b></td>
            <td class="fright"><b><?php echo "- ".currency_format($total); ?> <i class="fa fa-arrow-down"></i></b></td>
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
          <select class="form-control" name="chart_type" id="chart_type" onchange="getYearlyChart(this)" required>
            <option value="BAR_CHART">BAR CHART</option>
            <option value="AREA_CHART" selected>AREA CHART</option>
            <option value="PIE_CHART">PIE CHART</option>
          </select>
        </div>
      </div>
      <!-- Show chart view -->
      <div id="showyearlychartview"></div>
      <!-- Show chart view -->
    </div>
    <!-- Graphs -->

  </div>
  <!-- Overview & Graphs -->

  <!-- Details -->
  <div id="load_yearly_details">
    <?php if(!empty($yearly)) { ?>
      <?php foreach($yearly as $month) { ?>
        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
          <tbody>
            <tr>
              <td colspan="2" class="center app_color year_head_bottom"><b> <?php echo $month['month_name']; ?> Month Overview</b></td>
            </tr>
            <tr class="app_color_green">
              <td><b>Total Income</b></td>
              <td class="fright"><b><?php echo currency_format($month['income']); ?> <i class="fa fa-arrow-up"></i></b></td>
            </tr>
            <tr class="app_color_blue">
              <td><b>Total Investment</b></td>
              <td class="fright"><b><?php echo currency_format($month['investment']); ?> <i class="fa fa-arrow-down"></i></b></td>
            </tr>
            <tr class="app_color_red">
              <td><b>Total Expenses</b></td>
              <td class="fright"><b><?php echo currency_format($month['expenses']); ?> <i class="fa fa-arrow-down"></i></b></td>
            </tr>
            <?php if($month['total'] >= 0) { ?>
              <tr class="app_heading_bc">
                <td><b>Amount In Hands</b></td>
                <td class="fright"><b><?php echo currency_format($month['total']); ?> <i class="fa fa-arrow-up"></i></b></td>
              </tr>
            <?php } else { ?>
              <tr class="app_heading_bc">
                <td><b>Extra Expenses</b></td>
                <td class="fright"><b><?php echo "- ".currency_format($month['total']); ?> <i class="fa fa-arrow-down"></i></b></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="p5"></div>
      <?php } ?>
    <?php } ?>
  </div>
  <!-- Details -->

  <!-- Graphs -->
  <div id="load_yearly_graphs">

  </div>
  <!-- Graphs -->

</div>
<!-- Card Body End Here -->

<!-- Scripts -->
<script>
/** hide or show view */
document.getElementById("load_yearly_details").style.display = "none";
document.getElementById("load_yearly_graphs").style.display = "none";
document.getElementById("yearly_date_filter").style.display = "none";

/** year dropdown */
for (i = new Date().getFullYear(); i > 1900; i--) {
  var selectedYear = "<?php echo $year; ?>";
  if(selectedYear != i) {
    $('#year').append($('<option />').val(i).html(i));
  }
}

/** Year dropdown submit */
function get_yearly_expenses(input_type) {
  /** Get selected field name & value */
  var selectedText = input_type.options[input_type.selectedIndex].innerHTML;
  var selectedValue = input_type.value;

  /** load function */
  load_yearly(selectedValue);
}
/** Year dropdown submit */

/** calculate the height */
function calcHeight()
{
  /** find the height of the internal page  & change the height of the iframe */
  document.getElementById('iframe').height = document.getElementById('iframe').contentWindow.document.body.scrollHeight;
};


/** show yearly date */
function showYearlyDate()
{
  /** get element */
  var dateFilter = document.getElementById("yearly_date_filter");

  /** append style */
  if (dateFilter.style.display === "block") {
    dateFilter.style.display = "none";
  } else {
    dateFilter.style.display = "block";
  }
}

function getYearlyChart(input_type)
{
  /** Get selected field name & value */
  var domain = '<?php echo base_url(); ?>';
  var selectedText = input_type.options[input_type.selectedIndex].innerHTML;
  var selectedValue = input_type.value;
  var dataString = 'chart_type='+selectedValue;
  var selected_year = '<?php echo $year; ?>';

  /** init var */
  var str = '';
  str += '<iframe id="iframe" onload="calcHeight();" src="'+domain+'personal/yearly/charts?chart_type='+selectedValue+'&year='+selected_year+'" class="chart_iframe"></iframe>';

  /** append */
  document.getElementById('showyearlychartview').innerHTML = str;
}

/** load default graph */
var str     = '';
var domain  = '<?php echo base_url(); ?>';
var value   = "AREA_CHART";
var year    = '<?php echo $year; ?>';
str += '<iframe id="iframe" onload="calcHeight();" src="'+domain+'personal/yearly/charts?chart_type='+value+'&year='+year+'" class="chart_iframe"></iframe>';
document.getElementById('showyearlychartview').innerHTML = str;
</script>
<!-- Calculate Height Script -->