<!-- Heading -->
<div class="card-header personal_heading">
  <b><?php echo \date("M jS, y", \strtotime($cf_date))." - ".\date("M jS, y", \strtotime($ct_date)); ?></b>
  <b class="pl10"><i class="far fa-calendar-alt" onclick="showCustomDate();"></i></b>
</div>
<!-- Heading -->

<!-- Card body -->
<div class="card-body">

  <!-- Filter form -->
  <div id="custom_date_filter">
    <div class="row center width_100per pb5 pt1">
      <div class="width_50per display_inline_block">
        <input type="date" name="cf_date" id="cf_date" class="form-control" value="<?php echo $cf_date; ?>">
      </div>
      <div class="width_50per display_inline_block">
        <input type="date" name="ct_date" id="ct_date" class="form-control" value="<?php echo $ct_date; ?>">
      </div>
    </div>
  </div>
  <!-- Filter form -->

  <!-- Tabs -->
  <button id="custom_overview" class="tablink <?php if($tab == "overview") echo "tab_active"; ?>" onclick="load_custom_overview('<?php echo $cf_date; ?>', '<?php echo $ct_date; ?>');"><b>OVERVIEW</b></button>
  <button id="custom_details" class="tablink <?php if($tab == "details") echo "tab_active"; ?>" onclick="load_expenses('custom', '', '<?php echo $cf_date; ?>', '<?php echo $ct_date; ?>');"><b>DETAILS</b></button>
  <div class="pb35"></div>
  <!-- Tabs -->

  <!-- response -->
  <div id="personal_custom_response">
    <!-- Overview -->
    <?php include_once('custom_overview.php'); ?>
    <!-- Overview -->
  </div>
  <!-- response -->

</div>
<!-- End of card body -->

<!-- Script -->
<script type="text/javascript">
document.getElementById("custom_date_filter").style.display = "none";

/** Custom from & to date submit */
var date_input_from = document.getElementById('cf_date');
var date_input_to = document.getElementById('ct_date');

/** onchange from date */
date_input_from.onchange = function() {
  /** load function */
  load_custom(this.value, '<?php echo $ct_date; ?>');
}

/** onchange to date */
date_input_to.onchange = function() {
  /** load function */
  load_custom('<?php echo $cf_date; ?>', this.value);
}
/** Custom from & to date submit */

/** open expenses modal */
function open_expenses_modal(uuid)
{
  /** ajax call */
  $.ajax({
    url: "personal/modalview?uuid="+uuid+"&page=custom",
    method: "GET",
    async: true,
    success: function(data){
      $('#modal_response').html(data);
    }
  });

  /** open modal */
  $('#edit_delete_expenses_modal').modal('show');
}

/** show custom date */
function showCustomDate()
{
  /** get element */
  var dateFilter = document.getElementById("custom_date_filter");

  /** append style */
  if (dateFilter.style.display === "block") {
    dateFilter.style.display = "none";
  } else {
    dateFilter.style.display = "block";
  }
}
</script>
<!-- Script -->