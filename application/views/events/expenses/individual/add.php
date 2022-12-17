
<!-- Add Individual Event Expenses Modal -->
<div class="modal fade" id="add_iee_modal" tabindex="-1" role="dialog" aria-labelledby="add_iee_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="add_iee_modal_title center"><b class="app_color">ADD EXPENSES</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

      <!-- Success/error message -->
      <div id="add_ie_expenses_status"></div>
      <!-- Success/error message -->

        <!-- Add Individual Event Expenses -->
        <form id="add_ie_expenses_form">

          <!-- Send event uuid -->
          <input type="hidden" name="event_uuid" id="event_uuid" value="<?php echo $event['uuid']; ?>">
          <!-- Send event uuid -->

          <!-- Select Date | Title | Value -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="small mb-1" for="date">Date</label>
              <input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required/>
            </div>
            <div class="form-group col-md-4">
              <label class="small mb-1" for="title">Money Spent On *</label>
              <textarea name="title" id="title" class="form-control" placeholder="Title" required></textarea>
            </div>
            <div class="form-group col-md-4">
              <label class="small mb-1" for="value">Amount</label>
              <input type="number" name="value" id="value" class="form-control" placeholder="Amount" required/>
            </div>
          </div>
          <!-- Select Date | Title | Value -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input class="btn save_button" type="submit" name="submit_add_iee" id="submit_add_iee" value="SAVE" >
            <button class="btn cancel_button" type="button" data-dismiss="modal">CANCEL</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Add Individual Event Expenses -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Add Individual Event Expenses Modal -->

<!-- Custom Scripts -->
<script type="text/javascript">
$(document).ready(function()
{
  /** Add expenses */
  $("#add_ie_expenses_form").on('submit',(function(e) {
    /** Init var */
    var response = "";
    e.preventDefault();

    /** ajax call */
    e.preventDefault();
    $.ajax({
      url: "<?php echo base_url(); ?>event/expenses/individual/insert",
      type: "POST",
      data:  new FormData(this),
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {
        /** Show loader */
        $(".se-pre-con").show();
      },
      success: function(data) {
        /** append data to response  */
        response = data;
        /** on success load all required methods */
        if(data == "success") {
          /** Reset input data */
          document.getElementById("add_ie_expenses_form").reset();
          /** Load required functions */
          load_individual_event_expenses("<?php echo $event['uuid']; ?>");
        }
      },
      complete:function() {
        /** Hide spinner */
        $(".se-pre-con").hide();
        if(response == "success") {
          /** load html */
          $('#add_ie_expenses_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
        } else {
          /** load html */
          $('#add_ie_expenses_status').fadeIn().html(get_error_string("Error", response));
        }
        /** close success or error msg */
        close_alert_message();
      }
    });
  }));
});
</script>
<!-- Custom Scripts - Get Profile -->