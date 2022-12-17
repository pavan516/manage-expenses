<!-- Heading -->
<div class="card-header personal_heading">
  <b><?php echo \date("Y, F jS", \strtotime($date)); ?> DAY EXPENSES</b>
  <b class="pl10"><i class="far fa-calendar-alt" onclick="showDaytodayDate();"></i></b>
</div>
<!-- Heading -->

<!-- Card body -->
<div class="card-body">

  <!-- Filter form -->
  <div class="width_100per center" id="daytoday_date_filter">
    <div class="pt1 pb5">
      <input type="date" name="date" id="date" class="form-control" value="<?php echo $date; ?>">
    </div>
  </div>
  <!-- Filter form -->

  <!-- Tabs -->
  <button id="daytoday_overview" class="tablink <?php if($tab == "overview") echo "tab_active"; ?>" onclick="load_daytoday_overview('<?php echo $date; ?>');"><b>OVERVIEW</b></button>
  <button id="daytoday_details" class="tablink <?php if($tab == "details") echo "tab_active"; ?>" onclick="load_expenses('daytoday', '<?php echo $date; ?>');"><b>DETAILS</b></button>
  <div class="pb35"></div>
  <!-- Tabs -->

  <!-- Response -->
  <div id="personal_daytoday_response">
    <!-- Overview -->
    <?php include_once('daytoday_overview.php'); ?>
    <!-- Overview -->
  </div>
  <!-- Response -->

</div>
<!-- End of card body -->

<!-- Scripts -->
<script type="text/javascript">
/** hide/show view */
document.getElementById("daytoday_date_filter").style.display = "none";

/** load daytoday overview page */
load_daytoday_overview("<?php echo $date; ?>");

/** onchange date submit */
document.getElementById('date').onchange = function() {
  /** load function */
  load_daytoday(this.value);
}

/** show day-to-day date */
function showDaytodayDate()
{
  /** get element */
  var dateFilter = document.getElementById("daytoday_date_filter");

  /** append style */
  if (dateFilter.style.display === "block") {
    dateFilter.style.display = "none";
  } else {
    dateFilter.style.display = "block";
  }
}
</script>
<!-- Scripts -->