<!-- Edit & Delete Modal -->
<div class="modal fade" id="edit_delete_individual_expenses_modal_<?php echo $item['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="edit_delete_individual_expenses_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="edit_delete_individual_expenses_title"><b class="app_color">Update | Delete Expenses</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Event status check -->
        <?php if($event['status'] == 0 && !empty($event['closed_at'])) { ?>
          <b>You are not allowed to update your expenses! (<?php echo \strtolower($event['mode']); ?> closed)</b>
        <?php } else { ?>

          <!-- Event error status -->
          <div id="individual_event_edit_delete_status_<?php echo $item['id']; ?>"></div>
          <!-- Event error status -->

          <!-- Update Heading -->
          <div class="card-body update_heading">
            <b>Update Expenses</b></td>
          </div>
          <!-- Update Heading -->

          <!-- Update Expenses Form -->
          <form class="pt10">

            <!-- Select Type | Text | Value -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="small mb-1" for="title">Title</label>
                <textarea id="title_<?php echo $item['id']; ?>" class="form-control" required><?php echo $item['title']; ?></textarea>
              </div>
              <div class="form-group col-md-12">
                <label class="small mb-1" for="value">Amount</label>
                <input type="number" id="value_<?php echo $item['id']; ?>" class="form-control" value="<?php echo $item['value']; ?>" required/>
              </div>
            </div>
            <!-- Select Type | Text | Value -->

            <!-- Submit Button -->
            <div class="form-group center">
              <input type="submit"  class="btn save_button" id="updateexpenses_<?php echo $item['id']; ?>" value="SAVE" >
              <button type="button" class="btn cancel_button" data-dismiss="modal">CANCEL</button>
            </div>
            <!-- Submit Button -->

          </form>
          <!-- Update Expenses Form -->

          <!-- Delete Heading -->
          <div class="card-body update_heading">
            <b>Delete Expenses</b></td>
          </div>
          <!-- Delete Heading -->

          <!-- Delete Expenses Form -->
          <form class="pt10">

            <!-- Message -->
            <h6><b>Are you sure you want to delete this?</b></h6>
            <!-- Message -->

            <!-- Submit Button -->
            <div class="form-group center">
              <input type="submit"  class="btn yes_button" id="deleteexpenses_<?php echo $item['id']; ?>" value="YES" >
              <button type="button" class="btn no_button" data-dismiss="modal">NO</button>
            </div>
            <!-- Submit Button -->

          </form>
          <!-- Delete Expenses Form -->

        <?php } ?>

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Edit & Delete Modal -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">

/** Update */
$("#updateexpenses_<?php echo $item['id']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var idparam = '<?php echo $item['id']; ?>';
  var titleparam = $("#title_<?php echo $item['id']; ?>").val();
  var valueparam = $("#value_<?php echo $item['id']; ?>").val();
  var dataString = 'id='+idparam+'&title='+titleparam+'&value='+valueparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/expenses/individual/update',
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
        load_individual_event_expenses_overview("<?php echo $item['event_uuid']; ?>");
        load_individual_event_expenses_details("<?php echo $item['event_uuid']; ?>");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** modal related */
        $('edit_delete_individual_expenses_modal_<?php echo $item['id']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
      } else {
        /** load html */
        $('#individual_event_edit_delete_status_<?php echo $item['id']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});

/** Delete */
$("#deleteexpenses_<?php echo $item['id']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var idparam = '<?php echo $item['id']; ?>';
  var dataString = 'id='+idparam;

  /** ajax call */
  $.ajax({
  type:'POST',
  data:dataString,
  url:'<?php echo base_url(); ?>event/expenses/individual/delete',
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
        load_individual_event_expenses_overview("<?php echo $item['event_uuid']; ?>");
        load_individual_event_expenses_details("<?php echo $item['event_uuid']; ?>");
        /** Data table */
        $("#i_e_expenses").dataTable();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** modal related */
        $('#edit_delete_individual_expenses_modal_<?php echo $item['id']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
      } else {
        /** load html */
        $('#individual_event_edit_delete_status_<?php echo $item['id']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->