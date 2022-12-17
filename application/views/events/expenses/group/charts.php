<!-- Heading -->
<div class="card-header heading">
  <b>GROUP EXPENSES - CHARTS</b>
</div>
<!-- Heading -->

<!-- Charts view -->
<div class="card-body">

  <!-- Chart Filters -->
  <div class="form-row">
    <div class="form-group col-md-12">
      <label class="small mb-1" for="chart_type">Select Chart View</label>
      <select class="form-control" name="chart_type" id="chart_type" onchange="getSelectedChart(this)" required>
        <option value="BAR_CHART" selected>BAR CHART</option>
        <option value="AREA_CHART">AREA CHART</option>
        <option value="PIE_CHART">PIE CHART</option>
      </select>
    </div>
  </div>
  <!-- Chart Filters -->

  <!-- Show chart view -->
  <div id="show_charts"></div>
  <!-- Show chart view -->

  <!-- get charts using script -->
  <script>
  function getSelectedChart(input_type) {

    /** Get selected field name & value */
    var eventUuid = '<?php echo $event['uuid']; ?>';
    var domain = '<?php echo base_url(); ?>';
    var selectedText = input_type.options[input_type.selectedIndex].innerHTML;
    var selectedValue = input_type.value;
    var url = domain+'event/expenses/group/graphs/'+eventUuid+'?chart_type='+selectedValue;

    /** load function */
    load_selected_chart(url, eventUuid, selectedValue);
  }
  </script>
  <!-- get charts using script -->

</div>
<!-- Charts view -->

<!-- Custom Scripts -->
<script type="text/javascript">
$(document).ready(function()
{
  /** Get selected field name & value */
  var eventUuid = '<?php echo $event['uuid']; ?>';
  var domain = '<?php echo base_url(); ?>';
  var chart_type = 'BAR_CHART';
  var url = domain+'event/expenses/group/graphs/'+eventUuid+'?chart_type='+chart_type;

  /** load function */
  load_selected_chart(url, eventUuid, chart_type);
});

/** load selected chart */
function load_selected_chart(url, eventUuid, chart_type)
{
  /** init var */
  var str = '';
  str += '<iframe id="iframe" class="chart_iframe" onload="calcHeight();" src="'+url+'"></iframe>';

  /** append */
  document.getElementById('show_charts').innerHTML = str;
}
</script>
<!-- Custom Scripts -->
