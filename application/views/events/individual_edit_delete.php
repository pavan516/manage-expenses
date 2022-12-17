<!-- Edit Modal -->
<div class="modal fade" id="individual_event_edit_modal_<?php echo $event['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="individual_event_edit_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="individual_event_edit_modal_title"><b class="app_color">Update Your <?php echo ucwords(strtolower($event['mode'])); ?></b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body app_color">

        <!-- Success/error message -->
        <div id="event_update_status_<?php echo $event['uuid']; ?>"></div>
        <!-- Success/error message -->

        <!-- Update Param Form -->
        <form>

          <!-- Select Mode | Name | Type -->
          <div class="form-row">

            <!-- Mode -->
            <?php $plans = ['EVENT', 'TRIP']; ?>
            <div class="form-group col-md-4">
              <label class="small mb-1" for="mode">Select Plan</label>
              <select class="form-control" id="mode_<?php echo $event['uuid']; ?>" required>
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
              <input type="text" id="name_<?php echo $event['uuid']; ?>" class="form-control" value="<?php echo $event['name']; ?>" required/>
            </div>
            <!-- Name -->

            <!-- Type -->
            <div class="form-group col-md-4">
              <label class="small mb-1" for="title">Selected Type</label>
              <input type="text" id="type_<?php echo $event['uuid']; ?>" class="form-control" value="<?php echo $event['type']; ?>" readonly>
            </div>
            <!-- Type -->

          </div>
          <!-- Select Mode | Name | Type -->

          <!-- Select Budget -->
          <div class="form-row">

            <!-- Budget -->
            <div class="form-group col-md-4">
              <label class="small mb-1" for="budget">Budget</label>
              <input type="text" id="budget_<?php echo $event['uuid']; ?>" class="form-control" value="<?php echo $event['budget']; ?>" required/>
            </div>
            <!-- Budget -->

          </div>
          <!-- Select Budget -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input class="btn save_button" type="submit" id="updateevent_<?php echo $event['uuid']; ?>" value="SAVE" >
            <button class="btn cancel_button" type="button" data-dismiss="modal">CANCEL</button><br>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Param Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Edit Modal -->



<!-- Delete Modal -->
<div class="modal fade" id="individual_event_delete_modal_<?php echo $event['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="individual_event_delete_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="individual_event_delete_modal_title"><b class="app_color">Delete Confirmation</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Success/error message -->
        <div id="event_delete_status_<?php echo $event['uuid']; ?>"></div>
        <!-- Success/error message -->

        <!-- Delete Form -->
        <form>

          <!-- Message -->
          <h6><b>Are you sure you want to delete this <?php echo ucwords(strtolower($event['mode'])); ?> ?</b></h6>
          <!-- Message -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input class="btn yes_button" type="submit" id="deleteevent_<?php echo $event['uuid']; ?>" value="YES" >
            <button class="btn no_button" type="button" data-dismiss="modal">NO</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Delete Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Delete Modal -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">
/** Update Event */
$("#updateevent_<?php echo $event['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  e.preventDefault();
  var uuidparam = "<?php echo $event['uuid']; ?>";
  var modeparam = $("#mode_<?php echo $event['uuid']; ?>").val();
  var nameparam = $("#name_<?php echo $event['uuid']; ?>").val();
  var typeparam = $("#type_<?php echo $event['uuid']; ?>").val();
  var friendsparam = $("#friends_<?php echo $event['uuid']; ?>").val();
  var budgetparam = $("#budget_<?php echo $event['uuid']; ?>").val();
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
      /** on success load all required methods */
      if(data == "success") {
        /** close modal */
        $('#editeventmodal_<?php echo $event['uuid']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
        /** Load required functions */
        load_events("","","","");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
        $('#event_update_status_<?php echo $event['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});

/** Delete Event */
$("#deleteevent_<?php echo $event['uuid']; ?>").click(function(e) {
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
      /** on success load all required methods */
      if(data == "success") {
        /** close modal */
        $('#deleteeventmodal_<?php echo $event['uuid']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
        /** Load required functions */
        load_events("","","","");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
        $('#event_delete_status_<?php echo $event['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->

<!-- Multiselect dropdown -->
<?php if($event['type'] == "GROUP") { ?>
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

<?php } ?>