<!-- Add Expenses -->
<?php if($event['status'] == 1) { ?>

  <!-- Heading -->
  <div class="card-header heading">
    <b>ADD EXPENSES</b>
  </div>
  <!-- Heading -->

  <!-- Add Expenses -->
  <div class="card-body pt15">

    <!-- Success/error message -->
    <div id="add_ge_expenses_status"></div>
    <!-- Success/error message -->

    <!-- Add Group Event Expenses -->
    <form id="add_ge_expenses_form">

      <!-- Send event uuid -->
      <input type="hidden" name="event_uuid" id="event_uuid" value="<?php echo $event['uuid']; ?>">
      <!-- Send event uuid -->

      <!-- Select Type | Date -->
      <div class="form-row">
        <div class="form-group col-xs-6">
          <select class="form-control" name="split" id="split">
            <option value="1" selected>GROUP</option>
            <option value="0">PERSONAL</option>
          </select>
        </div>
        <div class="form-group col-xs-6">
          <input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required/>
        </div>
      </div>
      <!-- Select Type | Date -->

      <!-- Select Text | Value -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <textarea name="title" id="title" class="form-control" placeholder="Money spent on..." required></textarea>
        </div>
        <div class="form-group col-md-6">
          <input type="number" name="value" id="value" class="form-control" placeholder="Amount" required/>
        </div>
      </div>
      <!-- Select Text | Value -->

      <!-- Submit Button -->
      <div class="form-group center">
        <input type="submit" class="btn btn-primary save_button" name="submit_add_iee" id="submitpersonal" value="SAVE">
      </div>
      <!-- Submit Button -->

    </form>
    <!-- Add Group Event Expenses -->

  </div>
  <!-- Add Expenses -->

<?php } else if($event['status'] == 0 && $member['add_to_personal'] == 0 && $member['balance'] == 0) { ?>

  <!-- Heading -->
  <div class="card-header heading">
    <b><?php echo strtoupper($event['mode']); ?> CLOSED</b>
  </div>
  <!-- Heading -->

  <!-- Add Group Expenses To Personal -->
  <div class="card-body pt5">

    <!-- Heading -->
    <div class="card-header heading">
      <b class="fs15">ADD GROUP EXPENSES TO MONTHLY EXPENSES</b>
    </div>
    <!-- Heading -->

    <!-- Success/error message -->
    <div id="add_grout_event_expenses_to_monthly_status"></div>
    <!-- Success/error message -->

    <!-- Add event expenses to personal expenses -->
    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
      <tbody>
        <tr>
          <td colspan="2" class="center app_color fs15">
            <b>Total amount you spent in the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?> is
              <?php $total = $member['personal_expenses']+$member['group_expenses']+$member['paid_amount']-$member['received_amount']; ?>
              <?php echo currency_format($total); ?>
            </b>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <b>
              Do You Like to add your <?php echo strtolower($event['mode']); ?> expenses, into your monthly expenses?
            </b><br><br>
              <!-- Close Event -->
              <form>
                <!-- Submit Button -->
                <div class="form-group center">
                  <input type="hidden" name="expenses" id="expenses" value="<?php echo $total; ?>"/>
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
      </tbody>
    </table>
    <!-- Add event expenses to personal expenses -->

  </div>
  <!-- Add Group Expenses To Personal -->

<?php } else { ?>

  <!-- Heading -->
  <div class="card-header heading">
    <b><?php echo strtoupper($event['mode']); ?> CLOSED</b>
  </div>
  <!-- Heading -->

  <!-- Add Expenses Restrict -->
  <div class="card-body">

    <!-- Message -->
    <div class="p10 center"><b>You are not allowed to add expenses to a closed <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?></b></div>
    <!-- Message -->

  </div>
  <!-- Add Expenses Restrict -->

<?php } ?>

<!-- Custom Scripts -->
<script type="text/javascript">
$(document).ready(function()
{
  /** Add expenses */
  $("#add_ge_expenses_form").on('submit',(function(e) {
    /** Init var */
    var response = "";
    e.preventDefault();

    /** ajax call */
    $.ajax({
      url: "/event/expenses/group/insert",
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
          document.getElementById("add_ge_expenses_form").reset();
        }
      },
      complete:function() {
        /** Hide spinner */
        $(".se-pre-con").hide();
        if(response == "success") {
          /** load html */
          $('#add_ge_expenses_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
        } else {
          /** load html */
          $('#add_ge_expenses_status').fadeIn().html(get_error_string("Error", response));
        }
        /** close success or error msg */
        close_alert_message();
      }
    });
  }));
});


/** Add Expenses To Monthly Expenses */
$("#addtopersonal_<?php echo $event['uuid']; ?>").click(function(e) {
  /** Build data */
  e.preventDefault();
  var eventuuidparam = "<?php echo $event['uuid']; ?>";
  var expensesparam = $("#expenses").val();
  var nameparam = $("#name").val();
  var modeparam = $("#mode").val();
  var dateparam = $("#date").val();
  var dataString = 'uuid='+eventuuidparam+'&expenses='+expensesparam+'&name='+nameparam+'&mode='+modeparam+'&date='+dateparam;
  /** Send Request */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/expenses/group/addtopersonal',
    success:function(data) {
      if(data == "success") {
        /** Load required functions */
        load_group_event_add_expenses("<?php echo $event['uuid']; ?>");
      } else {
        /** load html */
        $('#add_grout_event_expenses_to_monthly_status').fadeIn().html(get_error_string("Error", response));
      }
    }
  });
});
</script>
<!-- Custom Scripts -->