<!-- Heading -->
<div class="card-header heading">
  <b>ADD RESPONSIBILITIES</b>
</div>
<!-- Heading -->

<!-- Add Responsibilities -->
<div class="card-body pt15">

  <!-- Success/error message -->
  <div id="personal_responsibilities_status"></div>
  <!-- Success/error message -->

  <!-- Personal Responsibilities Form -->
  <form id="personal_responsibilities_form">

    <!-- Select Type | Text | Value -->
    <div class="form-row">
      <div class="form-group col-md-3">
        <select class="form-control" name="type" id="type">
          <option value="INCOME" selected>INCOME</option>
          <option value="EXPENSES">EXPENSES</option>
          <option value="INVESTMENT">INVESTMENT</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <textarea name="title" id="title" class="form-control" placeholder="Salary | House Rent | Lic Policy | etc..." required></textarea>
      </div>
      <div class="form-group col-md-3">
        <input type="number" name="value" id="value" class="form-control" placeholder="Amount" required/>
      </div>
    </div>
    <!-- Select Type | Text | Value -->

    <!-- Submit Button -->
    <div class="form-group center">
      <input class="btn save_button" type="submit" name="submit" id="submit" value="SAVE" ><br>
    </div>
    <!-- Submit Button -->

  </form>
  <!-- Personal Responsibilities Form -->

</div>
<!-- Add Responsibilities -->

<!-- Scripts -->
<script type="text/javascript">
$("#personal_responsibilities_form").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>responsibilities/personal/insert",
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
        /** Load required functions */
        document.getElementById('personal_responsibilities_form').reset();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#personal_responsibilities_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#personal_responsibilities_status').fadeIn().html(get_error_string("Error", response));
      }
      /** close success or error msg */
      close_alert_message();
    }
  });
}));
</script>
<!-- Scripts -->