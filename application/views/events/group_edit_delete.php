<!-- Load required css & js files -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v1/css/jquery.multiselect.css">
<script src="<?php echo base_url(); ?>assets/v1/js/jquery.multiselect.js"></script>
<!-- Load required css & js files -->

<!-- Heading -->
<div class="card-header update_heading">
  <b>UPDATE <?php echo ucwords(strtoupper($event['mode'])); ?></b>
</div>
<!-- Heading -->

<!-- Update Event -->
<div class="card-body pt15">

  <!-- Success/error message -->
  <div id="edit_delete_event_status"></div>
  <!-- Success/error message -->

  <!-- Update Event Form -->
  <form>

    <!-- Select Mode | Name | Type -->
    <div class="form-row">

      <!-- Mode -->
      <?php $plans = ['EVENT', 'TRIP']; ?>
      <div class="form-group col-md-4">
        <label class="small mb-1" for="mode">Select Plan</label>
        <select class="form-control" id="mode" required>
          <?php foreach($plans as $plan) {
            if($event['mode'] == $plan) {
              echo "<option value='".$plan."' selected>".$plan."</option>";
            } else {
              echo "<option value='".$plan."'>".$plan."</option>";
            }
          }?>
        </select>
      </div>
      <!-- Mode -->

      <!-- Name -->
      <div class="form-group col-md-4">
        <label class="small mb-1" for="name">Edit Name</label>
        <input type="text" id="name" class="form-control" value="<?php echo $event['name']; ?>" required/>
      </div>
      <!-- Name -->

      <!-- Type -->
      <div class="form-group col-md-4">
        <label class="small mb-1" for="title">Selected Type</label>
        <input type="text" id="type" class="form-control" value="<?php echo $event['type']; ?>" readonly>
      </div>
      <!-- Type -->

    </div>
    <!-- Select Mode | Name | Type -->

    <!-- Select Friends | Budget -->
    <div class="form-row">

      <!-- Select Friends -->
      <div class="form-group col-md-4">
        <label class="small mb-1" for="friends">Add/Remove Friends</label>
        <div class="friends-dropdown-update">
          <select id="friends" multiple placeholder="Select Friends"></select>
        </div>
      </div>
      <!-- Select Friends -->

      <!-- Budget -->
      <div class="form-group col-md-4">
        <label class="small mb-1" for="budget">Budget</label>
        <input type="text" id="budget" class="form-control" value="<?php echo $event['budget']; ?>" required/>
      </div>
      <!-- Budget -->

    </div>
    <!-- Select Friends | Budget -->

    <!-- Submit Button -->
    <div class="form-group center">
      <input class="btn save_button" type="submit" id="updateevent" value="SAVE" >
      <button class="btn cancel_button" type="button" data-dismiss="modal">CANCEL</button><br>
    </div>
    <!-- Submit Button -->

  </form>
  <!-- Update Event Form -->

</div>
<!-- Update Event -->


<!-- Space -->
<div class="pt15"></div>
<!-- Space -->


<!-- Heading -->
<div class="card-header update_heading">
  <b>DELETE <?php echo ucwords(strtoupper($event['mode'])); ?></b>
</div>
<!-- Heading -->

<!-- Delete Event -->
<div class="card-body pt15">

  <!-- Delete Event Form -->
  <form>

    <!-- Message -->
    <h6><b>Are you sure you want to delete this <?php echo ucwords(strtolower($event['mode'])); ?> ?</b></h6>
    <!-- Message -->

    <!-- Submit Button -->
    <div class="form-group center">
      <input class="btn yes_button" type="submit" id="deleteevent" value="YES" >
      <button class="btn no_button" type="button" data-dismiss="modal">NO</button>
    </div>
    <!-- Submit Button -->

  </form>
  <!-- Delete Event Form -->

</div>
<!-- Delete Event -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">
/** Update Event */
$("#updateevent").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  e.preventDefault();
  var uuidparam = "<?php echo $event['uuid']; ?>";
  var modeparam = $("#mode").val();
  var nameparam = $("#name").val();
  var typeparam = $("#type").val();
  var friendsparam = $("#friends").val();
  var budgetparam = $("#budget").val();
  var dataString = 'uuid='+uuidparam+'&mode='+modeparam+'&name='+nameparam+'&type='+typeparam+'&friends='+friendsparam+'&budget='+budgetparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/update',
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
        $('#edit_delete_event_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#edit_delete_event_status').fadeIn().html(get_error_string("Error", response));
      }
      /** close success or error msg */
      close_alert_message();
    }
  });
});

/** Delete Event */
$("#deleteevent").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    type:'POST',
    data:'',
    url:'<?php echo base_url(); ?>event/delete/<?php echo $event['uuid']; ?>',
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
        /** by default redirect to events page */
        window.location = "<?php echo base_url(); ?>events";
      } else {
        /** load html */
        $('#edit_delete_event_status').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->

<!-- Multiselect dropdown -->
<!-- Build Friends data for dropdown -->
<?php
# Init var
$allFriends = [];
# loop each friend
foreach($friends as $friend) {
  # build data
  $data = [];
  $data['disabled'] = false;
  $data['groupId']  = 1;
  $data['groupName'] = "Select Friends";
  $data['id']  = $friend['_friend']['uuid'];
  $data['name'] = $friend['_friend']['name']."(".$friend['_friend']['mobile'].")";
  foreach($event['_members'] as $member) {
    if($member['member_uuid'] == $friend['_friend']['uuid']) {
      $data['selected']  = true;
    } else {
      $data['selected']  = false;
    }
  }
  # push to an array
  $allFriends[] = $data;
}
?>
<!-- Build Friends data for dropdown -->

<!-- Friends Dropdown Script -->
<script>
/** Init var */
var data = <?php echo json_encode($allFriends); ?>;

/** friends-dropdown */
$('.friends-dropdown-update').dropdown({
  /** params */
  data: data,
  limitCount: 40,
  multipleMode: 'label'
});
</script>
<!-- Friends Dropdown Script -->