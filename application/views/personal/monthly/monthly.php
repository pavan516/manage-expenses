<!-- Heading -->
<div class="card-header personal_heading">
  <b><?php echo \strtoupper(\date("F-Y", \strtotime($date))); ?> MONTH EXPENSES</b>
  <b class="pl10"><i class="far fa-calendar-alt" onclick="showMonthlyDate();"></i></b>
</div>
<!-- Heading -->

<!-- Card body -->
<div class="card-body">

  <!-- Filter form -->
  <div class="width_100per center" id="monthly_date_filter">
    <div class="pt1 pb5">
      <input type="month" name="monthly_date" id="monthly_date" class="form-control input-sm" value="<?php echo $date; ?>">
    </div>
  </div>
  <!-- Filter form -->

  <!-- Tabs -->
  <div class="pb41">
    <button id="monthly_overview" class="tablink <?php if($tab == "overview") echo "tab_active"; ?>" onclick="load_monthly_overview('<?php echo $date; ?>');"><b>OVERVIEW</b></button>
    <button id="monthly_details" class="tablink <?php if($tab == "details") echo "tab_active"; ?>" onclick="load_expenses('monthly', '<?php echo $date; ?>');"><b>DETAILS</b></button>
  </div>
  <!-- Tabs -->

  <!-- Response -->
  <div id="personal_monthly_response">
    <!-- Overview -->
    <?php include_once('monthly_overview.php'); ?>
    <!-- Overview -->
  </div>
  <!-- Response -->

</div>
<!-- End of card body -->

<!-- Scripts -->
<script type="text/javascript">
/** hide or show view */
document.getElementById("monthly_date_filter").style.display = "none";

/** onchange date */
document.getElementById('monthly_date').onchange = function() {
  /** load function */
  load_monthly("", this.value);
}
/** Month date submit */

/** show monthly date */
function showMonthlyDate()
{
  /** get element */
  var dateFilter = document.getElementById("monthly_date_filter");

  /** append style */
  if (dateFilter.style.display === "block") {
    dateFilter.style.display = "none";
  } else {
    dateFilter.style.display = "block";
  }
}
</script>
<!-- Scripts -->