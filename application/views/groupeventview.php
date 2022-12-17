<!DOCTYPE html>
<html lang="en">

  <!-- Head  -->
  <?php include_once("includes/head.php"); ?>
  <!-- Head  -->

<!-- Body -->
<body class="nav-fixed">

	<!-- Top Navbar -->
	<?php include_once("includes/topnavbar.php"); ?>
	<!-- Top Navbar -->

	<!-- Main Content -->
	<div id="layoutSidenav">

		<!-- Sidebar -->
		<?php include_once("includes/sidebar.php"); ?>
		<!-- Sidebar -->

    <!-- Center Content -->
    <div id="layoutSidenav_content">

      <!-- Group Event -->
      <div class="card-body bcolor">

        <!-- Event uuid -->
        <?php $eventUuid = $event['uuid']; ?>
        <!-- Event uuid -->

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">

          <!-- Back Button -->
          <a class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" href="<?php echo base_url(); ?>events?mode=<?php echo $old_par_mode; ?>&status=<?php echo $old_par_status; ?>&type=<?php echo $old_par_type; ?>&search=<?php echo $old_par_search; ?>" ><i class="fa fa-arrow-left" ></i></a>
          <!-- Back Button -->

          <!-- Title -->
          <b class="app_color"><?php echo $event['name']; ?></b>
          <!-- Title -->

          <!-- Get memberStatus -->
          <?php $memberStatus = "";
            if(isset($event['_members'])) {
              foreach($event['_members'] as $member) {
                if($this->session->userdata('uuid') == $member['member_uuid']) {
                  $memberStatus = $member['member_status'];
                }
              }
            }
          ?>
          <!-- Get memberStatus -->

          <!-- Exit Group -->
          <?php if($memberStatus == "ACCEPTED" && $event['user_uuid'] != $this->session->userdata('uuid')) { ?>
            <button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right" data-toggle="modal" data-target="#exitgroupmodal"><i class="fa fa-trash"></i></button>
            <!-- Delete Modal -->
            <div class="modal fade" id="exitgroupmodal" tabindex="-1" role="dialog" aria-labelledby="exitgroupmodaltitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                  <!-- Header -->
                  <div class="modal-header">
                    <h5 class="modal-title" id="exitgroupmodaltitle"><b class="app_color">Exit Group Confirmation</b></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  </div>
                  <!-- Header -->

                  <!-- Body -->
                  <div class="modal-body">
                    <!-- Delete Expenses Form -->
                    <form>
                      <!-- status -->
                      <div id="exit_group_status"></div>
                      <!-- status -->
                      <!-- Message -->
                      <h6>NOTE: all your expenses list will be deleted permanently from this group!</h6><br>
                      <h6><b>Are you sure, do you want to exit from this group?</b></h6>
                      <!-- Message -->
                      <!-- Submit Button -->
                      <div class="form-group center">
                        <input type="submit" class="btn yes_button" id="exitgroup" value="YES" >
                        <button type="button" class="btn no_button" data-dismiss="modal">NO</button>
                      </div>
                      <!-- Submit Button -->

                    </form>
                    <!-- Delete Expenses Form -->

                  </div>
                  <!-- Body -->

                </div>
              </div>
            </div>
            <!-- Delete Modal -->

          <?php } ?>
          <!-- Exit Group -->

          <!-- Update Group - admin -->
          <?php if($memberStatus == "ACCEPTED" && $event['user_uuid'] == $this->session->userdata('uuid')) { ?>
            <button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right" onclick="load_edit_delete_group('<?php echo $eventUuid; ?>');" id="edit_delete_group_event"><i class="fa fa-pen"></i></button>
          <?php } ?>
          <!-- Update Group - admin -->

        </div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

        <!-- Show details basen on user status -->
        <?php if($memberStatus == "PENDING") { ?>
          <!-- Show approve | reject view -->
          <div class="card-body text-black">

            <!-- Title -->
            <p class="card-text center pt10 pb10">
              Hi <b><?php echo $this->session->userdata('name'); ?></b>,
              welcome to <b><?php echo $event['name']." ".strtolower($event['type']); ?></b>,
              you are invited by <b><?php echo $event['admin_name']; ?></b>.
              please accept/reject your invitation.
            </p>
            <!-- Title -->

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
          <!-- Show approve | reject view -->

        <?php } else { ?>

          <!-- Menus -->
          <div class="card-header breadcrumb_header_1">

            <!-- Load group event user expenses -->
            <button class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" onclick="load_group_event_user_expenses('<?php echo $eventUuid; ?>');" id="user_expenses"><b><i class="fa fa-user"></i></b></button>
            <!-- Load group event user expenses -->

            <!-- Load group event expenses -->
            <button class="btn btn-blue btn-icon mr-2 group_breadcrumb_header_left1" onclick="load_group_event_expenses('<?php echo $eventUuid; ?>');" id="group_expenses"><b><i class="fa fa-users"></i></b></button>
            <!-- Load group event expenses -->

            <!-- Yearly -->
            <button class="btn btn-blue btn-icon mr-2 group_breadcrumb_header_left2" onclick="load_group_event_expenses_charts('<?php echo $eventUuid; ?>');" id="group_charts"><b><i class="fas fa-chart-area"></i></b></button>
            <!-- Yearly -->

            <!-- Split share -->
            <button class="btn btn-blue btn-icon mr-2 group_breadcrumb_header_left3" onclick="load_group_event_expenses_split_share('<?php echo $eventUuid; ?>');" id="group_shares"><b><i class="fa fa-sitemap"></i></b></button>
            <!-- Split share -->

            <!-- Split share -->
            <button class="btn btn-blue btn-icon mr-2 group_breadcrumb_header_left4" onclick="load_group_event_payments('<?php echo $eventUuid; ?>');" id="group_payments"><b><i class="fas fa-money-bill-alt"></i></b></button>
            <!-- Split share -->

            <!-- Add group event expenses -->
            <button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right" onclick="load_group_event_add_expenses('<?php echo $eventUuid; ?>');" id="add_expenses"><b><i class="fa fa-plus"></i></b></button>
            <!-- Add group event expenses -->

          </div>
          <div class="pb10"></div>
          <!-- Menus -->

          <!-- Response -->
          <div id="group_event_response"></div>
          <!-- Response -->

        <?php } ?>
        <!-- Show details basen on user status -->

      </div>
      <!-- Group Event -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Custom Scripts - Get Profile -->
  <script type="text/javascript">
  $(document).ready(function()
  {
    /** Exit group */
    $("#exitgroup").click(function(e) {
      /** Init var */
      var response = "";
      e.preventDefault();

      /** Build data */
      var eventUuid = '<?php echo $event['uuid']; ?>';
      var dataString = 'event_uuid='+eventUuid;

      /** ajax call */
      $.ajax({
        type:'POST',
        data:dataString,
        url:'<?php echo base_url(); ?>event/group/exit/<?php echo $event['uuid']; ?>',
        beforeSend: function() {
          /** Show loader */
          $(".se-pre-con").show();
        },
        success: function(data) {
          /** append data to response  */
          response = data;
          /** on success load all required methods */
          if(data == "success") {
            window.location.href = "<?php echo base_url(); ?>events?mode=<?php echo $old_par_mode; ?>&status=<?php echo $old_par_status; ?>&type=<?php echo $old_par_type; ?>&search=<?php echo $old_par_search; ?>";
          }
        },
        complete:function() {
          /** Hide spinner */
          $(".se-pre-con").hide();
          if(response != "success") {
            /** load html */
						$('#exit_group_status').fadeIn().html(get_error_string("Error", response));
            /** close success or error msg */
            close_alert_message();
          }
        }
      });
    });

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
        success: function(data) {
          /** on success load all required methods */
          if(data == "success") {
            /** Reload locations */
            location.reload();
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
        success: function(data) {
          /** on success load all required methods */
          if(data == "success") {
            /** redirect to events page */
            window.location = "<?php echo base_url(); ?>events?mode=&status=&type=&search=";
          }
        }
      });
    });
    /** Accept or Reject Scripts */

    /** Load required functions */
    load_group_event_user_expenses("<?php echo $event['uuid']; ?>");
  });
  </script>
  <!-- Custom Scripts - Get Profile -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

</body>
</html>