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

      <!-- Events/Trips -->
      <div class="card-body bcolor">

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">
          <button class="btn btn-icon mr-2 breadcrumb_header_left app_heading_bc" data-toggle="modal" data-target="#events_search_modal"><i class="fa fa-search"></i></button>
          <b class="app_color">EVENTS & TRIPS</b>
					<button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right app_heading_bc" data-toggle="modal" data-target="#add_events_modal"><i class="fa fa-plus"></i></button>
				</div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

        <!-- Load Events -->
        <div id="events"></div>
        <!-- Load Events -->

      </div>
      <!-- Events/Trips -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Add Events Modal -->
  <div class="modal fade" id="add_events_modal" tabindex="-1" role="dialog" aria-labelledby="add_events_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="add_events_modal_title center"><b class="app_color">ADD EVENT | TRIP</b></h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <!-- Header -->

        <!-- Add Event -->
        <div class="modal-body">
          <div id="events_add"></div>
        </div>
        <!-- Add Event -->

      </div>
    </div>
  </div>
  <!-- Add Events Modal -->

  <!-- Search Modal -->
  <div class="modal fade" id="events_search_modal" tabindex="-1" role="dialog" aria-labelledby="events_search_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="events_search_modal_title center"><b class="app_color">ADD EVENT | TRIP</b></h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <!-- Header -->

        <!-- Body -->
        <div class="modal-body">

          <!-- Filter Form -->
          <form>
            <div class="form-row">
              <div class="form-group col-md-12">
                <select class="form-control" name="status" id="status" onchange="getEvents(this)">
                  <?php foreach($filters as $key => $value) {
                    if($selected_filter == $key) { ?>
                      <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php }
                  }?>
                </select>
              </div>
            </div>
          </form>
          <!-- Filter Form -->

          <!-- Search Form -->
          <form>
            <div class="form-row">
              <div class="form-group col-md-12">
                <div class="input-group">
                  <?php if(!empty($search)) { ?>
                    <input type="search" class="form-control py-2" value="<?php echo $search; ?>" id="search" required>
                  <?php } else { ?>
                    <input type="search" class="form-control py-2" placeholder="search" id="search" required>
                  <?php } ?>
                  <span class="input-group-append">
                    <button type="submit" class="btn btn-outline-primary" id="searcheventstripsform"><i class="fa fa-search"></i></button>
                  </span>
                </div>
              </div>
            </div>
          </form>
          <!-- Search Form -->

        </div>
        <!-- Body -->

      </div>
    </div>
  </div>
  <!-- Search Modal -->

  <!-- Dropdown filter script -->
  <script>
    /** Get filtered events */
    function getEvents(input_type)
    {
      /** Get selected value */
      var selectedText = input_type.options[input_type.selectedIndex].innerHTML;
      var selectedValue = input_type.value;
      var mode = "";
      var status = "";
      var type = "";

      /** calculate mode & status based on selected value */
      if(selectedValue == "LIVE_EVENTS_INDIVIDUAL") {
        mode="EVENT"; status=1; type="INDIVIDUAL";
      }
      if(selectedValue == "LIVE_TRIPS_INDIVIDUAL") {
        mode="TRIP"; status=1; type="INDIVIDUAL";
      }
      if(selectedValue == "LIVE_EVENTS_GROUP") {
        mode="EVENT"; status=1; type="GROUP";
      }
      if(selectedValue == "LIVE_TRIPS_GROUP") {
        mode="TRIP"; status=1; type="GROUP";
      }
      if(selectedValue == "CLOSED_EVENTS_INDIVIDUAL") {
        mode="EVENT"; status=0; type="INDIVIDUAL";
      }
      if(selectedValue == "CLOSED_TRIPS_INDIVIDUAL") {
        mode="TRIP"; status=0; type="INDIVIDUAL";
      }
      if(selectedValue == "CLOSED_EVENTS_GROUP") {
        mode="EVENT"; status=0; type="GROUP";
      }
      if(selectedValue == "CLOSED_TRIPS_GROUP") {
        mode="TRIP"; status=0; type="GROUP";
      }

      /** load events / trips */
      load_events(mode,status,type,"");

      /** close search modal */
      $('#events_search_modal').modal('hide');
      $(".modal-backdrop").remove();
      $('body').removeClass('modal-open');
    }
    /** Dropdown filter script */

    /** Search script */
    $("#searcheventstripsform").click(function(e) {
      e.preventDefault();
      var modeparam = "<?php echo $mode; ?>";
      var statusparam = "<?php echo $status; ?>";
      var typeparam = "<?php echo $type; ?>";
      var searchparam = $("#search").val();

      /** Load function */
      load_events(modeparam,statusparam,typeparam,searchparam);

      /** close search modal */
      $('#events_search_modal').modal('hide');
      $(".modal-backdrop").remove();
      $('body').removeClass('modal-open');
    });

    /** Custom Scripts - Load Events */
    $(document).ready(function()
    {
      /** variables */
      var mode    = "<?php echo $mode; ?>";
      var status  = "<?php echo $status; ?>";
      var type    = "<?php echo $type; ?>";
      var search  = "<?php echo $search; ?>";

      /** By default load add */
      load_events_add();
      load_events(mode,status,type,search);
    });
  </script>
  <!-- Custom Scripts - Load Events -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

</body>
</html>