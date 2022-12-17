<!-- Heading -->
<div class="card-header personal_heading">
  <b>ADD EXPENSES</b>
</div>
<!-- Heading -->

<!-- Add Expenses -->
<div class="card-body pt10 bottom_radius_30_30">

  <!-- Success/error message -->
  <div id="personal_ft_status"></div>
  <!-- Success/error message -->

  <!-- Personal add form -->
  <form id="personalftform">

    <!-- Select Type | Date -->
    <div class="form-row">
      <div class="form-group col-xs-6 width_50per">
        <select class="form-control" name="type" id="type">
          <option value="INCOME">INCOME</option>
          <option value="INVESTMENT">INVESTMENT</option>
          <option value="EXPENSES" selected>EXPENSES</option>
        </select>
      </div>
      <div class="form-group col-xs-6 width_50per">
        <input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required/>
      </div>
    </div>
    <!-- Select Type | Date -->

    <!-- Select Text | Value -->
    <div class="form-row">
      <div class="form-group col-md-6">
        <textarea name="title" id="title" class="form-control" placeholder="ex: groceries, rents & etc..." required></textarea>
      </div>
      <div class="form-group col-md-6 mb05em">
        <input type="number" name="value" id="value" class="form-control" placeholder="Amount" required/>
      </div>
    </div>
    <!-- Select Text | Value -->

    <!-- Submit Button -->
    <div class="form-group center mb0em">
      <input type="submit" name="submitpersonal" id="submitpersonal" value="SAVE" class="btn btn-primary save_button">
    </div>
    <!-- Submit Button -->

  </form>
  <!-- Personal add form -->

</div>
<!-- Add Expenses -->


<!-- Add Script -->
<script>
$("#personalftform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>personal/insert",
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
        /** Reset form */
        document.getElementById('personalftform').reset();
        document.getElementById('title').focus();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** sucess message */
        enable_message("success", "successfully saved!");
      } else {
        /** error message */
        enable_message("error", "something went wrong!");
      }
    }
  });
}));

/** update textarea placeholder */
$('#type').on('change', function () {
  /** income */
  if (this.value == "INCOME") {
    $('#title').attr('placeholder', "ex: salary, bonus etc...");
  } else if(this.value == "INVESTMENT") {
    $('#title').attr('placeholder', "ex: LIC policy, FD's etc...");
  } else {
    $('#title').attr('placeholder', "ex: groceries, rents etc...");
  }
});
</script>
<!-- Add Script -->