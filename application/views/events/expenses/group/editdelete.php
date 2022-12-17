<!-- Edit & Delete Modal -->
<div class="modal fade" id="edit_delete_group_expenses_modal_<?php echo $item['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editexpensesmodalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="editexpensesmodalTitle"><b class="app_color">Modify <?php if($item['split'] == 1) echo "Group"; else echo "Personal"; ?> Expenses</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <?php if($event['status'] == 0 && !empty($event['closed_at'])) { ?>
        <div class="p10"><b>You are not allowed to add expenses! reason: (<?php echo \strtolower($event['mode']); ?> closed)</b></div>
      <?php } else { ?>

        <!-- Body -->
        <div class="modal-body">

          <!-- Event error status -->
          <div id="group_event_expenses_status_<?php echo $item['id']; ?>"></div>
          <!-- Event error status -->

          <!-- Update Heading -->
          <div class="card-body update_heading br0">
            <b>Update Expenses</b></td>
          </div>
          <!-- Update Heading -->

          <!-- Update Expenses Form -->
          <form class="pt10">

            <!-- Select Type | Date -->
            <div class="form-row flex_wrap_inherit">
              <div class="form-group col-xs-6">
                <select class="form-control" name="split_<?php echo $item['id']; ?>" id="split_<?php echo $item['id']; ?>">
                  <option value="1" <?php if($item['split'] == 1) echo "selected" ?>>GROUP</option>
                  <option value="0" <?php if($item['split'] == 0) echo "selected" ?>>PERSONAL</option>
                </select>
              </div>
              <div class="form-group col-xs-6">
                <input type="date" name="date_<?php echo $item['id']; ?>" id="date_<?php echo $item['id']; ?>" class="form-control" value="<?php echo \get_date($item['date']); ?>" required/>
              </div>
            </div>
            <!-- Select Type | Date -->

            <!-- Select Text | Value -->
            <div class="form-row">
              <div class="form-group col-md-6">
                <textarea name="title_<?php echo $item['id']; ?>" id="title_<?php echo $item['id']; ?>" class="form-control" required><?php echo $item['title']; ?></textarea>
              </div>
              <div class="form-group col-md-6">
                <input type="number" name="value_<?php echo $item['id']; ?>" id="value_<?php echo $item['id']; ?>" class="form-control" value="<?php echo $item['value']; ?>" required/>
              </div>
            </div>
            <!-- Select Text | Value -->

            <!-- Submit Button -->
            <div class="form-group center">
              <input  type="submit" class="btn save_button" id="update_group_expenses_<?php echo $item['id']; ?>" value="SAVE">
              <button type="button" class="btn cancel_button" data-dismiss="modal">CANCEL</button><br>
            </div>
            <!-- Submit Button -->

          </form>
          <!-- Update Expenses Form -->

          <!-- Delete Heading -->
          <div class="card-body update_heading br0">
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
              <input  type="submit" class="btn yes_button" id="delete_group_expenses_<?php echo $item['id']; ?>" value="YES" >
              <button type="button" class="btn no_button" data-dismiss="modal">NO</button>
            </div>
            <!-- Submit Button -->

          </form>
          <!-- Delete Expenses Form -->

        </div>
        <!-- Body -->

      <?php } ?>
      <!-- Show data based on event status -->

    </div>
  </div>
</div>
<!-- Edit & Delete Modal -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">

/** Update */
$("#update_group_expenses_<?php echo $item['id']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var idparam    = "<?php echo $item['id']; ?>";
  var titleparam = $("#title_<?php echo $item['id']; ?>").val();
  var valueparam = $("#value_<?php echo $item['id']; ?>").val();
  var splitparam = $("#split_<?php echo $item['id']; ?>").val();
  var dateparam  = $("#date_<?php echo $item['id']; ?>").val();
  var dataString = 'id='+idparam+'&title='+titleparam+'&value='+valueparam+'&split='+splitparam+'&date='+dateparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/expenses/group/update',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#group_event_expenses_status_<?php echo $item['id']; ?>').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#group_event_expenses_status_<?php echo $item['id']; ?>').fadeIn().html(get_error_string("Error", response));
      }

      /** close status after 2 seconds */
      window.setTimeout(function() {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
          $(this).remove();
          /** modal related */
          $('#edit_delete_group_expenses_modal_<?php echo $item['id']; ?>').modal('hide');
          $(".modal-backdrop").remove();
          $('body').removeClass('modal-open');
          if(response == "success") {
            /** Load required functions */
            load_group_event_user_expenses("<?php echo $event['uuid']; ?>");
          }
        });
      }, 2000);

    }
  });
});

/** Delete */
$("#delete_group_expenses_<?php echo $item['id']; ?>").click(function(e) {
  e.preventDefault();
  var idparam = "<?php echo $item['id']; ?>";
  var dataString = 'id='+idparam;

  $.ajax({
  type:'POST',
  data:dataString,
  url:'<?php echo base_url(); ?>event/expenses/group/delete',
  beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#group_event_expenses_status_<?php echo $item['id']; ?>').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#group_event_expenses_status_<?php echo $item['id']; ?>').fadeIn().html(get_error_string("Error", response));
      }

      /** close status after 2 seconds */
      window.setTimeout(function() {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
          $(this).remove();
          /** modal related */
          $('#edit_delete_group_expenses_modal_<?php echo $item['id']; ?>').modal('hide');
          $(".modal-backdrop").remove();
          $('body').removeClass('modal-open');
          if(response == "success") {
            /** Load required functions */
            load_group_event_user_expenses("<?php echo $event['uuid']; ?>");
          }
        });
      }, 2000);

    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->