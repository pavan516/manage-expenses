<!-- Load required css & js -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v1/css/jquery.multiselect.css">
<script src="<?php echo base_url(); ?>assets/v1/js/jquery.multiselect.js"></script>
<!-- Load required css & js -->

<!-- Success/error message -->
<div id="events_trips_status"></div>
<!-- Success/error message -->

<!-- Events Trips Form -->
<form id="eventstripsform">

  <!-- Select Mode | Name | Type -->
  <div class="form-row">

    <!-- Select Mode -->
    <div class="form-group col-md-4">
      <label class="small mb-1" for="mode">Select Plan</label>
      <select class="form-control" name="mode" id="mode" onchange="getMode(this)" required>
        <option value="" selected>Select Plan</option>
        <option value="EVENT">EVENT</option>
        <option value="TRIP">TRIP</option>
      </select>
    </div>
    <!-- Select Mode -->

    <!-- Name -->
    <div class="form-group col-md-4 plan_name" id="plan_name"></div>
    <!-- Name -->

    <!-- Select Type -->
    <div class="form-group col-md-4">
      <label class="small mb-1" for="type">Planning Alone OR Group?</label>
      <select class="form-control" name="type" id="type" onchange="getFriends(this)">
        <option value="" selected>Select Type</option>
        <option value="INDIVIDUAL">INDIVIDUAL</option>
        <option value="GROUP">GROUP</option>
      </select>
    </div>
    <!-- Select Type -->

  </div>
  <!-- Select Mode | Name | Type -->

  <!-- Select Budget | Friends -->
  <div class="form-row">

    <!-- Trip -->
    <div class="form-group col-md-4">
      <label class="small mb-1" for="budget">Set Budget</label>
      <input type="number" name="budget" id="budget" class="form-control" placeholder="Set Budget"/>
    </div>
    <!-- Trip -->

    <!-- Friends -->
    <div class="form-group col-md-8" id="friends_list">
      <label class="small mb-1" for="friends">Add Friends To Event/Trip</label>
      <div class="friends-dropdown">
        <select name="friends[]" id="friends[]" multiple placeholder="Select Friends"></select>
      </div>
    </div>
    <!-- Friends -->

  </div>
  <!-- Select Budget | Friends -->

  <!-- Submit Button -->
  <div class="form-group center">
    <input type="submit" class="btn create_button" name="submitpersonal" id="submitpersonal" value="CREATE" >
    <button type="button" class="btn cancel_button" data-dismiss="modal">CANCEL</button>
  </div>
  <!-- Submit Button -->

</form>
<!-- Events Trips Form -->

<!-- get name based on mode | friends if type is group -->
<script>
/** hide mode */
$(".plan_name").hide();
$("#friends_list").hide();

/** Get friends */
function getMode(input_type) {
  /** Get selected value */
  var selectedText = input_type.options[input_type.selectedIndex].innerHTML;
  var selectedValue = input_type.value;

  /** init var */
  var str = '';
  $(".plan_name").show();
  /** Return name text field based on mode */
  if(selectedValue == "EVENT") {
    str += '<label class="small mb-1" for="name">Event Name</label>';
    str += '<input type="text" name="name" id="name" class="form-control" placeholder="House Construction, Marriage, Family Parties etc..." required/>';
  } else if(selectedValue == "TRIP") {
    str += '<label class="small mb-1" for="name">Trip Name</label>';
    str += '<input type="text" name="name" id="name" class="form-control" placeholder="Maldives Trip, Goa Trip, Long rides etc..." required/>';
  }

  /** append */
  document.getElementById('plan_name').innerHTML = str;
}

/** Get friends */
function getFriends(input_type) {
  /** Get selected value */
  var selectedText = input_type.options[input_type.selectedIndex].innerHTML;
  var selectedValue = input_type.value;

  /** Return empty string if type is not group */
  if(selectedValue != "GROUP") {
    /** append */
    document.getElementById('friends_list').innerHTML = '';
  } else {
    /** show friends dropdown */
    $("#friends_list").show();
  }
}
/** get friends if type is group */

/** Add Script */
$("#eventstripsform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>event/insert",
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
        /** reset form */
        document.getElementById("eventstripsform").reset();
        /** Load required functions */
        load_events("","","","");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#events_trips_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#events_trips_status').fadeIn().html(get_error_string("Error", response));
      }

      /** close status after 2 seconds */
      window.setTimeout(function() {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
          $(this).remove();
          /** close search modal */
          $('#add_events_modal').modal('hide');
          $(".modal-backdrop").remove();
          $('body').removeClass('modal-open');
        });
      }, 2000);

    }
  });

  /** load add events */
  load_events_add();
}));
</script>
<!-- Add Script -->

<!-- Friends Dropdown Script -->
<script>
/** Init var */
var data = <?php echo json_encode($friends); ?>;

/** friends-dropdown */
$('.friends-dropdown').dropdown({
  /** params */
  data: data,
  limitCount: 40,
  multipleMode: 'label'
});
</script>
<!-- Friends Dropdown Script -->