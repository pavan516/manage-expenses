<!-- Event error status -->
<div id="event_status"></div>
<!-- Event error status -->

<!-- Overview -->
<table class="table table-bordered table-hover" width="100%" cellspacing="0">
  <tbody>
    <?php if(!empty($expenses)) { ?>
      <?php if($event['status'] == 1 && empty($event['closed_at'])) { ?>
        <tr>
          <td colspan="2" class="center app_color fs15">
            <b>
              Do You want to close the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?>?
            </b>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <b>
              note: by closing the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?>
              you may not be add/update your expenses. Please close the <?php echo strtolower($event['mode']); ?>
              only if it is completed.<br><br>
              <!-- Close Event -->
              <form>
                <!-- Submit Button -->
                <div class="form-group center">
                  <input type="submit" id="closeevent_<?php echo $event['uuid']; ?>" value="CLOSE <?php echo strtoupper($event['mode']); ?>" class="btn btn-primary">
                </div>
                <!-- Submit Button -->
              </form>
              <!-- Close Event -->
            </b>
          </td>
        </tr>
      <?php } else { ?>
        <?php if($event['add_to_personal'] == 0) { ?>
          <tr>
            <td colspan="2" class="center app_color fs15">
              <b>
                Total amount you spent in the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?> is
                <?php echo currency_format($expenses['total_expenses']); ?>
              </b>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <b>
                Do You Like to add your <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?> expenses, into your monthly expenses?
              </b><br><br>
                <!-- Close Event -->
                <form>
                  <!-- Submit Button -->
                  <div class="form-group center">
                    <input type="hidden" name="expenses" id="expenses" value="<?php echo $expenses['total_expenses']; ?>"/>
                    <input type="hidden" name="name" id="name" value="<?php echo $event['name']; ?>"/>
                    <input type="hidden" name="mode" id="mode" value="<?php echo $event['mode']; ?>"/>
                    <label class="small mb-1" for="date">Which month do you like to add your expenses?</label>
                    <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" class="form-control"/><br>
                    <input type="submit" id="addtopersonal_<?php echo $event['uuid']; ?>" value="ADD TO MY MONTHLY EXPENSES" class="btn btn-primary">
                  </div>
                  <!-- Submit Button -->
                </form>
                <!-- Close Event -->
              </b>
            </td>
          </tr>
        <?php } else { ?>
          <tr>
            <td class="center app_color fs15">
              <b><?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?> closed</b>
            </td>
          </tr>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <tr>
        <td colspan="2" class="center app_color"><b>No Data To Display!</b></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<!-- Overview -->

<!-- Accept or Reject Scripts -->
<script type="text/javascript">
/** Close Event */
$("#closeevent_<?php echo $event['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var eventuuidparam = "<?php echo $event['uuid']; ?>";
  var statusparam = 0;
  var dataString = 'uuid='+eventuuidparam+'&status='+statusparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/expenses/individual/close',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** Load required functions */
        load_individual_event_expenses_overview("<?php echo $event['uuid']; ?>");
        load_individual_event_expenses_details("<?php echo $event['uuid']; ?>");
        load_individual_event_expenses_status("<?php echo $event['uuid']; ?>");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
        $('#event_status').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});

/** Add Expenses To Monthly Expenses */
$("#addtopersonal_<?php echo $event['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var eventuuidparam = "<?php echo $event['uuid']; ?>";
  var expensesparam = $("#expenses").val();
  var nameparam = $("#name").val();
  var modeparam = $("#mode").val();
  var dateparam = $("#date").val();
  var dataString = 'uuid='+eventuuidparam+'&expenses='+expensesparam+'&name='+nameparam+'&mode='+modeparam+'&date='+dateparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/expenses/individual/addtopersonal',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** Load required functions */
        load_individual_event_expenses_status("<?php echo $event['uuid']; ?>");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
        $('#event_status').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>