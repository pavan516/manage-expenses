<!-- List Of Events -->
<div class="row">

  <!-- Process each event -->
  <?php foreach($events as $event) { ?>

    <!-- Get loggedinuser member status -->
    <?php $memberStatus = ""; ?>
    <?php if(isset($event['_members'])) { ?>
      <?php foreach($event['_members'] as $member) { ?>
        <?php if($this->session->userdata('uuid') == $member['member_uuid']) { ?>
          <?php $memberStatus = $member['member_status']; ?>
        <?php } ?>
      <?php } ?>
    <?php } ?>

    <!-- Event -->
    <div class="col-md-4 pl0 pr0">
      <div class="card card-header-actions mb-2">

        <!-- Heading -->
        <div class="card-header hbcolor cwhite event_header_padding">
          <?php if($event['type'] == "INDIVIDUAL") echo "<i class='fa fa-user'></i>"; else echo "<i class='fa fa-users'></i>"; ?>
          <?php echo $event['mode']; ?>
          <div>
            <?php if($this->session->userdata('uuid') == $event['user_uuid'] || $memberStatus == "ACCEPTED") { ?>
              <a href="<?php echo base_url(); ?>event/view/<?php echo $event['uuid']; ?>?mode=<?php echo $mode??""; ?>&status=<?php echo $status??""; ?>&type=<?php echo $type??""; ?>&search=<?php echo $search??""; ?>" class="btn btn-green btn-icon mr-2 event_ved_bsize" ><i class="fa fa-eye"></i></a>
            <?php } ?>
            <?php if($event['type'] == "INDIVIDUAL") { ?>
              <button class="btn btn_mustard_yellow btn-icon mr-2 event_ved_bsize" data-toggle="modal" data-target="#individual_event_edit_modal_<?php echo $event['uuid']; ?>"><i class="fa fa-pen"></i></button>
              <button class="btn btn-red btn-icon mr-2 event_ved_bsize" data-toggle="modal" data-target="#individual_event_delete_modal_<?php echo $event['uuid']; ?>"><i class="fa fa-trash"></i></button>
              <!-- Edit & delete Modal -->
              <?php include('individual_edit_delete.php'); ?>
              <!-- Edit & delete Modal -->
            <?php } ?>
          </div>
        </div>
        <!-- Heading -->

        <!-- Body -->
        <?php if($this->session->userdata('uuid') == $event['user_uuid'] || $memberStatus == "ACCEPTED") { ?>
          <div class="card-body text-black">
            <p class="card-text center pt5 pb5"><b><?php echo ucwords($event['name']); ?></b></p>
            <div class="event_footer">
              <div class="medium"><?php echo date_text_format($event['planned_at']); ?></div>
              <div class="medium"><b><?php if($event['status'] == 1) echo "LIVE"; else echo "CLOSED"; ?></b></div>
            </div>
          </div>
        <?php } else { ?>
          <div class="card-body text-black">
            <p class="card-text center pt10 pb10">Hi <b><?php echo $this->session->userdata('name'); ?></b>, welcome to <b><?php echo $event['name']." ".strtolower($event['type']); ?></b>, you are invited by <b><?php echo $event['admin_name']; ?></b>. please accept/reject your invitation.</p>
            <!-- Success/error message -->
            <div id="acceptrejectstatus_<?php echo $event['uuid']; ?>"></div>
            <!-- Success/error message -->
            <!-- Accept / Reject -->
            <form>
              <!-- Submit Button -->
              <div class="form-group center">
                <input type="submit" id="acceptevent_<?php echo $event['uuid']; ?>" value="ACCEPT" class="btn btn-primary">
                <input type="submit" id="rejectevent_<?php echo $event['uuid']; ?>" value="REJECT" class="btn btn-danger">
              </div>
              <!-- Submit Button -->
            </form>
            <!-- Accept / Reject -->
          </div>
        <?php } ?>
        <!-- Body -->

      </div>
    </div>
    <!-- Event -->

    <!-- Accept or Reject Scripts -->
    <script type="text/javascript">
    /** Accept Event */
    $("#acceptevent_<?php echo $event['uuid']; ?>").click(function(e) {
      /** Init var */
      var response = "";
      e.preventDefault();

      /** Build data */
      var eventuuidparam = "<?php echo $event['uuid']; ?>";
      var statusparam = "ACCEPTED";
      var dataString = 'uuid='+eventuuidparam+'&status='+statusparam;

      /** ajax call */
      $.ajax({
        type:'POST',
        data:dataString,
        url:'<?php echo base_url(); ?>event/member/update/status',
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
            load_events("","","","");
          }
        },
        complete:function() {
          /** Hide spinner */
          $(".se-pre-con").hide();
          if(response != "success") {
            /** load html */
						$('#acceptrejectstatus_<?php echo $event['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
            /** close success or error msg */
					  close_alert_message();
          }
        }
      });
    });

    /** Reject Event */
    $("#rejectevent_<?php echo $event['uuid']; ?>").click(function(e) {
      /** Init var */
      var response = "";
      e.preventDefault();

      /** Build data */
      var eventuuidparam = "<?php echo $event['uuid']; ?>";
      var statusparam = "REJECTED";
      var dataString = 'uuid='+eventuuidparam+'&status='+statusparam;

      /** ajax call */
      $.ajax({
        type:'POST',
        data:dataString,
        url:'<?php echo base_url(); ?>event/member/update/status',
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
            load_events("","","","");
          }
        },
        complete:function() {
          /** Hide spinner */
          $(".se-pre-con").hide();
          if(response != "success") {
            /** load html */
						$('#acceptrejectstatus_<?php echo $event['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
            /** close success or error msg */
					  close_alert_message();
          }
        }
      });
    });
    </script>
    <!-- Accept or Reject Scripts -->

  <?php } ?>
  <!-- Process each event -->

</div>
<!-- List Of Events -->